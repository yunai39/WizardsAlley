<?php

namespace Wizardalley\DefaultBundle\Controller;

use Wizardalley\DefaultBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends BaseController
{
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function searchDisplayAction(Request $request)
    {
        /* @var $form Form */
        $form = $this->createForm(new SearchType(), null, array(
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Create'));
        
        return $this->render('WizardalleyDefaultBundle:Default:search.html.twig', [ 'form' => $form->createView() ]);
    }
    
    /**
     * 
     * @param type $field
     * @param type $page
     * @return type
     */
    public function searchUserAction($field, $page = 1){
        $em = $this->getDoctrine()->getManager();
        $users =  $em->getRepository('WizardalleyUserBundle:WizardUser')
            ->findUsersLike($field, $page,8);
        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('WizardalleyDefaultBundle:Search:users.html.twig', array(
                    'users' => $users,
                )) ]
        );
        
    }
    
    /**
     * 
     * @param type $field
     * @param type $pageNb
     * @return type
     */
    public function searchPageAction($field, $pageNb = 1){
        $em = $this->getDoctrine()->getManager();
        $pages =  $em->getRepository('WizardalleyCoreBundle:Page')
            ->findPagesLike($field, $pageNb,8);
        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('WizardalleyDefaultBundle:Search:page.html.twig', array(
                    'pages' => $pages,
                )) ]
        );
        
    }
    
    /**
     * 
     * @param type $field
     * @param type $page
     * @return type
     */
    public function searchPublicationAction($field, $page = 1) {
        $em = $this->getDoctrine()->getManager();
        $publications =  $em->getRepository('WizardalleyCoreBundle:Publication')
            ->findPublicationLike($field, $page,8);
        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('WizardalleyDefaultBundle:Search:publication.html.twig', array(
                    'publications' => $publications,
                )) ]
        );
    }
    
}
