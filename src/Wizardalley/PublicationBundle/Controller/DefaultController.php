<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WizardalleyPublicationBundle:Default:index.html.twig', array('name' => $name));
    }
}
