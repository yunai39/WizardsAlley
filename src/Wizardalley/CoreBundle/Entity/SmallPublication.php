<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Publication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\SmallPublicationRepository")
 */
class SmallPublication extends AbstractPublication
{

    /**
     * @ORM\OneToMany(targetEntity="Wizardalley\CoreBundle\Entity\SmallPublicationUserLike", mappedBy="smallPublication",
     *                                                                                  cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $usersLiking;
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
     * Get number of usersLiking
     *
     * @return int
     */
    public function countLike()
    {
        return count($this->usersLiking);
    }

    /**
     * Add usersLiking
     *
     * @param \Wizardalley\CoreBundle\Entity\SmallPublicationUserLike $usersLiking
     * @return SmallPublication
     */
    public function addUsersLiking(\Wizardalley\CoreBundle\Entity\SmallPublicationUserLike $usersLiking)
    {
        $this->usersLiking[] = $usersLiking;

        return $this;
    }

    /**
     * Remove usersLiking
     *
     * @param \Wizardalley\CoreBundle\Entity\SmallPublicationUserLike $usersLiking
     */
    public function removeUsersLiking(\Wizardalley\CoreBundle\Entity\SmallPublicationUserLike $usersLiking)
    {
        $this->usersLiking->removeElement($usersLiking);
    }

    /**
     * Get usersLiking
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsersLiking()
    {
        return $this->usersLiking;
    }
}
