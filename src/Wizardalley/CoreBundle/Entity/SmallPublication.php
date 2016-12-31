<?php

namespace Wizardalley\CoreBundle\Entity;

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
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
