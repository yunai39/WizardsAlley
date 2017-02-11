<?php

namespace Wizardalley\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\MapLink;
use Wizardalley\CoreBundle\Entity\MapObject;
use Wizardalley\AdminBundle\Form\MapObjectType;

/**
 * MapObject controller.
 *
 * @Route("/admin/map")
 */
class MapObjectController extends Controller
{
    /**
     * Creates a new MapObject entity.
     *
     * @Route("/", name="admin_map_create")
     * @Method("POST")
     * @Template("WizardalleyAdminBundle:MapObject:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MapObject();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $entity->uploadLogo();
            if ($entity->getLinks()) {
                /** @var MapLink $link */
                foreach ($entity->getLinks() as $link) {
                    dump($link);
                    $link->setMap($entity);
                    $em->persist($link);
                }
            }
            $em->flush();

            return $this->redirect($this->generateUrl('admin_map_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a MapObject entity.
     *
     * @param MapObject $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MapObject $entity)
    {
        $form = $this->createForm(new MapObjectType(), $entity, array(
            'action' => $this->generateUrl('admin_map_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new MapObject entity.
     *
     * @Route("/new", name="admin_map_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MapObject();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a MapObject entity.
     *
     * @Route("/{id}", name="admin_map_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:MapObject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MapObject entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MapObject entity.
     *
     * @Route("/{id}/edit", name="admin_map_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:MapObject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MapObject entity.');
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
    * Creates a form to edit a MapObject entity.
    *
    * @param MapObject $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MapObject $entity)
    {
        $form = $this->createForm(new MapObjectType(), $entity, array(
            'action' => $this->generateUrl('admin_map_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing MapObject entity.
     *
     * @Route("/{id}", name="admin_map_update")
     * @Method("PUT")
     * @Template("WizardalleyAdminBundle:MapObject:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:MapObject')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MapObject entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_map_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a MapObject entity.
     *
     * @Route("/{id}", name="admin_map_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WizardalleyCoreBundle:MapObject')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MapObject entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_list_page', ['tableName' => 'map']));
    }


    /**
     * @return Response
     */
    public function renderFormDeleteTemplateAction()
    {
        $form =  $this->createFormBuilder()
                      ->setMethod('DELETE')
                      ->add('submit', 'submit', array('label' => 'Delete'))
                      ->getForm();
        return $this->render('WizardalleyAdminBundle:Table:renderForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Creates a form to delete a MapObject entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_map_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
