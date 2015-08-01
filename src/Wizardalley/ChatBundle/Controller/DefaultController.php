<?php

namespace Wizardalley\ChatBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\ChatBundle\Entity\UserConnected;
use Wizardalley\ChatBundle\Entity\Conversation;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WizardalleyChatBundle:Default:index.html.twig');
    }
    
    public function getUserAction()
    {
        $service = $this->get('chat.service');
        $repo = $this->getDoctrine()->getRepository('WizardalleyChatBundle:UserConnected');
        
        $users = $repo->findUserConnected($this->getUser()->getId());
        
        //Update timer
        $user = $this->getUser();
        $entity = new UserConnected();
        $entity->setId($user);
        $entity->setTimeConnected(new \DateTime('now'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->merge($entity);
        $entityManager->flush();
        return new JsonResponse($users);
    }
    
    public function getMessageConversation2Action()
    {
        $id = $this->get('request')->request->get('user_id');
        $repo = $this->getDoctrine()->getRepository('WizardalleyChatBundle:Conversation');
        $messages = $repo->findConversationBetweenUser($id,$this->getUser()->getId());
        if(!$messages){
            $conversation = new Conversation();
            $conversation->setDateStart(new \DateTime('now'));
            $conversation->setMultiple(false);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($conversation);
            $em->flush();
        }
        return new JsonResponse($messages);
        
    }
    
    public function addMessageConversationAction(){
        $conversation_id = $this->get('request')->request->get('conversation_id');
        $content = $this->get('request')->request->get('content');
        $user_id = $this->getUser()->getId();
        
        $em = $this->getDoctrine()->getManager();
        $message = new \Wizardalley\ChatBundle\Entity\ChatMessage();
        $message->setUser($this->getUser());
        $message->setContent($content);
        $message->setTimeSent(new \DateTime('now'));
        $message->setConversation($em->getReference('WizardalleyChatBundle:Conversation', $conversation_id));
        
        $em->persist($message);
        $em->flush();
        $retour = array(
            "user_id"   => $user_id,
            "username"  => $this->getUser()->getUsername(),
            "content"   => $content
        );
        return new JsonResponse($retour);
    }
}
