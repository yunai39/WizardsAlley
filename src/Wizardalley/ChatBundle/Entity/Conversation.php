<?php

namespace Wizardalley\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\ChatBundle\Entity\ConversationRepository")
 */
class Conversation
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
    * @ORM\OneToMany(targetEntity="ChatMessage", mappedBy="conversation", cascade={"remove", "persist"})
    */
    private $messages;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="multiple", type="boolean")
     */
    private $multiple;
    /**
     * @ORM\ManyToMany(targetEntity="Wizardalley\UserBundle\Entity\WizardUser", inversedBy="conversation")
     * @ORM\JoinTable(name="user_conversation")
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
     * @return Conversation
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
     * @return Conversation
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
     * @return Conversation
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
     * @return Conversation
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
     * Set multiple
     *
     * @param boolean $multiple
     * @return Conversation
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * Get multiple
     *
     * @return boolean 
     */
    public function getMultiple()
    {
        return $this->multiple;
    }
}
