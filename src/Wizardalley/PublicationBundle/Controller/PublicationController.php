<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\PublicationBundle\Entity\Publication;
use Wizardalley\PublicationBundle\Entity\Comment;
use Wizardalley\PublicationBundle\Form\PublicationType;
use Wizardalley\PublicationBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Publication controller.
 *
 */
class PublicationController extends Controller {

    /**
     * Creates a new Publication entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Publication();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());
            $entity->setDatePublication(new \DateTime('now'));
            $em->persist($entity);
            foreach($entity->getImages() as $img){
                $img->upload();
                $img->setPublication($entity);
                $em->persist($img);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'wizard.publication.new_success');

            return $this->redirect($this->generateUrl('publication_show', array('id' => $entity->getId())));
        }

        return $this->render('WizardalleyPublicationBundle:Publication:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Publication entity.
     *
     * @param Publication $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Publication $entity) {
        $form = $this->createForm(new PublicationType(), $entity, array(
            'action' => $this->generateUrl('publication_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Publication entity.
     *
     */
    public function newAction() {
        $entity = new Publication();
        $form = $this->createCreateForm($entity);

        return $this->render('WizardalleyPublicationBundle:Publication:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Publication entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyPublicationBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }

        $comment = new Comment();
        $commentForm = $this->createFormComment($comment, $entity);


        return $this->render('WizardalleyPublicationBundle:Publication:show.html.twig', array(
                    'entity' => $entity,
                    'comment_form' => $commentForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Publication entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyPublicationBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }

        if ($entity->getUser() != $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to edit this entity');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('WizardalleyPublicationBundle:Publication:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Publication entity.
     *
     * @param Publication $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Publication $entity) {
        $form = $this->createForm(new PublicationType(), $entity, array(
            'action' => $this->generateUrl('publication_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Publication entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyPublicationBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }


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
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Publication entity.
     *
     */
    /* public function deleteAction(Request $request, $id)
      {
      $form = $this->createDeleteForm($id);
      $form->handleRequest($request);

      if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('WizardalleyPublicationBundle:Publication')->find($id);

      if (!$entity) {
      throw $this->createNotFoundException('Unable to find Publication entity.');
      }

      $em->remove($entity);
      $em->flush();
      }

      return $this->redirect($this->generateUrl('publication'));
      } */

    /**
     * Creates a form to delete a Publication entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('publication_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /*******
     * Comment
     */

    /**
     * Creates a form to add a comment.
     *
     * @param Comment $comment The entity comment
     * @param Comment $comment The entity publication     
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFormComment(Comment $comment, Publication $entity) {
        $form = $this->createForm(new CommentType(), $comment, array(
            'action' => $this->generateUrl('comment_add', array('id' => $entity->getId())),
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
     * @param Request $request 
     * @param $id integer The entity publication id 
     *
     * @return Response
     */
    public function addCommentAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyPublicationBundle:Publication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publication entity.');
        }
        $comment = new Comment();
        $form = $this->createFormComment($comment, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setDateComment(new \DateTime('now'));
            $comment->setPublication($entity);
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
     * @param Request $request 
     * @param $id integer The entity publication id 
     *
     * @return Response
     */
    public function getCommentAction(Request $request, $id,$page){
        $limit = 2;
        $em = $this->getDoctrine()->getManager();
        return new JsonResponse($em->getRepository('WizardalleyPublicationBundle:Comment')->findCommentsPublication($id,$page,$limit));
        
    }
    /**
     * getPublicationAction
     * 
     * fetch the comment for an action
     *
     * @param Request $request 
     * @param $id integer user_id
     * @param $id integer page number
     *
     * @return Response
     */
    public function getPublicationAction(Request $request, $id,$page){
        $limit = 2;
        $em = $this->getDoctrine()->getManager();
        return new JsonResponse($em->getRepository('WizardalleyPublicationBundle:Publication')->findPublications($id,$page,$limit));
        
    }
}