<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Flowber\GalleryBundle\Manager;

use Doctrine\ORM\EntityManager;

use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Flowber\CircleBundle\Entity\Circle;
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
     * Return array of photos web paths of all galleries of a circle
     * @param Circle $circleId
     * @return array of photos webpaths
     */
    public function getGalleries($circleId, Circle $requesterCircle){
        $galleries = $this->getGalleryRepository()->getGalleriesIdsFromCircle($circleId);
        $photos = [];
        $count = 0;
        foreach ($galleries as $gallery){
            $gal = $this->getGalleryRepository()->find($gallery);
            
            // prevent error if gallery has no photo
            if(count($gal->getPhotos())>0){
                if (strlen ( $gal->getTitle() ) >=26 ){
                    $photos[$count]['title'] = substr($gal->getTitle(), 0, 25).' ...';
                } else {
                    $photos[$count]['title'] = $gal->getTitle();
                }  
                $photos[$count]['id'] = $gallery;
                $photos[$count]['description'] = $gal->getDescription();
                //$photos[$count]['createdBy'] = $gal->getCreatedBy();
                $photos[$count]['creationDate'] = 'Le '.$gal->getCreationDate()->format('d/m/Y').' à '. $gal->getCreationDate()->format('H:i:s');
                $photos[$count]['photos'] = $this->getTwoPhotoWebPathFromGallery($this->getGalleryRepository()->find($gallery));
                $photos[$count]['createdById'] = $gal->getCreatedBy()->getId();
                $photos[$count]['canDelete'] = $gal->canDelete($requesterCircle);
                $count++;
            }
        }
        
        return $photos;        
    }

    /**
     * Return array of id of all the galleries of a circle
     * @param Circle $circleId
     * @return array of id
     */
    public function getGalleriesId($circleId){
        $galleriesId = $this->getGalleryRepository()->getGalleriesIdsFromCircle($circleId);
        
        return $galleriesId;        
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
    
    /**
     * Return array of photos web paths of a gallery
     * @param Gallery $gallery
     * @return array of photos webpaths
     */
    public function getTwoPhotoWebPathFromGallery(Gallery $gallery){
        $webPaths = [];
        
        $count = 0;
        foreach($gallery->getPhotos() AS $photo){
            $count++;
            $webPaths[] = $photo->getWebPath();
            if ($count == 2){
                return $webPaths;   
            }
        }
        $webPaths[] = 'non';
        return $webPaths;     
    }   
    
    public function setDeleted(Gallery $gallery, $choice){
        $this->getGalleryRepository()->setDeleted($gallery, $choice);
    }
    
    public function getGalleryRepository()
    {
        return $this->em->getRepository('FlowberGalleryBundle:Gallery');
    }  
}
