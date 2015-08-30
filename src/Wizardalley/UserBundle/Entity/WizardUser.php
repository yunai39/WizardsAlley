<?php

// src/OC/UserBundle/Entity/User.php

namespace Wizardalley\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Wizardalley\UserBundle\Entity\WizardUserRepository")
 */
class WizardUser extends BaseUser {

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
     * @ORM\ManyToMany(targetEntity="Wizardalley\ChatBundle\Entity\ChatConversation", mappedBy="users")
    */
    private $conversations;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="WizardUser", mappedBy="myFriends")
     **/
    private $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="WizardUser", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     **/
    private $myFriends;
    
    
    /**
    * @ORM\OneToMany(targetEntity="Wizardalley\PublicationBundle\Entity\Page", mappedBy="creator", cascade={"remove", "persist"})
    */
    private $pagesCreated;
    
    
    /**
    * @ORM\ManyToMany(targetEntity="Wizardalley\PublicationBundle\Entity\Page", mappedBy="editors")
    */
   private $pagesEditor;
   
   
    
    /**
    * @ORM\OneToMany(targetEntity="Wizardalley\PublicationBundle\Entity\SmallPublication", mappedBy="user", cascade={"remove", "persist"})
    */
    private $smallPublication;
    
    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }
    
    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function getFacebook() {
        return $this->facebook;
    }

    public function setFacebook($facebook) {
        $this->facebook = $facebook;
        return $this;
    }
    
    public function getTwitter() {
        return $this->twitter;
    }

    public function setTwitter($twitter) {
        $this->twitter = $twitter;
        return $this;
    }
    
    public function getSexe() {
        return $this->sexe;
    }

    public function setSexe($sexe) {
        $this->sexe = $sexe;
        return $this;
    }
    
    public function getAbsolutePathProfile() {
        return null === $this->pathProfile ? null : $this->getUploadRootDir() . '/' . $this->pathProfile;
    }
    

    public function getAbsolutePathCouverture() {
        return null === $this->pathCouverture ? null : $this->getUploadRootDir() . '/' . $this->pathCouverture;
    }

    public function getPictureProfile() {
        return null === $this->pathProfile ? $this->getDefaultProfile() : $this->getUploadDir() . '/' . $this->pathProfile;
    }

    public function getPictureCouverture() {
        return null === $this->pathCouverture ? $this->getDefaultCouverture() : $this->getUploadDir() . '/' . $this->pathCouverture;
    }
    
    protected function getDefaultProfile(){
        return 'uploads/profile/default.png';
    }
    
    protected function getDefaultCouverture(){
        return 'uploads/profile/dCouverture.png';
    }
    protected function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/profile/' . $this->id ;
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $fileProfile;
    
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $fileCouverture;

    public function uploadProfile() {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->fileProfile) {
            return;
        }
        $ext = pathinfo($this->fileProfile->getClientOriginalName(), PATHINFO_EXTENSION);
        $name = 'profile.'.$ext;
        $this->fileProfile->move($this->getUploadRootDir(), $name);
        $this->pathProfile = $name;
        $this->fileProfile = null;
    }

    public function uploadCouverture(){
        
        if (null === $this->fileCouverture) {
            return;
        }
        $ext = pathinfo($this->fileCouverture->getClientOriginalName(), PATHINFO_EXTENSION);
        $name = 'couverture.'.$ext;
        $this->fileCouverture->move($this->getUploadRootDir(), $name);
        $this->pathCouverture = $name;
        $this->fileCouverture = null;
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
     * Add conversations
     *
     * @param \Wizardalley\ChatBundle\Entity\ChatConversation $conversations
     * @return WizardUser
     */
    public function addConversation(\Wizardalley\ChatBundle\Entity\ChatConversation $conversations)
    {
        $this->conversations[] = $conversations;

        return $this;
    }

    /**
     * Remove conversations
     *
     * @param \Wizardalley\ChatBundle\Entity\ChatConversation $conversations
     */
    public function removeConversation(\Wizardalley\ChatBundle\Entity\ChatConversation $conversations)
    {
        $this->conversations->removeElement($conversations);
    }

    /**
     * Get conversations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConversations()
    {
        return $this->conversations;
    }

    /**
     * Add friendsWithMe
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $friendsWithMe
     * @return WizardUser
     */
    public function addFriendsWithMe(\Wizardalley\UserBundle\Entity\WizardUser $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $friendsWithMe
     */
    public function removeFriendsWithMe(\Wizardalley\UserBundle\Entity\WizardUser $friendsWithMe)
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
     * @param \Wizardalley\UserBundle\Entity\WizardUser $myFriends
     * @return WizardUser
     */
    public function addMyFriend(\Wizardalley\UserBundle\Entity\WizardUser $myFriends)
    {
        $this->myFriends[] = $myFriends;

        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param \Wizardalley\UserBundle\Entity\WizardUser $myFriends
     */
    public function removeMyFriend(\Wizardalley\UserBundle\Entity\WizardUser $myFriends)
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
    
    public function askedAsAFriend($user){
        return ($this->myFriends->indexOf($user) !== false and $this->friendsWithMe->indexOf($user) ===false  );
    }
    
    public function askingForFriendship($user){
        return ($this->myFriends->indexOf($user) === false and $this->friendsWithMe->indexOf($user) !==false  );
    }
    
    public function isFriend($user){
        return ($this->myFriends->indexOf($user) !== false and $this->friendsWithMe->indexOf($user) !==false  );
    }
    

}
