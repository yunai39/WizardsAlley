<?php

namespace Wizardalley\PublicationBundle\Controller;

use Composer\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\Publication;
use Wizardalley\CoreBundle\Entity\CommentPublication;
use Wizardalley\CoreBundle\Entity\PublicationRepository;
use Wizardalley\CoreBundle\Entity\PublicationUserLike;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\DefaultBundle\Controller\BaseController;
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

        $em   = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyCoreBundle:Page')->find($id_page);
        $this->notFoundEntity($page);
        $this->creatorEditorOnly($page);

        $entity->setPage($page);
        $form = $this->createCreateForm($entity, $page);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());
            $entity->setDatePublication(new \DateTime('now'));
            $entity->setPage($page);
            $em->persist($entity);
            $entity->setSmallContent($entity->getContent());
            if ($entity->getImages()) {
                foreach ($entity->getImages() as $img) {
                    $img->upload();
                    $img->setPublication($entity);
                    $em->persist($img);
                }
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'wizard.publication.new_success');

            return $this->redirect($this->generateUrl('publication_show', array('id' => $entity->getId())));
        }

        return $this->render('WizardalleyPublicationBundle:Publication:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'page'   => $entity->getPage(),
        ));
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
        $form = $this->createForm(new PublicationType(), $entity, array(
            'action' => $this->generateUrl('publication_create', array('id_page' => $page->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Publication entity.
     *
     * @param int $id_page
     * @Route("/user/publication/new/{id_page}", name="publication_new")
     * @Method({"POST"})
     *
     * @return Response
     */
    public function newAction($id_page)
    {
        $entity = new Publication();

        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('WizardalleyCoreBundle:Page')->find($id_page);
        $this->notFoundEntity($page);
        $this->creatorEditorOnly($page);

        $entity->setPage($page);
        $form = $this->createCreateForm($entity, $page);
        return $this->render('WizardalleyPublicationBundle:Publication:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'page'   => $entity->getPage(),
        ));
    }

    /**
     * Finds and displays a Publication entity.
     *
     * @Route("/publication/{id}/show", name="publication_show")
     * @param int $id
     *
     * @return Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:Publication')->find($id);
        $this->notFoundEntity($entity);

        $comment     = new CommentPublication();
        $commentForm = $this->createFormComment($comment, $id);


        return $this->render('WizardalleyPublicationBundle:Publication:show.html.twig', array(
            'entity'       => $entity,
            'entity_id'    => $id,
            'comment_form' => $commentForm->createView(),
        ));
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:Publication')->find($id);
        $this->notFoundEntity($entity);
        $this->creatorPublicationOnly($entity);

        if ($entity->getUser() != $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to edit this entity');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('WizardalleyPublicationBundle:Publication:edit.html.twig', array(
            'entity'    => $entity,
            'edit_form' => $editForm->createView(),
            'page'      => $entity->getPage(),
        ));
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
        $form = $this->createForm(new PublicationType(), $entity, array(
            'action' => $this->generateUrl('publication_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:Publication')->find($id);

        $this->notFoundEntity($entity);
        $this->creatorPublicationOnly($entity);

        if ($entity->getUser() != $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to edit this entity');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'wizard.publication.edit_success');
            return $this->redirect($this->generateUrl('publication_show', array('id' => $id)));
        }

        return $this->render('WizardalleyPublicationBundle:Publication:edit.html.twig', array(
            'entity'    => $entity,
            'edit_form' => $editForm->createView(),
            'page'      => $entity->getPage(),
        ));
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
        $form = $this->createForm(new CommentType(), $comment, array(
            'action' => $this->generateUrl('comment_add', array('id' => $publicationId)),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Add comment'));

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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }
        $comment = new CommentPublication();
        $form    = $this->createFormComment($comment, $id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $comment
                ->setUser($this->getUser())
                ->setDateComment(new \DateTime('now'))
                ->setContent("test")
                ->setPublication($entity);
            $em->persist($comment);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('publication_show', array('id' => $id)));
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
        $limit = 2;
        $em    = $this->getDoctrine()->getManager();
        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'data' => $em
                    ->getRepository('WizardalleyCoreBundle:CommentPublication')
                    ->findCommentsPublication($id, $page, $limit)
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
        $limit = 2;
        $em    = $this->getDoctrine()->getManager();
        return $this->sendJsonResponse('success', [
            'no_message' => true,
            'data'       => $em
                ->getRepository('WizardalleyCoreBundle:Publication')
                ->findPublications($id, $page, $limit)
        ]);
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
        /** @var RepositoryInterface $repo */
        $repo            = $this->getDoctrine()->getRepository("WizardalleyCoreBundle:PublicationUserLike");
        $publicationLike = $repo->findOneBy([
            'user'        => $user->getId(),
            'publication' => $publication->getId()
        ]);

        if (!$publicationLike instanceof PublicationUserLike) {
            $publicationLike = new PublicationUserLike();
            $publicationLike->setUser($user)->setPublication($publication)->setDateLike(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($publicationLike);
            $em->flush();

            return $this->sendJsonResponse('success', []);
        }

        return $this->sendJsonResponse('error', []);
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
        $repo            = $this->getDoctrine()->getRepository("WizardalleyCoreBundle:PublicationUserLike");
        $publicationLike = $repo->findOneBy([
            'user'        => $user->getId(),
            'publication' => $publication->getId()
        ]);

        if ($publicationLike instanceof PublicationUserLike) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($publicationLike);
            $em->flush();

            return $this->sendJsonResponse('success', []);
        }

        return $this->sendJsonResponse('error', []);
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
    private function creatorPublicationOnly(Publication $entity)
    {
        $user = $this->getUser();
        if (!(($entity->getUser() == $user) or ($entity->getPage()->getCreator() == $user))) {
            throw new AccessDeniedException;
        }
    }

    /**
     * @param Page $page
     */
    private function creatorEditorOnly(Page $page)
    {
        $user = $this->getUser();
        if (!(($page->getCreator() == $user) or ($page->getEditors()->contains($user)))) {
            throw new AccessDeniedException;
        }
    }
}
