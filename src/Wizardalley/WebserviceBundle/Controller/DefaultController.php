<?php

namespace Wizardalley\WebserviceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class DefaultController extends Controller
{
    
    /**
     * @Route("/authentificationWsdl")
     * @Template()
     */
    public function indexAction()
    {
        
        $server = new \SoapServer('default.wsdl');
        $server->setObject($this->get('authentification_service'));

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $server->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }
}