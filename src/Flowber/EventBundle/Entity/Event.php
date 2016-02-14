<?php

namespace Flowber\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\UserBundle\Entity\PostalAddress;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\EventBundle\Validator\Constraints as EventAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Flowber\FrontOfficeBundle\Entity\Circle;
/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity (repositoryClass="Flowber\EventBundle\Entity\EventRepository")
 * @EventAssert\DateRange()
 */
class Event extends Circle
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

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
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="time")
     */
    private $startTime;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endTime", type="time", nullable=true)
     */
    private $endTime;

    /**
     *
     * @var integer
     * @Assert\Range(
     *      min = 1,
     *      minMessage = "Il faut au moins {{ limit }} participant pour l'évènement.")
     * @ORM\Column(name="max_participants", type="integer", nullable=true)
     */
    private $maxParticipants;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="number_participants", type="integer")
     */
    private $number_participants;
    
    /**
     * @var string
     *
     * @ORM\Column(name="privacy", type="string", length=255)
     */
    private $privacy;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\PostalAddress",cascade={"persist"})
     * @ORM\JoinColumn(name="postal_address_id", referencedColumnName="id", nullable=true)
     */
    private $postalAddress;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Flowber\FrontOfficeBundle\Entity\Category")
     */
    private $categories;

    /**
     * @var string
     *
     * @ORM\Column(name="posts", type="string", length=255, nullable=true)
     */
    private $posts;

    /**
     * @var string
     *
     * @ORM\Column(name="galleries", type="string", length=255, nullable=true)
     */
    private $galleries;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\GalleryBundle\Entity\Photo")
     * @ORM\JoinColumn(name="profile_picture_id", referencedColumnName="id", nullable=true)
     */
    private $profilePicture;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\GalleryBundle\Entity\Photo")
     * @ORM\JoinColumn(name="cover_picture_id", referencedColumnName="id", nullable=true)
     */
    private $coverPicture;

    /**
     * @var string
     *
     * @ORM\Column(name="likes", type="string", length=255, nullable=true)
     */
    private $likes;

    /**
     * @var string
     *
     * @ORM\Column(name="tagUsers", type="string", length=255, nullable=true)
     */
    private $tagUsers;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();  
        
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
//      $this->postalAddress = new PostalAddress();
//      $this->coverPicture = new Photo();
//      $this->profilePicture = new Photo();
        $this->number_participants = 1; // first participant is creator
//      $this->creationDate = new \Datetime();
    }
    
    /**
     * Set title
     *
     * @param string $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return Event
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
     * @return Event
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
     * Set eventDate
     *
     * @param \DateTime $eventDate
     * @return Event
     */
    public function setEventDate($eventDate)
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    /**
     * Get eventDate
     *
     * @return \DateTime 
     */
    public function getEventDate()
    {
        return $this->eventDate;
    }

    /**
     * Set privacy
     *
     * @param string $privacy
     * @return Event
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;

        return $this;
    }

    /**
     * Get privacy
     *
     * @return string 
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * Set postalAddress
     *
     * @param string $postalAddress
     * @return Event
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
     * Set category
     *
     * @param string $category
     * @return Event
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set posts
     *
     * @param string $posts
     * @return Event
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * Get posts
     *
     * @return string 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set galleries
     *
     * @param string $galleries
     * @return Event
     */
    public function setGalleries($galleries)
    {
        $this->galleries = $galleries;

        return $this;
    }

    /**
     * Get galleries
     *
     * @return string 
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return Event
     */
    public function setProfilePicture(\Flowber\GalleryBundle\Entity\Photo $profilePicture = null)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string 
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set coverPicture
     *
     * @param string $coverPicture
     * @return Event
     */
    public function setCoverPicture(\Flowber\GalleryBundle\Entity\Photo $coverPicture = null)
    {
        $this->coverPicture = $coverPicture;

        return $this;
    }

    /**
     * Get coverPicture
     *
     * @return string 
     */
    public function getCoverPicture()
    {
        return $this->coverPicture;
    }

    /**
     * Set likes
     *
     * @param string $likes
     * @return Event
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes
     *
     * @return string 
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set tagUsers
     *
     * @param string $tagUsers
     * @return Event
     */
    public function setTagUsers($tagUsers)
    {
        $this->tagUsers = $tagUsers;

        return $this;
    }

    /**
     * Get tagUsers
     *
     * @return string 
     */
    public function getTagUsers()
    {
        return $this->tagUsers;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Event
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Event
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Event
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Event
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set maxParticipants
     *
     * @param integer $maxParticipants
     * @return Event
     */
    public function setMaxParticipants($maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * Get maxParticipants
     *
     * @return integer 
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
    }

    /**
     * Set number_participants
     *
     * @param integer $numberParticipants
     * @return Event
     */
    public function setNumberParticipants($numberParticipants)
    {
        $this->number_participants = $numberParticipants;

        return $this;
    }

    /**
     * Get number_participants
     *
     * @return integer 
     */
    public function getNumberParticipants()
    {
        return $this->number_participants;
    }

    /**
     * Add categories
     *
     * @param \Flowber\FrontOfficeBundle\Entity\Category $categories
     * @return Event
     */
    public function addCategory(\Flowber\FrontOfficeBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Flowber\FrontOfficeBundle\Entity\Category $categories
     */
    public function removeCategory(\Flowber\FrontOfficeBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
