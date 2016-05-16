<?php

namespace Flowber\CircleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\CircleBundle\Entity\NotificationRepository")
 */
class Notification
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
     * @ORM\OneToMany(targetEntity="Flowber\CircleBundle\Entity\NotificationReceiver", mappedBy="notification", cascade={"persist"})
     */
    private $receivers;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="pageName", type="string", length=255, nullable=true)
     */
    private $pageName;

    /**
     * @var string
     *
     * @ORM\Column(name="pageRoute", type="string", length=255, nullable=true)
     */
    private $pageRoute;

    /**
     * @var string
     *
     * @ORM\Column(name="pageId", type="string", length=255, nullable=true)
     */
    private $pageId;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

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
        $this->statut = '1';
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
     * Set message
     *
     * @param string $message
     * @return Notification
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
     * Set pageName
     *
     * @param string $pageName
     * @return Notification
     */
    public function setPageName($pageName)
    {
        $this->pageName = $pageName;

        return $this;
    }

    /**
     * Get pageName
     *
     * @return string 
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * Set pageRoute
     *
     * @param string $pageRoute
     * @return Notification
     */
    public function setPageRoute($pageRoute)
    {
        $this->pageRoute = $pageRoute;

        return $this;
    }

    /**
     * Get pageRoute
     *
     * @return string 
     */
    public function getPageRoute()
    {
        return $this->pageRoute;
    }

    /**
     * Set pageId
     *
     * @param string $pageId
     * @return Notification
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return string 
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Notification
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Notification
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
     * @return Notification
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
     * @return Notification
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
}
