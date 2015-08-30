<?php

namespace Wizardalley\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SmallPublication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\PublicationBundle\Entity\SmallPublicationRepository")
 */
class SmallPublication
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="datetime")
     */
    private $datePublication;


    
    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\UserBundle\Entity\WizardUser", inversedBy="smallPublication")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
     * Set content
     *
     * @param string $content
     * @return SmallPublication
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
     * Set datePublication
     *
     * @param \DateTime $datePublication
     * @return SmallPublication
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime 
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set user
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $user
     * @return SmallPublication
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
