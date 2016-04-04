<?php

namespace Wizardalley\WebServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/test")
     * @Template()
     */
    public function authentificationAction()
    {
        return [ "contenu" => "Ceci est un test"];
    }
}
