<?php

namespace Wizardalley\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wizardalley\CoreBundle\Entity\FollowedNotification;
use Wizardalley\CoreBundle\Entity\FollowedNotificationRepository;
use Wizardalley\DefaultBundle\Controller\BaseController;

/**
 * Class NotificationController
 *
 * @package Wizardalley\UserBundle\Controller
 */
class NotificationController extends BaseController
{
    /**
     * @Route("/user/notifications", name="user_notification_index")
     * @return Response
     */
    public function indexNotificationAction()
    {
        return $this->render('::notification/index.html.twig');
    }

    /**
     * @Route("/user/notificationStatus/list/{status}/{page}", name="user_notification_status_list", options={"expose"=true})
     * @param int $page
     *
     * @return Response
     */
    public function listStatusNotificationJsonAction($status, $page)
    {
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:FollowedNotification');

        return $this->sendJsonResponse(
            'success',
            $this->renderView(
                '::notification/list.html.twig',
                [
                    'notifications' => $repo->findStatusNotification(
                        $this->get('security.token_storage')->getToken()->getUser()->getId(),
                        $page,
                        $status
                    )
                ]
            )
        );
    }

    /**
     * @Route("/user/notification/list/{page}", name="user_notification_list", options={"expose"=true})
     * @param int $page
     *
     * @return Response
     */
    public function listNotificationJsonAction($page)
    {
        $repo = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:FollowedNotification');

        return $this->sendJsonResponse(
            'success',
            $this->renderView(
                '::notification/list.html.twig',
                [
                    'notifications' => $repo->findNotification(
                        $this->get('security.token_storage')->getToken()->getUser()->getId(),
                        $page
                    )
                ]
            )
        );
    }

    /**
     * @param int $idNotification
     * @Route("/user/notification/follow/{idNotification}", name="user_notification_follow",
     *                                                      requirements={"idNotification" = "\d+"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function followNotificationAction($idNotification)
    {
        // Recuperer la notification
        /** @var FollowedNotification $notification */
        $notification = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:FollowedNotification')->find(
            $idNotification
        )
        ;

        /** @var Request $request */
        $request = $this->get('request');
        $url     = $request->headers->get('referer');
        if ($notification->getType() === FollowedNotification::TYPE_ANSWERS_ASK_FRIEND) {
            $url = $this->generateUrl('wizardalley_user_wall', ['id' => $notification->getData()[ 'asked_from' ]]);
        } elseif ($notification->getType() === FollowedNotification::TYPE_MESSAGE) {
            $url = $this->generateUrl(
                'fos_message_thread_view',
                ['threadId' => $notification->getData()[ 'thread_id' ]]
            );
        } elseif ($notification->getType() === FollowedNotification::TYPE_PUBLICATION) {
            $url = $this->generateUrl('publication_show', ['id' => $notification->getData()[ 'publication_id' ]]);
        }
        $em = $this->getDoctrine()->getManager();

        $notification->setChecked(true);
        $em->persist($notification);
        $em->flush();

        return $this->redirect($url);
    }

    /**
     * @Route("/user/notification/markAllAsRead", name="user_notification_mark_all_read")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function markAllNotificationAsCheckedAction()
    {
        $user = $this->getUser();
        $em   = $this->getDoctrine()->getManager();
        /** @var FollowedNotificationRepository $repo */
        $repo          = $this->getDoctrine()->getRepository('WizardalleyCoreBundle:FollowedNotification');
        $notifications = $repo->findBy(['user' => $user]);
        /** @var FollowedNotification $notification */
        foreach ($notifications as $notification) {
            $notification->setChecked(true);
            $em->persist($notification);
        }

        $em->flush();

        return $this->redirect($this->generateUrl('user_notification_index'));
    }
}