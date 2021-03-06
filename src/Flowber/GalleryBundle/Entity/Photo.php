<?php

namespace Flowber\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile as UploadedFile;
use Flowber\CircleBundle\Entity\Circle;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity (repositoryClass="Flowber\GalleryBundle\Entity\PhotoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Photo
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
     *
     * @var string
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;
    
    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255, nullable=true)
     */
    private $alt;

    /**
     * @var UploadedFile
     */
    private $file;

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
     * @var integer
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $size;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Flowber\CircleBundle\Entity\Circle")
     * @ORM\JoinColumn(name="created_by_circle_id", referencedColumnName="id")
     */
    private $createdBy;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;
    
    // On ajoute cet attribut pour y stocker le nom du fichier temporairement
    private $tempFilename;
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="Flowber\GalleryBundle\Entity\Gallery", inversedBy="photos",  cascade={"persist"})
     * @ORM\JoinTable(name="photos_galleries")
     */
    private $galleries;
    
    /**
     *
     * @var boolean 
     * 
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleteDate", type="datetime", nullable=true)
     */
    private $deleteDate;
    
    /**
     * @param UploadedFile $uploadedFile
     */
    public function __construct()
    {
        $this->galleries = new ArrayCollection();
        $this->creationDate = new \Datetime();
        $this->deleted = false;
    }
    
    /**
    * @param UploadedFile $uploadedFile
    */
    public function setTheFile(UploadedFile $uploadedFile)
    {
        $path = sha1(uniqid(mt_rand(), true)).'.'.$uploadedFile->guessExtension();
        $this->setExtension($uploadedFile->guessExtension());
        $this->setPath($path);
        $this->setSize($uploadedFile->getClientSize());
        $this->setAlt($uploadedFile->getClientOriginalName());

        $uploadedFile->move($this->getUploadRootDir(), $path);
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
     * Set alt
     *
     * @param string $alt
     * @return Photo
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     * @return Photo
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->tempFilename = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
            $this->extension = null;
            $this->alt = null;
        }
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Photo
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
     * @return Photo
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
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
   public function preUpload()
   {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->alt = $this->file->getClientOriginalName();
            $this->extension = $this->getFile()->guessExtension();
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
            $this->size = $this->getFile()->getClientSize();
        }
   }  
        
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->tempFilename)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempFilename);
            // clear the temp image path
            $this->tempFilename = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
      // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
      $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->extension;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
      // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
      if (file_exists($this->tempFilename)) {
        // On supprime le fichier
        unlink($this->tempFilename);
      }
    }

    public function getUploadDir()
    {
      // On retourne le chemin relatif vers l'image pour un navigateur
      return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    public function getWebPath()
    {
        return $this->getUploadDir().'/'.$this->getPath();//.$this->getId().'.'.$this->getExtension();
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Photo
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
     * Add galleries
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $galleries
     * @return Photo
     */
    public function addGallery(\Flowber\GalleryBundle\Entity\Gallery $galleries)
    {
        $this->galleries[] = $galleries;        
        return $this;
    }

    /**
     * Remove galleries
     *
     * @param \Flowber\GalleryBundle\Entity\Gallery $galleries
     */
    public function removeGallery(\Flowber\GalleryBundle\Entity\Gallery $galleries)
    {
        $this->galleries->removeElement($galleries);        
    }

    /**
     * Get galleries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Photo
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Photo
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Photo
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set createdBy
     *
     * @param \Flowber\CircleBundle\Entity\Circle $createdBy
     * @return Photo
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
    
    /**
     * Can the Circle delete this Gallery
     * 
     * @param Circle $circle
     * @return boolean
     */
    public function canDelete(Circle $circle){
        return $circle == $this->getCreatedBy();
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     * @return Photo
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deleteDate
     *
     * @param \DateTime $deleteDate
     * @return Photo
     */
    public function setDeleteDate($deleteDate)
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    /**
     * Get deleteDate
     *
     * @return \DateTime 
     */
    public function getDeleteDate()
    {
        return $this->deleteDate;
    }
}
