<?php

namespace Wybe\FrontOfficeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friendship
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Friendship
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
     * @ORM\ManyToOne(targetEntity="Wybe\FrontOfficeBundle\Entity\User", inversedBy="postalAddress")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateC", type="datetime")
     */
    private $dateC;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateC = new \Datetime();
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
     * Set user
     *
     * @param string $user
     * @return Friendship
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set dateC
     *
     * @param \DateTime $dateC
     * @return Friendship
     */
    public function setDateC($dateC)
    {
        $this->dateC = $dateC;

        return $this;
    }

    /**
     * Get dateC
     *
     * @return \DateTime 
     */
    public function getDateC()
    {
        return $this->dateC;
    }
}
