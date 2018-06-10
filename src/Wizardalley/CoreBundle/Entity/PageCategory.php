<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation as Serializer;

/**
 * PageCategory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\PageCategoryRepository")
 * @Vich\Uploadable
 */
class PageCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"category_detail", "category_list"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"category_detail", "category_list"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Serializer\Groups({"category_detail"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     * @Serializer\Groups({"category_detail", "category_list"})
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=255, nullable=true)
     * @Serializer\Groups({"category_detail"})
     */
    private $cover;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Groups({"category_detail"})
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Wizardalley\CoreBundle\Entity\Page", mappedBy="category")
     */
    private $pages;

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
     *
     * @return PageCategory
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
     *
     * @return PageCategory
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
     * Set logo
     *
     * @param string $logo
     *
     * @return PageCategory
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
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param mixed $cover
     *
     * @return PageCategory
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pages
     *
     * @param \Wizardalley\CoreBundle\Entity\Page $pages
     *
     * @return PageCategory
     */
    public function addPage(\Wizardalley\CoreBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Wizardalley\CoreBundle\Entity\Page $pages
     */
    public function removePage(\Wizardalley\CoreBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
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
        return 'uploads/category/' . $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @Vich\UploadableField(mapping="page_category_images", fileNameProperty="logo")
     * @Assert\File(maxSize="6000000")
     */
    public $fileLogo;

    public function setFileLogo(File $image)
    {

        $this->fileLogo = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFileLogo()
    {
        return $this->fileLogo;
    }

    public function uploadLogo()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->fileLogo) {
            return;
        }
        $ext  =
            pathinfo(
                $this->fileLogo->getClientOriginalName(),
                PATHINFO_EXTENSION
            );
        $name = 'profile.' . $ext;
        $this->fileLogo->move(
            $this->getUploadRootDir(),
            $name
        );
        $this->logo     = $name;
        $this->fileLogo = null;
    }

    /**
     * @Vich\UploadableField(mapping="page_category_cover", fileNameProperty="cover")
     * @Assert\File(maxSize="6000000")
     */
    public $fileCover;

    public function setFileCover(File $image)
    {

        $this->fileCover = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFileCover()
    {
        return $this->fileCover;
    }

    public function uploadCover()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->fileLogo) {
            return;
        }
        $ext  =
            pathinfo(
                $this->fileCover->getClientOriginalName(),
                PATHINFO_EXTENSION
            );
        $name = 'cover.' . $ext;
        $this->fileCover->move(
            $this->getUploadRootDir(),
            $name
        );
        $this->cover     = $name;
        $this->fileCover = null;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return PageCategory
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
