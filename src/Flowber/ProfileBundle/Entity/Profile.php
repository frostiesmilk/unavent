<?php

namespace Flowber\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Profile
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
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="job", type="string", length=255, nullable=true)
     */
    private $job;

    /**
     * @ORM\ManyToMany(targetEntity="Hobby", cascade={"persist"})
     * @ORM\JoinTable(name="users_hobbies",
     *      joinColumns={@ORM\JoinColumn(name="profile_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="hobby_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $hobbies;
    
    /**
     * @ORM\OneToOne(targetEntity="Flowber\UserBundle\Entity\User", cascade={"persist"})
     */
    private $user;
    
    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Photo", cascade={"persist"})
     */
    private $profilePicture;
    
    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist"})
     */
    private $profileGallery;

    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Photo", cascade={"persist"})
     */
    private $coverPicture;
     
    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist"})
     */
    private $coverGallery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateC", type="date")
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
     * Set subtitle
     *
     * @param string $subtitle
     * @return Profile
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Profile
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
     * Set job
     *
     * @param string $job
     * @return Profile
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return string 
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set hobbies
     *
     * @param string $hobbies
     * @return Flowber\ProfileBundle\Entity\Hobby
     */
    public function setHobbies(\Flowber\ProfileBundle\Entity\Hobby $hobbies)
    {
        $hobbies->setProfile($this);
        $this->hobbies = $hobbies;

        return $this;
    }

    /**
     * Get hobbies
     *
     * @return Flowber\ProfileBundle\Entity\Hobby 
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return Flowber\GalleryBundle\Entity\Photo
     */
    public function setProfilePicture(\Flowber\GalleryBundle\Entity\Photo $profilePicture = null)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return Flowber\GalleryBundle\Entity\Photo 
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set coverPicture
     *
     * @param string $coverPicture
     * @return Flowber\GalleryBundle\Entity\Photo
     */
    public function setCoverPicture(\Flowber\GalleryBundle\Entity\Photo $coverPicture = null)
    {
        $this->coverPicture = $coverPicture;

        return $this;
    }

    /**
     * Get coverPicture
     *
     * @return Flowber\GalleryBundle\Entity\Photo 
     */
    public function getCoverPicture()
    {
        return $this->coverPicture;
    }

    /**
     * Set dateC
     *
     * @param \DateTime $dateC
     * @return Profile
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
     * Constructor
     */
    public function __construct()
    {
        $this->hobbies = new \Doctrine\Common\Collections\ArrayCollection();

        $this->dateC = new \Datetime();
    }

    /**
     * Add hobbies
     *
     * @param \Flowber\ProfileBundle\Entity\Hobby $hobbies
     * @return Profile
     */
    public function addHobby(\Flowber\ProfileBundle\Entity\Hobby $hobbies)
    {
        $hobbies->setProfile($this);
        $this->hobbies[] = $hobbies;

        return $this;
    }

    /**
     * Remove hobbies
     *
     * @param \Flowber\ProfileBundle\Entity\Hobby $hobbies
     */
    public function removeHobby(\Flowber\ProfileBundle\Entity\Hobby $hobbies)
    {
        $this->hobbies->removeElement($hobbies);
    }

    /**
     * Set user
     *
     * @param \Flowber\UserBundle\Entity\User $user
     * @return Profile
     */
    public function setUser(\Flowber\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Flowber\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set profileGallery
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $profileGallery
     * @return Profile
     */
    public function setProfileGallery(\Flowber\GalleryBundle\Entity\Gallery $profileGallery = null)
    {
        $this->profileGallery = $profileGallery;

        return $this;
    }

    /**
     * Get profileGallery
     *
     * @return \Flowber\GalleryBundle\Entity\Gallery 
     */
    public function getProfileGallery()
    {
        return $this->profileGallery;
    }

    /**
     * Set coverGallery
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $coverGallery
     * @return Profile
     */
    public function setCoverGallery(\Flowber\GalleryBundle\Entity\Gallery $coverGallery = null)
    {
        $this->coverGallery = $coverGallery;

        return $this;
    }

    /**
     * Get coverGallery
     *
     * @return \Flowber\GalleryBundle\Entity\Gallery 
     */
    public function getCoverGallery()
    {
        return $this->coverGallery;
    }
}
