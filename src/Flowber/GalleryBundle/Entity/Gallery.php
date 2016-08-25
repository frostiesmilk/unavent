<?php

namespace Flowber\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Flowber\GalleryBundle\Entity\Photo;

/**
 * Gallery
 *
 * @ORM\Table()
 * @ORM\Entity (repositoryClass="Flowber\GalleryBundle\Entity\GalleryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Gallery
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Flowber\GalleryBundle\Entity\Photo
     * 
     * @ORM\ManyToMany(targetEntity="Flowber\GalleryBundle\Entity\Photo", mappedBy="galleries", cascade={"persist"})
     */
    private $photos;

    /**
     *
     * @var ArrayCollection
     */
    private $uploadedFiles;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     * @ORM\JoinColumn(name="created_by_circle_id", referencedColumnName="id")
     */
    private $createdBy;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->uploadedFiles = new ArrayCollection();
        $this->creationDate = new \Datetime();
    }

    /**
    * @ORM\PreFlush()
    */
    public function upload()
    {
        foreach($this->uploadedFiles as $uploadedFile)
        {
            if ($uploadedFile) {
                $photo = new Photo();
                $photo->setTheFile($uploadedFile);                
                $this->getPhotos()->add($photo);
                $photo->addGallery($this);
                unset($uploadedFile);
            }
        }
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
     * Set title
     *
     * @param string $title
     * @return Gallery
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Gallery
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set photos
     *
     * @param string $photos
     * @return Gallery
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * Get photos
     *
     * @return string 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Gallery
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
     * Add photos
     *
     * @param \Flowber\GalleryBundle\Entity\Photo $photos
     * @return Gallery
     */
    public function addPhoto(\Flowber\GalleryBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;
        
        return $this;
    }

    /**
     * Remove photos
     *
     * @param \Flowber\GalleryBundle\Entity\Photo $photos
     */
    public function removePhoto(\Flowber\GalleryBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }
    
    /**
     * @return ArrayCollection
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }
    /**
     * @param ArrayCollection $uploadedFiles
     */
    public function setUploadedFiles($uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;
    }

    /**
     * Set createdBy
     *
     * @param \Flowber\CircleBundle\Entity\Circle $createdBy
     * @return Gallery
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
