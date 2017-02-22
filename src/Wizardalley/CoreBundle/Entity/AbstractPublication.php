<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Wizardalley\CoreBundle\Entity\Interfaces\TimedEntityInterface;
use Wizardalley\CoreBundle\Entity\Traits\TimedEntityTrait;

/**
 * Publication
 *
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="related_type", type="string")
 * @ORM\DiscriminatorMap({"page_publication"="Publication", "user_publication"="SmallPublication"})
 */
class AbstractPublication implements TimedEntityInterface
{
    use TimedEntityTrait;
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


    /**
    * @ORM\OneToMany(targetEntity="CommentPublication", mappedBy="publication", cascade={"remove", "persist"})
    */
    private $comments;
    
    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\WizardUser", inversedBy="publications" )
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
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comments
     *
     * @param \Wizardalley\CoreBundle\Entity\CommentPublication $comments
     * @return Publication
     */
    public function addComment(\Wizardalley\CoreBundle\Entity\CommentPublication $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Wizardalley\CoreBundle\Entity\CommentPublication $comments
     */
    public function removeComment(\Wizardalley\CoreBundle\Entity\CommentPublication $comments)
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
     * Set user
     *
     * @param \Wizardalley\CoreBundle\Entity\WizardUser $user
     * @return Publication
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
     * @param string $string
     * @return int
     */
    public function strip($html,$maxLength=200) {
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
