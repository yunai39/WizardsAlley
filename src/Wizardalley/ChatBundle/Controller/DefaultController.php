<?php

namespace Wizardalley\ChatBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WizardalleyChatBundle:Default:index.html.twig');
    }
    
    public function getUserAction()
    {
        $service = $this->get('chat.service');
        $users = $service->getUserChat();
        return new JsonResponse($users);
    }
}
