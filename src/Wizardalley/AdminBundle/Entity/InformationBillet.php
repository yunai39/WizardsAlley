<?php

namespace Wizardalley\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InformationBillet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\AdminBundle\Entity\InformationBilletRepository")
 */
class InformationBillet
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreateBillet", type="datetime")
     */
    private $dateCreateBillet;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublicationBillet", type="datetime")
     */
    private $datePublicationBillet;
    
    /**
     * @var \boolean
     *
     * @ORM\Column(name="commentsEnabled", type="boolean")
     */
    private $commentsEnabled;
    
    /**
    * @ORM\ManyToOne(targetEntity="Wizardalley\UserBundle\Entity\WizardUser", cascade={"remove"})
    */
    private $user;

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
     * Set name
     *
     * @param string $name
     * @return InformationBillet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return InformationBillet
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
     * Set dateCreateBillet
     *
     * @param \DateTime $dateCreateBillet
     * @return InformationBillet
     */
    public function setDateCreateBillet($dateCreateBillet)
    {
        $this->dateCreateBillet = $dateCreateBillet;

        return $this;
    }

    /**
     * Get dateCreateBillet
     *
     * @return \DateTime 
     */
    public function getDateCreateBillet()
    {
        return $this->dateCreateBillet;
    }

    /**
     * Set datePublicationBillet
     *
     * @param \DateTime $datePublicationBillet
     * @return InformationBillet
     */
    public function setDatePublicationBillet($datePublicationBillet)
    {
        $this->datePublicationBillet = $datePublicationBillet;

        return $this;
    }

    /**
     * Get datePublicationBillet
     *
     * @return \DateTime 
     */
    public function getDatePublicationBillet()
    {
        return $this->datePublicationBillet;
    }

    /**
     * Set commentsEnabled
     *
     * @param boolean $commentsEnabled
     * @return InformationBillet
     */
    public function setCommentsEnabled($commentsEnabled)
    {
        $this->commentsEnabled = $commentsEnabled;

        return $this;
    }

    /**
     * Get commentsEnabled
     *
     * @return boolean 
     */
    public function getCommentsEnabled()
    {
        return $this->commentsEnabled;
    }


    /**
     * Set user
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $user
     * @return InformationBillet
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
