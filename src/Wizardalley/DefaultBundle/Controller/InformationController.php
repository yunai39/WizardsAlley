<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\InformationBilletRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class InformationController
 *
 * @package Wizardalley\DefaultBundle\Controller
 */
class InformationController extends BaseController
{

    /**
     * getInformationsAction
     *
     * This action will return the list of information
     *
     * @Route("/user/information/{page}", name="wizardalley_information_page", options = {"expose" = true})
     *
     * @param $page
     *
     * @return Response
     */
    public function getInformationsAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var InformationBilletRepository $repo */
        $repo         = $em->getRepository('WizardalleyCoreBundle:InformationBillet');
        $informations = $repo->findInformationLimit(
            $page,
            1
        );

        return $this->sendJsonResponse(
            200,
            [
                'contenu' => $this->renderView(
                    '::information/list.html.twig',
                    ['informations' => $informations]
                )
            ]
        );
    }
}
