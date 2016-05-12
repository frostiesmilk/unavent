<?php

namespace Flowber\LikeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flowber\LikeBundle\Entity\LikesRepository")
 */
class Likes
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
    private $createdBy;

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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Likes
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
     * Set createdBy
     *
     * @param \Flowber\CircleBundle\Entity\Circle $createdBy
     * @return Likes
     */
    public function setCreatedBy(\Flowber\CircleBundle\Entity\Circle $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Flowber\CircleBundle\Entity\Circle 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
