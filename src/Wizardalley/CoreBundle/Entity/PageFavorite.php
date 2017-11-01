<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PageFavorite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\PageFavoriteRepository"))
 */
class PageFavorite
{
    /**
     * @var Page
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Page", cascade={"persist"}, inversedBy="favorite")
     */
    private $page;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_favorite", type="datetime")
     */
    private $dateFavorite;

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     *
     * @return PageFavorite
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateFavorite()
    {
        return $this->dateFavorite;
    }

    /**
     * @param \DateTime $dateFavorite
     *
     * @return PageFavorite
     */
    public function setDateFavorite($dateFavorite)
    {
        $this->dateFavorite = $dateFavorite;

        return $this;
    }

    public function __toString()
    {
        return 'favorite';
    }
}
