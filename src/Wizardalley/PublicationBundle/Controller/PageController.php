<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\PageCategory;
use Wizardalley\CoreBundle\Entity\PageRepository;
use Wizardalley\CoreBundle\Entity\PageUserFollow;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wizardalley\CoreBundle\Entity\PublicationRepository;

/**
 * Class PageController
 *
 * @package Wizardalley\PublicationBundle\Controller
 */
class PageController extends \Wizardalley\DefaultBundle\Controller\BaseController
{
    const LIMIT_PER_PAGE = 1;

    /**
     * @param $category_id
     * @Route("/category/{category_id}", name="wizardalley_page_category", requirements={"category_id" = "\d+"})
     *
     * @return Response
     */
    public function categoryPageIndexAction($category_id)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em       = $this->getDoctrine()->getManager();
        $category = $em->getRepository('WizardalleyCoreBundle:PageCategory')->find($category_id);
        if (!$category instanceof PageCategory) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        /** @var PageRepository $pageRepository */
        $pageRepository = $em->getRepository('WizardalleyCoreBundle:Page');

        return $this->render(
            '::default/category.html.twig',
            [
                'category' => $category,
                'pages'    => $pageRepository->findBy(['category' => $category])
            ]
        );
    }

    /**
     * @param int $id_page
     *
     * @Route("/page/show/{id_page}", name="page_show")
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

        return $this->render(
            '::page/show.html.twig',
            [
                'page'       => $page,
                'followers'  => $latestFollower,
                'creator_id' => $page->getCreator()
                                     ->getId(),
                'editors'    => $page->getEditors(),
                'checkers'   => $page->getCheckers(),
            ]
        );
    }

    /**
     * @param int $id
     * @param int $page
     * @Route(
     *     "/page/publication/get/{id}/{page}",
     *      name="page_publication_get",
     *      requirements={"page" = "\d+", "id" = "\d+"},
     *      defaults={"page" = 1},
     *     options = {"expose" = true}
     *     )
     *
     * @return Response
     */
    public function displayPublicationPageAction($id, $page)
    {
        /** @var PublicationRepository $repo */
        $repo         = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Publication');
        $publications = $repo->findPublicationsPage($id, $page, self::LIMIT_PER_PAGE);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::page/publication.html.twig',
                    [
                        'publications' => $publications,
                    ]
                )
            ]
        );
    }

    /**
     * @param int $page
     * @Route(
     *     "/user/page/followed/{user}/{page}",
     *      name="page_user_followed_get",
     *      requirements={"page" = "\d+"},
     *      defaults={"page" = 1},
     *     options = {"expose" = true}
     *     )
     *
     * @return Response
     */
    public function getPageFollowedAction($user, $page = 1)
    {
        $userRepo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $repo     = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Page');
        $pages    = $repo->findPageFollowedUser($userRepo->find($user), $page, self::LIMIT_PER_PAGE);

        return $this->sendJsonResponse('success', $pages);
    }

    /**
     * @param int $page
     *
     * @Route(
     *     "/user/page/editor/{page}",
     *      name="page_user_editor_get",
     *      requirements={"page" = "\d+"},
     *      defaults={"page" = 1},
     *     options = {"expose" = true}
     *     )
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
     * @Route(
     *     "/user/page/created/{user}/{page}",
     *      name="page_user_created_get",
     *      requirements={"page" = "\d+"},
     *      defaults={"page" = 1},
     *     options = {"expose" = true}
     *     )
     * @return Response
     */
    public function getPageCreatedAction($user, $page = 1)
    {
        $userRepo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $user     = $userRepo->find($user);
        $repo     = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Page');
        $pages    = $repo->findPageCreatedUser($user, $page, self::LIMIT_PER_PAGE);

        return $this->sendJsonResponse('success', $pages);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route(
     *     "/user/page/unlike",
     *      name="page_unlike",
     *     options = {"expose" = true}
     *     )
     * @return Response
     */
    public function unlikePageAction(Request $request)
    {
        $pageId = $request->request->get('page_id');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em   = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Page');
        $page = $repo->find($pageId);

        if (!$page instanceof Page) {
            return $this->sendJsonResponse(
                'error',
                [
                    'message' => 'wizard.page.unlike.error.unknown_page'
                ]
            );
        }

        $repo     = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:PageUserFollow');
        $pageLike = $repo->findOneBy(
            [
                'user' => $this->getUser()
                               ->getId(),
                'page' => $page->getId()
            ]
        );

        if ($pageLike instanceof PageUserFollow) {
            $em->remove($pageLike);
            $em->flush();
        }

        return $this->sendJsonResponse(
            'success',
            [
                'message' => 'wizard.page.unlike.success'
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @Route(
     *     "/user/page/like",
     *      name="page_like",
     *     options = {"expose" = true}
     *     )
     * @return Response
     */
    public function likePageAction(Request $request)
    {
        $page_id = $request->request->get('page_id');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em   = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:Page');
        $page = $repo->find($page_id);

        if (!$page instanceof Page) {
            return $this->sendJsonResponse(
                'error',
                [
                    'message' => 'wizard.page.like.error.unknown_page'
                ]
            );
        }

        // Chercher si l'utilisateur aime deja la page
        $repo     = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:PageUserFollow');
        $pageLike = $repo->findOneBy(
            [
                'user' => $this->getUser()
                               ->getId(),
                'page' => $page->getId()
            ]
        );

        if (!$pageLike instanceof PageUserFollow) {
            $pageFollowed = new PageUserFollow();
            $pageFollowed->setPage($page)->setUser($this->getUser());
            $pageFollowed->setDateInscription(new \DateTime('now'));
            $em->persist($pageFollowed);
            $em->flush();
        }

        return $this->sendJsonResponse(
            'success',
            [
                'message' => 'wizard.page.like.success'
            ]
        );
    }
}
