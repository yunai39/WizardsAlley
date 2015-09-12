<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\PublicationBundle\Entity\Publication;
use Wizardalley\PublicationBundle\Entity\Comment;
use Wizardalley\PublicationBundle\Form\PublicationType;
use Wizardalley\PublicationBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Page controller.
 *
 */
class PageController extends Controller {
    
    public function indexPageAction($id_page){
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        
        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        
        return $this->render('WizardalleyPublicationBundle:Page:show.html.twig', array(
                    'page' => $page,
        ));
    }
}
