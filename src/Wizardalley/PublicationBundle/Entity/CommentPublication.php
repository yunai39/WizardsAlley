<?php

namespace Wizardalley\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\PublicationBundle\Entity\CommentPublicationRepository")
 */
class CommentPublication
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateComment", type="datetime")
     */
    private $dateComment;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\UserBundle\Entity\WizardUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="Publication", inversedBy="comments")
     * @ORM\JoinColumn(name="publication_id", referencedColumnName="id")
    */
    private $publication;

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
     * Set dateComment
     *
     * @param \DateTime $dateComment
     * @return CommentPublication
     */
    public function setDateComment($dateComment)
    {
        $this->dateComment = $dateComment;

        return $this;
    }

    /**
     * Get $dateComment
     *
     * @return \DateTime 
     */
    public function getDateComment()
    {
        return $this->dateComment;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return CommentPublication
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $user
     * @return CommentPublication
     */
    public function setUser(\Wizardalley\UserBundle\Entity\WizardUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Wizardalley\UserBundle\Entity\WizardUser 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set publication
     *
     * @param \Wizardalley\PublicationBundle\Entity\Publication $publication
     * @return CommentPublication
     */
    public function setPublication(\Wizardalley\PublicationBundle\Entity\Publication $publication = null)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return \Wizardalley\PublicationBundle\Entity\Publication 
     */
    public function getPublication()
    {
        return $this->publication;
    }
}