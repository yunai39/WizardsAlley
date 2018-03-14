<?php

namespace Wizardalley\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\CoreBundle\Entity\Blame;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\Publication;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\DefaultBundle\Form\BlameType;
use Wizardalley\DefaultBundle\Form\ContactType;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;
use Wizardalley\CoreBundle\Entity\SmallPublication;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;

/**
 * Class DefaultController
 *
 * @package Wizardalley\DefaultBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * indexAction
     *
     * This action will present the presentation page of the web site
     *
     * @Configuration\Route("/user/addBlame/{type}/{id}", name="wizardalley_add_blame", options = {"expose" = true})
     *
     * @return Response
     */
    public function addBlameAction(Request $request, $type, $id)
    {
        $blame = new Blame();
        $blame->setType($type)->setDateBlame(new \DateTime())->setContentId($id);

        $form = $this->createForm(
            new BlameType(),
            $blame,
            [
                'action' => $this->generateUrl(
                    'wizardalley_add_blame',
                    ['type' => $type, 'id' => $id]
                ),
                'method' => 'POST',
            ]
        );
        $form->handleRequest($request);

        $typeArray = [
            'page'        => 0,
            'publication' => 1,
            'user'        => 2,
        ];

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $blame->setUser($this->getUser())->setType($typeArray[ $type ]);
            if ($type == 'page') {
                /** @var Page $entity */
                $entity = $em->getRepository('WizardalleyCoreBundle:Page')->find($blame->getContentId());
                $blame->setTitle($entity->getName());
            } elseif ($type == 'publication') {
                /** @var Publication $entity */
                $entity = $em->getRepository('WizardalleyCoreBundle:Publication')->find($blame->getContentId());
                if ($entity instanceof Publication) {
                    $blame->setTitle($entity->getTitle());
                } else {
                    /** @var SmallPublication $entity */
                    $entity =
                        $em->getRepository('WizardalleyCoreBundle:SmallPublication')->find($blame->getContentId());
                    $blame->setTitle(
                        $entity->getUser()
                               ->getUsername() .
                        ' - ' .
                        substr(
                            $entity->getContent(),
                            0,
                            30
                        )
                    );
                }
            } elseif ($type == 'user') {
                /** @var WizardUser $entity */
                $entity = $em->getRepository('WizardalleyCoreBundle:WizardUser')->find($blame->getContentId());
                $blame->setTitle($entity->getUsername());
            }
            $em->persist($blame);
            $em->flush();

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(
            '::user/addBlame.html.twig',
            ['type' => $type, 'id' => $id, 'form' => $form->createView()]
        );
    }

    /**
     * indexAction
     *
     * This action will present the presentation page of the web site
     *
     * @Configuration\Route("/user/newsfeed", name="wizardalley_default_homepage")
     *
     * @return Response
     */
    public function indexAction()
    {
        $entity = new SmallPublication();
        $formSP = $this->createSmallPublicationForm($entity);

        return $this->render(
            '::user/home.html.twig',
            [
                'formSmallPublication' => $formSP->createView()
            ]
        );
    }

    /**
     * mentionAction
     *
     * This action will present the legal mention page
     *
     * @Configuration\Route("/legals", name="wizardalley_default_mention")
     *
     * @return Response
     */
    public function mentionAction()
    {
        return $this->render('::default/legals.html.twig');
    }

    /**
     * copyrightAction
     *
     * This action will present the copyright page
     *
     * @Configuration\Route("/copyright", name="wizardalley_default_copyright")
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
     * @Configuration\Route("/confidentiality", name="wizardalley_default_confidentiality")
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
     * @Configuration\Route("/contact", name="wizardalley_default_contact")
     *
     * @param Request $request http request
     *
     * @return Response
     */
    public function contactFormAction(Request $request)
    {
        $form = $this->createForm(
            new ContactType(),
            null,
            [
                'action' => $this->generateUrl('wizardalley_default_contact'),
                'method' => 'POST',
            ]
        );
        $form->add(
            'submit',
            'submit',
            ['label' => 'wizard.utility.form.contact']
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data    = $form->getData();
            $email   = $data[ 'email' ];
            $name    = $data[ 'name' ];
            $message = $data[ 'message' ];
            $message = \Swift_Message::newInstance()
                                     ->setSubject('Demande de contact')
                                     ->setFrom('contact@wizardAlley.com')//->setTo($email)
                                     ->setTo('yunai39@gmail.com')
                                     ->setBody(
                                         $this->renderView(
                                             '::email/emailContact.html.twig',
                                             ['name' => $name, 'email' => $email, 'message' => $message]
                                         )
                                     )
            ;
            $this->get('mailer')->send($message);
            $request->getSession()->getFlashBag()->add(
                'message_send',
                'wizard.contact.message'
            )
            ;
        }

        return $this->render(
            '::default/contact.html.twig',
            ['form' => $form->createView()]
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
        $form = $this->createForm(
            new SmallPublicationType(),
            $entity,
            [
                'action' => $this->generateUrl('user_small_publication_create'),
                'method' => 'POST',
            ]
        );

        $form->add(
            'submit',
            'submit',
            ['label' => 'wizard.utility.form.create']
        );

        return $form;
    }
}
