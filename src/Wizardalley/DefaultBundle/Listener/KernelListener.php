<?php

namespace Wizardalley\DefaultBundle\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * Class KernelListener
 * @package Wizardalley\DefaultBundle\Listener
 */
class KernelListener
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
    public function __construct(TokenStorage $tokenStorage, EntityManager $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        /** @var WizardUser $user */
        $user  = $token ? $token->getUser() : null;
        if ($user instanceof WizardUser) {
            $user->setLastConnect(new \DateTime());
            $this->em->persist($user);
            $this->em->flush();
        }
    }
}