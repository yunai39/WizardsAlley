<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Message as BaseMessage;

/**
 * @ORM\Entity
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Wizardalley\CoreBundle\Entity\Thread",
     *   inversedBy="messages"
     * )
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\WizardUser")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Wizardalley\CoreBundle\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageMetadata[]|Collection
     */
    protected $metadata;

    /**
     * Add metadata
     *
     * @param \Wizardalley\CoreBundle\Entity\MessageMetadata $metadata
     *
     * @return Message
     */
    public function addMetadatum(\Wizardalley\CoreBundle\Entity\MessageMetadata $metadata)
    {
        $this->metadata[] = $metadata;

        return $this;
    }

    /**
     * Remove metadata
     *
     * @param \Wizardalley\CoreBundle\Entity\MessageMetadata $metadata
     */
    public function removeMetadatum(\Wizardalley\CoreBundle\Entity\MessageMetadata $metadata)
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
