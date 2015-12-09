<?php

namespace Flowber\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Flowber\ProfileBundle\Entity\Profile as Profile;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     * 
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     */
    private $birthdate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sex", type="string", length=255)
     */
    private $sex;
    
    /**
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\PostalAddress", cascade={"persist"})
     */
    private $postalAddress;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\Phone", cascade={"persist"})
     */
    private $phone;
    
    /**
     * @ORM\OneToOne(targetEntity="Flowber\ProfileBundle\Entity\Profile", mappedBy="user", cascade={"persist", "remove"})
     */
    private $profile;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="Flowber\EventBundle\Entity\Event", mappedBy="participants", cascade={"persist"})
     */
    private $attended_events;
    
    /**
     * @ORM\ManyToMany(targetEntity="Flowber\GroupBundle\Entity\Groups", inversedBy="members")
     * @ORM\JoinTable(name="group_members")
     */
    protected $groups;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->postalAddress = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phone = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attended_events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        
        // for user profile
        $this->profile = new Profile();
        $this->getProfile()->setUser($this);
        
        $this->creationDate = new \Datetime();
                
        $this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Custom Validator
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('plainPassword', new Assert\Length(array(
            'min'        => 6,
            //'max'        => 50,
            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
            //'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
        )))
        ->addPropertyConstraint('birthdate', new Assert\Date(array(
            'message' => "La date de naissance n'est pas valide."
        )));
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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set postalAddress
     *
     * @param string $postalAddress
     * @return User
     */
    public function setPostalAddress($postalAddress)
    {
        $this->postalAddress = $postalAddress;

        return $this;
    }

    /**
     * Get postalAddress
     *
     * @return string 
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
    }

    /**
     * Add postalAddress
     *
     * @param \Flowber\UserBundle\Entity\PostalAddress $postalAddress
     * @return User
     */
    public function addPostalAddress(\Flowber\UserBundle\Entity\PostalAddress $postalAddress)
    {
        $this->postalAddress[] = $postalAddress;

        return $this;
    }

    /**
     * Remove postalAddress
     *
     * @param \Flowber\UserBundle\Entity\PostalAddress $postalAddress
     */
    public function removePostalAddress(\Flowber\UserBundle\Entity\PostalAddress $postalAddress)
    {
        $this->postalAddress->removeElement($postalAddress);
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    /**
     * Add phone
     *
     * @param \Flowber\UserBundle\Entity\Phone $phone
     * @return User
     */
    public function addPhone(\Flowber\UserBundle\Entity\Phone $phone)
    {
        $this->phone[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \Flowber\UserBundle\Entity\Phone $phone
     */
    public function removePhone(\Flowber\UserBundle\Entity\Phone $phone)
    {
        $this->phone->removeElement($phone);
    }
    
    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return User
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex()
    {
        return $this->sex;
    }
    
    /**
     * Sets the email.
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->setUsername($email);

        return parent::setEmail($email);
    }
    
    /**
     * Set the canonical email.
     *
     * @param string $emailCanonical
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->setUsernameCanonical($emailCanonical);

        return parent::setEmailCanonical($emailCanonical);
    }
    
    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set profile
     *
     * @param \Flowber\ProfileBundle\Entity\Profile $profile
     * @return User
     */
    public function setProfile(\Flowber\ProfileBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Flowber\UserBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Add myFriends
     *
     * @param \Flowber\UserBundle\Entity\User $myFriends
     * @return User
     */
    public function addFriend(\Flowber\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends[] = $myFriends;

        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param \Flowber\UserBundle\Entity\User $myFriends
     */
    public function removeFriend(\Flowber\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends->removeElement($myFriends);
    }

    /**
     * Get myFriends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriends()
    {
        return $this->myFriends;
    }

    /**
     * Add myFriends
     *
     * @param \Flowber\UserBundle\Entity\User $myFriends
     * @return User
     */
    public function addMyFriend(\Flowber\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends[] = $myFriends;

        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param \Flowber\UserBundle\Entity\User $myFriends
     */
    public function removeMyFriend(\Flowber\UserBundle\Entity\User $myFriends)
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

    /**
     * Add friendsWithMe
     *
     * @param \Flowber\UserBundle\Entity\User $friendsWithMe
     * @return User
     */
    public function addFriendsWithMe(\Flowber\UserBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;

        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \Flowber\UserBundle\Entity\User $friendsWithMe
     */
    public function removeFriendsWithMe(\Flowber\UserBundle\Entity\User $friendsWithMe)
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
     * Set myFriends
     *
     * @param \Flowber\UserBundle\Entity\User $myFriends
     * @return User
     */
    public function setMyFriends(\Flowber\UserBundle\Entity\User $myFriends = null)
    {
        $this->myFriends = $myFriends;

        return $this;
    }

    /**
     * Add attended_events
     *
     * @param \Flowber\EventBundle\Entity\Event $attendedEvents
     * @return User
     */
    public function addAttendedEvent(\Flowber\EventBundle\Entity\Event $attendedEvents)
    {
        $this->attended_events[] = $attendedEvents;

        return $this;
    }

    /**
     * Remove attended_events
     *
     * @param \Flowber\EventBundle\Entity\Event $attendedEvents
     */
    public function removeAttendedEvent(\Flowber\EventBundle\Entity\Event $attendedEvents)
    {
        $this->attended_events->removeElement($attendedEvents);
    }

    /**
     * Get attended_events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAttendedEvents()
    {
        return $this->attended_events;
    }
}
