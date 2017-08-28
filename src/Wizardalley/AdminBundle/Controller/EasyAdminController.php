<?php

namespace Wizardalley\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Wizardalley\CoreBundle\Entity\PageFavorite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Wizardalley\CoreBundle\Entity\PageRepository;
use Wizardalley\CoreBundle\Entity\Publication;
use Wizardalley\CoreBundle\Entity\PublicationFavorite;

/**
 * EasyAdminController controller.
 *
 * @Route("/easyadmin")
 */
class EasyAdminController extends Controller
{

    /**
     * @Route(path="/blame/redirect", name="redirect_blame")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function redirectBlameAction(Request $request)
    {
        // change the properties of the given entity and save the changes
        $em         =
            $this->getDoctrine()
                 ->getManager()
        ;
        $repository =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:Blame')
        ;

        $id    = $request->query->get('id');
        $blame = $repository->find($id);

        switch ($blame->getType()) {
            // Page
            case 0:
                return $this->redirectToRoute(
                    'easyadmin',
                    [
                        'action' => 'edit',
                        'entity' => 'Page',
                        'id'     => $blame->getContentId()
                    ]
                );
                break;
            // Publication
            case 1:
                $entity =
                    $em->getRepository('WizardalleyCoreBundle:Publication')
                       ->find($blame->getContentId())
                ;
                if ($entity instanceof Publication) {
                    return $this->redirectToRoute(
                        'easyadmin',
                        [
                            'action' => 'edit',
                            'entity' => 'Publication',
                            'id'     => $blame->getContentId()
                        ]
                    );
                } else {
                    return $this->redirectToRoute(
                        'easyadmin',
                        [
                            'action' => 'edit',
                            'entity' => 'SmallPublication',
                            'id'     => $blame->getContentId()
                        ]
                    );
                }
                break;
            // User
            case 2:
                return $this->redirectToRoute(
                    'easyadmin',
                    [
                        'action' => 'edit',
                        'entity' => 'WizardUser',
                        'id'     => $blame->getContentId()
                    ]
                );
                break;
        }
    }

    /**
     * @Route(path="/page/fav", name="page_fav")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function favPageAction(Request $request)
    {
        // change the properties of the given entity and save the changes
        $em         =
            $this->getDoctrine()
                 ->getManager()
        ;
        $repository =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:Page')
        ;

        $id   = $request->query->get('id');
        $page = $repository->find($id);

        $favPage = new PageFavorite();
        $favPage
            ->setDateFavorite(new \DateTime())
            ->setPage($page)
        ;

        $em->persist($favPage);
        $em->flush();

        return $this->redirectToRoute(
            'easyadmin',
            [
                'action' => 'list',
                'entity' => $request->query->get('entity'),
            ]
        );
    }

    /**
     * @Route(path="/page/unfav", name="page_unfav")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function unfavPageAction(Request $request)
    {
        // change the properties of the given entity and save the changes
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var PageRepository $repository */
        $repository =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:PageFavorite')
        ;

        $id      = $request->query->get('id');
        $favPage = $repository->findOneBy(['page' => $id]);

        $em->remove($favPage);
        $em->flush();

        return $this->redirectToRoute(
            'easyadmin',
            [
                'action' => 'list',
                'entity' => $request->query->get('entity'),
            ]
        );
    }

    /**
     * @Route(path="/publication/fav", name="publication_fav")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function favPublicationAction(Request $request)
    {
        // change the properties of the given entity and save the changes
        $em         =
            $this->getDoctrine()
                 ->getManager()
        ;
        $repository =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:Publication')
        ;

        $id          = $request->query->get('id');
        $publication = $repository->find($id);

        $favPublication = new PublicationFavorite();
        $favPublication
            ->setDateFavorite(new \DateTime())
            ->setPublication($publication)
        ;

        $em->persist($favPublication);
        $em->flush();

        return $this->redirectToRoute(
            'easyadmin',
            [
                'action' => 'list',
                'entity' => $request->query->get('entity'),
            ]
        );
    }

    /**
     * @Route(path="/publication/unfav", name="publication_unfav")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function unfavPublicationAction(Request $request)
    {
        // change the properties of the given entity and save the changes
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var PageRepository $repository */
        $repository =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:PublicationFavorite')
        ;

        $id             = $request->query->get('id');
        $favPublication = $repository->findOneBy(['publication' => $id]);

        $em->remove($favPublication);
        $em->flush();

        return $this->redirectToRoute(
            'easyadmin',
            [
                'action' => 'list',
                'entity' => $request->query->get('entity'),
            ]
        );
    }
}
