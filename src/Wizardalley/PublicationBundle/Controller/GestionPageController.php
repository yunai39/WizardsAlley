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
     
    /**
     * indexAction
     * 
     * This action will present the page for the gestion of the page
     * 
     * pattern: /page/gestion/show/{id_page}
     * road_name: page_gestion_show
     * 
     * @return Response
     */
    public function indexAction($id_page){
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        
        $this->notFoundEntity($page);
        $this->creatorEditorOnly($page);
        
        $form = $this->editFormPage($page);
        return $this->render('WizardalleyPublicationBundle:GestionPage:index.html.twig',array(
            'page'  => $page,
            'form'  => $form->createView(),
        ));
    }
    
    /**
     * editUserFormAction
     * 
     * This action will display a form to edit the user allowed to manage the page
     * 
     * pattern: /page/gestion/user/{id_page}
     * road_name: page_gestion_user
     * 
     * @return Response
     */
    public function editUserFormAction($id_page) {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        
        $this->notFoundEntity($page);
        $this->creatorOnly($page);
        
        $form = $this->editFormPage($page);
        return $this->render('WizardalleyPublicationBundle:GestionPage:editUser.html.twig',array(
            'page'  => $page,
            'form'  => $form->createView(),
        ));
        
    }
    
    
    /**
     * editUserFormAction
     * 
     * This action will record the edition for the user management of the page
     * 
     * pattern: /page/gestion/user/edit/{id_page}
     * road_name: page_gestion_user_edit
     * 
     * @return Response
     */
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
    
        
    
    
    /**
     * editPageAction
     * 
     * This action will display a form to edit the content of the page
     * 
     * pattern: /page/gestion/edit/{id_page}
     * road_name: page_gestion_edit
     * 
     * @return Response
     */
    public function editPageAction(Request $request,$id_page){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        
        $this->notFoundEntity($entity);
        $this->creatorEditorOnly($entity);
        $editForm = $this->editFormPage($entity);
        $editForm->handleRequest($request);
        
        if ($editForm->isValid()) {
            $entity->setPathCouverture("");
            $entity->setPathProfile("");
            $em->flush();
            $entity->uploadProfile();
            $entity->uploadCouverture();
            $this->get('session')->getFlashBag()->add('success', 'wizard.page.user.edit_success');
            return $this->redirect($this->generateUrl('page_gestion_show', array('id_page' => $id_page)));
        }
        
        return $this->render('WizardalleyPublicationBundle:GestionPage:index.html.twig', array(
                    'page' => $entity,
                    'form' => $editForm->createView(),
        ));
    }
    
    
    /**
     * Displays a form to create a new Publication entity.
     *
     */
    public function newAction() {
        $entity = new Page();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormPage($entity);
        return $this->render('WizardalleyPublicationBundle:Page:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }
    
    
    /**
     * Creates a new Publication entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Page();
        
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormPage($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setCreator($this->getUser());
            $entity->uploadCouverture();
            $entity->uploadProfile();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'wizard.page.new_success');

            return $this->redirect($this->generateUrl('page_show', array('id' => $entity->getId())));
        }

        return $this->render('WizardalleyPublicationBundle:Page:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }
    
    
    /**
     * displayPublicationUserAction
     * 
     * This action will display a form to edit the content of the page
     * 
     * pattern: /page/gestion/publication/{id_page}
     * road_name: page_gestion_publication
     * 
     * @return Response
     */
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
     
    /**
     * createFormPage
     * 
     * This function will return the form for the creation of the page
     * 
     * @param Page $page
     */   
    private function createFormPage(Page $page) {
        $form = $this->createForm(new PageType(), $page, array(
            'action' => $this->generateUrl('page_gestion_create'),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Creer la page'));

        return $form;
    }
    
    /**
     * editFormPage
     * 
     * This function will return the form for the edition of the page
     * 
     * @param Page $page
     */   
    private function editFormPage(Page $page) {
        $form = $this->createForm(new PageType(), $page, array(
            'action' => $this->generateUrl('page_gestion_edit', array('id_page' => $page->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier la page'));

        return $form;
    }
     
     
    /**
     * createFormUserPage
     * 
     * This function will return the form for the edition of the editor of the page
     * 
     * @param Page $page
     */   
    private function createFormUserPage(Page $page) {
        $form = $this->createForm(new PageEditorType(), $page, array(
            'action' => $this->generateUrl('page_gestion_user_edit', array('id_page' => $page->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modification des editeurs'));

        return $form;
    }
    
    /**
     * notFoundEntity
     * Throw an exception in case the entity does not existe
     * 
     * @param type $entity
     * @throws NotFoundException
     */
    private function notFoundEntity($entity){
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entity.');
        }
    }
    
    /**
     * creatorOnly
     * Throw an exception in case the user is not allowed creator only
     * 
     * @param type $page
     * @throws AccessDeniedException
     */
    private function creatorOnly($page){
        $user = $this->getUser();
        if ( !(($page->getCreator() == $user)) ) {
           throw new AccessDeniedException; 
        }
    }
    
    /**
     * creatorEditorOnly
     * Throw an exception in case the user is not allowed, creator end editor only
     * 
     * @param type $page
     * @throws AccessDeniedException
     */
    private function creatorEditorOnly($page){
        $user = $this->getUser();
        if ( !(($page->getCreator() == $user) or ($page->getEditors()->contains($user))) ) {
           throw new AccessDeniedException; 
        }
    }
}


