<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\DefaultBundle\Form\ContactType;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;
use Wizardalley\CoreBundle\Entity\SmallPublication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * Class DefaultController
 * @package Wizardalley\DefaultBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * indexAction
     *
     * This action will present the presentation page of the web site
     *
     * @Route("/user/home", name="wizardalley_default_homepage")
     *
     * @return Response
     */
    public function indexAction()
    {
        $entity = new SmallPublication();
        $formSP = $this->createSmallPublicationForm($entity);
        return $this->render('::user/home.html.twig', array(
            'formSmallPublication' => $formSP->createView()
        ));
    }

    /**
     * mentionAction
     *
     * This action will present the legal mention page
     *
     * @Route("/mention", name="wizardalley_default_mention")
     *
     * @return Response
     */
    public function mentionAction()
    {
        return $this->render('::default/mention.html.twig');
    }


    /**
     * copyrightAction
     *
     * This action will present the copyright page
     *
     * @Route("/copyright", name="wizardalley_default_copyright")
     *
     * @return Response
     */
    public function copyrightAction()
    {
        return $this->render('::default/copyright.html.twig');
    }

    /**
     * confidentialityAction
     *
     * This action will present the confidentiality page
     *
     * @Route("/confidentiality", name="wizardalley_default_confidentiality")
     *
     * @return Response
     */
    public function confidentialityAction()
    {
        return $this->render('::default/confidentiality.html.twig');
    }

    /**
     * contactFormAction
     *
     * This action will present a contact form and treat it
     *
     * @Route("/contact", name="wizardalley_default_contact")
     *
     * @param Request $request http request
     *
     * @return Response
     */
    public function contactFormAction(Request $request)
    {
        $form = $this->createForm(new ContactType(), null, array(
            'action' => $this->generateUrl('wizardalley_default_contact'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'contact'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data    = $form->getData();
            $email   = $data['email'];
            $name    = $data['name'];
            $message = $data['message'];
            $message = \Swift_Message::newInstance()
                ->setSubject('Demande de contact')
                ->setFrom('contact@wizardAlley.com')//->setTo($email)
                ->setTo('yunai39@gmail.com')
                ->setBody($this->renderView(
                    '::email/emailContact.html.twig',
                    array('name' => $name, 'email' => $email, 'message' => $message)
                ));
            $this->get('mailer')->send($message);
            $request->getSession()->getFlashBag()->add('message_send', 'wizard.contact.message');
        }

        return $this->render(
            '::default/contact.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Creates a form to create a SmallPublication entity.
     *
     * @param SmallPublication $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSmallPublicationForm(SmallPublication $entity)
    {
        $form = $this->createForm(new SmallPublicationType(), $entity, array(
            'action' => $this->generateUrl('user_small_publication_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }
}
