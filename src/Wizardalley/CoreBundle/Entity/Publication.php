<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Publication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\PublicationRepository")
 */
class Publication extends AbstractPublication
{
    /**
     * @var PublicationFavorite
     * @ORM\OneToOne(targetEntity="PublicationFavorite", mappedBy="publication", cascade={"remove", "persist"})
     */
    private $favorite;

    /**
     * @ORM\OneToMany(targetEntity="Wizardalley\CoreBundle\Entity\PublicationUserLike", mappedBy="publication",
     *                                                                                  cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $usersLiking;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="ImagePublication", mappedBy="publication", cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\Page", inversedBy="publications" )
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;

    /**
     * @var string
     *
     * @ORM\Column(name="small_content", type="text")
     */
    private $smallContent;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hasBennPublished", type="boolean", options={"default": false})
     */
    private $hasBeenPublished = false;

    /**
     * Publication constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->images      = new ArrayCollection();
        $this->usersLiking = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Publication
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return PublicationFavorite
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * @param PublicationFavorite $favorite
     *
     * @return Publication
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

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
     * Add images
     *
     * @param ImagePublication $images
     *
     * @return Publication
     */
    public function addImage(ImagePublication $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param ImagePublication $images
     */
    public function removeImage(ImagePublication $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set page
     *
     * @param Page $page
     *
     * @return Publication
     */
    public function setPage(Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set smallContent
     *
     * @param string $smallContent
     *
     * @return Publication
     */
    public function setSmallContent($smallContent)
    {
        $this->smallContent =
            $this->strip(
                $smallContent,
                200
            );

        return $this;
    }

    /**
     * Get smallContent
     *
     * @return string
     */
    public function getSmallContent()
    {
        return $this->smallContent;
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
     * Add usersLiking
     *
     * @param PublicationUserLike $usersLiking
     *
     * @return Publication
     */
    public function addUsersLiking(PublicationUserLike $usersLiking)
    {
        $this->usersLiking[] = $usersLiking;

        return $this;
    }

    /**
     * Remove usersLiking
     *
     * @param PublicationUserLike $usersLiking
     */
    public function removeUsersLiking(PublicationUserLike $usersLiking)
    {
        $this->usersLiking->removeElement($usersLiking);
    }

    /**
     * Get usersLiking
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsersLiking()
    {
        return $this->usersLiking;
    }

    /**
     * Get number of usersLiking
     *
     * @return int
     */
    public function countLike()
    {
        return count($this->usersLiking);
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set hasBeenPublished
     *
     * @param bool $hasBeenPublished
     * @return Publication
     */
    public function setHasBeenPublished($hasBeenPublished)
    {
        $this->hasBeenPublished = $hasBeenPublished;

        return $this;
    }

    /**
     * Get hasBeenPublished
     *
     * @return bool
     */
    public function getHasBeenPublished()
    {
        return $this->hasBeenPublished;
    }
}
