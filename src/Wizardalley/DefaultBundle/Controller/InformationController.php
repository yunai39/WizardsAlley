<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InformationController
 * @package Wizardalley\DefaultBundle\Controller
 */
class InformationController extends BaseController
{


    /**
     * getInformationsAction
     *
     * This action will return the list of information
     *
     * pattern: /user/information/{page}
     * road_name: wizardalley_information_page
     *
     * @param $page
     * @return Response
     */
    public function getInformationsAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo =  $em->getRepository('WizardalleyCoreBundle:InformationBillet');
        $informations = $repo->findInformationLimit($page,1);
        return $this->sendJsonResponse(
            200,
            ['contenu' =>$this->renderView('WizardalleyDefaultBundle:Information:list.html.twig', ['informations' => $informations])]
        );
    }
}
