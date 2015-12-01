<?php

namespace Flowber\GroupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\GalleryBundle\Entity\Gallery;

/**
 * Groups
 *
 * @ORM\Table()
 * @ORM\Entity (repositoryClass="Flowber\GroupBundle\Entity\GroupRepository")
 */
class Groups
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
     * @ORM\ManyToMany(targetEntity="Flowber\FrontOfficeBundle\Entity\Category")
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
     */
    private $createdBy;

     /**
     * @ORM\ManyToOne(targetEntity="Flowber\GalleryBundle\Entity\Photo")
     * @ORM\JoinColumn(name="profile_picture_id", referencedColumnName="id", nullable=true)
     */
    private $profilePicture;

    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="profile_gallery_id", referencedColumnName="id")
     */
    private $profileGallery;

    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Photo")
     * @ORM\JoinColumn(name="cover_picture_id", referencedColumnName="id", nullable=true)
     */
    private $coverPicture;

    /**
     * @ORM\OneToOne(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="cover_gallery_id", referencedColumnName="id")
     */
    private $coverGallery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /*
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\User")
     * @ORM\JoinTable(name="group_members")
     */
    private $members;

    /**
     * @var string
     *
     * @ORM\Column(name="posts", type="string", length=255, nullable=true)
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist", "remove"})
     */
    private $galleries;

    /**
     * @var string
     *
     * @ORM\Column(name="administrators", type="string", length=255, nullable=true)
     */
    private $administrators;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy", type="string", length=255)
     */
    private $privacy;
 
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->galleries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();

        $this->coverGallery = new Gallery();
        $this->getCoverGallery()->setTitle("Cover Pictures");
        $this->profileGallery = new Gallery();
        $this->getProfileGallery()->setTitle("Profile Pictures");        
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
     * @return Groups
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
     * @return Groups
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
     * Set decription
     *
     * @param string $description
     * @return Groups
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
     * Set categories
     *
     * @param string $categories
     * @return Groups
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return string 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     * @return Groups
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
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return Groups
     */
    public function setProfilePicture($profilePicture)
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
     * Set profileGallery
     *
     * @param string $profileGallery
     * @return Groups
     */
    public function setProfileGallery($profileGallery)
    {
        $this->profileGallery = $profileGallery;

        return $this;
    }

    /**
     * Get profileGallery
     *
     * @return string 
     */
    public function getProfileGallery()
    {
        return $this->profileGallery;
    }

    /**
     * Set coverPicture
     *
     * @param string $coverPicture
     * @return Groups
     */
    public function setCoverPicture($coverPicture)
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
     * Set coverGallery
     *
     * @param string $coverGallery
     * @return Groups
     */
    public function setCoverGallery($coverGallery)
    {
        $this->coverGallery = $coverGallery;

        return $this;
    }

    /**
     * Get coverGallery
     *
     * @return string 
     */
    public function getCoverGallery()
    {
        return $this->coverGallery;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Groups
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
     * Set members
     *
     * @param string $members
     * @return Groups
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members
     *
     * @return string 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set posts
     *
     * @param string $posts
     * @return Groups
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
     * @return Groups
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
     * Set administrators
     *
     * @param string $administrators
     * @return Groups
     */
    public function setAdministrators($administrators)
    {
        $this->administrators = $administrators;

        return $this;
    }

    /**
     * Get administrators
     *
     * @return string 
     */
    public function getAdministrators()
    {
        return $this->administrators;
    }

    /**
     * Add galleries
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $galleries
     * @return Groups
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
     * Set privacy
     *
     * @param string $privacy
     * @return Groups
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
     * Add categories
     *
     * @param \Flowber\FrontOfficeBundle\Entity\Category $categories
     * @return Groups
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
}
