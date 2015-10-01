<?php

namespace Wizardalley\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChatMessage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\ChatBundle\Entity\ChatMessageRepository")
 */
class ChatMessage
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
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeSent", type="datetime")
     */
    private $timeSent;


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
     * @ORM\ManyToOne(targetEntity="ChatConversation", inversedBy="messages", cascade={"remove"})
     * @ORM\JoinColumn(name="conversation_id", referencedColumnName="id")
    */
    private $conversation;
    
    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\UserBundle\Entity\WizardUser", )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;
    /**
     * Set content
     *
     * @param string $content
     * @return ChatMessage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set timeSent
     *
     * @param \DateTime $timeSent
     * @return ChatMessage
     */
    public function setTimeSent($timeSent)
    {
        $this->timeSent = $timeSent;

        return $this;
    }

    /**
     * Get timeSent
     *
     * @return \DateTime 
     */
    public function getTimeSent()
    {
        return $this->timeSent;
    }

    /**
     * Set conversation
     *
     * @param \Wizardalley\ChatBundle\Entity\ChatConversation $conversation
     * @return ChatMessage
     */
    public function setConversation(\Wizardalley\ChatBundle\Entity\ChatConversation $conversation = null)
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get conversation
     *
     * @return \Wizardalley\ChatBundle\Entity\ChatConversation 
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * Set user
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $user
     * @return ChatMessage
     */
    public function setUser(\Wizardalley\UserBundle\Entity\WizardUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Wizardalley\UserBundle\Entity\WizardUser 
     */
    public function getUser()
    {
        return $this->user;
    }
        
}
