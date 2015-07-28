<?php
// src/OC/UserBundle/Entity/User.php

namespace Wizardalley\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 */
class WizardUser extends BaseUser
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
    
    
    public function getLastname(){
        return $this->lastname;
    }
    
    public function setLastname($lastname){
        $this->lastname = $lastname;
        return $this;
    }
    
    public function getFirstname(){
        return $this->firstname;
    }
    
    public function setFirstname($firstname){
        $this->firstname = $firstname;
        return $this;
    }
}