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
     * @ORM\Column(name="subtitle", type="string", length=255)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="job", type="string", length=255)
     */
    private $job;

    /**
     * @var string
     *
     * @ORM\Column(name="hobbies", type="string", length=255)
     */
    private $hobbies;

    /**
     * @var string
     *
     * @ORM\Column(name="profilePicture", type="string", length=255)
     */
    private $profilePicture;

    /**
     * @var string
     *
     * @ORM\Column(name="coverPicture", type="string", length=255)
     */
    private $coverPicture;

    /**
     * @var string
     *
     * @ORM\Column(name="galleries", type="string", length=255)
     */
    private $galleries;

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
     * @return Profile
     */
    public function setHobbies($hobbies)
    {
        $this->hobbies = $hobbies;

        return $this;
    }

    /**
     * Get hobbies
     *
     * @return string 
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * Set profilePicture
     *
     * @param string $profilePicture
     * @return Profile
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
     * Set coverPicture
     *
     * @param string $coverPicture
     * @return Profile
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
     * Set galleries
     *
     * @param string $galleries
     * @return Profile
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
}
