<?php

namespace Wizardalley\PublicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wizardalley\CoreBundle\Entity\SmallPublication;
use Wizardalley\PublicationBundle\Form\SmallPublicationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * SmallPublication controller.
 *
 * @Route("/user/smallPublication")
 */
class SmallPublicationController extends \Wizardalley\DefaultBundle\Controller\BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderSmallPublicationAction()
    {
        $entity = new SmallPublication();
        $form   = $this->createCreateForm($entity);

        return $this->render(
            '::publication/smallPublicationForm.html.twig',
            ['formSmallPublication' => $form->createView()]
        );
    }

    /**
     * Creates a new SmallPublication entity.
     * @Route("/user/smallPublication/create", name="user_small_publication_create")
     * @Method({"PUT", "POST"})
     */
    public function createAction(Request $request)
    {
        $entity = new SmallPublication();
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();
            $entity->setUser($user);
            $entity->setCreatedAt(new \DateTime('now'));
            $entity->setUpdatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->sendJsonResponse('success', ['message' => 'wizard.smallPublication.add.success']);
            } else {
                return $this->redirect($this->getRequest()->headers->get('referer'));
            }
        }

        return $this->sendJsonResponse(
            'error',
            [
                'message' => 'wizard.smallPublication.add.error',
                'error'   => $form->getErrors()
            ],
            500
        );
    }

    /**
     * Creates a form to create a SmallPublication entity.
     *
     * @param SmallPublication $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SmallPublication $entity)
    {
        $form = $this->createForm(
            new SmallPublicationType(),
            $entity,
            [
                'action' => $this->generateUrl('user_small_publication_create'),
                'method' => 'POST',
            ]
        );

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Finds and displays a SmallPublication entity.
     * @Route("/smallPublication/{id}/show", name="user_small_publication_show", requirements={"id" = "\d+"})
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WizardalleyCoreBundle:SmallPublication')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SmallPublication entity.');
        }

        return $this->render(
            '::smallPublication/show.html.twig',
            [
                'entity' => $entity,
            ]
        );
    }
}
