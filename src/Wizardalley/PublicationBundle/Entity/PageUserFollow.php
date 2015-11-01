<?php

namespace Wizardalley\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class PageUserFollow
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
     * @ORM\ManyToOne(targetEntity="Wizardalley\PublicationBundle\Entity\Page", inversedBy="followers")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
    */
    private $page;
    
    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\UserBundle\Entity\WizardUser", inversedBy="pagesFollowed")
     * @ORM\JoinColumn(name="wizard_user_id", referencedColumnName="id")
    */
    private $user;

    /**
     * @var Datetime
     * 
     * @ORM\Column(type="datetime") 
     */
    private $dateInscription;
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
     * Set page
     *
     * @param \Wizardalley\PublicationBundle\Entity\Page $page
     * @return PageFollower
     */
    public function setPage(\Wizardalley\PublicationBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Wizardalley\PublicationBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set user
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $user
     * @return PageFollower
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

    /**
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     * @return PageUserFollow
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * Get dateInscription
     *
     * @return \DateTime 
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }
}