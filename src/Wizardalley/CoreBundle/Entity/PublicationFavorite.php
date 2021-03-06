<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PublicationFavorite
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\PublicationFavoriteRepository"))
 */
class PublicationFavorite
{
    /**
     * @var Publication
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Publication", cascade={"persist"}, inversedBy="favorite")
     */
    private $publication;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_favorite", type="datetime")
     */
    private $dateFavorite;

    /**
     * @return int
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param Publication $publication
     *
     * @return PublicationFavorite
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

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
     * @return PublicationFavorite
     */
    public function setDateFavorite($dateFavorite)
    {
        $this->dateFavorite = $dateFavorite;

        return $this;
    }
}
