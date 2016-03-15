<?php

namespace Flowber\PrivateMessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Receiver
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\PrivateMessageBundle\Entity\ReceiverRepository")
 */
class Receiver
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
     * @ORM\ManyToOne(targetEntity="Flowber\PrivateMessageBundle\Entity\PrivateMessage", inversedBy="messageTo")
     */
    private $message;

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
     * @return Receiver
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
     * Set message
     *
     * @param \Flowber\PrivateMessageBundle\Entity\PrivateMessage $message
     * @return Receiver
     */
    public function setMessage(\Flowber\PrivateMessageBundle\Entity\PrivateMessage $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \Flowber\PrivateMessageBundle\Entity\PrivateMessage 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set receiver
     *
     * @param \Flowber\CircleBundle\Entity\Circle $receiver
     * @return Receiver
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
