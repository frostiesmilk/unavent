<?php

namespace Flowber\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\EventBundle\Validator\Constraints as EventAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Flowber\CircleBundle\Entity\Circle;

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
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="time", nullable=true)
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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();  
        
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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

}
