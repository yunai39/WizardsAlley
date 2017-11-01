<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Wizardalley\CoreBundle\Entity\Interfaces\TimedEntityInterface;
use Wizardalley\CoreBundle\Entity\Traits\TimedEntityTrait;

/**
 * FollowedNotification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\FollowedNotificationRepository")
 */
class FollowedNotification implements TimedEntityInterface
{
    const TYPE_ASK_FRIEND = 'ask_friend';

    const TYPE_ANSWERS_ASK_FRIEND = 'answer_ask_friend';

    const TYPE_MESSAGE = 'message';

    const TYPE_PUBLICATION = 'publication';

    use TimedEntityTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Wizardalley\CoreBundle\Entity\WizardUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="data_notification", type="string", length=255)
     */
    private $dataNotification;

    /**
     * @var \boolean
     *
     * @ORM\Column(name="checked", type="boolean", options={"default" = 0})
     */
    private $checked;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=20)
     */
    private $type;

    /**
     * Set dataNotification
     *
     * @param string $dataNotification
     *
     * @return FollowedNotification
     */
    public function setDataNotification($dataNotification)
    {
        $this->dataNotification = $dataNotification;

        return $this;
    }

    /**
     * Get dataNotification
     *
     * @return string
     */
    public function getDataNotification()
    {
        return $this->dataNotification;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     *
     * @return FollowedNotification
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set user
     *
     * @param \Wizardalley\CoreBundle\Entity\WizardUser $user
     *
     * @return FollowedNotification
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
     * Set type
     *
     * @param string $type
     *
     * @return FollowedNotification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

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
     * @return array
     */
    public function getData()
    {
        return json_decode(
            $this->dataNotification,
            true
        );
    }
}
