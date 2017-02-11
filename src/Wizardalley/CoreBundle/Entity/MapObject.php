<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MapObject
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\MapObjectRepository")
 */
class MapObject
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;



    /**
     * @ORM\OneToMany(targetEntity="Wizardalley\CoreBundle\Entity\MapLink", mappedBy="map", cascade={"remove", "persist"})
     */
    private $links;

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
     * Set title
     *
     * @param string $title
     * @return MapObject
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return MapObject
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return MapObject
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


    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/map/' . $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $fileLogo;

    public function uploadLogo()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->fileLogo) {
            return;
        }
        $ext  = pathinfo($this->fileLogo->getClientOriginalName(), PATHINFO_EXTENSION);
        $name = $this->fileLogo->getClientOriginalName();
        $this->fileLogo->move($this->getUploadRootDir(), $name);
        $this->logo = $name;
        $this->fileLogo = null;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->links = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add links
     *
     * @param \Wizardalley\CoreBundle\Entity\MapLink $links
     * @return MapObject
     */
    public function addLink(\Wizardalley\CoreBundle\Entity\MapLink $links)
    {
        $this->links->add($links);

        $links->setMap($this);

        return $this;
    }

    /**
     * Remove links
     *
     * @param \Wizardalley\CoreBundle\Entity\MapLink $links
     */
    public function removeLink(\Wizardalley\CoreBundle\Entity\MapLink $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinks()
    {
        return $this->links;
    }
}
