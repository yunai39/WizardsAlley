<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\DefaultBundle\Form\ContactType;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;
use Wizardalley\PublicationBundle\Entity\SmallPublication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $repo =  $em->getRepository('WizardalleyAdminBundle:InformationBillet');
        
    }
    
    

}
