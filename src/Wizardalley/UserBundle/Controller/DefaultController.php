<?php

namespace Wizardalley\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller {

    public function userWallAction(Request $request, $id) {
        //
        $repo = $this->getDoctrine()->getRepository('WizardalleyUserBundle:WizardUser');
        $user = $repo->find($id);
        if (!$user) {
            return new \Symfony\Component\Translation\Exception\NotFoundResourceException();
        }
        return $this->render('WizardalleyUserBundle:Default:userWall.html.twig', array(
                    'user' => $user,
        ));
    }

    public function addAsAFriendAction(Request $request, $id_user) {
        $repo = $this->getDoctrine()->getRepository('WizardalleyUserBundle:WizardUser');
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


    public function friendListAction(Request $request) {
        $numberDisplay = 24;
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository('WizardalleyUserBundle:WizardUser');
        $friends = $repo->findFriends($user);
        return new JsonResponse($friends);
    }

}
