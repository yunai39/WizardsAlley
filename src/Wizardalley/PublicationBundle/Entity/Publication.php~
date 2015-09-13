<?php

namespace Wizardalley\PublicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\PublicationBundle\Entity\PublicationRepository")
 */
class Publication
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
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="datetime")
     */
    private $datePublication;

    /**
    * @ORM\OneToMany(targetEntity="CommentPublication", mappedBy="publication", cascade={"remove", "persist"})
    */
    private $comments;
    
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
     * @ORM\ManyToOne(targetEntity="Wizardalley\UserBundle\Entity\WizardUser", inversedBy="publications" )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;
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
     * Set content
     *
     * @param string $content
     * @return Publication
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
     * Set datePublication
     *
     * @param \DateTime $datePublication
     * @return Publication
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime 
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comments
     *
     * @param \Wizardalley\PublicationBundle\Entity\CommentPublication $comments
     * @return Publication
     */
    public function addComment(\Wizardalley\PublicationBundle\Entity\CommentPublication $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Wizardalley\PublicationBundle\Entity\CommentPublication $comments
     */
    public function removeComment(\Wizardalley\PublicationBundle\Entity\CommentPublication $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
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
     * Set user
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $user
     * @return Publication
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
     * @param string $string
     * @return int
     */
    private function strip($html,$maxLength=200) {
        $isUtf8 = true;
        $printedLength = 0;
        $position = 0;
        $tags = array();
        $finalStr = "";
        // For UTF-8, we need to count multibyte sequences as one character.
        $re = $isUtf8 ? '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;|[\x80-\xFF][\x80-\xBF]*}' : '{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}';

        while ($printedLength < $maxLength && preg_match($re, $html, $match, PREG_OFFSET_CAPTURE, $position)) {
            list($tag, $tagPosition) = $match[0];
            // Print text leading up to the tag.
            $str = substr($html, $position, $tagPosition - $position);
            if ($printedLength + strlen($str) > $maxLength) {
                $finalStr .= substr($str, 0, $maxLength - $printedLength);
                $printedLength = $maxLength;
                break;
            }

            $finalStr .= $str;
            $printedLength += strlen($str);
            if ($printedLength >= $maxLength)
                break;

            if ($tag[0] == '&' || ord($tag) >= 0x80) {
                // Pass the entity or UTF-8 multibyte sequence through unchanged.
                $finalStr .= $tag;
                $printedLength++;
            } else {
                // Handle the tag.
                $tagName = $match[1][0];
                if ($tag[1] == '/') {
                    // This is a closing tag.

                    $openingTag = array_pop($tags);
                    assert($openingTag == $tagName); // check that tags are properly nested.

                    $finalStr .= $tag;
                } else if ($tag[strlen($tag) - 2] == '/') {
                    // Self-closing tag.
                    $finalStr .= $tag;
                } else {
                    // Opening tag.
                    $finalStr .= $tag;
                    $tags[] = $tagName;
                }
            }

            // Continue after the tag.
            $position = $tagPosition + strlen($tag);
        }

        // Print any remaining text.
        if ($printedLength < $maxLength && $position < strlen($html))
            $finalStr .= substr($html, $position, $maxLength - $printedLength);

        // Close any open tags.
        while (!empty($tags)){
            $tag = array_pop($tags);
            $finalStr .= "</{$tag}>";
        }
        return $finalStr;
    }

}
