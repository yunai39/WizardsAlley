<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\DefaultBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;

/**
 * Class SearchController
 *
 * @package Wizardalley\DefaultBundle\Controller
 */
class SearchController extends BaseController
{
    /**
     * @Configuration\Route("/search", name="wizardalley_search_display")
     * @return Response
     */
    public function searchDisplayAction()
    {
        /* @var $form Form */
        $form = $this->createForm(
            new SearchType(), null, [
                'method'   => 'POST',
                'isOnline' => ($this->getUser() instanceof WizardUser)
            ]
        );
        $form->add(
            'submit',
            'submit',
            ['label' => 'wizard.search.action', 'attr' => ['class' => 'btn-block']]
        );

        return $this->render(
            '::default/search.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @param int $page
     * @Configuration\Route(
     *     "/searchAllUser/{page}",
     *      name="wizardalley_search_all_user",
     *      defaults={"page" = 1},
     *      requirements={"page" = "\d+"},
     *      options={"expose": true}
     *     )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAllUserAction($page = 1)
    {
        $em    = $this->getDoctrine()->getManager();
        $users = $em->getRepository('WizardalleyCoreBundle:WizardUser')->findUsersOrderCreated($page, 2);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::search/users.html.twig',
                    [
                        'users' => $users,
                    ]
                )
            ]
        );
    }

    /**
     * @param int $page
     * @Configuration\Route(
     *     "/searchAllCommunity/{page}",
     *      name="wizardalley_search_all_community",
     *      defaults={"page" = 1},
     *      requirements={"page" = "\d+"},
     *      options={"expose": true}
     *     )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAllCommunityAction($page = 1)
    {
        $em    = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('WizardalleyCoreBundle:Page')->findPagesOrderCreated($page, 2);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::search/page.html.twig',
                    [
                        'pages' => $pages,
                    ]
                )
            ]
        );
    }

    /**
     * @param     $field
     * @param int $page
     * @Configuration\Route(
     *     "/searchUser/{field}/{page}",
     *      name="wizardalley_search_user",
     *      defaults={"page" = 1},
     *      requirements={"page" = "\d+"},
     *      options={"expose": true}
     *     )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchUserAction($field, $page = 1)
    {
        $em    = $this->getDoctrine()->getManager();
        $users = $em->getRepository('WizardalleyCoreBundle:WizardUser')->findUsersLike($field, $page, 2);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::search/users.html.twig',
                    [
                        'users' => $users,
                    ]
                )
            ]
        );
    }

    /**
     * @param     $field
     * @param int $page
     * @Configuration\Route(
     *     "/searchPage/{field}/{page}",
     *      name="wizardalley_search_page",
     *      defaults={"page" = 1},
     *      requirements={"page" = "\d+"},
     *      options={"expose": true}
     *     )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchPageAction($field, $page = 1)
    {
        $em    = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('WizardalleyCoreBundle:Page')->findPagesLike($field, $page, 2);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::search/page.html.twig',
                    [
                        'pages' => $pages,
                    ]
                )
            ]
        );
    }

    /**
     * @param int $page
     * @Configuration\Route(
     *     "/searchOnline/{page}",
     *      name="wizardalley_search_online_page",
     *      defaults={"page" = 1},
     *      requirements={"page" = "\d+"},
     *      options={"expose": true}
     *     )
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchOnlineUserAction($page = 1)
    {
        $em    = $this->getDoctrine()->getManager();
        $users = $em->getRepository('WizardalleyCoreBundle:WizardUser')->findUsersOrderLastAction($page, 2);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::search/users.html.twig',
                    [
                        'users' => $users,
                    ]
                )
            ]
        );
    }

    /**
     * @Configuration\Route(
     *     "/searchPublication/{field}/{page}",
     *      name="wizardalley_search_publication",
     *      defaults={"page" = 1},
     *      requirements={"page" = "\d+"},
     *      options={"expose": true}
     *     )
     * @param     $field
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchPublicationAction($field, $page = 1)
    {
        $em           = $this->getDoctrine()->getManager();
        $publications = $em->getRepository('WizardalleyCoreBundle:Publication')->findPublicationLike($field, $page, 2);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::search/publication.html.twig',
                    [
                        'publications' => $publications,
                    ]
                )
            ]
        );
    }
}
