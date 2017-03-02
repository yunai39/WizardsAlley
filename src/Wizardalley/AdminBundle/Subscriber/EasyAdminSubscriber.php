<?php

namespace Wizardalley\AdminBundle\Subscriber;

use Doctrine\ORM\EntityManager;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Wizardalley\CoreBundle\Entity\InformationBillet;
use Wizardalley\CoreBundle\Entity\Interfaces\TimedEntityInterface;

/**
 * Class EasyAdminSubscriber
 * @package Wizardalley\AdminBundle\Subscriber
 */
class EasyAdminSubscriber implements EventSubscriberInterface
{
    /** @var TokenStorage */
    protected $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            EasyAdminEvents::PRE_PERSIST => 'prePersist',
            EasyAdminEvents::PRE_UPDATE  => 'preUpdate',
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function preUpdate(GenericEvent $event)
    {
        $entity = $event->getSubject();
        if ($entity instanceof TimedEntityInterface) {
            $entity->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param GenericEvent $event
     */
    public function prePersist(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if ($entity instanceof InformationBillet) {
            $entity->setDateCreateBillet(new \DateTime());
            /** @var EntityManager $em */
            $em = $event->getArgument('em');
            $user = $this->tokenStorage->getToken()->getUser();
            $entity->setUser($em->getReference('WizardalleyCoreBundle:WizardUser', $user->getId()));
        }

        if ($entity instanceof TimedEntityInterface) {
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}
