<?php

namespace Wizardalley\CoreBundle\Entity;

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
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var PublicationFavorite
     * @ORM\OneToOne(targetEntity="PublicationFavorite", mappedBy="publication")
     */
    private $favorite;

    /**
     * @ORM\OneToMany(targetEntity="Wizardalley\CoreBundle\Entity\PublicationUserLike", mappedBy="publication")
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
     * Set title
     *
     * @param string $title
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
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    /**
     * Add images
     *
     * @param \Wizardalley\CoreBundle\Entity\ImagePublication $images
     * @return Publication
     */
    public function addImage(\Wizardalley\CoreBundle\Entity\ImagePublication $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Wizardalley\CoreBundle\Entity\ImagePublication $images
     */
    public function removeImage(\Wizardalley\CoreBundle\Entity\ImagePublication $images)
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
     * @param \Wizardalley\CoreBundle\Entity\Page $page
     * @return Publication
     */
    public function setPage(\Wizardalley\CoreBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Wizardalley\CoreBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }

    

    /**
     * Set smallContent
     *
     * @param string $smallContent
     * @return Publication
     */
    public function setSmallContent($smallContent)
    {
        $this->smallContent = $this->strip($smallContent,200);

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
     * @param \Wizardalley\CoreBundle\Entity\PublicationUserLike $usersLiking
     * @return Publication
     */
    public function addUsersLiking(\Wizardalley\CoreBundle\Entity\PublicationUserLike $usersLiking)
    {
        $this->usersLiking[] = $usersLiking;

        return $this;
    }

    /**
     * Remove usersLiking
     *
     * @param \Wizardalley\CoreBundle\Entity\PublicationUserLike $usersLiking
     */
    public function removeUsersLiking(\Wizardalley\CoreBundle\Entity\PublicationUserLike $usersLiking)
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
}
