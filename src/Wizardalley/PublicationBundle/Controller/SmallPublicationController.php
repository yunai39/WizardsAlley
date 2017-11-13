<?php

namespace Wizardalley\PublicationBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\CommentPublication;
use Wizardalley\CoreBundle\Entity\SmallPublication;
use Wizardalley\CoreBundle\Entity\SmallPublicationUserLike;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\PublicationBundle\Form\CommentType;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * SmallPublication controller.
 *
 * @Route("/user/smallPublication")
 */
class SmallPublicationController extends \Wizardalley\DefaultBundle\Controller\BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderSmallPublicationAction()
    {
        $entity = new SmallPublication();
        $form   = $this->createCreateForm($entity);

        return $this->render(
            '::publication/smallPublicationForm.html.twig',
            ['formSmallPublication' => $form->createView()]
        );
    }

    /**
     * addCommentAction
     *
     * Add a coment for a specific publication
     *
     * @Route("/user/commentSmall/add/{id}", name="comment_small_add")
     * @param Request $request
     * @param         $id integer The entity publication id
     *
     * @return Response
     */
    public function addCommentSmallAction(Request $request, $id)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        /** @var SmallPublication $entity */
        $entity =
            $em->getRepository('WizardalleyCoreBundle:SmallPublication')
               ->find($id)
        ;

        if (!$entity ) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
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
                'user_small_publication_show',
                ['id' => $id]
            )
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
                    'comment_small_add',
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
     * Creates a new SmallPublication entity.
     * @Route("/user/smallPublication/create", name="user_small_publication_create")
     * @Method({"PUT", "POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new SmallPublication();
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $entity->setUser($user);
            $entity->setOnline(true);
            $entity->setCreatedAt(new \DateTime('now'));
            $entity->setUpdatedAt(new \DateTime('now'));
            $em =
                $this->getDoctrine()
                     ->getManager()
            ;
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->sendJsonResponse(
                    'success',
                    ['message' => 'wizard.smallPublication.add.success']
                );
            } else {
                return $this->redirect($this->getRequest()->headers->get('referer'));
            }
        }

        return $this->sendJsonResponse(
            'error',
            [
                'message' => 'wizard.smallPublication.add.error',
                'error'   => $form->getErrors()
            ],
            500
        );
    }

    /**
     * Creates a form to create a SmallPublication entity.
     *
     * @param SmallPublication $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SmallPublication $entity)
    {
        $form = $this->createForm(
            new SmallPublicationType(),
            $entity,
            [
                'action' => $this->generateUrl('user_small_publication_create'),
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
     * @Route("/user/smallpublication/{id}/like", name="small_publication_user_like", options={"expose"=true})
     * @Method({"POST"})
     * @param SmallPublication $publication
     * @ParamConverter("publication", class="WizardalleyCoreBundle:SmallPublication")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function likeSmallPublicationUser(SmallPublication $publication)
    {
        /** @var WizardUser $user */
        $user = $this->getUser();
        /** @var EntityRepository $repo */
        $repo            =
            $this->getDoctrine()
                 ->getRepository("WizardalleyCoreBundle:SmallPublicationUserLike")
        ;
        $publicationLike = $repo->findOneBy(
            [
                'user'        => $user->getId(),
                'smallPublication' => $publication->getId()
            ]
        );

        if (!$publicationLike instanceof SmallPublicationUserLike) {
            $publicationLike = new SmallPublicationUserLike();
            $publicationLike->setUser($user)
                            ->setSmallPublication($publication)
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
     * @Route("/user/smallpublication/{id}/unlike", name="small_publication_user_unlike", options={"expose"=true})
     * @Method({"POST"})
     * @param SmallPublication $publication
     * @ParamConverter("publication", class="WizardalleyCoreBundle:SmallPublication")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function unlikeSmallPublicationUser(SmallPublication $publication)
    {
        /** @var WizardUser $user */
        $user = $this->getUser();
        /** @var EntityRepository $repo */
        $repo            =
            $this->getDoctrine()
                 ->getRepository("WizardalleyCoreBundle:SmallPublicationUserLike");
        $publicationLike = $repo->findOneBy(
            [
                'user'        => $user->getId(),
                'smallPublication' => $publication->getId()
            ]
        );

        if ($publicationLike instanceof SmallPublicationUserLike) {
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
     * Finds and displays a SmallPublication entity.
     * @Route("/{id}/show", name="user_small_publication_show", requirements={"id" = "\d+"})
     */
    public function showAction($id)
    {
        $em =
            $this->getDoctrine()
                 ->getManager()
        ;

        $entity =
            $em->getRepository('WizardalleyCoreBundle:SmallPublication')
               ->find($id)
        ;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
        }

        $comment     = new CommentPublication();
        $commentForm = $this->createFormComment(
            $comment,
            $id
        );

        return $this->render(
            '::user/smallPublication/show.html.twig',
            [
                'entity' => $entity,
                'user'   => $entity->getUser(),
                'comment_form' => $commentForm->createView()
            ]
        );
    }
}
