<?php

namespace Wizardalley\DefaultBundle\Controller;

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
        return $this->render('WizardalleyDefaultBundle:Discover:index.html.twig');
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
                    'WizardalleyDefaultBundle:Discover:page.html.twig',
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
        $publications = $em->getRepository('WizardalleyCoreBundle:PublicationFavorite')
            ->findPublicationLimit($page);

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    'WizardalleyDefaultBundle:Discover:publication.html.twig',
                    array(
                        'publications' => $publications,
                    )
                )
            ]
        );
    }


}
