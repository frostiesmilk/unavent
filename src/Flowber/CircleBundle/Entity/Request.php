<?php

namespace Flowber\CircleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Request
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\CircleBundle\Entity\RequestRepository")
 */
class Request
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
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     */
    private $sender;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\CircleBundle\Entity\Circle")
     */
    private $receivers;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     */
    private $circle;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=true)
     */
    private $statut;
    
    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=true)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

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
        $this->creationDate = new \Datetime();   
        $this->receivers = new \Doctrine\Common\Collections\ArrayCollection();     
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
     * Set statut
     *
     * @param string $statut
     * @return Request
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Request
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Request
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Request
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
     * Set sender
     *
     * @param \Flowber\CircleBundle\Entity\Circle $sender
     * @return Request
     */
    public function setSender(\Flowber\CircleBundle\Entity\Circle $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Flowber\CircleBundle\Entity\Circle 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Add receivers
     *
     * @param \Flowber\CircleBundle\Entity\Circle $receivers
     * @return Request
     */
    public function addReceiver(\Flowber\CircleBundle\Entity\Circle $receivers)
    {
        $this->receivers[] = $receivers;

        return $this;
    }

    /**
     * Remove receivers
     *
     * @param \Flowber\CircleBundle\Entity\Circle $receivers
     */
    public function removeReceiver(\Flowber\CircleBundle\Entity\Circle $receivers)
    {
        $this->receivers->removeElement($receivers);
    }

    /**
     * Get receivers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReceivers()
    {
        return $this->receivers;
    }

    /**
     * Set circle
     *
     * @param \Flowber\CircleBundle\Entity\Circle $circle
     * @return Request
     */
    public function setCircle(\Flowber\CircleBundle\Entity\Circle $circle = null)
    {
        $this->circle = $circle;

        return $this;
    }

    /**
     * Get circle
     *
     * @return \Flowber\CircleBundle\Entity\Circle 
     */
    public function getCircle()
    {
        return $this->circle;
    }
}