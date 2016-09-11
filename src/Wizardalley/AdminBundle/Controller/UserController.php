<?php

namespace Wizardalley\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Wizardalley\AdminBundle\Form\AdministrateurForm;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\PageUserFollow;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * USer controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /** @var  EntityManager */
    protected $em;

    /**
     * Form to create a new administrator
     *
     * @Route("/newAdministrateur", name="admin_administrateur_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new WizardUser();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Form to create a new administrator
     *
     * @Route("/newAdministrateur", name="admin_administrateur_create")
     * @Method("POST")
     */
    public function createAdministrateurAction(Request $request)
    {
        $entity = new WizardUser();
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEntityManager();
            $entity->setLocked(false);
            $entity->addRole('ROLE_ADMIN');
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_list_page', array('tableName' => 'user')));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Edit a current user
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Template()
     */
    public function editAction(request $request, $id)
    {
        $em = $this->getEntityManager();
        /** @var WizardUser $entity */
        $entity = $this->getWizardUser($id);

        return ['user' => $entity];
    }

    /**
     * Creates a form to create a WizardUser entity.
     *
     * @param WizardUser $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(WizardUser $entity)
    {
        $form = $this->createForm(new AdministrateurForm(), $entity, array(
            'action' => $this->generateUrl('admin_administrateur_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

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
            $em = $this->getEntityManager();
            /** @var WizardUser $entity */
            $entity = $this->getWizardUser($id);

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
            $em = $this->getEntityManager();
            /** @var WizardUser $entity */
            $entity = $this->getWizardUser($id);

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
     * @Route("/getListPageFollowed/{userId}", name="admin_user_list_page_followed")
     * @Method("GET")
     */
    public function listPageFollowedAction($userId)
    {
        $user = $this->getWizardUser($userId);

        $data = [];
        /** @var PageUserFollow $pageUser */
        foreach ($user->getPagesFollowed() as $pageUser) {
            $page = $pageUser->getPage();
            $data[] = [
                'id'   => $page->getId(),
                'name' => $page->getName()
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    /**
     * @Route("/getListPageCreated/{userId}", name="admin_user_list_page_created")
     * @Method("GET")
     */
    public function listPageCreatedAction($userId)
    {
        $user = $this->getWizardUser($userId);

        $data = [];
        /** @var Page $page */
        foreach ($user->getPagesCreated() as $page) {
            $data[] = [
                'id'   => $page->getId(),
                'name' => $page->getName()
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    /**
     * @Route("/getListPageEditor/{userId}", name="admin_user_list_page_editor")
     * @Method("GET")
     */
    public function listPageEditorAction($userId)
    {
        $user = $this->getWizardUser($userId);

        $data = [];
        /** @var Page $pageUser */
        foreach ($user->getPagesEditor() as $page) {
            $data[] = [
                'id'   => $page->getId(),
                'name' => $page->getName()
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    /**
     * @Route("/getListFriend/{userId}", name="admin_user_list_friends")
     * @Method("GET")
     */
    public function listFriendsAction($userId)
    {
        $user = $this->getWizardUser($userId);

        $data = [];
        /** @var WizardUser $friendWithMe */
        foreach ($user->getFriendsWithMe() as $friendWithMe) {
            $data[] = [
                'id'   => $friendWithMe->getId(),
                'firstname' => $friendWithMe->getFirstname(),
                'lastname' => $friendWithMe->getLastname(),
                'email' => $friendWithMe->getEmail()
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormLockTemplateAction()
    {
        $form = $this->createFormBuilder()
                     ->setMethod('DELETE')
                     ->add('submit', 'submit', array('label' => 'Lock'))
                     ->getForm();
        return $this->render('WizardalleyAdminBundle:Table:renderForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormUnlockTemplateAction()
    {
        $form = $this->createFormBuilder()
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
                    ->getForm();
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

    /**
     * @return EntityManager|object
     */
    protected function getEntityManager(){
        if (!$this->em instanceof EntityManager) {
            $this->em = $this->getDoctrine()->getManager();
        }

        return $this->em;
    }

    /**
     * @param $userId
     *
     * @return WizardUser|null
     */
    protected function getWizardUser($userId)
    {
        return $this->getEntityManager()->getRepository('WizardalleyCoreBundle:WizardUser')->find($userId);
    }
}
