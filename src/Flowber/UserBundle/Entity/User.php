<?php

namespace Flowber\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
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
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\MailAddress", cascade={"persist"})
     */
    private $mailAddress;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\Phone", cascade={"persist"})
     */
    private $phone;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateC", type="datetime")
     */
    private $dateC;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->mailAddress = new \Doctrine\Common\Collections\ArrayCollection();
        $this->postalAddress = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phone = new \Doctrine\Common\Collections\ArrayCollection();
        
        $this->dateC = new \Datetime();
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
     * Set mailAddress
     *
     * @param string $mailAddress
     * @return User
     */
    public function setMailAddress($mailAddress)
    {
        $this->mailAddress = $mailAddress;

        return $this;
    }

    /**
     * Get mailAddress
     *
     * @return string 
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }
    
    /**
     * Add mailAddress
     *
     * @param \Flowber\UserBundle\Entity\MailAddress $mailAddress
     * @return User
     */
    public function addMailAddress(\Flowber\UserBundle\Entity\MailAddress $mailAddress)
    {
        $this->mailAddress[] = $mailAddress;

        return $this;
    }

    /**
     * Remove mailAddress
     *
     * @param \Flowber\UserBundle\Entity\MailAddress $mailAddress
     */
    public function removeMailAddress(\Flowber\UserBundle\Entity\MailAddress $mailAddress)
    {
        $this->mailAddress->removeElement($mailAddress);
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
     * Set dateC
     *
     * @param \DateTime $dateC
     * @return User
     */
    public function setDateC($dateC)
    {
        $this->dateC = $dateC;

        return $this;
    }

    /**
     * Get dateC
     *
     * @return \DateTime 
     */
    public function getDateC()
    {
        return $this->dateC;
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
}