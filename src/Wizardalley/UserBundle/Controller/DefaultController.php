<?php

namespace Wizardalley\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\FollowedNotification;
use Wizardalley\CoreBundle\Entity\FollowedNotificationRepository;
use Wizardalley\CoreBundle\Entity\WizardUser;
use Wizardalley\CoreBundle\Entity\WizardUserRepository;
use Wizardalley\DefaultBundle\Controller\BaseController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 *
 * @package Wizardalley\UserBundle\Controller
 */
class DefaultController extends BaseController
{
    /**
     * userWallAction
     *
     * This action will present the presentation page of the web site
     *
     * @Route("/user/wall/display/{id}", name="wizardalley_user_wall")
     * @param integer $id id for the user
     *
     * @return Response
     */
    public function userWallAction($id = 1)
    {
        $repo =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;
        $user = $repo->find($id);
        if (!$user) {
            return new NotFoundResourceException();
        }

        return $this->render(
            '::user/wall.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * addAsAFriendAction
     *
     * This action will add a user as a friend
     *
     * @Route("/user/addAsAFriend/{id_user}", name="wizard_add_as_a_friend")
     * @param integer $id_user id for the user
     * @param Request $request
     *
     * @return Response
     */
    public function addAsAFriendAction(Request $request,
                                       $id_user)
    {
        $em   =
            $this->getDoctrine()
                 ->getManager()
        ;
        $repo =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;

        /** @var WizardUser $friend */
        $friend = $repo->find($id_user);
        if (!$friend) {
            return new NotFoundResourceException();
        }
        /** @var WizardUser $userAsking */
        $userAsking       = $this->getUser();
        $notificationType = FollowedNotification::TYPE_ASK_FRIEND;
        if ($userAsking->askingForFriendship($friend)) {
            $notificationType = FollowedNotification::TYPE_ANSWERS_ASK_FRIEND;
        }
        $userAsking->addMyFriend($friend);
        // Creer la notification
        $followedNotification = new FollowedNotification();

        $followedNotification
            ->setType($notificationType)
            ->setChecked(false)
            ->setUser($friend)
            ->setCreatedAt(
                new \DateTime()
            )
            ->setUpdatedAt(new \DateTime())
            ->setDataNotification(
                json_encode(
                    [
                        'asked_from'          => $userAsking->getId(),
                        'asked_from_username' => $userAsking->getUsername(),
                        'asked_to'            => $id_user
                    ],
                    true
                )
            )
        ;

        $em->persist($userAsking);
        $em->persist($followedNotification);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @param         $id
     * @Route("/user/validateFriend/{id}", name="wizard_validate_friend")
     *
     * @return Response
     */
    public function validateUserAsFriendsAction(Request $request,
                                                $id)
    {
        /** @var WizardUser $user */
        $user =
            $this->get('security.token_storage')
                 ->getToken()
                 ->getUser()
        ;
        /** @var WizardUserRepository $repoUser */
        $repoUser =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;
        /** @var FollowedNotificationRepository $repoNotification */
        $repoNotification =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:FollowedNotification')
        ;
        /** @var FollowedNotification $notification */
        $notification     = $repoNotification->find($id);
        $dataNotification = $notification->getData();
        $friend           = $repoUser->find($dataNotification[ 'asked_from' ]);
        if ($notification->getType() != FollowedNotification::TYPE_ASK_FRIEND ||
            $dataNotification[ 'asked_to' ] != $user->getId() ||
            !($friend instanceof WizardUser)
        ) {
            $request->getSession()
                    ->getFlashBag()
                    ->add(
                        'error',
                        'wizard.unknown_error'
                    )
            ;

            return $this->redirect($this->generateUrl('user_notification_index'));
        }

        $this->addAsAFriendAction(
            $request,
            $user->getId()
        );

        return $this->redirect(
            $this->generateUrl(
                'wizardalley_user_wall',
                ['id' => $dataNotification[ 'asked_from' ]]
            )
        );
    }

    /**
     * friendListAction
     *
     * This action will return a list of friends
     *
     * @Route("/user/getFriendsJson/{user}/{page}", name="wizard_get_friends_json",  options={"expose"=true})
     *
     * @param int $page
     * @param int $user
     *
     * @return JsonResponse
     */
    public function friendListAction($user, $page = 1)
    {
        $numberDisplay = 3;
        $userRepo          =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;
        $user          = $userRepo->find($user);
        $repo          =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;
        $friends       =
            $repo->findFriends(
                $user,
                $page,
                $numberDisplay
            );

        return $this->sendJsonResponse(
            'success',
            $friends
        );
    }

    /**
     * friendListAction
     *
     * This action will display a list of friends
     *
     * @Route("/user/getFriendsView", name="wizard_get_friends_view",  options={"expose"=true})
     *
     * @return JsonResponse
     */
    public function displayFriendListAction()
    {
        $user    = $this->getUser();
        $repo    =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;
        $friends = $repo->findFriends($user);

        return $this->render(
            '::user/getFriendsView.html.twig',
            [
                'friends' => $friends,
            ]
        );
    }

    /**
     * @param int $page
     *
     * @Route("/user/getPublication/{page}", name="wizard_get_publication_view",  options={"expose"=true})
     * @return Response
     */
    public function displayPublicationRuelleAction($page = 1)
    {
        /** @var WizardUserRepository $repo */
        $repo         =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;
        $publications =
            $repo->findPublicationRuelle(
                $this->getUser(),
                $page
            );

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::user/publication.html.twig',
                    [
                        'publications' => $publications,
                    ]
                )
            ]
        );
    }

    /**
     * @param int $page
     * @Route("/user/profile/publication/{user}/{page}", name="wizardalley_user_profile_publication",  options={"expose"=true})
     *
     * @return JsonResponse
     */
    public function displayPublicationProfileAction($user, $page = 1)
    {
        /** @var WizardUserRepository $repo */
        $repo         =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;
        $user = $repo->find($user);

        $publications =
            $repo->findPublication(
                $user,
                $page,
                4
            );

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::user/publication.html.twig',
                    [
                        'publications' => $publications,
                    ]
                )
            ]
        );
    }

    /**
     * @return JsonResponse
     * @Route("/user/lastConnected", name="wizard_last_connected_list",  options={"expose"=true})
     */
    public function displayUserConnectedLastAction()
    {
        /** @var WizardUserRepository $repo */
        $repo =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;

        $users = $repo->findUserLastAction();

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::user/userLastConnected.html.twig',
                    [
                        'users' => $users,
                    ]
                )
            ]
        );
    }

    /**
     * @param int $page
     * @param int $user
     *
     * @Route("/user/wall/publication/{user}/{page}", name="wizardalley_user_wall_publication",
     *                                                options={"expose"=true})
     * @return JsonResponse
     */
    public function displayPublicationWallAction($user, $page = 1)
    {
        /** @var WizardUserRepository $repo */
        $repo         =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;
        $publications =
            $repo->findPublicationWall(
                $repo->find($user),
                $page,
                4
            );

        return $this->sendJsonResponse(
            'success',
            null,
            200,
            [
                'html' => $this->renderView(
                    '::user/publication.html.twig',
                    [
                        'publications' => $publications,
                    ]
                )
            ]
        );
    }

    /**
     * @param mixed $search
     *
     * @Route("/search/user/{search}", name="wizard_search_user_json")
     * @return Response
     */
    public function searchUserJsonAction($search = -1)
    {
        $repo =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;

        return $this->sendJsonResponse(
            'success',
            $repo->searchUser($search)
        );
    }

    /**
     * @Route("/user/getFriendList", name="wizard_friend_list",  options={"expose"=true})
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
     * @Route("/user/getUserConnected", name="wizard_user_connected",  options={"expose"=true})
     * @return JsonResponse
     */
    public function getUserConnectedAction()
    {
        /** @var WizardUser $user */
        $user = $this->getUser();
        /** @var WizardUserRepository $repo */
        $repo =
            $this->getDoctrine()
                 ->getRepository('WizardalleyCoreBundle:WizardUser')
        ;

        return new JsonResponse($repo->findUserConnected($user));
    }
}
