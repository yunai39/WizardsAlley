<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Blame
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\BlameRepository")
 */
class Blame
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
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="content_id", type="integer")
     */
    private $contentId;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text")
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\WizardUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateBlame", type="datetime")
     */
    private $dateBlame;

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
     * Set type
     *
     * @param integer $type
     *
     * @return Blame
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set contentId
     *
     * @param integer $contentId
     *
     * @return Blame
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * Get contentId
     *
     * @return integer
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Blame
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param \Wizardalley\CoreBundle\Entity\WizardUser $user
     *
     * @return Blame
     */
    public function setUser(\Wizardalley\CoreBundle\Entity\WizardUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Wizardalley\CoreBundle\Entity\WizardUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Blame
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
     * Set dateBlame
     *
     * @param \DateTime $dateBlame
     *
     * @return Blame
     */
    public function setDateBlame($dateBlame)
    {
        $this->dateBlame = $dateBlame;

        return $this;
    }

    /**
     * Get dateBlame
     *
     * @return \DateTime
     */
    public function getDateBlame()
    {
        return $this->dateBlame;
    }
}
