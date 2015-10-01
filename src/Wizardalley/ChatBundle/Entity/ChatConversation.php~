<?php

namespace Wizardalley\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChatConversation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\ChatBundle\Entity\ChatConversationRepository")
 */
class ChatConversation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStart", type="datetime")
     */
    private $dateStart;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastMessage", type="datetime")
     */
    private $lastMessage;
    /**
    * @ORM\OneToMany(targetEntity="ChatMessage", mappedBy="conversation", cascade={"remove", "persist"})
    */
    private $messages;
    
    

    /**
     * @ORM\ManyToMany(targetEntity="Wizardalley\UserBundle\Entity\WizardUser", inversedBy="conversation")
     * @ORM\JoinTable(name="chat_user_conversation")
     */
    private $users;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return ChatConversation
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set users
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $users
     * @return ChatConversation
     */
    public function setUsers(\Wizardalley\UserBundle\Entity\WizardUser $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \Wizardalley\UserBundle\Entity\WizardUser 
     */
    public function getUsers()
    {
        return $this->users;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add messages
     *
     * @param \Wizardalley\ChatBundle\Entity\ChatMessage $messages
     * @return ChatConversation
     */
    public function addMessage(\Wizardalley\ChatBundle\Entity\ChatMessage $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Wizardalley\ChatBundle\Entity\ChatMessage $messages
     */
    public function removeMessage(\Wizardalley\ChatBundle\Entity\ChatMessage $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add users
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $users
     * @return ChatConversation
     */
    public function addUser(\Wizardalley\UserBundle\Entity\WizardUser $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $users
     */
    public function removeUser(\Wizardalley\UserBundle\Entity\WizardUser $users)
    {
        $this->users->removeElement($users);
    }



    /**
     * Set lastMessage
     *
     * @param \DateTime $lastMessage
     * @return ChatConversation
     */
    public function setLastMessage($lastMessage)
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }

    /**
     * Get lastMessage
     *
     * @return \DateTime 
     */
    public function getLastMessage()
    {
        return $this->lastMessage;
    }
    
}
