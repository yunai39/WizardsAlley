<?php

namespace Wizardalley\PublicationBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Wizardalley\CoreBundle\Entity\PublicationRepository;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\PublicationBundle\Form\PageCheckerType;
use Wizardalley\PublicationBundle\Form\PageType;
use Wizardalley\PublicationBundle\Form\PageEditorType;
use Wizardalley\CoreBundle\Entity\Page;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class GestionPageController
 *
 * @package Wizardalley\PublicationBundle\Controller
 */
class GestionPageController extends Controller
{

    /**
     * indexAction
     *
     * This action will present the page for the gestion of the page
     *
     * @Route("/page/gestion/show/{id_page}", name="page_gestion_show", requirements={"id_page" = "\d+"})
     *
     * @param int $id_page
     *
     * @return Response
     */
    public function indexAction($id_page)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var Page $page */
        $page =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;

        $this->notFoundEntity($page);
        $this->creatorEditorOnly($page);

        $form = $this->editFormPage($page);

        return $this->render(
            '::page/gestionPage/index.html.twig',
            [
                'page' => $page,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * editUserWriterFormAction
     *
     * This action will display a form to edit the user allowed to manage the page
     * @Route("/page/gestion/user/writer/{id_page}", name="page_gestion_user_writer", requirements={"id_page" = "\d+"})
     *
     * @param int $id_page
     *
     * @return Response
     */
    public function editUserWriterFormAction($id_page)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var Page $page */
        $page =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;

        $this->notFoundEntity($page);
        $this->creatorOnly($page);

        $form =
            $this->createFormUserPage(
                $page,
                'page_gestion_user_writer_edit',
                new PageEditorType()
            );

        return $this->render(
            '::page/gestionPage/editUser.html.twig',
            [
                'page' => $page,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * editUserWriterAction
     *
     * This action will record the edition for the user management of the page
     * @Route("/page/gestion/user/edit/writer/{id_page}", name="page_gestion_user_writer_edit", requirements={"id_page"
     *                                                    = "\d+"})
     *
     * @param Request $request
     * @param int     $id_page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws EntityNotFoundException
     */
    public function editUserWriterAction(Request $request, $id_page)
    {
        $em     =
            $this->getDoctrine()
                 ->getManager()
        ;
        $entity =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;

        if (!$entity instanceof Page) {
            throw new EntityNotFoundException();
        }
        $this->notFoundEntity($entity);
        $this->creatorOnly($entity);

        $editForm =
            $this->createFormUserPage(
                $entity,
                'page_gestion_user_writer_edit',
                new PageEditorType()
            );
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->removeAllEditor();
            $editors = $request->get('wizardalley_publicationbundle_page_editor');
            foreach ($editors[ 'editors' ] as $editor) {
                $entity->addEditor(
                    $em->getReference(
                        'WizardalleyCoreBundle:WizardUser',
                        $editor[ 'id' ]
                    )
                );
            }

            $em->persist($entity);
            $em->flush();
            $this->get('session')
                 ->getFlashBag()
                 ->add(
                     'success',
                     'wizard.page.edit_success'
                 )
            ;

            return $this->redirect(
                $this->generateUrl(
                    'page_gestion_user_writer',
                    ['id_page' => $id_page]
                )
            );
        }

        return $this->render(
            '::page/gestionPage/editUser.html.twig',
            [
                'page' => $entity,
                'form' => $editForm->createView(),
            ]
        );
    }

    /**
     * editUserCheckedFormAction
     *
     * This action will display a form to edit the user allowed to manage the page
     * @Route("/page/gestion/user/checker/{id_page}", name="page_gestion_user_checker", requirements={"id_page" =
     *                                                "\d+"})
     *
     * @param int $id_page
     *
     * @return Response
     */
    public function editUserCheckedFormAction($id_page)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var Page $page */
        $page =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;

        $this->notFoundEntity($page);
        $this->creatorOnly($page);

        $form =
            $this->createFormUserPage(
                $page,
                'page_gestion_user_checker_edit',
                new PageCheckerType()
            );

        return $this->render(
            '::page/gestionPage/editUserChecker.html.twig',
            [
                'page' => $page,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * editUserCheckerAction
     *
     * This action will record the edition for the user management of the page
     * @Route("/page/gestion/user/checker/edit/{id_page}", name="page_gestion_user_checker_edit",
     *                                                     requirements={"id_page" = "\d+"})
     *
     * @param Request $request
     * @param int     $id_page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws EntityNotFoundException
     */
    public function editUserCheckerAction(Request $request, $id_page)
    {
        $em     =
            $this->getDoctrine()
                 ->getManager()
        ;
        $entity =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;

        if (!$entity instanceof Page) {
            throw new EntityNotFoundException();
        }
        $this->notFoundEntity($entity);
        $this->creatorOnly($entity);

        $editForm =
            $this->createFormUserPage(
                $entity,
                'page_gestion_user_checker_edit',
                new PageCheckerType()
            );
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->removeAllChecker();
            $checkers = $request->get('wizardalley_publicationbundle_page_checker');
            /** @var WizardUser $checker */
            foreach ($checkers[ 'checkers' ] as $checker) {
                $entity->addChecker(
                    $em->getReference(
                        'WizardalleyCoreBundle:WizardUser',
                        $checker[ 'id' ]
                    )
                );
            }

            $em->persist($entity);
            $em->flush();
            $this->get('session')
                 ->getFlashBag()
                 ->add(
                     'success',
                     'wizard.page.edit_success'
                 )
            ;

            return $this->redirect(
                $this->generateUrl(
                    'page_gestion_user_checker',
                    ['id_page' => $id_page]
                )
            );
        }

        return $this->render(
            '::page/gestionPage/editUserChecker.html.twig',
            [
                'page' => $entity,
                'form' => $editForm->createView(),
            ]
        );
    }

    /**
     * editPageAction
     *
     * This action will display a form to edit the content of the page
     * @Route("/page/gestion/edit/{id_page}", name="page_gestion_edit", requirements={"id_page" = "\d+"})
     *
     * @param Request $request
     * @param int     $id_page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws EntityNotFoundException
     */
    public function editPageAction(Request $request, $id_page)
    {
        $em     =
            $this->getDoctrine()
                 ->getManager()
        ;
        $entity =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;

        if (!$entity instanceof Page) {
            throw new EntityNotFoundException();
        }

        $this->notFoundEntity($entity);
        $this->creatorEditorOnly($entity);
        $editForm = $this->editFormPage($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->uploadProfile();
            $entity->uploadCouverture();
            $em->flush();
            $this->get('session')
                 ->getFlashBag()
                 ->add(
                     'success',
                     'wizard.page.user.edit_success'
                 )
            ;

            return $this->redirect(
                $this->generateUrl(
                    'page_gestion_show',
                    ['id_page' => $id_page]
                )
            );
        }

        return $this->render(
            '::page/gestionPage/index.html.twig',
            [
                'page' => $entity,
                'form' => $editForm->createView(),
            ]
        );
    }

    /**
     * Displays a form to create a new Publication entity.
     * @Route("/user/page/new", name="page_gestion_new")
     *
     * @return Response
     */
    public function newAction()
    {
        $entity = new Page();

        $form = $this->createFormPage($entity);

        return $this->render(
            '::user/page/new.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * Creates a new Publication entity.
     *
     * @Route("/user/page/create", name="page_gestion_create")
     * @Method({"POST", "PUT"})
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $entity = new Page();

        $form = $this->createFormPage($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em =
                $this->getDoctrine()
                     ->getManager()
            ;
            $entity->setCreator($this->getUser());
            $entity->setCreatedAt(new \DateTime());
            $entity->setOfficialPage(false);
            $entity->uploadCouverture();
            $entity->uploadProfile();
            $em->persist($entity);
            $em->flush();
            $this->get('session')
                 ->getFlashBag()
                 ->add(
                     'success',
                     'wizard.page.new_success'
                 )
            ;

            return $this->redirect(
                $this->generateUrl(
                    'page_show',
                    ['id_page' => $entity->getId()]
                )
            );
        }

        return $this->render(
            '::user/page/new.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]
        );
    }

    /**
     * displayPublicationAction
     *
     * This action will display a form to edit the content of the page
     *
     *
     * @Route("/user/page/publication/{id_page}/{page}", name="page_gestion_publication", defaults={"page" = 1})
     * @param int $id_page
     * @param int $page
     *
     * @return Response
     */
    public function displayPublicationAction($id_page, $page = 1)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var PublicationRepository $repository */
        $repository = $em->getRepository('WizardalleyCoreBundle:Publication');
        /** @var Page $pageEntity */
        $pageEntity =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;

        $qb    = $repository->createQueryBuilder('p');
        $query =
            $qb->join(
                'p.page',
                'pa'
            )
               ->where(' pa.id = ' . $id_page)
               ->orderBy(
                   'p.createdAt',
                   'DESC'
               )
               ->getQuery()
        ;

        $this->notFoundEntity($pageEntity);
        $this->creatorEditorOnly($pageEntity);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            /* query NOT result */
            $page
            /*page number*/,
            10/*limit per page*/
        );

        return $this->render(
            '::page/gestionPage/list.html.twig',
            [
                'id_page'    => $id_page,
                'pagination' => $pagination,
                'page'       => $pageEntity,
            ]
        );
    }

    /**
     * createFormPage
     *
     * This function will return the form for the creation of the page
     *
     * @param Page $page
     *
     * @return Form
     */
    private function createFormPage(Page $page)
    {
        $form = $this->createForm(
            new PageType(),
            $page,
            [
                'action' => $this->generateUrl('page_gestion_create'),
                'method' => 'PUT',
            ]
        );

        $form->add(
            'submit',
            'submit',
            ['label' => 'wizard.utility.form.create']
        );

        return $form;
    }

    /**
     * editFormPage
     *
     *
     * This function will return the form for the edition of the page
     *
     * @param Page $page
     *
     * @return Form
     */
    private function editFormPage(Page $page)
    {
        $form = $this->createForm(
            new PageEditType(),
            $page,
            [
                'action' => $this->generateUrl(
                    'page_gestion_edit',
                    ['id_page' => $page->getId()]
                ),
                'method' => 'PUT',
            ]
        );

        $form->add(
            'submit',
            'submit',
            ['label' => 'wizard.utility.form.update']
        );

        return $form;
    }

    /**
     * createFormUserPage
     *
     * This function will return the form for the edition of the editor of the page
     *
     * @param Page $page
     *
     * @return Form
     */
    private function createFormUserPage(Page $page, $route_name, $formType)
    {
        $form = $this->createForm(
            $formType,
            $page,
            [
                'action' => $this->generateUrl(
                    $route_name,
                    ['id_page' => $page->getId()]
                ),
                'method' => 'PUT',
            ]
        );

        $form->add(
            'submit',
            'submit',
            ['label' => 'wizard.utility.form.update']
        );

        return $form;
    }

    /**
     * notFoundEntity
     * Throw an exception in case the entity does not existe
     *
     * @param Page $entity
     *
     * @throws NotFoundHttpException
     */
    private function notFoundEntity($entity)
    {
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }
    }

    /**
     * creatorOnly
     * Throw an exception in case the user is not allowed creator only
     *
     * @param Page $page
     *
     * @throws AccessDeniedException
     */
    private function creatorOnly($page)
    {
        $user = $this->getUser();
        if (!(($page->getCreator() == $user))) {
            throw new AccessDeniedException;
        }
    }

    /**
     * creatorEditorOnly
     * Throw an exception in case the user is not allowed, creator end editor only
     *
     * @param Page $page
     *
     * @throws AccessDeniedException
     */
    private function creatorEditorOnly($page)
    {
        $user = $this->getUser();
        if (!(($page->getCreator() == $user) or
              ($page->getEditors()
                    ->contains($user)))
        ) {
            throw new AccessDeniedException;
        }
    }

}
