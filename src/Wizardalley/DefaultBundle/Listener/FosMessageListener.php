<?php

namespace Wizardalley\DefaultBundle\Listener;

use Doctrine\ORM\EntityManager;
use FOS\MessageBundle\Event\MessageEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Wizardalley\CoreBundle\Entity\FollowedNotification;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * Class FosMessageListener
 *
 * @package Wizardalley\DefaultBundle\Listener
 */
class FosMessageListener
{
    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * KernelListener constructor.
     *
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        TokenStorage $tokenStorage,
        EntityManager $em
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->em           = $em;
    }

    public function onMessageCreated(MessageEvent $event)
    {
        // Creer la notification pour le destinatire
        $message = $event->getMessage();
        /** @var WizardUser $sender */
        $sender               = $message->getSender();
        $followedNotification = new FollowedNotification();
        // Pour chaque utilisateur du thread souf l'utilisateur actuel
        foreach ($message->getThread()->getParticipants() as $user) {
            if ($user->getId() != $sender->getId()) {
                $followedNotification
                    ->setChecked(false)
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setType(FollowedNotification::TYPE_MESSAGE)
                    ->setDataNotification(
                        json_encode(
                            [
                                'sender_id'   => $sender->getId(),
                                'sender_name' => $sender->getUsername(),
                                'thread_id'   => $message->getThread()->getId(),
                                'thread_name' => $message->getThread()->getSubject(),
                                'message_id'  => $message->getId()
                            ]
                        )
                    )
                    ->setUser($user)
                ;
                $this->em->persist($followedNotification);
            }
        }
        $this->em->flush();
    }
}
