<?php

namespace Wizardalley\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InformationBillet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\AdminBundle\Entity\InformationBilletRepository")
 */
class InformationBillet
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateBillet", type="datetime")
     */
    private $dateBillet;


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
     * Set name
     *
     * @param string $name
     * @return InformationBillet
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return InformationBillet
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
     * Set dateBillet
     *
     * @param \DateTime $dateBillet
     * @return InformationBillet
     */
    public function setDateBillet($dateBillet)
    {
        $this->dateBillet = $dateBillet;

        return $this;
    }

    /**
     * Get dateBillet
     *
     * @return \DateTime 
     */
    public function getDateBillet()
    {
        return $this->dateBillet;
    }
}
