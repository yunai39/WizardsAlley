<?php

namespace Wizardalley\ChatBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wizardalley\ChatBundle\Entity\ChatUserConnected;
use Wizardalley\ChatBundle\Entity\ChatConversation;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WizardalleyChatBundle:Default:index.html.twig');
    }
    
    public function getUserAction()
    {
        $service = $this->get('chat.service');
        $repo = $this->getDoctrine()->getRepository('WizardalleyChatBundle:ChatUserConnected');
        
        $users = $repo->findUserConnected($this->getUser()->getId());
        
        //Update timer
        $user = $this->getUser();
        $entity = new ChatUserConnected();
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
        $repo = $this->getDoctrine()->getRepository('WizardalleyChatBundle:ChatConversation');
        $messages = $repo->findConversationBetweenUser($id,$this->getUser()->getId());
        return new JsonResponse($messages);
        
    }
    
    public function addMessageConversationAction(){
        $conversation_id = $this->get('request')->request->get('conversation_id');
        
        $content = $this->get('request')->request->get('content');
        $user_id = $this->getUser()->getId();
        
        if(!$conversation_id){
            $conversation = new ChatConversation();
            
            die();
            
        }
        $em = $this->getDoctrine()->getManager();
        
        
        $message = new \Wizardalley\ChatBundle\Entity\ChatMessage();
        $message->setUser($this->getUser());
        $message->setContent($content);
        $message->setTimeSent(new \DateTime('now'));
        $message->setConversation($em->getReference('WizardalleyChatBundle:ChatConversation', $conversation_id));
        
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
