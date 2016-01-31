<?php

namespace Wizardalley\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ImagePublication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\PublicationBundle\Entity\ImagePublicationRepository")
 */
class ImagePublication {

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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Publication", inversedBy="images")
     * @ORM\JoinColumn(name="publication_id", referencedColumnName="id")
     */
    private $publication;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return ImagePublication
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ImagePublication
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set publication
     *
     * @param \Wizardalley\PublicationBundle\Entity\Publication $publication
     * @return ImagePublication
     */
    public function setPublication(\Wizardalley\PublicationBundle\Entity\Publication $publication = null) {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return \Wizardalley\PublicationBundle\Entity\Publication 
     */
    public function getPublication() {
        return $this->publication;
    }

    
    
public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir()  . $this->path;
    }
    
    protected function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/publications/';
    }
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    public function upload() {

        if (null === $this->file) {
            return;
        }
        $ext = pathinfo($this->file->getClientOriginalName(), PATHINFO_EXTENSION);
        $uniqtoken = uniqid();
        $name = $uniqtoken.'.'.$ext;
        $this->file->move($this->getUploadRootDir(), $name);
        $this->path = $name;
        $this->file = null;
    }

}
