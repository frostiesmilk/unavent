<?php

namespace Flowber\GroupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\GroupBundle\Entity\MembershipRepository")
 */
class Membership
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Flowber\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Flowber\GroupBundle\Entity\Groups")
     */
    private $group;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;
    
    /**
     * @var \String
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;
    
    /**
     * @var \String
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;  
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creationDate = new \Datetime();
        $this->statut = 'pending';
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Membership
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
     * @return Membership
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
     * Set user
     *
     * @param \Flowber\UserBundle\Entity\User $user
     * @return Membership
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
     * Set group
     *
     * @param \Flowber\GroupBundle\Entity\Groups $group
     * @return Membership
     */
    public function setGroup(\Flowber\GroupBundle\Entity\Groups $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Flowber\GroupBundle\Entity\Groups 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Membership
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
}
