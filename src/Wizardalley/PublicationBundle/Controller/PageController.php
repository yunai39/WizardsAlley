<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Page controller.
 *
 */
class PageController extends \Wizardalley\DefaultBundle\Controller\BaseController
{

    const LIMIT_PER_PAGE = 1;

    /**
     * 
     * @param type $id_page
     * @return type
     * @throws type
     */
    public function indexPageAction($id_page)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em   = $this->getDoctrine()->getManager();
        /* @var $page \Wizardalley\PublicationBundle\Entity\Page */
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        if ( !$page ) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        $latestFollower = $em->getRepository('WizardalleyPublicationBundle:Page')->findLatestFollower($page->getId(), 9);
        return $this->render('WizardalleyPublicationBundle:Page:show.html.twig'
            , [
                'page'       => $page,
                'followers'  => $latestFollower,
                'creator_id' => $page->getCreator()->getId(),
                'editors'    => $page->getEditors(),
        ]);
    }

    /**
     * 
     * @param type $id
     * @param type $page
     * @return type
     */
    public function displayPublicationPageAction($id, $page)
    {
        $repo         = $this->getDoctrine()->getRepository('WizardalleyPublicationBundle:Publication');
        $publications = $repo->findPublicationsPage($id, $page, self::LIMIT_PER_PAGE);
        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('WizardalleyPublicationBundle:Page:publication.html.twig', array(
                    'publications' => $publications,
                )) ]
        );
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $page
     * @return type
     */
    public function getPageFollowedAction(Request $request, $page = 1)
    {
        $em    = $this->getDoctrine()->getManager();
        $repo  = $this->getDoctrine()->getRepository('WizardalleyPublicationBundle:Page');
        $pages = $repo->findPageFollowedUser($this->getUser(), $page, self::LIMIT_PER_PAGE);
        return $this->sendJsonResponse('success', $pages
        );
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $page
     * @return type
     */
    public function getPageEditorAction(Request $request, $page = 1)
    {
        /* @var $repo \Wizardalley\PublicationBundle\Entity\PageRepository */
        $repo  = $this->getDoctrine()->getRepository('WizardalleyPublicationBundle:Page');
        /* @var $pages \Wizardalley\PublicationBundle\Entity\Page[] */
        $pages = $repo->findPageEditorUser($this->getUser(), $page, self::LIMIT_PER_PAGE);
        return $this->sendJsonResponse('success', $pages);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $page
     * @return type
     */
    public function getPageCreatedAction(Request $request, $page = 1)
    {
        $em    = $this->getDoctrine()->getManager();
        $repo  = $this->getDoctrine()->getRepository('WizardalleyPublicationBundle:Page');
        $pages = $repo->findPageCreatedUser($this->getUser(), $page, self::LIMIT_PER_PAGE);
        return $this->sendJsonResponse('success', $pages);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function likePageAction(Request $request)
    {
        $page_id = $request->request->get('page_id');

        $em           = $this->getDoctrine()->getManager();
        $page         = $em->getReference('Wizardalley\PublicationBundle\Entity\Page', $page_id);
        $pageFollowed = new \Wizardalley\PublicationBundle\Entity\PageUserFollow();
        $pageFollowed->setPage($page)->setUser($this->getUser());
        $pageFollowed->setDateInscription(new \DateTime('now'));
        $em->persist($pageFollowed);
        $em->flush();
        return $this->sendJsonResponse('success', [
                'message' => 'wizard.page.like.success'
        ]);
    }

}
