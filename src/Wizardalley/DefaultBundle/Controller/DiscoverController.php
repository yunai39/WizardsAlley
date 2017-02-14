<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DiscoverController
 * @package Wizardalley\DefaultBundle\Controller
 */
class DiscoverController extends BaseController
{
    /**
     * @Route("/discover", name="wizardalley_discover_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexDiscoverAction()
    {
        return $this->render('::discover/index.html.twig');
    }

    /**
     * @Route("/discover/favPage/{page}", name="wizardalley_discover_fav_page", options={"expose"=true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadPageFavoriteAction($page)
    {
        $em    = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('WizardalleyCoreBundle:PageFavorite')
            ->findPageLimit($page);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::discover/page.html.twig',
                    array(
                        'pages' => $pages,
                    )
                )
            ]
        );
    }

    /**
     * @Route("/discover/favPublication/{page}", name="wizardalley_discover_fav_publication", options={"expose"=true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadPublicationFavoriteAction($page)
    {
        $em           = $this->getDoctrine()->getManager();
        $publications = $em->getRepository('WizardalleyCoreBundle:Publication')
            ->findPublicationFavorite($page);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::discover/publication.html.twig',
                    array(
                        'publications' => $publications,
                    )
                )
            ]
        );
    }

    /**
     * @Route("/discover/map", name="wizardalley_discover_map", options={"expose"=true})
     * @return \Symfony\Component\HttpFoundation\Response
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
