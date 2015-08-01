<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\DefaultBundle\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:index.html.twig');
    }
    public function mentionAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:mention.html.twig');
    }
    public function copyrightAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:copyright.html.twig');
    }
    public function confidentialityAction()
    {
        return $this->render('WizardalleyDefaultBundle:Default:confidentiality.html.twig');
    }
    
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
