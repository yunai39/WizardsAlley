<?php

namespace Wizardalley\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\PublicationBundle\Entity\PublicationRepository")
 */
class Publication extends AbstractPublication
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
    * @ORM\OneToMany(targetEntity="ImagePublication", mappedBy="publication", cascade={"remove", "persist"})
    */
    private $images;
    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\PublicationBundle\Entity\Page", inversedBy="publications" )
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
     * @return Publication
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
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    /**
     * Add images
     *
     * @param \Wizardalley\PublicationBundle\Entity\ImagePublication $images
     * @return Publication
     */
    public function addImage(\Wizardalley\PublicationBundle\Entity\ImagePublication $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Wizardalley\PublicationBundle\Entity\ImagePublication $images
     */
    public function removeImage(\Wizardalley\PublicationBundle\Entity\ImagePublication $images)
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
     * @param \Wizardalley\PublicationBundle\Entity\Page $page
     * @return Publication
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

}
