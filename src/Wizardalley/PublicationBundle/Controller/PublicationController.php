<?php

namespace Wizardalley\PublicationBundle\Controller;

use Composer\Repository\RepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\AbstractPublication;
use Wizardalley\CoreBundle\Entity\ImagePublication;
use Wizardalley\CoreBundle\Entity\ImagePublicationRepository;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\PageUserFollow;
use Wizardalley\CoreBundle\Entity\Publication;
use Wizardalley\CoreBundle\Entity\CommentPublication;
use Wizardalley\CoreBundle\Entity\PublicationRepository;
use Wizardalley\CoreBundle\Entity\PublicationUserLike;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\DefaultBundle\Controller\BaseController;
use Wizardalley\PublicationBundle\Form\DeletePublicationType;
use Wizardalley\PublicationBundle\Form\PublicationType;
use Wizardalley\PublicationBundle\Form\CommentType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Publication controller.
 *
 */
class PublicationController extends BaseController
{
    /**
     * Creates a new Publication entity.
     *
     * @Route("/user/publication/create/{id_page}", name="publication_create")
     * @Method({"POST"})
     * @param Request $request
     * @param         $id_page
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request, $id_page)
    {
        $entity = new Publication();

        $em   =
            $this->getDoctrine()
                 ->getManager()
        ;
        $page =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;
        $this->notFoundEntity($page);
        $this->creatorEditorOnly($page);

        $entity->setPage($page);
        $form = $this->createCreateForm(
            $entity,
            $page
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em =
                $this->getDoctrine()
                     ->getManager()
            ;
            $entity->setUser($this->getUser());
            $entity->setCreatedAt(new \DateTime('now'));
            $entity->setUpdatedAt(new \DateTime('now'));
            $entity->setPage($page);
            $entity->setSmallContent($entity->getContent());
            $em->persist($entity);
            /** @var ImagePublication $image */
            if (!empty($entity->getImages())) {
                foreach ($entity->getImages() as $image) {
                    $image->setPublication($entity);
                    $em->persist($image);
                }
            }
            $em->flush();

            $this->get('session')
                 ->getFlashBag()
                 ->add(
                     'success',
                     'wizard.publication.new_success'
                 )
            ;

            return $this->redirect(
                $this->generateUrl(
                    'publication_preview',
                    ['id' => $entity->getId()]
                )
            );
        }

        return $this->render(
            '::user/publication/new.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
                'page'   => $entity->getPage(),
            ]
        );
    }

    /**
     * Creates a form to create a Publication entity.
     *
     * @param Publication $entity The entity
     * @param Page        $page
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Publication $entity, $page)
    {
        $form = $this->createForm(
            new PublicationType(),
            $entity,
            [
                'action' => $this->generateUrl(
                    'publication_create',
                    ['id_page' => $page->getId()]
                ),
                'method' => 'POST',
            ]
        );

        $form->add(
            'submit',
            'submit',
            ['label' => 'Create']
        );

        return $form;
    }

    /**
     * Displays a form to create a new Publication entity.
     *
     * @param int $id_page
     * @Route("/user/publication/new/{id_page}", name="publication_new")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function newAction($id_page)
    {
        $entity = new Publication();

        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        $page =
            $em->getRepository('WizardalleyCoreBundle:Page')
               ->find($id_page)
        ;
        $this->notFoundEntity($page);
        $this->creatorEditorOnly($page);

        $entity->setPage($page);
        $form = $this->createCreateForm(
            $entity,
            $page
        );

        return $this->render(
            '::user/publication/new.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
                'page'   => $entity->getPage(),
            ]
        );
    }

    /**
     * Finds and displays a Publication entity.
     *
     * @Route("/{id}/show", name="publication_show")
     * @param int $id
     *
     * @return Response
     */
    public function showAction($id)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        /** @var Publication $entity */
        $entity =
            $em->getRepository('WizardalleyCoreBundle:Publication')
               ->find($id)
        ;
        $this->notFoundEntity($entity);

        if ($entity->getOnline() == 0) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }

        $comment     = new CommentPublication();
        $commentForm = $this->createFormComment(
            $comment,
            $id
        );

        $followers = [];
        /** @var PageUserFollow $follower */
        foreach($entity->getPage()->getFollowers() as $follower) {
            $followers[] = $follower->getUser();
        }


        return $this->render(
            '::user/publication/show.html.twig',
            [
                'entity'       => $entity,
                'page'         => $entity->getPage(),
                'creator_id'   => $entity->getPage()->getCreator()->getId(),
                'editors'      => $entity->getPage()->getEditors(),
                'followers'    => $followers,
                'entity_id'    => $id,
                'comment_form' => $commentForm->createView(),
            ]
        );
    }

    /**
     * Finds and displays a Publication entity.
     *
     * @Route("/user/publication/{id}/preview", name="publication_preview")
     * @param int $id
     *
     * @return Response
     */
    public function previewAction($id)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var Publication $entity */
        $entity =
            $em->getRepository('WizardalleyCoreBundle:Publication')
               ->find($id)
        ;
        $this->notFoundEntity($entity);

        $page = $entity->getPage();

        // Verifier les droits
        $user = $this->getUser();

        if (!($page->getCheckers()
                   ->contains($user) ||
              $page->getEditors()
                   ->contains($user) ||
              $page->getCreator() == $user)
        ) {
            throw new Exception('Access Forbidden');
        }

        return $this->render(
            '::user/publication/preview.html.twig',
            [
                'entity'    => $entity,
                'entity_id' => $id
            ]
        );
    }

    /**
     * Displays a form to edit an existing Publication entity.
     *
     * @Route("/publication/{id}/edit", name="publication_edit")
     * @param int $id
     *
     * @return Response
     */
    public function editAction($id)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        $entity =
            $em->getRepository('WizardalleyCoreBundle:Publication')
               ->find($id)
        ;
        $this->notFoundEntity($entity);
        $this->creatorPublicationAndCreatorOnly($entity);

        $editForm = $this->createEditForm($entity);

        return $this->render(
            '::user/publication/edit.html.twig',
            [
                'entity'    => $entity,
                'edit_form' => $editForm->createView(),
                'page'      => $entity->getPage(),
            ]
        );
    }

    /**
     * @param $id
     * @Route("/publication/{id}/toggleOnline", name="publication_toggle_online")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function togglePublicationOnlineAction($id)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        /** @var Publication $entity */
        $entity =
            $em->getRepository('WizardalleyCoreBundle:Publication')
               ->find($id)
        ;
        $this->notFoundEntity($entity);

        $user = $this->getUser();
        if ((!$entity->getPage()
                     ->getCheckers()
                     ->contains($user)) and
            ($entity->getPage()
                    ->getCreator() !== $user)
        ) {
            throw new AccessDeniedException;
        }

        $entity->setOnline(!$entity->getOnline());
        $em->persist($entity);
        $em->flush();
        $this->get('session')
             ->getFlashBag()
             ->add(
                 'success',
                 'wizard.publication.toogle_online.' . ($entity->getOnline() ? 'on' : 'off')
             )
        ;

        // Si l'entite est en ligne et quel n'a jamais encore ete mis en ligne
        if($entity->getOnline() && $entity->getHasBeenPublished() == false) {
            // Generation des notifications
            $this->get('wizard.helper.publication.notification')
                 ->generateNotificationForPublicationCreated($entity)
            ;

            $entity->setHasBeenPublished(true);
            $em->persist($entity);
            $em->flush();
        }


        /** @var Request $request */
        $request = $this->get('request');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * Creates a form to edit a Publication entity.
     *
     * @param Publication $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Publication $entity)
    {
        $form = $this->createForm(
            new PublicationType(),
            $entity,
            [
                'action' => $this->generateUrl(
                    'publication_update',
                    ['id' => $entity->getId()]
                ),
                'method' => 'PUT',
            ]
        );

        $form->add(
            'submit',
            'submit',
            ['label' => 'Update']
        );

        return $form;
    }

    /**
     * Edits an existing Publication entity.
     * @Route("/user/publication/{id}/update", name="publication_update")
     * @Method({"POST", "PUT"})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        /** @var Publication $entity */
        $entity =
            $em->getRepository('WizardalleyCoreBundle:Publication')
               ->find($id)
        ;

        $this->notFoundEntity($entity);
        $this->creatorPublicationAndCreatorOnly($entity);

        /** @var ImagePublicationRepository $imageRepo */
        $imageRepo     = $em->getRepository('WizardalleyCoreBundle:ImagePublication');
        $currentImages = $imageRepo->findBy(['publication' => $entity]);
        $editForm      = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUpdatedAt(new \DateTime('now'));
            // Gerer les images ajouter ou supprimer
            /** @var ImagePublication $image */
            if (!empty($entity->getImages())) {

                foreach ($entity->getImages() as $image) {
                    $image->setPublication($entity);
                    $em->persist($image);
                }
                foreach ($currentImages as $image) {
                    if (!$entity->getImages()
                                ->contains($image)
                    ) {//employee was removed, update entity/entities
                        $em->remove($image);
                    }
                }
            }
            $em->flush();

            $this->get('session')
                 ->getFlashBag()
                 ->add(
                     'success',
                     'wizard.publication.edit_success'
                 )
            ;

            return $this->redirect(
                $this->generateUrl(
                    'publication_show',
                    ['id' => $id]
                )
            );
        }

        return $this->render(
            '::user/publication/edit.html.twig',
            [
                'entity'    => $entity,
                'edit_form' => $editForm->createView(),
                'page'      => $entity->getPage(),
            ]
        );
    }

    /**
     * Creates a form to add a comment.
     *
     * @param CommentPublication $comment       The entity comment
     * @param int                $publicationId The id of the entity publication
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFormComment(CommentPublication $comment, $publicationId)
    {
        $form = $this->createForm(
            new CommentType(),
            $comment,
            [
                'action' => $this->generateUrl(
                    'comment_add',
                    ['id' => $publicationId]
                ),
                'method' => 'PUT',
            ]
        );

        $form->add(
            'submit',
            'submit',
            ['label' => 'Add comment']
        );

        return $form;
    }

    /**
     * addCommentAction
     *
     * Add a coment for a specific publication
     *
     * @Route("/user/comment/add/{id}", name="comment_add")
     * @param Request $request
     * @param         $id integer The entity publication id
     *
     * @return Response
     */
    public function addCommentAction(Request $request, $id)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        /** @var Publication $entity */
        $entity =
            $em->getRepository('WizardalleyCoreBundle:Publication')
               ->find($id)
        ;

        if (!$entity || $entity->getOnline() == 0) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }
        $comment = new CommentPublication();
        $form    = $this->createFormComment(
            $comment,
            $id
        );
        $form->handleRequest($request);

        if ($form->isValid()) {

            $comment->setUser($this->getUser())
                    ->setDateComment(new \DateTime('now'))
                    ->setContent($form->get('content')->getData())
                    ->setPublication($entity)
            ;
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirect(
            $this->generateUrl(
                'publication_show',
                ['id' => $id]
            )
        );
    }

    /**
     * getCommentAction
     *
     * fetch the comment for an action
     *
     * @Route("/comment/get/{id}/{page}", name="comment_get", options={"expose"=true})
     * @param Page    $page
     * @param         $id integer The entity publication id
     *
     * @return Response
     */
    public function getCommentAction($id, $page)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'data' => $em->getRepository('WizardalleyCoreBundle:CommentPublication')
                             ->findCommentsPublication(
                                 $id,
                                 $page
                             )
            ]
        );
    }

    /**
     * getPublicationAction
     *
     * fetch the comment for an action
     *
     * @Route("/publication/get/{id}/{page}", name="publication_get")
     * @param int  $id   integer user_id
     * @param Page $page integer page number
     *
     * @return Response
     */
    public function getPublicationAction($id, $page)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var PublicationRepository $repo */
        $repo = $em->getRepository('WizardalleyCoreBundle:Publication');

        return $this->sendJsonResponse(
            'success',
            [
                'no_message' => true,
                'data'       => $repo->findPublications(
                    $id,
                    $page
                )
            ]
        );
    }

    /**
     * getMostCommentPublicationAction
     *
     *
     * @Route("/publication/getMostComment/{page}", name="publication_get_most_comments", options={"expose"=true})
     * @param Page $page integer page number
     *
     * @return Response
     */
    public function getMostCommentPublicationAction($page)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var PublicationRepository $repo */
        $repo = $em->getRepository('WizardalleyCoreBundle:Publication');

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::user/publicationMostCommented.html.twig',
                    [
                        'publications' => $repo->findMostCommentedPublications(
                            $page
                        ),
                    ]
                )
            ]
        );
    }

    /**
     * getLatestPublicationAction
     *
     * @Route("/publication/latestPublication", name="publication_get_latest")
     *
     * @return Response
     */
    public function getLatestPublicationAction()
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;
        /** @var PublicationRepository $repo */
        $repo         = $em->getRepository('WizardalleyCoreBundle:Publication');
        $publications = $repo->findLatestPublication();

        return $this->render(
            '::discover/latestPublication.html.twig',
            [
                'publications' => $publications
            ]
        );
    }

    /**
     * @Route("/user/publication/{id}/like", name="publication_user_like", options={"expose"=true})
     * @Method({"POST"})
     * @param Publication $publication
     * @ParamConverter("publication", class="WizardalleyCoreBundle:Publication")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function likePublicationUser(Publication $publication)
    {
        /** @var WizardUser $user */
        $user = $this->getUser();
        /** @var EntityRepository $repo */
        $repo            =
            $this->getDoctrine()
                 ->getRepository("WizardalleyCoreBundle:PublicationUserLike")
        ;
        $publicationLike = $repo->findOneBy(
            [
                'user'        => $user->getId(),
                'publication' => $publication->getId()
            ]
        );

        if (!$publicationLike instanceof PublicationUserLike) {
            $publicationLike = new PublicationUserLike();
            $publicationLike->setUser($user)
                            ->setPublication($publication)
                            ->setDateLike(new \DateTime())
            ;
            $em =
                $this->getDoctrine()
                     ->getManager()
            ;
            $em->persist($publicationLike);
            $em->flush();

            return $this->sendJsonResponse(
                'success',
                []
            );
        }

        return $this->sendJsonResponse(
            'error',
            []
        );
    }

    /**
     * @Route("/user/publication/{id}/unlike", name="publication_user_unlike", options={"expose"=true})
     * @Method({"POST"})
     * @param Publication $publication
     * @ParamConverter("publication", class="WizardalleyCoreBundle:Publication")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function unlikePublicationUser(Publication $publication)
    {
        /** @var WizardUser $user */
        $user = $this->getUser();
        /** @var RepositoryInterface $repo */
        $repo            =
            $this->getDoctrine()
                 ->getRepository("WizardalleyCoreBundle:PublicationUserLike")
        ;
        $publicationLike = $repo->findOneBy(
            [
                'user'        => $user->getId(),
                'publication' => $publication->getId()
            ]
        );

        if ($publicationLike instanceof PublicationUserLike) {
            $em =
                $this->getDoctrine()
                     ->getManager()
            ;
            $em->remove($publicationLike);
            $em->flush();

            return $this->sendJsonResponse(
                'success',
                []
            );
        }

        return $this->sendJsonResponse(
            'error',
            []
        );
    }

    /**
     * @param $entity
     */
    private function notFoundEntity($entity)
    {
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }
    }

    /**
     * @param Publication $entity
     *
     * @throws AccessDeniedException
     */
    private function creatorPublicationAndCreatorOnly(Publication $entity)
    {
        $user = $this->getUser();
        if (($entity->getUser() !== $user) and
            ($entity->getPage()
                    ->getCreator() !== $user)
        ) {
            throw new AccessDeniedException;
        }
    }

    /**
     * @param Page $page
     */
    private function creatorEditorOnly(Page $page)
    {
        $user = $this->getUser();
        if (!(($page->getCreator() == $user) or
              ($page->getEditors()
                    ->contains($user)))
        ) {
            throw new AccessDeniedException;
        }
    }

    /**
     * @param $id
     *
     * @Route("/user/publication/remove/{id}", name="publication_user_remove", options={"expose"=true})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removePublicationAction($id)
    {
        $form = $this->createForm(new DeletePublicationType());

        $form->handleRequest($this->get('request'));
        $repo        =
            $this->getDoctrine()
                 ->getRepository("WizardalleyCoreBundle:Publication")
        ;
        $publication = $repo->find($id);

        if (!$publication instanceof Publication) {
            $repo        =
                $this->getDoctrine()
                     ->getRepository("WizardalleyCoreBundle:SmallPublication")
            ;
            $publication = $repo->find($id);
        }

        if ($form->isValid()) {
            /** @var WizardUser $user */
            $user = $this->getUser();

            if (!$publication instanceof AbstractPublication) {
                throw $this->createNotFoundException('Unable to find Publication.');
            }
            if ($publication->getUser()
                            ->getId() != $user->getId()
            ) {
                throw $this->createAccessDeniedException('This is not your publication');
            }

            $em =
                $this->getDoctrine()
                     ->getManager()
            ;
            $em->remove($publication);
            $em->flush();

            return $this->redirectToRoute(
                'wizardalley_user_wall',
                ['id' => $user->getId()]
            );
        }

        return $this->render(
            '::user/publication/deletePublication.html.twig',
            ['form' => $form->createView(), 'id' => $publication->getId()]
        );
    }
}
