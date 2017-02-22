<?php

namespace Wizardalley\AdminBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use Wizardalley\CoreBundle\Entity\Page;

/**
 * Class AdminController
 * @package Wizardalley\AdminBundle\Controller
 */
class AdminController extends EasyAdminController
{
    protected function prepareEditEntityForPersist($entity)
    {
        if ($entity instanceof Page) {
            $entity->setUpdatedAt(new\ DateTime());
        }

        return $entity;
    }

    protected function prepareNewEntityForPersist($entity)
    {
        if ($entity instanceof Page) {
            $entity->setDateCreation(new\ DateTime());
            $entity->setUpdatedAt(new\ DateTime());
        }

        return $entity;
    }
}
