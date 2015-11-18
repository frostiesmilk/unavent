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
 * @ORM\Entity
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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->postalAddress = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phone = new \Doctrine\Common\Collections\ArrayCollection();
        
        // for user profile
        $this->profile = new Profile();
        $this->getProfile()->setUser($this);
        
        $this->creationDate = new \Datetime();
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
     * @param \Flowber\UserBundle\Entity\Profile $profile
     * @return User
     */
    public function setProfile(\Flowber\UserBundle\Entity\Profile $profile = null)
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
}
