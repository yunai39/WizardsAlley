<?php

namespace Wizardalley\DefaultBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wizardalley\CoreBundle\Entity\PageFavoriteRepository;
use Wizardalley\CoreBundle\Entity\PublicationRepository;

/**
 * Class DiscoverController
 *
 * @package Wizardalley\DefaultBundle\Controller
 */
class DiscoverController extends BaseController
{
    /**
     * @Route("/discover", name="wizardalley_discover_index")
     * @Route("/", name="wizardalley_index")
     * @return Response
     */
    public function indexDiscoverAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        return $this->render(
            '::discover/index.html.twig',
            [
                'categories' => $em->getRepository('WizardalleyCoreBundle:PageCategory')->findAll()
            ]
        );
    }

    /**
     * @Route("/discover/favPage/{page}", name="wizardalley_discover_fav_page", options={"expose"=true})
     * @return Response
     */
    public function loadPageFavoriteAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var PageFavoriteRepository $repo */
        $repo  = $em->getRepository('WizardalleyCoreBundle:PageFavorite');
        $pages = $repo->findPageLimit($page);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::discover/page.html.twig',
                    [
                        'pages' => $pages,
                    ]
                )
            ]
        );
    }

    /**
     * @Route("/discover/favPublication/{page}", name="wizardalley_discover_fav_publication", options={"expose"=true})
     * @return Response
     */
    public function loadPublicationFavoriteAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var PublicationRepository $repo */
        $repo         = $em->getRepository('WizardalleyCoreBundle:Publication');
        $publications = $repo->findPublicationFavorite($page);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::discover/publication.html.twig',
                    [
                        'publications' => $publications,
                    ]
                )
            ]
        );
    }

    /**
     * @Route("/discover/map", name="wizardalley_discover_map", options={"expose"=true})
     * @return Response
     */
    public function mapWizardWorldAction()
    {
        $em   = $this->getDoctrine()->getManager();
        $maps = $em->getRepository('WizardalleyCoreBundle:MapObject')->findAll();

        return $this->render(
            '::discover/map.html.twig',
            [
                'maps' => $maps
            ]
        );
    }
}
