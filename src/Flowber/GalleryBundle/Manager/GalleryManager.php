<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Flowber\GalleryBundle\Manager;

use Doctrine\ORM\EntityManager;

use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Flowber\GalleryBundle\Entity\Gallery;

/**
 * Description of GalleryManager
 *
 * @author Equina
 */
class GalleryManager extends BaseManager {
    protected $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }    
    
    /**
     * Return array of photos web paths of a gallery
     * @param Gallery $gallery
     * @return array of photos webpaths
     */
    public function getPhotoWebPathFromGallery(Gallery $gallery){
        $webPaths = [];
        $photos = $gallery->getPhotos();
        
        foreach($photos AS $photo){
            $webPaths[] = $photo->getWebPath();
        }
        
        return $webPaths;        
    }
}
