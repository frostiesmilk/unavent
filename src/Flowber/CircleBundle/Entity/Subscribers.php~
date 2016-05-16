<?php

namespace Flowber\CircleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscribers
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\CircleBundle\Entity\SubscribersRepository")
 */
class Subscribers
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     */
    private $circle;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     */
    private $subscriber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

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
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();   
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Subscribers
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
     * Set statut
     *
     * @param string $statut
     * @return Subscribers
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
     * @return Subscribers
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
     * @return Subscribers
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
     * Set circle
     *
     * @param \Flowber\CircleBundle\Entity\Circle $circle
     * @return Subscribers
     */
    public function setCircle(\Flowber\CircleBundle\Entity\Circle $circle)
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

    /**
     * Set subscriber
     *
     * @param \Flowber\CircleBundle\Entity\Circle $subscriber
     * @return Subscribers
     */
    public function setSubscriber(\Flowber\CircleBundle\Entity\Circle $subscriber)
    {
        $this->subscriber = $subscriber;

        return $this;
    }

    /**
     * Get subscriber
     *
     * @return \Flowber\CircleBundle\Entity\Circle 
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }
}
