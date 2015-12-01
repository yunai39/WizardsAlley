<?php

namespace Wizardalley\DefaultBundle\Controller;

use Wizardalley\DefaultBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends BaseController
{
    
    public function searchDisplayAction(Request $request)
    {
        /* @var $form Form */
        $form = $this->createForm(new SearchType(), null, array(
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        
        return $this->render('WizardalleyDefaultBundle:Default:search.html.twig', [ 'form' => $form->createView() ]);
    }
    
    public function searchUserAction($field, $page = 1){
        
    }
    
    public function searchPageAction($field, $page1 = 1){
        
    }
    
    public function searchPublicationAction($field, $page = 1) {
        $em = $this->getDoctrine()->getManager();
        $publications =  $em->getRepository('WizardalleyPublicationBundle:Publication')
            ->findPublicationLike($field, $page,8);
        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('WizardalleyDefaultBundle:Search:publication.html.twig', array(
                    'publications' => $publications,
                )) ]
        );
    }
    
}
