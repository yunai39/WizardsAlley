<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wizardalley\PublicationBundle\Entity\SmallPublication;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;

/**
 * SmallPublication controller.
 *
 * @Route("/user/smallPublication")
 */
class SmallPublicationController extends Controller
{

    /**
     * Lists all SmallPublication entities.
     *
     * @Route("/", name="user_smallPublication")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WizardalleyPublicationBundle:SmallPublication')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SmallPublication entity.
     *
     * @Route("/", name="user_smallPublication_create")
     * @Method("POST")
     * @Template("WizardalleyPublicationBundle:SmallPublication:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SmallPublication();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_smallPublication_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
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
        $form = $this->createForm(new SmallPublicationType(), $entity, array(
            'action' => $this->generateUrl('user_smallPublication_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SmallPublication entity.
     *
     * @Route("/new", name="user_smallPublication_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SmallPublication();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SmallPublication entity.
     *
     * @Route("/{id}", name="user_smallPublication_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyPublicationBundle:SmallPublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SmallPublication entity.
     *
     * @Route("/{id}/edit", name="user_smallPublication_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyPublicationBundle:SmallPublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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
            'action' => $this->generateUrl('user_smallPublication_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SmallPublication entity.
     *
     * @Route("/{id}", name="user_smallPublication_update")
     * @Method("PUT")
     * @Template("WizardalleyPublicationBundle:SmallPublication:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyPublicationBundle:SmallPublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('user_smallPublication_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SmallPublication entity.
     *
     * @Route("/{id}", name="user_smallPublication_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WizardalleyPublicationBundle:SmallPublication')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SmallPublication entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user_smallPublication'));
    }

    /**
     * Creates a form to delete a SmallPublication entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_smallPublication_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
