<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Wizardalley\CoreBundle\Entity\PageCategory;
use Wizardalley\CoreBundle\Entity\PageRepository;
use Wizardalley\CoreBundle\Entity\PageUserFollow;

/**
 * Class PageController
 * @package Wizardalley\PublicationBundle\Controller
 */
class PageController extends \Wizardalley\DefaultBundle\Controller\BaseController
{
    const LIMIT_PER_PAGE = 1;

    /**
     * @param $category_id
     *
     * @return Response
     */
    public function categoryPageIndexAction($category_id)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('WizardalleyCoreBundle:PageCategory')->find($category_id);
        if (!$category instanceof PageCategory) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        /** @var PageRepository $pageRepository */
        $pageRepository =  $em->getRepository('WizardalleyCoreBundle:Page');

        return $this->render(':default:page_category.html.twig', [
            'category'  => $category,
            'pages'     => $pageRepository->findBy(['category' => $category])
        ]);
    }

    /**
     * @param int $id_page
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function indexPageAction($id_page)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /* @var $page \Wizardalley\CoreBundle\Entity\Page */
        $page = $em->getRepository('WizardalleyCoreBundle:Page')->find($id_page);
        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        $latestFollower = $em->getRepository('WizardalleyCoreBundle:Page')->findLatestFollower($page->getId(), 9);
        return $this->render('::page/show.html.twig'
            , [
                'page' => $page,
                'followers' => $latestFollower,
                'creator_id' => $page->getCreator()->getId(),
                'editors' => $page->getEditors(),
            ]);
    }

    /**
     * @param int $id
     * @param int $page
     *
     * @return Response
     */
    public function displayPublicationPageAction($id, $page)
    {
        $repo         = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Publication');
        $publications = $repo->findPublicationsPage($id, $page, self::LIMIT_PER_PAGE);
        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::page/publication.html.twig',
                    array(
                        'publications' => $publications,
                    )
                )
            ]
        );
    }

    /**
     * @param int $page
     *
     * @return Response
     */
    public function getPageFollowedAction($page = 1)
    {
        $repo  = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Page');
        $pages = $repo->findPageFollowedUser($this->getUser(), $page, self::LIMIT_PER_PAGE);

        return $this->sendJsonResponse(
            'success',
            $pages
        );
    }

    /**
     * @param int $page
     *
     * @return Response
     */
    public function getPageEditorAction($page = 1)
    {
        /* @var $repo \Wizardalley\CoreBundle\Entity\PageRepository */
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Page');
        /* @var $pages \Wizardalley\CoreBundle\Entity\Page[] */
        $pages = $repo->findPageEditorUser($this->getUser(), $page, self::LIMIT_PER_PAGE);

        return $this->sendJsonResponse('success', $pages);
    }

    /**
     * @param int $page
     *
     * @return Response
     */
    public function getPageCreatedAction($page = 1)
    {
        $repo  = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Page');
        $pages = $repo->findPageCreatedUser($this->getUser(), $page, self::LIMIT_PER_PAGE);

        return $this->sendJsonResponse('success', $pages);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return Response
     */
    public function likePageAction(Request $request)
    {
        $page_id = $request->request->get('page_id');

        $em           = $this->getDoctrine()->getManager();
        $repo         = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Page');
        $page         = $repo->find($page_id);
        $pageFollowed = new PageUserFollow();
        $pageFollowed
            ->setPage($page)
            ->setUser($this->getUser());
        $pageFollowed->setDateInscription(new \DateTime('now'));
        $em->persist($pageFollowed);
        $em->flush();

        return $this->sendJsonResponse('success', [
            'message' => 'wizard.page.like.success'
        ]);
    }
}
