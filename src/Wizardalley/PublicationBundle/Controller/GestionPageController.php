<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\PublicationBundle\Form\PageType;
use Wizardalley\PublicationBundle\Form\PageEditorType;
use Wizardalley\PublicationBundle\Entity\Page;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Page controller.
 *
 */
class GestionPageController extends Controller {
     
    public function indexAction($id_page){
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        
        $this->notFoundEntity($page);
        $this->creatorEditorOnly($page);
        
        $form = $this->createFormPage($page);
        return $this->render('WizardalleyPublicationBundle:GestionPage:index.html.twig',array(
            'page'  => $page,
            'form'  => $form->createView(),
        ));
    }
    
    public function editUserFormAction($id_page) {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        
        $this->notFoundEntity($page);
        $this->creatorOnly($page);
        
        $form = $this->createFormUserPage($page);
        return $this->render('WizardalleyPublicationBundle:GestionPage:editUser.html.twig',array(
            'page'  => $page,
            'form'  => $form->createView(),
        ));
        
    }
    
    public function editUserAction(Request $request,$id_page){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        
        $this->notFoundEntity($entity);
        $this->creatorOnly($page);
        
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
        
        $this->notFoundEntity($entity);
        $this->creatorEditorOnly($page);
        
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
    
    public function displayPublicationUserAction(Request $request,$id_page){
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        
        $this->notFoundEntity($page);
        $this->creatorEditorOnly($page);
        
        $entities = $em->getRepository('WizardalleyPublicationBundle:Publication')->findBy(array( 'page' => $page ));
        if (!$entities) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }
        return $this->render('WizardalleyPublicationBundle:Publication:index.html.twig', array(
                    'id_page'  => $id_page,
                    'entities' => $entities,
                    'page'     => $page,
        ));
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
    
    private function notFoundEntity($entity){
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }
    }
    
    private function creatorOnly($page){
        $user = $this->getUser();
        if ( !(($page->getCreator() == $user)) ) {
           throw new AccessDeniedException; 
        }
    }
    
    private function creatorEditorOnly($page){
        $user = $this->getUser();
        if ( !(($page->getCreator() == $user) or ($page->getEditors()->contains($user))) ) {
           throw new AccessDeniedException; 
        }
    }
}


