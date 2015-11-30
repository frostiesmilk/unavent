<?php

namespace Flowber\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\EventBundle\Entity\Event;
use Flowber\EventBundle\Entity\EventRepository;
use Flowber\EventBundle\Form\EventType;
use Flowber\GalleryBundle\Form\ProfilePictureType;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GalleryBundle\Entity\Photo;

class DefaultController extends Controller
{
    public function eventAction($id)
    {
   
        $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('FlowberEventBundle:Event');
        $event = $repository->getInfosEvent($id);
        
        $profilePicture = null;
        $coverPicture = null;

        if (isset($event['profilePicture'])){
            $profilePicture = $this->getDoctrine()->getManager()->getRepository('FlowberGalleryBundle:Photo')->find($event['profilePicture'])->getWebPath();
        }
        if (isset($event['coverPicture'])){ 
            $coverPicture = $this->getDoctrine()->getManager()->getRepository('FlowberGalleryBundle:Photo')->find($event['coverPicture'])->getWebPath();
        }
        return $this->render('FlowberEventBundle:Default:event.html.twig', 
                array('result' => $event, 
                    'profilePicture' => $profilePicture, 
                    'coverPicture' => $coverPicture));
    }
    
    public function createEventAction()
    {
        $user = $this->getUser();        
        $error = FALSE;
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        $event = new \Flowber\EventBundle\Entity\Event;
        $eventForm = $this->createForm(new EventType, $event);
        
        //preparing eventual new profile picture
        $profilePicture = new Photo();
        $profilePictureForm = $this->createForm(new ProfilePictureType, $profilePicture);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPictureForm = $this->createForm(new CoverPictureType, $coverPicture);
        
        $request = $this->get('request');
            
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $eventForm->handleRequest($request);
            $profilePictureForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);  
            $em = $this->getDoctrine()->getManager();
            
            if ($eventForm->isValid()) {
                $event->setCreatedBy($user); 
                $em->persist($event);
                $em->flush();
            }  else{
                $error = true;
            }
            
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){
                    $event->setProfilePicture($profilePicture);
                    $em->persist($profilePicture);
                    $em->flush();
                }
            }else{
                $error = true;
            }
            
            // processing cover picture form
            if($coverPictureForm->isValid()){
                // cover picture was submitted
                if($coverPicture->getFile() !== null){
                    $event->setCoverPicture($coverPicture); 
                    $em->persist($coverPicture);
                    $em->flush();
                }
            }else{
                $error = true;
            }
            
            // no error
            if(!$error){         
                // all good, back to profile page
                return $this->redirect($this->generateUrl('flowber_event_homepage', array('id' => $event->getId()) ));
            }
        }
  
        return $this->render('FlowberEventBundle:Default:create-event.html.twig', array(
            'eventForm' => $eventForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
                ));
    }
}
