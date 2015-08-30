<?php

namespace Wizardalley\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\PublicationBundle\Entity\PageRepository")
 */
class Page
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="urlFacebook", type="string", length=255)
     */
    private $urlFacebook;

    /**
     * @var string
     *
     * @ORM\Column(name="imgPresentation", type="string", length=255)
     */
    private $imgPresentation;

    /**
     * @var string
     *
     * @ORM\Column(name="imgProfile", type="string", length=255)
     */
    private $imgProfile;

    /**
    * @ORM\OneToMany(targetEntity="Wizardalley\PublicationBundle\Entity\Publication", mappedBy="page", cascade={"remove", "persist"})
    */
    private $publications;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\UserBundle\Entity\WizardUser", inversedBy="pagesCreated")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
    */
    private $creator;
    
    
    /**
    * @ORM\ManyToMany(targetEntity="Wizardalley\USerBundle\Entity\WizardUser", inversedBy="pagesEditor")
    * @ORM\JoinTable(name="page_user_editor")
    */
   private $editors;
    
    public function __construct()
    {
        parent::__construct();
        $this->publications = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return Page
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
     * Set description
     *
     * @param string $description
     * @return Page
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set urlFacebook
     *
     * @param string $urlFacebook
     * @return Page
     */
    public function setUrlFacebook($urlFacebook)
    {
        $this->urlFacebook = $urlFacebook;

        return $this;
    }

    /**
     * Get urlFacebook
     *
     * @return string 
     */
    public function getUrlFacebook()
    {
        return $this->urlFacebook;
    }

    /**
     * Set imgPresentation
     *
     * @param string $imgPresentation
     * @return Page
     */
    public function setImgPresentation($imgPresentation)
    {
        $this->imgPresentation = $imgPresentation;

        return $this;
    }

    /**
     * Get imgPresentation
     *
     * @return string 
     */
    public function getImgPresentation()
    {
        return $this->imgPresentation;
    }

    /**
     * Set imgProfile
     *
     * @param string $imgProfile
     * @return Page
     */
    public function setImgProfile($imgProfile)
    {
        $this->imgProfile = $imgProfile;

        return $this;
    }

    /**
     * Get imgProfile
     *
     * @return string 
     */
    public function getImgProfile()
    {
        return $this->imgProfile;
    }
    
    

    /**
     * Add publications
     *
     * @param \Wizardalley\PublicationBundle\Entity\Publication $publications
     * @return Page
     */
    public function addPublication(\Wizardalley\PublicationBundle\Entity\Publication $publications)
    {
        $this->publications[] = $publications;

        return $this;
    }

    /**
     * Remove publications
     *
     * @param \Wizardalley\PublicationBundle\Entity\Publication $publications
     */
    public function removePublication(\Wizardalley\PublicationBundle\Entity\Publication $publications)
    {
        $this->publications->removeElement($publications);
    }

    /**
     * Get publications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Set creator
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $creator
     * @return Page
     */
    public function setCreator(\Wizardalley\UserBundle\Entity\WizardUser $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Wizardalley\UserBundle\Entity\WizardUser 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add editors
     *
     * @param \Wizardalley\USerBundle\Entity\WizardUser $editors
     * @return Page
     */
    public function addEditor(\Wizardalley\USerBundle\Entity\WizardUser $editors)
    {
        $this->editors[] = $editors;

        return $this;
    }

    /**
     * Remove editors
     *
     * @param \Wizardalley\USerBundle\Entity\WizardUser $editors
     */
    public function removeEditor(\Wizardalley\USerBundle\Entity\WizardUser $editors)
    {
        $this->editors->removeElement($editors);
    }

    /**
     * Get editors
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEditors()
    {
        return $this->editors;
    }
}
