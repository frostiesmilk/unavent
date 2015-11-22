<?php

namespace Flowber\PrivateMessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\UserBundle\Entity\User as User;

/**
 * PrivateMessage
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PrivateMessage
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
     * @ORM\OneToOne(targetEntity="Flowber\UserBundle\Entity\User", cascade={"persist"})
     */
    private $userFrom;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinTable(name="users_messages",
     *      joinColumns={@ORM\JoinColumn(name="message_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $userTo;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;
    
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
     * Set userFrom
     *
     * @param string $userFrom
     * @return PrivateMessage
     */
    public function setUserFrom($userFrom)
    {
        $this->userFrom = $userFrom;

        return $this;
    }

    /**
     * Get userFrom
     *
     * @return string 
     */
    public function getUserFrom()
    {
        return $this->userFrom;
    }

    /**
     * Set userTo
     *
     * @param string $userTo
     * @return PrivateMessage
     */
    public function setUserTo($userTo)
    {
        $this->userTo = $userTo;

        return $this;
    }

    /**
     * Get userTo
     *
     * @return string 
     */
    public function getUserTo()
    {
        return $this->userTo;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return PrivateMessage
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return PrivateMessage
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
     * Set status
     *
     * @param string $status
     * @return PrivateMessage
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return PrivateMessage
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
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->status = 0;
        $this->userTo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add userTo
     *
     * @param \Flowber\UserBundle\Entity\User $userTo
     * @return PrivateMessage
     */
    public function addUserTo(\Flowber\UserBundle\Entity\User $userTo)
    {
        $this->userTo[] = $userTo;

        return $this;
    }

    /**
     * Remove userTo
     *
     * @param \Flowber\UserBundle\Entity\User $userTo
     */
    public function removeUserTo(\Flowber\UserBundle\Entity\User $userTo)
    {
        $this->userTo->removeElement($userTo);
    }
}
