<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\DefaultBundle\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * indexAction
     * 
     * This action will present the presentation page of the web site
     * 
     * pattern: /home
     * road_name: wizardalley_default_homepage
     * 
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:index.html.twig');
    }
    
    /**
     * mentionAction
     * 
     * This action will present the legal mention page
     * 
     * pattern: /mention
     * road_name: wizardalley_default_mention
     * 
     * @return Response
     */
    public function mentionAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:mention.html.twig');
    }
    
    
    /**
     * copyrightAction
     * 
     * This action will present the copyright page
     * 
     * pattern: /copyright
     * road_name: wizardalley_default_copyright
     * 
     * @return Response
     */
    public function copyrightAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:copyright.html.twig');
    }
    
    /**
     * confidentialityAction
     * 
     * This action will present the confidentiality page
     * 
     * pattern: /confidentiality
     * road_name: wizardalley_default_confidentiality
     * 
     * @return Response
     */
    public function confidentialityAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:confidentiality.html.twig');
    }
    
    /**
     * contactFormAction
     * 
     * This action will present a contact form and treat it
     * 
     * pattern: /contact
     * road_name: wizardalley_default_contact
     * 
     * @param Request $request http request
     * @return Response
     */
    public function contactFormAction(Request $request){
        $form = $this->createForm(new ContactType(), NULL, array(
            'action' => $this->generateUrl('wizardalley_default_contact'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'contact'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            $name = $data['name'];
            $message = $data['message'];
            $message = \Swift_Message::newInstance()
                ->setSubject('Demande de contact')
                ->setFrom('contact@wizardAlley.com')
                //->setTo($email)
                ->setTo('yunai39@gmail.com')
                ->setBody($this->renderView('WizardalleyDefaultBundle:Email:emailContact.html.twig', array('name' => $name, 'email' => $email, 'message' => $message)))
            ;
            $this->get('mailer')->send($message);
            $request->getSession()->getFlashBag()->add('message_send', 'wizard.contact.message');
        }
        
        return $this->render('WizardalleyDefaultBundle:Default:contact.html.twig',array('form' => $form->createView() ));
        
    }
}