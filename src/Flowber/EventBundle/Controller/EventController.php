<?php

namespace Flowber\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\EventBundle\Form\EventType;
use Flowber\GalleryBundle\Form\ProfilePictureType;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageOnlyType;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\PostType;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\CommentType;

class EventController extends Controller
{
    public function getEventPageAction($id)
    {  
        $user=$this->getUser();
        $event = $this->container->get('flowber_event.event')->getEvent($id);        
        $eventInfo = $this->container->get('flowber_event.event')->getEventInfos($event);
        $isCreator = $this->container->get('flowber_event.event')->isCreator($user, $event);
        //die(var_dump($isCreator)) ;
        
        $mailToCreator = new PrivateMessage();
        $mailToCreatorForm = $this->createForm(new PrivateMessageOnlyType, $mailToCreator);
        
        $request = $this->get('request');
        // if form has been submitted
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $mailToCreatorForm->handleRequest($request);
            
            // mail to creator has been submitted
            if ($mailToCreatorForm->isValid()) {
                $user = $this->getUser();  
                $userTo = $event->getCreatedBy();
                
                // setting subject, sender and destination
                $subject = "[".$event->getTitle()."] : Nouveau message privé de ".$user->getFirstname()." ".$user->getSurname();
                $mailToCreator->setSubject($subject);
                $mailToCreator->setUserFrom($user);
                $mailToCreator->setUserTo($userTo);
                
                $em->persist($mailToCreator);
                $em->flush();
               
                // message bag
                $this->addFlash(
                    'success',
                    "Votre message a bien été envoyé à l'organisateur."
                );
                
                // to prevent reposting
                return $this->redirectToRoute('flowber_event_homepage', array('id'=>$event->getId()));
            }else{
                // message bag
                $this->addFlash(
                    'error',
                    "Une erreur est survenue lors de l'envoi du message."
                );
            }
        }   
        
        $postRepository = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post');
        $posts = $postRepository->getPost($id);  

        $post = new Post();
        $postForm = $this->createForm(new PostType, $post);
        $CommentArray = array();

        foreach ($posts as $post)
        {
            $comment = new Comment();
            $commentForm = $this->createForm(new CommentType, $comment);
            $CommentArray[] = $commentForm->createView();
        }
        
        return $this->render('FlowberEventBundle:Default:event.html.twig', 
            array(
                'isCreator' => $isCreator,
                'circle' => $eventInfo,
                'mailToCreatorForm' => $mailToCreatorForm->createView(),
                'postForm' => $postForm->createView(),
                'posts' => $posts,
                'commentForm' => $CommentArray,
            )
        );
    }
    
    public function getEventParticipantsPageAction($id){
        $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('FlowberEventBundle:Event');
        $event = $repository->find($id);
        
        $profilePicture = $event->getProfilePicture();
        $coverPicture = $event->getCoverPicture();
        
        if(isset($profilePicture)){
            $profilePicture = $profilePicture->getWebPath();
        }
        if(isset($coverPicture)){
            $coverPicture = $coverPicture->getWebPath();
        }
        
        return $this->render('FlowberEventBundle:Default:eventMember.html.twig', 
                array('eventId'=>$id, 
                'result' => $event,
                'profilePicture' => $profilePicture, 
                'coverPicture' => $coverPicture));
    }
    
    public function getEventGalleryPageAction($id){
        $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('FlowberEventBundle:Event');
        $event = $repository->find($id);
        
        $profilePicture = $event->getProfilePicture();
        $coverPicture = $event->getCoverPicture();
        
        if(isset($profilePicture)){
            $profilePicture = $profilePicture->getWebPath();
        }
        if(isset($coverPicture)){
            $coverPicture = $coverPicture->getWebPath();
        }
        
        return $this->render('FlowberEventBundle:Default:eventGallery.html.twig', 
                array('eventId'=>$id, 
                'result' => $event,
                'profilePicture' => $profilePicture, 
                'coverPicture' => $coverPicture));
    }
    
    public function getCreateEventAction()
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
                
            }  else{
                $error = true;
            }
            
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){
                    $event->setProfilePicture($profilePicture);
                    $em->persist($profilePicture);
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
                }
            }else{
                $error = true;
            }
            
            // no error
            if(!$error){
                // DB update
                $em->persist($event);
                $em->flush();
                // all good, back to profile page
                return $this->redirect($this->generateUrl('flowber_event_homepage',array('id' => $event->getId())));
            }
        }
  
        return $this->render('FlowberEventBundle:Default:createEvent.html.twig', array(
            'eventForm' => $eventForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
        ));
    }
    
    public function getEditEventAction($id)
    {
        $user=$this->getUser();
        $event = $this->container->get('flowber_event.event')->getEvent($id);        
        $coverInfo = $this->container->get('flowber_event.event')->getCoverInfos($event);
        $eventInfo = $this->container->get('flowber_event.event')->getEventInfos($event);
        $eventForm = $this->createForm(new EventType, $event);
        
        //preparing eventual new profile picture
        $profilePicture = new Photo();
        $profilePictureForm = $this->createForm(new ProfilePictureType, $profilePicture);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPictureForm = $this->createForm(new CoverPictureType, $coverPicture);
        
        $error = FALSE;
        $request = $this->get('request');
            
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $eventForm->handleRequest($request);
            $profilePictureForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);  
            $em = $this->getDoctrine()->getManager();
            
            if ($eventForm->isValid()) {
                $event->setCreatedBy($user);  
                
            }  else{
                $error = true;
            }
            
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){
                    $event->setProfilePicture($profilePicture);
                    $em->persist($profilePicture);
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
                }
            }else{
                $error = true;
            }
            
            // no error
            if(!$error){
                // DB update
                $em->persist($event);
                $em->flush();
                // all good, back to profile page
                return $this->redirect($this->generateUrl('flowber_event_homepage',array('id' => $event->getId())));
            }
        }
        
        return $this->render('FlowberEventBundle:Default:editEvent.html.twig', array(
            'eventForm' => $eventForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
            'circle' => $eventInfo,
            'coverInfo' => $coverInfo,
        ));
    }
    
    public function getAllEventsPageAction()
    {
        $user = $this->getUser();        

        $allEvent = $this->getDoctrine()->getManager()->getRepository('FlowberEventBundle:Event')->findByCreatedBy($user);
        
        return $this->render('FlowberEventBundle:Default:allEvent.html.twig', array(
            'allEvent' => $allEvent,
        ));
    }
}
