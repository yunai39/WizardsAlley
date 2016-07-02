<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\DefaultBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchController
 * @package Wizardalley\DefaultBundle\Controller
 */
class SearchController extends BaseController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Request $request
     * @return Response
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
     * @param $field
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchUserAction($field, $page = 1){
        $em = $this->getDoctrine()->getManager();
        $users =  $em->getRepository('WizardalleyCoreBundle:WizardUser')
            ->findUsersLike($field, $page,2);
        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('WizardalleyDefaultBundle:Search:users.html.twig', array(
                    'users' => $users,
                )) ]
        );
        
    }

    /**
     * @param $field
     * @param int $pageNb
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchPageAction($field, $page = 1){
        $em = $this->getDoctrine()->getManager();
        $pages =  $em->getRepository('WizardalleyCoreBundle:Page')
            ->findPagesLike($field, $page,2);
        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('WizardalleyDefaultBundle:Search:page.html.twig', array(
                    'pages' => $pages,
                )) ]
        );
        
    }

    /**
     * @param $field
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchPublicationAction($field, $page = 1) {
        $em = $this->getDoctrine()->getManager();
        $publications =  $em->getRepository('WizardalleyCoreBundle:Publication')
            ->findPublicationLike($field, $page,2);
        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('WizardalleyDefaultBundle:Search:publication.html.twig', array(
                    'publications' => $publications,
                )) ]
        );
    }
    
}
