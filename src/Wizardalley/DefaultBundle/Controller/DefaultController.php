<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:index.html.twig');
    }
    public function mentionAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:mention.html.twig');
    }
    public function copyrightAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:copyright.html.twig');
    }
    public function confidentialityAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:confidentiality.html.twig');
    }
}
