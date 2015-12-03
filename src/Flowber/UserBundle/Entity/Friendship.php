<?php

namespace Flowber\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Friendship
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
     */
    private $friend;

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
     * @return Friendship
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
     * Set user
     *
     * @param \Flowber\UserBundle\Entity\User $user
     * @return Friendship
     */
    public function setUser(\Flowber\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Flowber\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set friend
     *
     * @param \Flowber\UserBundle\Entity\User $friend
     * @return Friendship
     */
    public function setFriend(\Flowber\UserBundle\Entity\User $friend)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend
     *
     * @return \Flowber\UserBundle\Entity\User 
     */
    public function getFriend()
    {
        return $this->friend;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Friendship
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
}
