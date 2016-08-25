<?php

namespace Flowber\CircleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\GalleryBundle\Entity\Gallery;
/**
 * Circle
 *
 * @ORM\Table()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"circle" = "Circle", "groups" = "Flowber\GroupBundle\Entity\Groups", "event" = "Flowber\EventBundle\Entity\Event", "profile" = "Flowber\ProfileBundle\Entity\Profile"})
 * @ORM\Entity(repositoryClass="Flowber\CircleBundle\Entity\CircleRepository")
 */
class Circle
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
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     */
    private $createdBy;

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
     * 
     */
    private $description;

     /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Photo")
     * @ORM\JoinColumn(name="profile_picture_id", referencedColumnName="id", nullable=true)
     */
    private $profilePicture;

    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="profile_gallery_id", referencedColumnName="id", nullable=true)
     */
    private $profileGallery;

    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Photo")
     * @ORM\JoinColumn(name="cover_picture_id", referencedColumnName="id", nullable=true)
     */
    private $coverPicture;

    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="cover_gallery_id", referencedColumnName="id", nullable=true)
     */
    private $coverGallery;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist"})
     */
    private $galleries;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy", type="string", length=255)
     */
    private $privacy;
    
    /**
     * @ORM\OneToMany(targetEntity="Flowber\PostBundle\Entity\Post", cascade={"persist"}, mappedBy="circle")
     */
    private $posts;
    
     /**
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="subscribers", referencedColumnName="id", nullable=true)
     */
    private $subscribers;
    
    /**
     * @var string
     *
     * @ORM\Column(name="likes", type="string", length=255, nullable=true)
     */
    private $likes;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();     
        $this->profileGallery = new Gallery();    
        $this->profileGallery->setTitle("Photos de profil");
        $this->profileGallery->setCreatedBy($this);
        $this->coverGallery = new Gallery();     
        $this->coverGallery->setTitle("Photos de couverture");
        $this->coverGallery->setCreatedBy($this);
        $this->galleries = new \Doctrine\Common\Collections\ArrayCollection();     
        $this->subscribers = new \Doctrine\Common\Collections\ArrayCollection();     
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Circle
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
     * Set title
     *
     * @param string $title
     * @return Circle
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
     * @return Circle
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
     * @return Circle
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
     * Set privacy
     *
     * @param string $privacy
     * @return Circle
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
     * Set likes
     *
     * @param string $likes
     * @return Circle
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
     * Set createdBy
     *
     * @param \Flowber\CircleBundle\Entity\Circle $createdBy
     * @return Circle
     */
    public function setCreatedBy(\Flowber\CircleBundle\Entity\Circle $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Flowber\CircleBundle\Entity\Circle 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set profilePicture
     *
     * @param \Flowber\GalleryBundle\Entity\Photo $profilePicture
     * @return Circle
     */
    public function setProfilePicture(\Flowber\GalleryBundle\Entity\Photo $profilePicture = null)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return \Flowber\GalleryBundle\Entity\Photo 
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set profileGallery
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $profileGallery
     * @return Circle
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
     * Set coverPicture
     *
     * @param \Flowber\GalleryBundle\Entity\Photo $coverPicture
     * @return Circle
     */
    public function setCoverPicture(\Flowber\GalleryBundle\Entity\Photo $coverPicture = null)
    {
        $this->coverPicture = $coverPicture;

        return $this;
    }

    /**
     * Get coverPicture
     *
     * @return \Flowber\GalleryBundle\Entity\Photo 
     */
    public function getCoverPicture()
    {
        return $this->coverPicture;
    }

    /**
     * Set coverGallery
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $coverGallery
     * @return Circle
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

    /**
     * Add galleries
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $galleries
     * @return Circle
     */
    public function addGallery(\Flowber\GalleryBundle\Entity\Gallery $galleries)
    {
        $this->galleries[] = $galleries;

        return $this;
    }

    /**
     * Remove galleries
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $galleries
     */
    public function removeGallery(\Flowber\GalleryBundle\Entity\Gallery $galleries)
    {
        $this->galleries->removeElement($galleries);
    }

    /**
     * Get galleries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    /**
     * Add posts
     *
     * @param \Flowber\PostBundle\Entity\Post $posts
     * @return Circle
     */
    public function addPost(\Flowber\PostBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Flowber\PostBundle\Entity\Post $posts
     */
    public function removePost(\Flowber\PostBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add subscribers
     *
     * @param \Flowber\UserBundle\Entity\User $subscribers
     * @return Circle
     */
    public function addSubscriber(\Flowber\UserBundle\Entity\User $subscribers)
    {
        $this->subscribers[] = $subscribers;

        return $this;
    }

    /**
     * Remove subscribers
     *
     * @param \Flowber\UserBundle\Entity\User $subscribers
     */
    public function removeSubscriber(\Flowber\UserBundle\Entity\User $subscribers)
    {
        $this->subscribers->removeElement($subscribers);
    }

    /**
     * Get subscribers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }
}
