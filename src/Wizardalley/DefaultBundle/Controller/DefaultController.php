<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:index.html.twig', array('name' => 'Harry'));
    }
}
