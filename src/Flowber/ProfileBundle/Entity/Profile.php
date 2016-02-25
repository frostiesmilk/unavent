<?php

namespace Flowber\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\UserBundle\Entity\User as User;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Flowber\GalleryBundle\Entity\Gallery as Gallery;
use Flowber\CircleBundle\Entity\Circle;

/**
 * Profile
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\ProfileBundle\Entity\ProfileRepository")
 */
class Profile extends Circle
{

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
     *@ORM\OneToOne(targetEntity="Flowber\UserBundle\Entity\User", inversedBy="profile")
     *@ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();  
        
        $this->hobbies = new ArrayCollection(); 
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
     * Add hobbies
     *
     * @param \Flowber\ProfileBundle\Entity\Hobby $hobbies
     * @return Profile
     */
    public function addHobby(\Flowber\ProfileBundle\Entity\Hobby $hobbies)
    {
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
     * Get hobbies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHobbies()
    {
        return $this->hobbies;
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
}
