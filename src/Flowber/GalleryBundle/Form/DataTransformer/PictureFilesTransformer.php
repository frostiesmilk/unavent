<?php
// src/Flowber/GalleryBundle/Form/DataTransformer/PictureFilesTransformer.php

namespace Flowber\GalleryBundle\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Flowber\GalleryBundle\Entity\Photo;

/**
 * Description of PictureFilesTransformer
 *
 * @author Equina
 */
class PictureFilesTransformer implements DataTransformerInterface{
    
//    private $om;
//
//    public function __construct(ObjectManager $om){
//        $this->om = $om;
//    }
    
    public function reverseTransform($files) {
        
        $photos = array();
        
        try{
            foreach($files as $file){
                $photo = new Photo();
                $photo->setFile($file);
                $photos[] = $photo;
            }
        } catch (TransformationFailedException $ex) {
            return $ex->getMessage();
        }
        
        return $photos;
    }

    public function transform($files) {
        if (null === $files) {
            return '';
        }

        $transformed = array();
        
        try{
            foreach($files as $file){
                $transformed[] = $file->getWebPath();
            }
        } catch (TransformationFailedException $ex) {
            return $ex->getMessage();
        }
        
        return $transformed;
    }

}