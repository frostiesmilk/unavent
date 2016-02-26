<?php

namespace Flowber\PrivateMessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Flowber\UserBundle\Entity\User as User;

/**
 * PrivateMessage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\PrivateMessageBundle\Entity\PrivateMessageRepository")
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
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     */
    private $messageFrom;

    /**
     * @ORM\ManyToMany(targetEntity="Flowber\CircleBundle\Entity\Circle")
     * @ORM\JoinColumn(name="message_receiver", referencedColumnName="id", nullable=true)
     */
    private $messageTo;
    
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
    
    public function deleteMessage(\Flowber\PrivateMessageBundle\Entity\PrivateMessage $message)
    {
        $message.setStatut(0);
    }

    /**
     * Set messageFrom
     *
     * @param \Flowber\CircleBundle\Entity\Circle $messageFrom
     * @return PrivateMessage
     */
    public function setMessageFrom(\Flowber\CircleBundle\Entity\Circle $messageFrom = null)
    {
        $this->messageFrom = $messageFrom;

        return $this;
    }

    /**
     * Get messageFrom
     *
     * @return \Flowber\CircleBundle\Entity\Circle 
     */
    public function getMessageFrom()
    {
        return $this->messageFrom;
    }

    /**
     * Add messageTo
     *
     * @param \Flowber\CircleBundle\Entity\Circle $messageTo
     * @return PrivateMessage
     */
    public function addMessageTo(\Flowber\CircleBundle\Entity\Circle $messageTo)
    {
        $this->messageTo[] = $messageTo;

        return $this;
    }

    /**
     * Remove messageTo
     *
     * @param \Flowber\CircleBundle\Entity\Circle $messageTo
     */
    public function removeMessageTo(\Flowber\CircleBundle\Entity\Circle $messageTo)
    {
        $this->messageTo->removeElement($messageTo);
    }

    /**
     * Get messageTo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessageTo()
    {
        return $this->messageTo;
    }
}
