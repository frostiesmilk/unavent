<?php

namespace Flowber\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\PostBundle\Entity\PostRepository")
 */
class Post
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
      * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
      */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleteDate", type="datetime", nullable=true)
     */
    private $deleteDate;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\LikeBundle\Entity\Likes", cascade={"persist"})
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="Flowber\PostBundle\Entity\Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\GalleryBundle\Entity\Gallery", cascade={"persist"})
     */
    private $gallery;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\EventBundle\Entity\Event", cascade={"persist"})
     */
    private $event;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\GalleryBundle\Entity\Photo", cascade={"persist"})
     */
    private $photo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;
    
    /**
     * @ORM\ManyToOne(targetEntity="Flowber\GroupBundle\Entity\Groups", cascade={"persist"})
     */
    private $groups;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gallery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->photo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationDate = new \Datetime();
        $this->status = 1;
    }

    /**
     * Pseudo-delete post
     */
    public function setDeleted(){
        $this->setStatus(false);
        $this->setDeleteDate(new \Datetime());
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
     * @return Post
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
     * Set message
     *
     * @param string $message
     * @return Post
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set likes
     *
     * @param string $likes
     * @return Post
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
     * @param \Flowber\UserBundle\Entity\User $createdBy
     * @return Post
     */
    public function setCreatedBy(\Flowber\UserBundle\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Flowber\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Add comments
     *
     * @param \Flowber\PostBundle\Entity\Comment $comments
     * @return Post
     */
    public function addComment(\Flowber\PostBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Flowber\PostBundle\Entity\Comment $comments
     */
    public function removeComment(\Flowber\PostBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        $commentsNotDeleted = array();
        
        foreach($this->comments as $comment){
            if($comment->getStatus() != '0'){
                $commentsNotDeleted[] = $comment;
            }        
        }
        return $commentsNotDeleted;
    }

    /**
     * Add gallery
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $gallery
     * @return Post
     */
    public function addGallery(\Flowber\GalleryBundle\Entity\Gallery $gallery)
    {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $gallery
     */
    public function removeGallery(\Flowber\GalleryBundle\Entity\Gallery $gallery)
    {
        $this->gallery->removeElement($gallery);
    }

    /**
     * Get gallery
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set event
     *
     * @param \Flowber\EventBundle\Entity\Event $event
     * @return Post
     */
    public function setEvent(\Flowber\EventBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \Flowber\EventBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Add photo
     *
     * @param \Flowber\GalleryBundle\Entity\Photo $photo
     * @return Post
     */
    public function addPhoto(\Flowber\GalleryBundle\Entity\Photo $photo)
    {
        $this->photo[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \Flowber\GalleryBundle\Entity\Photo $photo
     */
    public function removePhoto(\Flowber\GalleryBundle\Entity\Photo $photo)
    {
        $this->photo->removeElement($photo);
    }

    /**
     * Get photo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set groups
     *
     * @param \Flowber\GroupBundle\Entity\Groups $groups
     * @return Post
     */
    public function setGroups(\Flowber\GroupBundle\Entity\Groups $groups = null)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Get groups
     *
     * @return \Flowber\GroupBundle\Entity\Groups 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add likes
     *
     * @param \Flowber\LikeBundle\Entity\Likes $likes
     * @return Post
     */
    public function addLike(\Flowber\LikeBundle\Entity\Likes $likes)
    {
        $this->likes[] = $likes;

        return $this;
    }

    /**
     * Remove likes
     *
     * @param \Flowber\LikeBundle\Entity\Likes $likes
     */
    public function removeLike(\Flowber\LikeBundle\Entity\Likes $likes)
    {
        $this->likes->removeElement($likes);
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set deleteDate
     *
     * @param \DateTime $deleteDate
     * @return Post
     */
    public function setDeleteDate($deleteDate)
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    /**
     * Get deleteDate
     *
     * @return \DateTime 
     */
    public function getDeleteDate()
    {
        return $this->deleteDate;
    }
}
