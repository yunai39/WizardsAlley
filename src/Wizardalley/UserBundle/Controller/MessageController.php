<?php

namespace Wizardalley\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\MessageBundle\Controller\MessageController as BaseController;

/**
 * Class MessageController
 *
 * @package WizardsAlley\UserBundle\Controller
 */
class MessageController extends BaseController
{
    /**
     * @Route("/user/message/all", name="user_message_all")
     * @return mixed
     */
    public function listAllAction()
    {
        $inboxThread = $this->getProvider()->getInboxThreads();
        $sentThread  = $this->getProvider()->getSentThreads();

        return $this->container->get('templating')->renderResponse(
            'FOSMessageBundle:Message:all.html.twig',
            [
                'inboxThread' => $inboxThread,
                'sentThread'  => $sentThread,
            ]
        );
    }
}
