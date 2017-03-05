<?php

namespace Wizardalley\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\DefaultBundle\Controller\BaseController;

/**
 * Class NotificationController
 * @package Wizardalley\UserBundle\Controller
 */
class NotificationController extends BaseController
{
    /**
     * @Route("/user/notification", name="user_notification_index")
     * @return Response
     */
    public function indexNotificationAction()
    {
        return $this->render('::notification/index.html.twig');
    }

    /**
     * @Route("/user/notification/list/{page}", name="user_notification_list", options={"expose"=true})
     * @param int $page
     * @return Response
     */
    public function listNotificationJsonAction($page)
    {
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:FollowedNotification');

        return $this->sendJsonResponse('success', $this->renderView(
            '::notification/list.html.twig',
            [
                'notifications' => $repo->findNotification(
                    $this->get('security.token_storage')->getToken()->getUser()->getId(),
                    $page
                )
            ]
        ));
    }

    public function markMotificationAsCheckedAction($idNotification)
    {

    }

    public function markAllNotificationAsChecked()
    {

    }
}