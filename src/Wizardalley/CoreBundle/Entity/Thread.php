<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\WizardUser")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Wizardalley\CoreBundle\Entity\Message",
     *   mappedBy="thread"
     * )
     * @var Message[]|Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Wizardalley\CoreBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var ThreadMetadata[]|Collection
     */
    protected $metadata;

    /**
     * Remove messages
     *
     * @param \Wizardalley\CoreBundle\Entity\Message $messages
     */
    public function removeMessage(\Wizardalley\CoreBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Add metadata
     *
     * @param \Wizardalley\CoreBundle\Entity\ThreadMetadata $metadata
     * @return Thread
     */
    public function addMetadatum(\Wizardalley\CoreBundle\Entity\ThreadMetadata $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata
     *
     * @param \Wizardalley\CoreBundle\Entity\ThreadMetadata $metadata
     */
    public function removeMetadatum(\Wizardalley\CoreBundle\Entity\ThreadMetadata $metadata)
    {
        $this->metadata->removeElement($metadata);
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
