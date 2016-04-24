<?php

namespace Wizardalley\WebServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/test", name="api_test", options={"expose"=true})
     */
    public function testAction()
    {
        return new JsonResponse([ "contenu" => "Ceci est un test"]);
    }
}
