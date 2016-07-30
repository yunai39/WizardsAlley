<?php

namespace Wizardalley\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\InformationBillet;
use Wizardalley\AdminBundle\Form\InformationBilletType;
use Wizardalley\CoreBundle\Entity\InformationBilletRepository;
use Wizardalley\DefaultBundle\Controller\BaseController;

/**
 * InformationBillet controller.
 *
 * @Route("/admin/infoBillet")
 */
class InformationBilletController extends BaseController
{
    /**
     * Creates a new InformationBillet entity.
     *
     * @Route("/", name="admin_infoBillet_create")
     * @Method("POST")
     * @Template("WizardalleyAdminBundle:InformationBillet:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new InformationBillet();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setDateCreateBillet(new \DateTime);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_infoBillet_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a InformationBillet entity.
     *
     * @param InformationBillet $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(InformationBillet $entity)
    {
        $form = $this->createForm(new InformationBilletType(), $entity, array(
            'action' => $this->generateUrl('admin_infoBillet_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new InformationBillet entity.
     *
     * @Route("/new", name="admin_infoBillet_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new InformationBillet();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a InformationBillet entity.
     *
     * @Route("/{id}", name="admin_infoBillet_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:InformationBillet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InformationBillet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing InformationBillet entity.
     *
     * @Route("/{id}/edit", name="admin_infoBillet_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var InformationBillet $entity */
        $entity = $em->getRepository('WizardalleyCoreBundle:InformationBillet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InformationBillet entity.');
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
    * Creates a form to edit a InformationBillet entity.
    *
    * @param InformationBillet $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(InformationBillet $entity)
    {
        $form = $this->createForm(new InformationBilletType(), $entity, array(
            'action' => $this->generateUrl('admin_infoBillet_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing InformationBillet entity.
     *
     * @Route("/{id}", name="admin_infoBillet_update")
     * @Method("PUT")
     * @Template("WizardalleyAdminBundle:InformationBillet:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:InformationBillet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find InformationBillet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_infoBillet_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a InformationBillet entity.
     *
     * @Route("/{id}", name="admin_infoBillet_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WizardalleyCoreBundle:InformationBillet')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find InformationBillet entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_list_page', ['tableName' => 'information']));
    }

    /**
     * Creates a form to delete a InformationBillet entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_infoBillet_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @return Response
     */
    public function renderFormDeleteTemplateAction(){
        $form =  $this->createFormBuilder()
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
        return $this->render('WizardalleyAdminBundle:Table:renderForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * 
     * @Route("/loadMore/billet/{lastId}", name="load_more_entity_billet", options={"expose"=true})
     * @Method("GET")
     * @param int $lastId
     * @return Response
     */
    public function loadMoreBilletAction($lastId)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var InformationBilletRepository $repo */
        $repo = $em->getRepository('WizardalleyCoreBundle:InformationBillet');
        $result = $repo->findMore($lastId);
        return $this->sendJsonResponse('success', $result);
    }
}
