<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InformationController
 * @package Wizardalley\DefaultBundle\Controller
 */
class InformationController extends Controller
{
    /**
     * listAction
     * 
     * This action will return the list of information
     * 
     * pattern: /user/information/list
     * road_name: wizardalley_information_list
     * 
     * @return Response
     */
    public function listAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $repo =  $em->getRepository('WizardalleyCoreBundle:InformationBillet');
    }
}
