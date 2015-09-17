<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\PublicationBundle\Form\PageType;
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
    
    public function editUserFormAction() {
        
    }
    
    public function editUserAction(){
        
    }
    
        
    
    public function editPageAction(Request $request,$id_page){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WizardalleyPublicationBundle:Page')->find($id_page);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Page entity.');
        }

        $editForm = $this->createFormPage($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->uploadProfile();
            $entity->uploadCouverture();
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'wizard.page.edit_success');
            return $this->redirect($this->generateUrl('page_gestion_show', array('id_page' => $id_page)));
        }

        return $this->render('WizardalleyPublicationBundle:GestionPage:show.html.twig', array(
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
    
}
