<?php

namespace Wizardalley\AdminBundle\Subscriber;

use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Wizardalley\CoreBundle\Entity\Interfaces\TimedEntityInterface;

/**
 * Class EasyAdminSubscriber
 * @package Wizardalley\AdminBundle\Subscriber
 */
class EasyAdminSubscriber implements EventSubscriberInterface
{
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
        if ($entity instanceof TimedEntityInterface) {
            $entity->setCreatedAt(new \DateTime());
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}
