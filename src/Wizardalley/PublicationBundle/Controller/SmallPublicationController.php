<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wizardalley\CoreBundle\Entity\SmallPublication;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;

/**
 * SmallPublication controller.
 *
 * @Route("/user/smallPublication")
 */
class SmallPublicationController extends \Wizardalley\DefaultBundle\Controller\BaseController{
    /**
     * Creates a new SmallPublication entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new SmallPublication();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $entity->setUser($user);
            $entity->setCreatedAt(new \DateTime('now'));
            $entity->setUpdatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->sendJsonResponse('success', ['message' => 'wizard.smallPublication.add.success']);
        }
        
        return $this->sendJsonResponse('error', 
            [
                'message' => 'wizard.smallPublication.add.error',
                'error'     => $form->getErrors()
                ], 500);
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
        $form = $this->createForm(new SmallPublicationType(), $entity, array(
            'action' => $this->generateUrl('user_small_publication_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SmallPublication entity.
     *
     */
    public function newAction()
    {
        $entity = new SmallPublication();
        $form   = $this->createCreateForm($entity);

        return $this->render('::smallPublication/new.html.twig',array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SmallPublication entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:SmallPublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
        }


        return $this->render('::smallPublication/show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing SmallPublication entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:SmallPublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('::smallPublication/edit.html.twig',array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a SmallPublication entity.
    *
    * @param SmallPublication $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SmallPublication $entity)
    {
        $form = $this->createForm(new SmallPublicationType(), $entity, array(
            'action' => $this->generateUrl('user_small_publication_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SmallPublication entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:SmallPublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('user_small_publication_edit', array('id' => $id)));
        }

        return $this->render('::smallPublication/edit.html.twig',array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
}
