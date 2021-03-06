<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class PublicationUserLike
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
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\Publication", inversedBy="usersLiking")
     * @ORM\JoinColumn(name="publication_id", referencedColumnName="id")
     */
    private $publication;

    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\WizardUser", inversedBy="publicationLiked")
     * @ORM\JoinColumn(name="wizard_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateLike;

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
     * Set page
     *
     * @param Publication $publication
     *
     * @return PageUserFollow
     */
    public function setPage(Publication $publication = null)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get page
     *
     * @return Publication
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Set user
     *
     * @param WizardUser $user
     *
     * @return PublicationUserLike
     */
    public function setUser(WizardUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return WizardUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set dateLike
     *
     * @param \DateTime $dateLike
     *
     * @return PublicationUserLike
     */
    public function setDateLike($dateLike)
    {
        $this->dateLike = $dateLike;

        return $this;
    }

    /**
     * Get dateLike
     *
     * @return \DateTime
     */
    public function getDateLike()
    {
        return $this->dateLike;
    }

    /**
     * Set publication
     *
     * @param Publication $publication
     *
     * @return PublicationUserLike
     */
    public function setPublication(Publication $publication = null)
    {
        $this->publication = $publication;

        return $this;
    }
}
