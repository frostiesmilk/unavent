<?php

namespace Flowber\FrontOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Circle
 *
 * @ORM\Table()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"circle" = "Circle", "groups" = "Flowber\GroupBundle\Entity\Groups", "event" = "Flowber\EventBundle\Entity\Event"})
 * @ORM\Entity(repositoryClass="Flowber\FrontOfficeBundle\Entity\CircleRepository")
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
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
     */
    private $createdBy;
    
    /**
     * @ORM\OneToMany(targetEntity="Flowber\PostBundle\Entity\Post", cascade={"persist"}, mappedBy="circle")
     */
    private $posts;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();        
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
     * Set createdBy
     *
     * @param string $createdBy
     * @return Circle
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
     * Set posts
     *
     * @param string $posts
     * @return Circle
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
}
