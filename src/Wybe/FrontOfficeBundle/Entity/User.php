<?php

namespace Wybe\FrontOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=255)
     */
    private $sex;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetime")
     */
    private $birthdate;

    /**
     * @ORM\OneToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\MailAddress", mappedBy="user")
     */
    private $mailAddress;

    /**
     * @ORM\OneToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\PostalAddress", mappedBy="user")
     */
    private $postalAddress;

    /**
     * @ORM\OneToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Phone", mappedBy="user")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Subtitle", cascade={"persist"})
     */
    private $subtitle;

    /**
     *@ORM\ManyToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Description", cascade={"persist"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Job", mappedBy="user")
     */
    private $job;

    /**
     * @ORM\OneToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Hobby", mappedBy="user")
     */
    private $hobbies;

    /**
     * @ORM\ManyToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Groups", cascade={"persist"})
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Event", cascade={"persist"})
     */
    private $events;

    /**
     *@ORM\ManyToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Friendship", cascade={"persist"})
     */
    private $friendship;

    /**
     * @var string
     *
     * @ORM\Column(name="parameters", type="string", length=255)
     */
    private $parameters;
    
    /**
     * @ORM\ManyToMany(targetEntity="Wybe\FrontOfficeBundle\Entity\Gallery", cascade={"persist"})
     */
    private $galleries;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateC", type="datetime")
     */
    private $dateC;

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
     * @return Doctrine\Common\Collections\Collection
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
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
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
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
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Set image
     *
     * @param string $image
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return User
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set job
     *
     * @param string $job
     * @return User
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set hobbies
     *
     * @param string $hobbies
     * @return User
     */
    public function setHobbies($hobbies)
    {
        $this->hobbies = $hobbies;

        return $this;
    }

    /**
     * Get hobbies
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * Set groups
     *
     * @param string $groups
     * @return User
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set events
     *
     * @param string $events
     * @return User
     */
    public function setEvents($events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * Get events
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set friendship
     *
     * @param string $friendship
     * @return User
     */
    public function setFriendship($friendship)
    {
        $this->friendship = $friendship;

        return $this;
    }

    /**
     * Get friendship
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFriendship()
    {
        return $this->friendship;
    }

    /**
     * Set parameters
     *
     * @param string $parameters
     * @return User
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get parameters
     *
     * @return string 
     */
    public function getParameters()
    {
        return $this->parameters;
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
     * Set galleries
     *
     * @param string $galleries
     * @return User
     */
    public function setGalleries($galleries)
    {
        $this->galleries = $galleries;

        return $this;
    }

    /**
     * Get galleries
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getGalleries()
    {
        return $this->galleries;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mailAddress = new \Doctrine\Common\Collections\ArrayCollection();
        $this->postalAddress = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phone = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subtitle = new \Doctrine\Common\Collections\ArrayCollection();
        $this->description = new \Doctrine\Common\Collections\ArrayCollection();
        $this->job = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hobbies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->friendship = new \Doctrine\Common\Collections\ArrayCollection();
        $this->galleries = new \Doctrine\Common\Collections\ArrayCollection();
        
        $this->dateC = new \Datetime();
        $this->image = 'image';
        $this->parameters = 'parameters';
        $this->password = 'password';
    }

    /**
     * Add mailAddress
     *
     * @param \Wybe\FrontOfficeBundle\Entity\MailAddress $mailAddress
     */
    public function addMailAddress(\Wybe\FrontOfficeBundle\Entity\MailAddress $mailAddress)
    {
        $mailAddress->setUser($this);
        $this->mailAddress[] = $mailAddress;

        return $this;
    }

    /**
     * Remove mailAddress
     *
     * @param \Wybe\FrontOfficeBundle\Entity\MailAddress $mailAddress
     */
    public function removeMailAddress(\Wybe\FrontOfficeBundle\Entity\MailAddress $mailAddress)
    {
        $this->mailAddress->removeElement($mailAddress);
    }

    /**
     * Add postalAddress
     *
     * @param \Wybe\FrontOfficeBundle\Entity\PostalAddress $postalAddress
     */
    public function addPostalAddress(\Wybe\FrontOfficeBundle\Entity\PostalAddress $postalAddress)
    {
        $postalAddress->setUser($this);
        $this->postalAddress[] = $postalAddress;

        return $this;
    }

    /**
     * Remove postalAddress
     *
     * @param \Wybe\FrontOfficeBundle\Entity\PostalAddress $postalAddress
     */
    public function removePostalAddress(\Wybe\FrontOfficeBundle\Entity\PostalAddress $postalAddress)
    {
        $this->postalAddress->removeElement($postalAddress);
    }

    /**
     * Add phone
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Phone $phone
     */
    public function addPhone(\Wybe\FrontOfficeBundle\Entity\Phone $phone)
    {
        $phone->setUser($this);
        $this->phone[] = $phone;
        

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Phone $phone
     */
    public function removePhone(\Wybe\FrontOfficeBundle\Entity\Phone $phone)
    {
        $this->phone->removeElement($phone);
    }

    /**
     * Add subtitle
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Subtitle $subtitle
     * @return User
     */
    public function addSubtitle(\Wybe\FrontOfficeBundle\Entity\Subtitle $subtitle)
    {
        $this->subtitle[] = $subtitle;

        return $this;
    }

    /**
     * Remove subtitle
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Subtitle $subtitle
     */
    public function removeSubtitle(\Wybe\FrontOfficeBundle\Entity\Subtitle $subtitle)
    {
        $this->subtitle->removeElement($subtitle);
    }

    /**
     * Add description
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Description $description
     * @return User
     */
    public function addDescription(\Wybe\FrontOfficeBundle\Entity\Description $description)
    {
        $this->description[] = $description;

        return $this;
    }

    /**
     * Remove description
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Description $description
     */
    public function removeDescription(\Wybe\FrontOfficeBundle\Entity\Description $description)
    {
        $this->description->removeElement($description);
    }

    /**
     * Add job
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Job $job
     * @return User
     */
    public function addJob(\Wybe\FrontOfficeBundle\Entity\Job $job)
    {
        $job->setUser($this);
        $this->job[] = $job;

        return $this;
    }

    /**
     * Remove job
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Job $job
     */
    public function removeJob(\Wybe\FrontOfficeBundle\Entity\Job $job)
    {
        $this->job->removeElement($job);
    }

    /**
     * Add hobbies
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Hobby $hobbies
     * @return User
     */
    public function addHobby(\Wybe\FrontOfficeBundle\Entity\Hobby $hobbies)
    {
        $hobbies->setUser($this);
        $this->hobbies[] = $hobbies;

        return $this;
    }

    /**
     * Remove hobbies
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Hobby $hobbies
     */
    public function removeHobby(\Wybe\FrontOfficeBundle\Entity\Hobby $hobbies)
    {
        $this->hobbies->removeElement($hobbies);
    }

    /**
     * Add groups
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Groups $groups
     * @return User
     */
    public function addGroup(\Wybe\FrontOfficeBundle\Entity\Groups $groups)
    {
        $groups->setAdmin($this);
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Groups $groups
     */
    public function removeGroup(\Wybe\FrontOfficeBundle\Entity\Groups $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Add events
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Event $events
     * @return User
     */
    public function addEvent(\Wybe\FrontOfficeBundle\Entity\Event $events)
    {
        $events->setAdmin($this);
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Event $events
     */
    public function removeEvent(\Wybe\FrontOfficeBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Add friendship
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Friendship $friendship
     * @return User
     */
    public function addFriendship(\Wybe\FrontOfficeBundle\Entity\Friendship $friendship)
    {
        $this->friendship[] = $friendship;

        return $this;
    }

    /**
     * Remove friendship
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Friendship $friendship
     */
    public function removeFriendship(\Wybe\FrontOfficeBundle\Entity\Friendship $friendship)
    {
        $this->friendship->removeElement($friendship);
    }

    /**
     * Add galleries
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Gallery $galleries
     * @return User
     */
    public function addGallery(\Wybe\FrontOfficeBundle\Entity\Gallery $galleries)
    {
        $this->galleries[] = $galleries;

        return $this;
    }

    /**
     * Remove galleries
     *
     * @param \Wybe\FrontOfficeBundle\Entity\Gallery $galleries
     */
    public function removeGallery(\Wybe\FrontOfficeBundle\Entity\Gallery $galleries)
    {
        $this->galleries->removeElement($galleries);
    }
}
