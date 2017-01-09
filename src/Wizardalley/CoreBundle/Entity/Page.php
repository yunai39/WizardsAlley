<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\PageRepository")
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
     * @var PageFavorite
     * @ORM\OneToOne(targetEntity="PageFavorite", mappedBy="page")
     */
    private $favorite;

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
     * @ORM\Column(name="path_couverture",type="string", length=255, nullable=true)
     */
    public $pathCouverture;

    /**
     * @var string
     *
     * @ORM\Column(name="path_profile", type="string", length=255)
     */
    private $pathProfile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="date")
     */
    private $dateCreation;

    /**
    * @ORM\OneToMany(targetEntity="Wizardalley\CoreBundle\Entity\Publication", mappedBy="page", cascade={"remove", "persist"})
    */
    private $publications;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\WizardUser", inversedBy="pagesCreated")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
    */
    private $creator;


    /**
     * @ORM\ManyToMany(targetEntity="Wizardalley\CoreBundle\Entity\WizardUser", inversedBy="pagesEditor")
     * @ORM\JoinTable(name="page_user_editor")
     */
    private $editors;

    /**
     * @ORM\OneToMany(targetEntity="Wizardalley\CoreBundle\Entity\PageUserFollow", mappedBy="page")
     */
    private $followers;

    public function __construct()
    {
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
     * @return PageFavorite
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * @param PageFavorite $favorite
     *
     * @return Page
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;
        return $this;
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
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param \DateTime $dateCreation
     *
     * @return Page
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }


    /**
     * Add publications
     *
     * @param \Wizardalley\CoreBundle\Entity\Publication $publications
     * @return Page
     */
    public function addPublication(\Wizardalley\CoreBundle\Entity\Publication $publications)
    {
        $this->publications[] = $publications;

        return $this;
    }

    /**
     * Remove publications
     *
     * @param \Wizardalley\CoreBundle\Entity\Publication $publications
     */
    public function removePublication(\Wizardalley\CoreBundle\Entity\Publication $publications)
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
     * @param \Wizardalley\CoreBundle\Entity\WizardUser $creator
     * @return Page
     */
    public function setCreator(\Wizardalley\CoreBundle\Entity\WizardUser $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Wizardalley\CoreBundle\Entity\WizardUser
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Add editors
     *
     * @param \Wizardalley\CoreBundle\Entity\WizardUser $editors
     * @return Page
     */
    public function addEditor(\Wizardalley\CoreBundle\Entity\WizardUser $editors)
    {
        $this->editors[] = $editors;

        return $this;
    }

    /**
     * Remove editors
     *
     * @param \Wizardalley\CoreBundle\Entity\WizardUser $editors
     */
    public function removeEditor(\Wizardalley\CoreBundle\Entity\WizardUser $editors)
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

    /**
     * Remove Editors
     *
     */
    public function removeAllEditor()
    {
        $this->editors->clear();
        return $this;
    }
    
    /**
     * Add followers
     *
     * @param \Wizardalley\CoreBundle\Entity\WizardUser $followers
     * @return Page
     */
    public function addFollower(\Wizardalley\CoreBundle\Entity\WizardUser $followers)
    {
        $this->followers[] = $followers;

        return $this;
    }

    /**
     * Remove followers
     *
     * @param \Wizardalley\CoreBundle\Entity\WizardUser $followers
     */
    public function removeFollower(\Wizardalley\CoreBundle\Entity\WizardUser $followers)
    {
        $this->followers->removeElement($followers);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    /**
     * Set pathCouverture
     *
     * @param string $pathCouverture
     * @return WizardUser
     */
    public function setPathCouverture($pathCouverture)
    {
        $this->pathCouverture = $pathCouverture;

        return $this;
    }

    /**
     * Get pathCouverture
     *
     * @return string
     */
    public function getPathCouverture()
    {
        return $this->pathCouverture;
    }

    /**
     * Set pathProfile
     *
     * @param string $pathProfile
     * @return Page
     */
    public function setPathProfile($pathProfile)
    {
        $this->pathProfile = $pathProfile;

        return $this;
    }

    /**
     * Get pathProfile
     *
     * @return string
     */
    public function getPathProfile()
    {
        return $this->pathProfile;
    }

    public function getAbsolutePathProfile()
    {
        return null === $this->pathProfile ? null : $this->getUploadRootDir() . '/' . $this->pathProfile;
    }


    public function getAbsolutePathCouverture()
    {
        return null === $this->pathCouverture ? null : $this->getUploadRootDir() . '/' . $this->pathCouverture;
    }

    public function getPictureProfile()
    {
        return null === $this->pathProfile ?
            $this->getDefaultProfile() : $this->getUploadDir() . '/' . $this->pathProfile;
    }

    public function getPictureCouverture()
    {
        return null === $this->pathCouverture ?
            $this->getDefaultCouverture() : $this->getUploadDir() . '/' . $this->pathCouverture;
    }

    protected function getDefaultProfile()
    {
        return 'uploads/page/default.png';
    }

    protected function getDefaultCouverture()
    {
        return 'uploads/page/dCouverture.png';
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/page/' . $this->id;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $fileProfile;


    /**
     * @Assert\File(maxSize="6000000")
     */
    public $fileCouverture;

    public function uploadProfile()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->fileProfile) {
            return;
        }
        $ext  = pathinfo($this->fileProfile->getClientOriginalName(), PATHINFO_EXTENSION);
        $name = 'profile.' . $ext;
        $this->fileProfile->move($this->getUploadRootDir(), $name);
        $this->pathProfile = $name;
        $this->fileProfile = null;
    }

    public function uploadCouverture()
    {
        if (null === $this->fileCouverture) {
            return;
        }
        $ext  = pathinfo($this->fileCouverture->getClientOriginalName(), PATHINFO_EXTENSION);
        $name = 'couverture.' . $ext;
        $this->fileCouverture->move($this->getUploadRootDir(), $name);
        $this->pathCouverture = $name;
        $this->fileCouverture = null;
    }
}
