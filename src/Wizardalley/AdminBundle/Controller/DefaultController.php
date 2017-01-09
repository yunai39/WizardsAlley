<?php

namespace Wizardalley\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wizardalley\CoreBundle\Entity\PageRepository;
use Wizardalley\CoreBundle\Entity\PublicationRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/admin")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }
    /**
     * @Route("/admin/statistique")
     */
    public function statistiqueAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var PublicationRepository $publicationRepository */
        $publicationRepository = $em->getRepository('WizardalleyCoreBundle:Publication');
        /** @var PageRepository $pageRepository */
        $pageRepository = $em->getRepository('WizardalleyCoreBundle:Page');
        return $this->render(
            'WizardalleyAdminBundle:Default:statistique.html.twig',
            [
                'nbPage'                 => count($pageRepository->findAll()),
                'nbPublication'          => count($publicationRepository->findAll()),
                'nbUtilisateur'          => count($em->getRepository('WizardalleyCoreBundle:WizardUser')->findAll()),
                'nbPublicationThisMonth' => count($publicationRepository->findPublicationThisMonth()),
                'nbPageThisMonth'        => count($pageRepository->findPageThisMonth()),
            ]
        );
    }
}
