<?php

namespace Flowber\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Exception;
use FOS\RestBundle\View\View as ResponseView;

use Flowber\GalleryBundle\Form\PhotoMultipleType;
use Flowber\GalleryBundle\Entity\Photo;

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
            $gallery->setDeleted(true);
//            $this->container->get("flowber_gallery.gallery")->setDeleted($gallery, true);
            $em->persist($gallery);

            try{
//                $em->remove($gallery);
                $em->flush();
                
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
    
    public function deletePhotoAction(Request $request){
        $photoId = $request->get("delete-photo-id");
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
    
    public function postPhotosAction(){
        $user = $this->getUser();
        $userProfile = $user->getProfile();
        
        $newPhotosForm = $this->createFormBuilder()
                ->add('files','file',array(
                    "multiple" => "multiple",
                    "attr" => array(
                        "accept" => "image/*",                        
                    ),
                ))
                ->add('galleryId', 'hidden')
                ->add('circleId', 'hidden')
                ->getForm();
        
        $request = $this->getRequest();
        if($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getManager();
            $newPhotosForm->handleRequest($request);

            $data = $newPhotosForm->getData();
            $files = $data["files"];
            $circleId = $data["circleId"];
            $galleryId = $data["galleryId"];
            $gallery = $this->getDoctrine()->getManager()->getRepository("FlowberGalleryBundle:Gallery")->find($galleryId);
            
//            die(var_dump($files));
            // do stuff with your files
            foreach($files AS $file){
                $newPhoto = new Photo();
                $newPhoto->addGallery($gallery);
                $newPhoto->getCreatedBy($userProfile);
                $newPhoto->setTheFile($file);
                
                $em->persist($newPhoto);
                $em->flush();                
            }
            return $this->redirect($this->generateUrl(
                        'flowber_circle_gallery',
                        array('circleId' => $circleId, 'galleryId' =>$galleryId)
                    ));
        }
    }
}
