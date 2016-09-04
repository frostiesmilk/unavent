<?php

namespace Flowber\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Exception;
use FOS\RestBundle\View\View as ResponseView;


class GalleryRestController extends Controller
{
    /**
     * 
     * @param type $galleryId
     * @return \Flowber\GalleryBundle\Controller\ResponseView|string
     */
    public function deleteGalleryAction($galleryId){
        $gallery = $this->getDoctrine()->getRepository('FlowberGalleryBundle:Gallery')->find($galleryId);
        
        // preparing response
        $view = new ResponseView();

        if(!is_object($gallery)){
            $repsData = array("message" => "Gallery not found");
            $view->setData($repsData)->setStatusCode(400); // error
            return $view;
        }

        $currentUserProfile = $this->getUser()->getProfile();
        if($currentUserProfile == $gallery->getCreatedBy()){ // checking if allowed to delete post (by author)
            $em = $this->getDoctrine()->getManager();
//            $gallery->setDeleted(true);
            $this->container->get("flowber_gallery.gallery")->setDeleted($gallery, true);
//            $em->persist($gallery);
//            $em->flush();
            try{
//                $em->remove($gallery);
                
            } catch (Exception $ex) {
                $repsData = array("message" => "flush error");
                $view->setData($repsData)->setStatusCode(400); // error
                return $view;
            }
            
            $repsData = array("message" => "Success: Delete Galery");
                $view->setData($repsData)->setStatusCode(200); // Success
                return $view;
        }
        
        return "Error: Delete Gallery";
    }
    
    public function deletePhotoAction($photoId){
        $photo = $this->getDoctrine()->getRepository('FlowberGalleryBundle:Photo')->find($photoId);
        
        // preparing response
        $view = new ResponseView();

        if(!is_object($photo)){
            $repsData = array("message" => "Photo not found");
            $view->setData($repsData)->setStatusCode(400); // error
            return $view;
        }

        $currentUserProfile = $this->getUser()->getProfile();
        if($currentUserProfile == $photo->getCreatedBy()){ // checking if allowed to delete post (by author)

            $this->container->get("flowber_gallery.photo")->setDeleted($photo, true);
            
            
            try{

                
            } catch (Exception $ex) {
                $repsData = array("message" => "flush error");
                $view->setData($repsData)->setStatusCode(400); // error
                return $view;
            }
            
            $repsData = array("message" => "Success: Delete Photo");
                $view->setData($repsData)->setStatusCode(200); // Success
                return $view;
        }
        
        return "Error: Delete Photo";
    }
}
