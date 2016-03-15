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
     * @ORM\OneToMany(targetEntity="Flowber\PrivateMessageBundle\Entity\Receiver", mappedBy="message", cascade={"persist"})
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
     * @param \Flowber\PrivateMessageBundle\Entity\Receiver $messageTo
     * @return PrivateMessage
     */
    public function addMessageTo(\Flowber\PrivateMessageBundle\Entity\Receiver $messageTo)
    {
        $this->messageTo[] = $messageTo;

        return $this;
    }

    /**
     * Remove messageTo
     *
     * @param \Flowber\PrivateMessageBundle\Entity\Receiver $messageTo
     */
    public function removeMessageTo(\Flowber\PrivateMessageBundle\Entity\Receiver $messageTo)
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
