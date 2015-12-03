<?php

namespace Flowber\PrivateMessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\UserBundle\Entity\User as User;

/**
 * PrivateMessage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\PrivateMessageBundle\Entity\Repository\PrivateMessageRepository")
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
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
     */
    private $userFrom;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
     */
    private $userTo;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->statut = '2';
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
     * Set statut
     *
     * @param string $statut
     * @return PrivateMessage
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
     * Set userFrom
     *
     * @param \Flowber\UserBundle\Entity\User $userFrom
     * @return PrivateMessage
     */
    public function setUserFrom(\Flowber\UserBundle\Entity\User $userFrom = null)
    {
        $this->userFrom = $userFrom;

        return $this;
    }

    /**
     * Get userFrom
     *
     * @return \Flowber\UserBundle\Entity\User 
     */
    public function getUserFrom()
    {
        return $this->userFrom;
    }

    /**
     * Set userTo
     *
     * @param \Flowber\UserBundle\Entity\User $userTo
     * @return PrivateMessage
     */
    public function setUserTo(\Flowber\UserBundle\Entity\User $userTo = null)
    {
        $this->userTo = $userTo;

        return $this;
    }

    /**
     * Get userTo
     *
     * @return \Flowber\UserBundle\Entity\User 
     */
    public function getUserTo()
    {
        return $this->userTo;
    }
    
    public function deleteMessage(\Flowber\PrivateMessageBundle\Entity\PrivateMessage $message)
    {
        $message.setStatut(0);
    }
}
