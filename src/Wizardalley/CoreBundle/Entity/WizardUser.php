<?php

namespace Wizardalley\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Wizardalley\CoreBundle\Entity\WizardUserRepository")
 */
class WizardUser extends BaseUser implements ParticipantInterface
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=32)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=32)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="small_description", type="string", length=128, nullable=true)
     */
    private $smallDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text",  nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="last_connect", type="datetime", nullable=true)
     */
    private $lastConnect;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="accept_cgv", type="boolean")
     */
    private $acceptCGV;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="boolean")
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $pathProfile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $pathCouverture;

    /**
     * @ORM\ManyToMany(targetEntity="WizardUser", mappedBy="myFriends")
     * @var ArrayCollection
     */
    private $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="WizardUser", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")},
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     * @var ArrayCollection
     */
    private $myFriends;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="creator", cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $pagesCreated;

    /**
     * @ORM\ManyToMany(targetEntity="Page", mappedBy="editors", cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $pagesEditor;

    /**
     * @ORM\ManyToMany(targetEntity="Page", mappedBy="checkers", cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $pagesChecker;

    /**
     * @ORM\OneToMany(targetEntity="PageUserFollow", mappedBy="user")
     * @var ArrayCollection
     */
    private $pagesFollowed;

    /**
     * @ORM\OneToMany(targetEntity="PublicationUserLike", mappedBy="user")
     * @var ArrayCollection
     */
    private $publicationsLiked;

    /**
     * @ORM\OneToMany(targetEntity="SmallPublication", mappedBy="user", cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $smallPublication;

    /**
     * @ORM\OneToMany(targetEntity="Publication", mappedBy="user", cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $publications;

    /**
     * @ORM\OneToMany(targetEntity="InformationBillet", mappedBy="user",cascade={"remove", "persist"})
     * @var ArrayCollection
     */
    private $informationBillets;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param $lastname
     *
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param $firstname
     *
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param $facebook
     *
     * @return $this
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param $twitter
     *
     * @return $this
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param $sexe
     *
     * @return $this
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAbsolutePathProfile()
    {
        return null === $this->pathProfile ? null : $this->getUploadRootDir() . '/' . $this->pathProfile;
    }

    /**
     * @return null|string
     */
    public function getAbsolutePathCouverture()
    {
        return null === $this->pathCouverture ? null : $this->getUploadRootDir() . '/' . $this->pathCouverture;
    }

    /**
     * @return string
     */
    public function getWebPathProfile()
    {
        if (empty($this->getPathProfile())) {
            return $this->getDefaultProfile();
        } else {
            return $this->getUploadDir() . '/' . $this->getPathProfile();
        }
    }

    /**
     * @return string
     */
    public function getWebPathCouverture()
    {
        if (empty($this->getPathCouverture())) {
            return $this->getDefaultCouverture();
        } else {
            return $this->getUploadDir() . '/' . $this->getPathCouverture();
        }
    }

    /**
     * @return string
     */
    public function getPictureProfile()
    {
        return null === $this->pathProfile
            ? $this->getDefaultProfile()
            : $this->getUploadDir() .
              '/' .
              $this->pathProfile;
    }

    /**
     * @return string
     */
    public function getPictureCouverture()
    {
        return null === $this->pathCouverture
            ? $this->getDefaultCouverture()
            : $this->getUploadDir() .
              '/' .
              $this->pathCouverture;
    }

    /**
     * @return string
     */
    protected function getDefaultProfile()
    {
        return 'uploads/profile/default.png';
    }

    /**
     * @return string
     */
    protected function getDefaultCouverture()
    {
        return 'uploads/profile/dCouverture.png';
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/profile/' . $this->id;
    }

    /**
     * @return array
     */
    public function getPageFollowedEntity()
    {
        $collection = [];
        foreach ($this->getPagesFollowed() as $p) {
            $collection[] = $p->getPage();
        }

        return $collection;
    }

    /**
     * @Assert\File(maxSize="6000000")
     * @var UploadedFile
     */
    public $fileProfile;

    /**
     * @Assert\File(maxSize="6000000")
     * @var UploadedFile
     */
    public $fileCouverture;

    public function uploadProfile()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->fileProfile) {
            return;
        }
        $ext  =
            pathinfo(
                $this->fileProfile->getClientOriginalName(),
                PATHINFO_EXTENSION
            );
        $name = 'profile.' . $ext;
        $this->fileProfile->move(
            $this->getUploadRootDir(),
            $name
        );
        $this->pathProfile = $name;
        $this->fileProfile = null;
    }

    public function uploadCouverture()
    {

        if (null === $this->fileCouverture) {
            return;
        }
        $ext  =
            pathinfo(
                $this->fileCouverture->getClientOriginalName(),
                PATHINFO_EXTENSION
            );
        $name = 'couverture.' . $ext;
        $this->fileCouverture->move(
            $this->getUploadRootDir(),
            $name
        );
        $this->pathCouverture = $name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->conversations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set pathProfile
     *
     * @param string $pathProfile
     *
     * @return WizardUser
     */
    public function setPathProfile($pathProfile)
    {
        $this->pathProfile = $pathProfile;

        return $this;
    }

    /**
     * Get pathProfile
     *
     * @return string
     */
    public function getPathProfile()
    {
        return $this->pathProfile;
    }

    /**
     * Set pathCouverture
     *
     * @param string $pathCouverture
     *
     * @return WizardUser
     */
    public function setPathCouverture($pathCouverture)
    {
        $this->pathCouverture = $pathCouverture;

        return $this;
    }

    /**
     * Get pathCouverture
     *
     * @return string
     */
    public function getPathCouverture()
    {
        return $this->pathCouverture;
    }

    /**
     * Add friendsWithMe
     *
     * @param WizardUser $friendsWithMe
     *
     * @return WizardUser
     */
    public function addFriendsWithMe(WizardUser $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param WizardUser $friendsWithMe
     */
    public function removeFriendsWithMe(WizardUser $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * Add myFriends
     *
     * @param WizardUser $myFriends
     *
     * @return WizardUser
     */
    public function addMyFriend(WizardUser $myFriends)
    {
        $this->myFriends[] = $myFriends;

        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param WizardUser $myFriends
     */
    public function removeMyFriend(WizardUser $myFriends)
    {
        $this->myFriends->removeElement($myFriends);
    }

    /**
     * Get myFriends
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyFriends()
    {
        return $this->myFriends;
    }

    public function askedAsAFriend($user)
    {
        return ($this->myFriends->indexOf($user) !== false and $this->friendsWithMe->indexOf($user) === false);
    }

    public function askingForFriendship($user)
    {
        return ($this->myFriends->indexOf($user) === false and $this->friendsWithMe->indexOf($user) !== false);
    }

    public function isFriend($user)
    {
        return ($this->myFriends->indexOf($user) !== false and $this->friendsWithMe->indexOf($user) !== false);
    }

    /**
     * Add pagesCreated
     *
     * @param Page $pagesCreated
     *
     * @return WizardUser
     */
    public function addPagesCreated(Page $pagesCreated)
    {
        $this->pagesCreated[] = $pagesCreated;

        return $this;
    }

    /**
     * Remove pagesCreated
     *
     * @param Page $pagesCreated
     */
    public function removePagesCreated(Page $pagesCreated)
    {
        $this->pagesCreated->removeElement($pagesCreated);
    }

    /**
     * Get pagesCreated
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagesCreated()
    {
        return $this->pagesCreated;
    }

    /**
     * Add pagesEditor
     *
     * @param Page $pagesEditor
     *
     * @return WizardUser
     */
    public function addPagesEditor(Page $pagesEditor)
    {
        $this->pagesEditor[] = $pagesEditor;

        return $this;
    }

    /**
     * Remove pagesEditor
     *
     * @param Page $pagesEditor
     */
    public function removePagesEditor(Page $pagesEditor)
    {
        $this->pagesEditor->removeElement($pagesEditor);
    }

    /**
     * Get pagesEditor
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagesEditor()
    {
        return $this->pagesEditor;
    }

    /**
     * Add pagesFollowed
     *
     * @param Page $pagesFollowed
     *
     * @return WizardUser
     */
    public function addPagesFollowed(Page $pagesFollowed)
    {
        $this->pagesFollowed[] = $pagesFollowed;

        return $this;
    }

    /**
     * Remove pagesFollowed
     *
     * @param Page $pagesFollowed
     */
    public function removePagesFollowed(Page $pagesFollowed)
    {
        $this->pagesFollowed->removeElement($pagesFollowed);
    }

    /**
     * Get pagesFollowed
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagesFollowed()
    {
        return $this->pagesFollowed;
    }

    /**
     * Add smallPublication
     *
     * @param SmallPublication $smallPublication
     *
     * @return WizardUser
     */
    public function addSmallPublication(SmallPublication $smallPublication)
    {
        $this->smallPublication[] = $smallPublication;

        return $this;
    }

    /**
     * Remove smallPublication
     *
     * @param SmallPublication $smallPublication
     */
    public function removeSmallPublication(SmallPublication $smallPublication)
    {
        $this->smallPublication->removeElement($smallPublication);
    }

    /**
     * Get smallPublication
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSmallPublication()
    {
        return $this->smallPublication;
    }

    /**
     * Add publications
     *
     * @param Publication $publications
     *
     * @return WizardUser
     */
    public function addPublication(Publication $publications)
    {
        $this->publications[] = $publications;

        return $this;
    }

    /**
     * Remove publications
     *
     * @param Publication $publications
     */
    public function removePublication(Publication $publications)
    {
        $this->publications->removeElement($publications);
    }

    /**
     * Get publications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Add informationBillets
     *
     * @param InformationBillet $informationBillets
     *
     * @return WizardUser
     */
    public function addInformationBillet(InformationBillet $informationBillets)
    {
        $this->informationBillets[] = $informationBillets;

        return $this;
    }

    /**
     * Remove informationBillets
     *
     * @param InformationBillet $informationBillets
     */
    public function removeInformationBillet(InformationBillet $informationBillets)
    {
        $this->informationBillets->removeElement($informationBillets);
    }

    /**
     * Get informationBillets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInformationBillets()
    {
        return $this->informationBillets;
    }

    /**
     * Add publicationsLiked
     *
     * @param PublicationUserLike $publicationsLiked
     *
     * @return WizardUser
     */
    public function addPublicationsLiked(PublicationUserLike $publicationsLiked)
    {
        $this->publicationsLiked[] = $publicationsLiked;

        return $this;
    }

    /**
     * Remove publicationsLiked
     *
     * @param PublicationUserLike $publicationsLiked
     */
    public function removePublicationsLiked(PublicationUserLike $publicationsLiked)
    {
        $this->publicationsLiked->removeElement($publicationsLiked);
    }

    /**
     * Get publicationsLiked
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublicationsLiked()
    {
        return $this->publicationsLiked;
    }

    /**
     * Set setLastConnect
     *
     * @param \DateTime $lastConect
     *
     * @return WizardUser
     */
    public function setLastConnect($lastConnect)
    {
        $this->lastConnect = $lastConnect;

        return $this;
    }

    /**
     * Get lastConnect
     *
     * @return \DateTime
     */
    public function getLastConnect()
    {
        return $this->lastConnect;
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTime $expireAt)
    {
        $this->expiresAt = $expireAt;

        return $this;
    }

    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    public function setCredentialsExpireAt(\DateTime $credentialsExpireAt)
    {
        $this->credentialsExpireAt = $credentialsExpireAt;

        return $this;
    }

    /**
     * Add pagesChecker
     *
     * @param Page $pagesChecker
     *
     * @return WizardUser
     */
    public function addPagesChecker(Page $pagesChecker)
    {
        $this->pagesChecker[] = $pagesChecker;

        return $this;
    }

    /**
     * Remove pagesChecker
     *
     * @param Page $pagesChecker
     */
    public function removePagesChecker(Page $pagesChecker)
    {
        $this->pagesChecker->removeElement($pagesChecker);
    }

    /**
     * Get pagesChecker
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagesChecker()
    {
        return $this->pagesChecker;
    }

    /**
     * Set smallDescription
     *
     * @param string $smallDescription
     *
     * @return WizardUser
     */
    public function setSmallDescription($smallDescription)
    {
        $this->smallDescription = $smallDescription;

        return $this;
    }

    /**
     * Get smallDescription
     *
     * @return string
     */
    public function getSmallDescription()
    {
        return $this->smallDescription;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return WizardUser
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return WizardUser
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set acceptCGV
     *
     * @param boolean $acceptCGV
     *
     * @return WizardUser
     */
    public function setAcceptCGV($acceptCGV)
    {
        $this->acceptCGV = $acceptCGV;

        return $this;
    }

    /**
     * Get acceptCGV
     *
     * @return boolean
     */
    public function getAcceptCGV()
    {
        return $this->acceptCGV;
    }
}
