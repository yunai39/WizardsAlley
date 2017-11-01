<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Wizardalley\CoreBundle\Entity\Traits\ArrayAccessTrait;

/**
 * MapLink
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\MapLinkRepository")
 */
class MapLink implements \ArrayAccess
{
    use ArrayAccessTrait;

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
     * @ORM\Column(name="display", type="string", length=255)
     */
    private $display;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\MapObject", inversedBy="links", cascade={"persist"})
     */
    private $map;

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
     * Set display
     *
     * @param string $display
     *
     * @return MapLink
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Get display
     *
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return MapLink
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set map
     *
     * @param \Wizardalley\CoreBundle\Entity\MapObject $map
     *
     * @return MapLink
     */
    public function setMap(\Wizardalley\CoreBundle\Entity\MapObject $map = null)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map
     *
     * @return \Wizardalley\CoreBundle\Entity\MapObject
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->display;
    }
}
