<?php

namespace Flowber\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\UserBundle\Entity\PostalAddress;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity (repositoryClass="Flowber\EventBundle\Entity\EventRepository")
 */
class Event 
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
     * @ORM\Column(name="eventStartDate", type="date")
     */
    private $eventStartDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eventStartTime", type="time")
     */
    private $eventStartTime;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eventEndDate", type="date", nullable=true)
     */
    private $eventEndDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eventEndTime", type="time", nullable=true)
     */
    private $eventEndTime;
    
    /**
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
     */
    private $createdBy;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\User")
     * @ORM\JoinTable(name="event_organized")
     */
    private $organizer;

     /**
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\User")
     * @ORM\JoinTable(name="event_participants")
     */
    private $participants;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy", type="string", length=255)
     */
    private $privacy;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\PostalAddress",cascade={"persist"})
     */
    private $postalAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     */
    private $category;

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
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->organizer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->postalAddress = new PostalAddress();
//        $this->coverPicture = new Photo();
//        $this->profilePicture = new Photo();
        
        $this->creationDate = new \Datetime();
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
     * Set createdBy
     *
     * @param string $createdBy
     * @return Event
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set organizer
     *
     * @param string $organizer
     * @return Event
     */
    public function setOrganizer($organizer)
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * Get organizer
     *
     * @return string 
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * Set participants
     *
     * @param string $participants
     * @return Event
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;

        return $this;
    }

    /**
     * Get participants
     *
     * @return string 
     */
    public function getParticipants()
    {
        return $this->participants;
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
     * Add organizer
     *
     * @param \Flowber\UserBundle\Entity\User $organizer
     * @return Event
     */
    public function addOrganizer(\Flowber\UserBundle\Entity\User $organizer)
    {
        $this->organizer[] = $organizer;

        return $this;
    }

    /**
     * Remove organizer
     *
     * @param \Flowber\UserBundle\Entity\User $organizer
     */
    public function removeOrganizer(\Flowber\UserBundle\Entity\User $organizer)
    {
        $this->organizer->removeElement($organizer);
    }

    /**
     * Add participants
     *
     * @param \Flowber\UserBundle\Entity\User $participants
     * @return Event
     */
    public function addParticipant(\Flowber\UserBundle\Entity\User $participants)
    {
        $this->participants[] = $participants;

        return $this;
    }

    /**
     * Remove participants
     *
     * @param \Flowber\UserBundle\Entity\User $participants
     */
    public function removeParticipant(\Flowber\UserBundle\Entity\User $participants)
    {
        $this->participants->removeElement($participants);
    }

    /**
     * Set eventStartDate
     *
     * @param \DateTime $eventStartDate
     * @return Event
     */
    public function setEventStartDate($eventStartDate)
    {
        $this->eventStartDate = $eventStartDate;

        return $this;
    }

    /**
     * Get eventStartDate
     *
     * @return \DateTime 
     */
    public function getEventStartDate()
    {
        return $this->eventStartDate;
    }

    /**
     * Set eventStartTime
     *
     * @param \DateTime $eventStartTime
     * @return Event
     */
    public function setEventStartTime($eventStartTime)
    {
        $this->eventStartTime = $eventStartTime;

        return $this;
    }

    /**
     * Get eventStartTime
     *
     * @return \DateTime 
     */
    public function getEventStartTime()
    {
        return $this->eventStartTime;
    }

    /**
     * Set eventEndDate
     *
     * @param \DateTime $eventEndDate
     * @return Event
     */
    public function setEventEndDate($eventEndDate)
    {
        $this->eventEndDate = $eventEndDate;

        return $this;
    }

    /**
     * Get eventEndDate
     *
     * @return \DateTime 
     */
    public function getEventEndDate()
    {
        return $this->eventEndDate;
    }

    /**
     * Set eventEndTime
     *
     * @param \DateTime $eventEndTime
     * @return Event
     */
    public function setEventEndTime($eventEndTime)
    {
        $this->eventEndTime = $eventEndTime;

        return $this;
    }

    /**
     * Get eventEndTime
     *
     * @return \DateTime 
     */
    public function getEventEndTime()
    {
        return $this->eventEndTime;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Event
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
}
