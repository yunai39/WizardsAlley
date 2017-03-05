<?php

namespace Wizardalley\DefaultBundle\Listener;

use Doctrine\ORM\EntityManager;
use FOS\MessageBundle\Event\MessageEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Wizardalley\CoreBundle\Entity\FollowedNotification;

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
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->em           = $em;
    }

    public function onMessageCreated(MessageEvent $event)
    {
        // Creer la notification pour le destinatire
        $message              = $event->getMessage();
        $sender               = $message->getSender();
        $followedNotification = new FollowedNotification();
        // Pour chaque utilisateur du thread souf l'utilisateur actuel
        foreach ($message->getThread()->getParticipants() as $user) {
            if ($user->getId() != $sender->getId()) {
                $followedNotification
                    ->setChecked(false)
                     ->setCreatedAt(new \DateTime())
                     ->setUpdatedAt(new \DateTime())
                     ->setType('message')
                     ->setDataNotification(
                         json_encode(
                             [
                                 'sender_id'  => $sender->getId(),
                                 'thread_id'  => $message->getThread()->getId(),
                                 'message_id' => $message->getId()
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
