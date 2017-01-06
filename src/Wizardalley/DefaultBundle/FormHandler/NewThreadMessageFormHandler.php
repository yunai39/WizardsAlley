<?php

namespace Wizardalley\DefaultBundle\FormHandler;

use Doctrine\ORM\EntityManager;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\FormHandler\NewThreadMessageFormHandler as BaseMessageFormHandler;
use FOS\MessageBundle\FormModel\AbstractMessage;
use FOS\MessageBundle\FormModel\NewThreadMessage;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use JMS\Serializer\Exception\ValidationFailedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Wizardalley\CoreBundle\Entity\WizardUser;

class NewThreadMessageFormHandler extends BaseMessageFormHandler
{
    /** @var EntityManager */
    protected $em;

    /** @var TokenStorage */
    protected $tokenStorage;

    public function __construct(
        $request,
        ComposerInterface $composer,
        SenderInterface $sender,
        ParticipantProviderInterface $participantProvider,
        EntityManager $em,
        TokenStorage $tokenStorage
    ) {
        parent::__construct($request, $composer, $sender, $participantProvider);
        $this->em           = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Composes a message from the form data.
     *
     * @param AbstractMessage $message
     *
     * @return MessageInterface the composed message ready to be sent
     *
     * @throws \InvalidArgumentException if the message is not a NewThreadMessage
     */
    public function composeMessage(AbstractMessage $message)
    {
        if (!$message instanceof NewThreadMessage) {
            throw new \InvalidArgumentException(
                sprintf('Message must be a NewThreadMessage instance, "%s" given', get_class($message))
            );
        }
        /** @var WizardUser $user */
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user->isFriend($message->getRecipient())) {
            throw new \Exception("You must be friends to send a message");
        }

        // Verifier aue l'utilisateur est bien un amis
        return $this->composer->newThread()
            ->setSubject($message->getSubject())
            ->addRecipient($message->getRecipient())
            ->setSender($this->getAuthenticatedParticipant())
            ->setBody($message->getBody())
            ->getMessage();
    }
}
