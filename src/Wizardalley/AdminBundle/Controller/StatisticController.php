<?php

namespace Wizardalley\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\CoreBundle\Entity\PageRepository;
use Wizardalley\CoreBundle\Entity\PublicationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;

/**
 * Class StatisticController
 *
 * @package Wizardalley\AdminBundle\Controller
 */
class StatisticController extends Controller
{
    /**
     * @Configuration\Route("/statistic")
     */
    public function statisticAction()
    {
        /** @var EntityManager $em */
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var PublicationRepository $publicationRepository */
        $publicationRepository = $em->getRepository('WizardalleyCoreBundle:Publication');
        /** @var PageRepository $pageRepository */
        $pageRepository = $em->getRepository('WizardalleyCoreBundle:Page');

        return $this->render(
            'WizardalleyAdminBundle:Default:statistique.html.twig',
            [
                'nbPage'                 => count($pageRepository->findAll()),
                'nbPublication'          => count($publicationRepository->findAll()),
                'nbUtilisateur'          => count(
                    $em->getRepository('WizardalleyCoreBundle:WizardUser')
                       ->findAll()
                ),
                'nbPublicationThisMonth' => count($publicationRepository->findPublicationThisMonth()),
                'nbPageThisMonth'        => count($pageRepository->findPageThisMonth()),
            ]
        );
    }
}
