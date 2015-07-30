<?php
namespace Wizardalley\ChatBundle\Service;
class ChatService{
    
    protected $doctrine;
    
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    public function getUserChat(){
        $repo = $this->doctrine->getRepository('WizardalleyUserBundle:WizardUser');
        // Normalement il ne faudra recuperer que les utilisateur connecter
        $em = $this->doctrine->getManager();
        $query = $em->createQuery(
            'SELECT u.id,u.username
            FROM WizardalleyUserBundle:WizardUser u'
        );

        $users = $query->getResult();
        
        return $users;
    }
}