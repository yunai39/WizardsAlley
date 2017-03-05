<?php

namespace Wizardalley\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\FollowedNotification;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\CoreBundle\Entity\WizardUserRepository;
use Wizardalley\DefaultBundle\Controller\BaseController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class DefaultController extends BaseController
{
    /**
     * userWallAction
     *
     * This action will present the presentation page of the web site
     *
     * pattern: /user/wall/{id}
     * road_name: wizardalley_user_wall
     *
     * @param integer $id      id for the user
     *
     * @return Response
     */
    public function userWallAction($id)
    {
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $user = $repo->find($id);
        if (!$user) {
            return new NotFoundResourceException();
        }

        return $this->render('::user/home.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * addAsAFriendAction
     *
     * This action will add a user as a friend
     *
     * pattern: /user/addAsAFriend/{id_user}
     * road_name: wizard_add_as_a_friend
     *
     * @param integer $id_user id for the user
     * @param Request $request
     *
     * @return Response
     */
    public function addAsAFriendAction(
        Request $request,
        $id_user
    ) {
        $em     = $this->getDoctrine()->getManager();
        $repo   = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $friend = $repo->find($id_user);
        if (!$friend) {
            return new NotFoundResourceException();
        }
        $user = $this->getUser();
        $user->addMyFriend($friend);
        // Creer la notification
        $followedNotification = new FollowedNotification();
        $followedNotification
            ->setType('ask_friend')
            ->setChecked(false)
            ->setUser($friend)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime())
            ->setDataNotification(json_encode([]))
        ;

        $em->persist($user);
        $em->persist($followedNotification);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }


    /**
     * friendListAction
     *
     * This action will return a list of friends
     *
     * pattern: /user/getFriendsJson
     * road_name: wizard_get_friends_json
     *
     * @param int $page
     *
     * @return JsonResponse
     */
    public function friendListAction($page = 1)
    {
        $numberDisplay = 3;
        $user          = $this->getUser();
        $repo          = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $friends       = $repo->findFriends($user, $page, $numberDisplay);

        return $this->sendJsonResponse('success', $friends);
    }


    /**
     * friendListAction
     *
     * This action will display a list of friends
     *
     * pattern: /user/getFriendsView
     * road_name: wizard_get_friends_view
     *
     * @return JsonResponse
     */
    public function displayFriendListAction()
    {
        $user    = $this->getUser();
        $repo    = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $friends = $repo->findFriends($user);

        return $this->render('::user/friendList.html.twig', array(
            'friends' => $friends,
        ));
    }

    /**
     * @param int $page
     *
     * @return Response
     */
    public function displayPublicationPageAction($page)
    {
        $limit        = 2;
        $offset       = ($page - 1) * $limit;
        $repo         = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $publications = $repo->findPublication($this->getUser(), $offset, $limit);

        return $this->sendJsonResponse('success', null, 200, [
            'html' => $this->renderView('::user/publication.html.twig', array(
                    'publications' => $publications,
                ))
        ]);
    }

    /**
     * @param int $page
     *
     * @return JsonResponse
     */
    public function displayPublicationUserAction($page = 1)
    {
        $limit        = 2;
        $offset       = ($page - 1) * $limit;
        $repo         = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $publications = $repo->findPublication($this->getUser(), $offset, $limit);

        return $this->sendJsonResponse('success', null, 200, [
                'html' => $this->renderView('::user/publication.html.twig', array(
                        'publications' => $publications,
                    ))
            ]);
    }

    /**
     * @param int|null $id
     *
     * @return JsonResponse
     */
    public function displayPublicationWallAction($id = null)
    {
        $limit        = 2;
        $repo         = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $publications = $repo->findPublicationWall($this->getUser(), $id, $limit);

        return $this->sendJsonResponse('success', null, 200, [
                'extra' => $publications
            ]);
    }

    /**
     *
     * @param string $search
     *
     * @return Response
     */
    public function searchUserJsonAction($search)
    {
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');

        return $this->sendJsonResponse('success', $repo->searchUser($search));
    }

    /**
     * @return JsonResponse
     */
    public function getFriendListAction()
    {
        /** @var WizardUser $user */
        $user        = $this->getUser();
        $friends     = $user->getMyFriends();
        $friendArray = [];
        /** @var WizardUser $friend */
        foreach ($friends as $friend) {
            $friendArray[] = [
                'id'   => $friend->getUsername(),
                'name' => $friend->getUsername()
            ];
        }

        return new JsonResponse($friendArray);
    }

    /**
     * @return JsonResponse
     */
    public function getUserConnectedAction()
    {
        $limit = 10;
        /** @var WizardUser $user */
        $user        = $this->getUser();
        /** @var WizardUserRepository $repo */
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');

        return new JsonResponse($repo->findUserConnected($user, $limit));
    }
}
