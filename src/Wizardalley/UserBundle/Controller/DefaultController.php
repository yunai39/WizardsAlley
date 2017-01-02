<?php

namespace Wizardalley\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends \Wizardalley\DefaultBundle\Controller\BaseController
{
    /**
     * userWallAction
     *
     * This action will present the presentation page of the web site
     *
     * pattern: /wall/{id}
     * road_name: wizardalley_user_wall
     *
     * @param Request $request http request
     * @param integer $id      id for the user
     *
     * @return Response
     */
    public function userWallAction(Request $request, $id)
    {
        //
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $user = $repo->find($id);
        if (!$user) {
            return new \Symfony\Component\Translation\Exception\NotFoundResourceException();
        }

        return $this->render('WizardalleyUserBundle:Default:userWall.html.twig', array(
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
     * @param Request $request http request
     * @param integer $id_user id for the user
     *
     * @return Response
     */
    public function addAsAFriendAction(Request $request, $id_user)
    {
        $repo   = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $friend = $repo->find($id_user);
        if (!$friend) {
            return new \Symfony\Component\Translation\Exception\NotFoundResourceException();
        }
        $user = $this->getUser();
        $user->addMyFriend($friend);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }


    /**
     * friendListAction
     *
     * This action will return a list of friends
     *
     * pattern: /user/getFriendsJson
     * road_name: wizard_get_friends_json
     *
     * @param Request $request http request
     *
     * @return JsonResponse
     */
    public function friendListAction(Request $request, $page = 1)
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
     * @param Request $request http request
     *
     * @return JsonResponse
     */
    public function displayFriendListAction()
    {
        $user    = $this->getUser();
        $repo    = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:WizardUser');
        $friends = $repo->findFriends($user);

        return $this->render('WizardalleyUserBundle:Default:friendList.html.twig', array(
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
            'html' => $this->renderView('WizardalleyUserBundle:Default:publication.html.twig', array(
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
                'html' => $this->renderView('WizardalleyUserBundle:Default:publication.html.twig', array(
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
}
