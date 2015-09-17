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

    public function indexPageAction($id_page) {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        $latestFollower = $em->getRepository('WizardalleyPublicationBundle:Page')->findLatestFollower($page->getId(), 9);
        return $this->render('WizardalleyPublicationBundle:Page:show.html.twig', array(
                    'page' => $page,
                    'followers' => $latestFollower,
                    'creator_id' => $page->getCreator()->getId(),
                    'editors' => $page->getEditors(),
        ));
    }

    public function displayPublicationPageAction($id, $page) {

        $limit = 2;
        $repo = $this->getDoctrine()->getRepository('WizardalleyPublicationBundle:Publication');
        $publications = $repo->findPublicationsPage($id, $page, $limit);
        return $this->render('WizardalleyPublicationBundle:Page:publication.html.twig', array(
                    'publications' => $publications,
        ));
    }

}
