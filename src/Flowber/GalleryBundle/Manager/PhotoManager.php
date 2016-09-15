<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Flowber\GalleryBundle\Manager;

use Doctrine\ORM\EntityManager;

use Flowber\FrontOfficeBundle\Entity\BaseManager;

use Flowber\GalleryBundle\Entity\Photo;

/**
 * Description of PhotoManager
 *
 * @author Equina
 */
class PhotoManager extends BaseManager {
    protected $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }    
        
    public function setDeleted(Photo $photo, $choice){
        $this->getPhotoRepository()->setDeleted($photo, $choice);
    }
    
    public function getPhotoRepository()
    {
        return $this->em->getRepository('FlowberGalleryBundle:Photo');
    }  
}
