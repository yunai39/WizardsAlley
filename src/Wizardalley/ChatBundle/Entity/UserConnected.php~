<?php

namespace Wizardalley\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserConnected
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\ChatBundle\Entity\UserConnectedRepository")
 */
class UserConnected
{
    /** @ORM\Id @ORM\OneToOne(targetEntity="\Wizardalley\UserBundle\Entity\WizardUser") */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timeConnected", type="datetime")
     */
    private $timeConnected;


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
     * Set timeConnected
     *
     * @param \DateTime $timeConnected
     * @return UserConnected
     */
    public function setTimeConnected($timeConnected)
    {
        $this->timeConnected = $timeConnected;

        return $this;
    }

    /**
     * Get timeConnected
     *
     * @return \DateTime 
     */
    public function getTimeConnected()
    {
        return $this->timeConnected;
    }

    /**
     * Set id
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $id
     * @return UserConnected
     */
    public function setId(\Wizardalley\UserBundle\Entity\WizardUser $id)
    {
        $this->id = $id;

        return $this;
    }
}
