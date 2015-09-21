<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\PublicationBundle\Form\PageType;
use Wizardalley\PublicationBundle\Form\PageEditorType;
use Wizardalley\PublicationBundle\Entity\Page;

/**
 * Page controller.
 *
 */
class GestionPageController extends Controller {
     
    public function indexAction($id_page){
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        $form = $this->createFormPage($page);
        return $this->render('WizardalleyPublicationBundle:GestionPage:index.html.twig',array(
            'page'  => $page,
            'form'  => $form->createView(),
        ));
    }
    
    public function editUserFormAction($id_page) {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        if (!$page) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        $form = $this->createFormUserPage($page);
        return $this->render('WizardalleyPublicationBundle:GestionPage:editUser.html.twig',array(
            'page'  => $page,
            'form'  => $form->createView(),
        ));
        
    }
    
    public function editUserAction(Request $request,$id_page){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        $editForm = $this->createFormUserPage($entity);
        $editForm->handleRequest($request);
        
        if ($editForm->isValid()) {
            $entity->removeAllEditor();
            $editors = $request->get('wizardalley_publicationbundle_page_editor');
            foreach($editors['editors'] as $editor ){
                $entity->addEditor($em->getReference('WizardalleyUserBundle:WizardUser',$editor['id']));
            }
            
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'wizard.page.edit_success');
            return $this->redirect($this->generateUrl('page_gestion_user', array('id_page' => $id_page)));
        }
        return $this->render('WizardalleyPublicationBundle:GestionPage:editUser.html.twig',array(
            'page'  => $entity,
            'form'  => $editForm->createView(),
        ));
    }
    
        
    
    public function editPageAction(Request $request,$id_page){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createFormUserPage($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->uploadProfile();
            $entity->uploadCouverture();
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'wizard.page.user.edit_success');
            return $this->redirect($this->generateUrl('page_gestion_user', array('id_page' => $id_page)));
        }

        return $this->render('WizardalleyPublicationBundle:GestionPage:index.html.twig', array(
                    'page' => $entity,
                    'form' => $editForm->createView(),
        ));
    }
    
    public function displayPublicationUserAction(){
        
    }
        
    private function createFormPage(Page $page) {
        $form = $this->createForm(new PageType(), $page, array(
            'action' => $this->generateUrl('page_gestion_edit', array('id_page' => $page->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier la page'));

        return $form;
    }
     
    private function createFormUserPage(Page $page) {
        $form = $this->createForm(new PageEditorType(), $page, array(
            'action' => $this->generateUrl('page_gestion_user_edit', array('id_page' => $page->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modification des editeurs'));

        return $form;
    }
    
    
}


