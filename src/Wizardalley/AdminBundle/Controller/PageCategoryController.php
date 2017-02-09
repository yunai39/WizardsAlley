<?php

namespace Wizardalley\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wizardalley\CoreBundle\Entity\PageCategory;
use Wizardalley\AdminBundle\Form\PageCategoryType;

/**
 * PageCategory controller.
 *
 * @Route("/admin/pagecategory")
 */
class PageCategoryController extends Controller
{
    /**
     * Creates a new PageCategory entity.
     *
     * @Route("/", name="admin_pagecategory_create")
     * @Method("POST")
     * @Template("WizardalleyCoreBundle:PageCategory:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new PageCategory();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->uploadLogo();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_list_page', array('tableName' => 'category')));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a PageCategory entity.
     *
     * @param PageCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PageCategory $entity)
    {
        $form = $this->createForm(new PageCategoryType(), $entity, array(
            'action' => $this->generateUrl('admin_pagecategory_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PageCategory entity.
     *
     * @Route("/new", name="admin_pagecategory_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new PageCategory();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    /**
     * Displays a form to edit an existing PageCategory entity.
     *
     * @Route("/{id}/edit", name="admin_pagecategory_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:PageCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PageCategory entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a PageCategory entity.
    *
    * @param PageCategory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PageCategory $entity)
    {
        $form = $this->createForm(new PageCategoryType(), $entity, array(
            'action' => $this->generateUrl('admin_pagecategory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing PageCategory entity.
     *
     * @Route("/{id}", name="admin_pagecategory_update")
     * @Method("PUT")
     * @Template("WizardalleyCoreBundle:PageCategory:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var PageCategory $entity */
        $entity = $em->getRepository('WizardalleyCoreBundle:PageCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PageCategory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->uploadLogo();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_pagecategory_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a PageCategory entity.
     *
     * @Route("/{id}", name="admin_pagecategory_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WizardalleyCoreBundle:PageCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PageCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_pagecategory'));
    }

    /**
     * Creates a form to delete a PageCategory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_pagecategory_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
