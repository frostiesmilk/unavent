<?php


namespace Flowber\CircleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationReceiver
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\CircleBundle\Entity\NotificationReceiverRepository")
 */
class NotificationReceiver {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Notification", inversedBy="receivers")
     */
    private $notification;
    
    /**
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     * @ORM\JoinColumn(name="receiver", referencedColumnName="id", nullable=true)
     */
    private $receiver;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;
    
    

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
     * @return NotificationReceiver
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
     * Set notification
     *
     * @param \Flowber\CircleBundle\Entity\Notification $notification
     * @return NotificationReceiver
     */
    public function setNotification(\Flowber\CircleBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \Flowber\CircleBundle\Entity\Notification 
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set receiver
     *
     * @param \Flowber\CircleBundle\Entity\Circle $receiver
     * @return NotificationReceiver
     */
    public function setReceiver(\Flowber\CircleBundle\Entity\Circle $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \Flowber\CircleBundle\Entity\Circle 
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}
