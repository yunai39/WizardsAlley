<?php

namespace Wizardalley\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Wizardalley\CoreBundle\Entity\Page;
use Wizardalley\CoreBundle\Entity\PageUserFollow;
use Wizardalley\CoreBundle\Entity\WizardUser;

/**
 * USer controller.
 *
 * @Configuration\Route("/admin/user")
 */
class UserController extends Controller
{
    /** @var  EntityManager */
    protected $em;

    /**
     * Edit a current user
     *
     * @Configuration\Route("/{id}/edit", name="admin_user_edit")
     * @Configuration\Template()
     * @param int $id
     *
     * @return array
     */
    public function editAction($id)
    {
        /** @var WizardUser $entity */
        $entity = $this->getWizardUser($id);

        return ['user' => $entity];
    }

    /**
     * @Configuration\Route("/getListPageFollowed/{userId}", name="admin_user_list_page_followed")
     * @Configuration\Method("GET")
     * @param int $userId
     *
     * @return JsonResponse
     */
    public function listPageFollowedAction($userId)
    {
        $user = $this->getWizardUser($userId);

        $data = [];
        /** @var PageUserFollow $pageUser */
        foreach ($user->getPagesFollowed() as $pageUser) {
            $page   = $pageUser->getPage();
            $data[] = [
                'id'   => $page->getId(),
                'name' => $page->getName()
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    /**
     * @Configuration\Route("/getListPageCreated/{userId}", name="admin_user_list_page_created")
     * @Configuration\Method("GET")
     * @param int $userId
     *
     * @return JsonResponse
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
     * @Configuration\Route("/getListPageEditor/{userId}", name="admin_user_list_page_editor")
     * @Configuration\Method("GET")
     * @param int $userId
     *
     * @return JsonResponse
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
     * @Configuration\Route("/getListFriend/{userId}", name="admin_user_list_friends")
     * @Configuration\Method("GET")
     * @param int $userId
     *
     * @return JsonResponse
     */
    public function listFriendsAction($userId)
    {
        $user = $this->getWizardUser($userId);

        $data = [];
        /** @var WizardUser $friendWithMe */
        foreach ($user->getFriendsWithMe() as $friendWithMe) {
            $data[] = [
                'id'        => $friendWithMe->getId(),
                'firstname' => $friendWithMe->getFirstname(),
                'lastname'  => $friendWithMe->getLastname(),
                'email'     => $friendWithMe->getEmail()
            ];
        }

        return new JsonResponse(['data' => $data]);
    }

    /**
     * @return EntityManager|object
     */
    protected function getEntityManager()
    {
        if (!$this->em instanceof EntityManager) {
            $this->em =
                $this->getDoctrine()
                     ->getManager()
            ;
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
        return $this->getEntityManager()
                    ->getRepository('WizardalleyCoreBundle:WizardUser')
                    ->find($userId)
            ;
    }
}
