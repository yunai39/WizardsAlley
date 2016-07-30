<?php

namespace Wizardalley\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\PublicationBundle\Form\PageType;

/**
 * USer controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * Lock a user.
     *
     * @Route("/lock/{id}", name="admin_user_lock")
     * @Method("DELETE")
     */
    public function lockAction(Request $request, $id)
    {
        $form = $this->createLockForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var WizardUser $entity */
            $entity = $em->getRepository('WizardalleyCoreBundle:WizardUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $entity->setLocked(true);
            $em->persist($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_list_page', ['tableName' => 'user']));
    }

    /**
     * Lock a user.
     *
     * @Route("/unlock/{id}", name="admin_user_unlock")
     * @Method("PUT")
     */
    public function unlockAction(Request $request, $id)
    {
        $form = $this->createUnlockForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var WizardUser $entity */
            $entity = $em->getRepository('WizardalleyCoreBundle:WizardUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $entity->setLocked(false);
            $em->persist($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_list_page', ['tableName' => 'user']));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormLockTemplateAction(){
        $form =  $this->createFormBuilder()
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Lock'))
            ->getForm();
        return $this->render('WizardalleyAdminBundle:Table:renderForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormUnlockTemplateAction(){
        $form =  $this->createFormBuilder()
            ->setMethod('PUT')
            ->add('submit', 'submit', array('label' => 'Unlock'))
            ->getForm();
        return $this->render('WizardalleyAdminBundle:Table:renderForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Creates a form to lock a user.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createLockForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_lock', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Lock'))
            ->getForm()
            ;
    }
    /**
     * Creates a form to unlock a user.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createUnlockForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_unlock', array('id' => $id)))
            ->setMethod('PUT')
            ->add('submit', 'submit', array('label' => 'unlock'))
            ->getForm()
            ;
    }
}
