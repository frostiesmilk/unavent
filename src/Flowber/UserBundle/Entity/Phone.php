<?php

namespace Flowber\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Phone
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
     * @ORM\Column(name="number", type="string", length=255)
     */
    private $number;

    /**
     * @var boolean
     *
     * @ORM\Column(name="main", type="boolean")
     */
    private $main;

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
        $this->main = true;
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
     * Set number
     *
     * @param string $number
     * @return Phone
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set main
     *
     * @param boolean $main
     * @return Phone
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean 
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set dateC
     *
     * @param \DateTime $dateC
     * @return Phone
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
