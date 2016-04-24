<?php

namespace Flowber\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\EventBundle\Form\EventType;
use Flowber\GalleryBundle\Form\ProfilePictureType;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\GalleryBundle\Entity\Gallery;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageOnlyType;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\PostType;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\CommentType;
use Flowber\PostBundle\Form\PostWithPicturesType;
use Flowber\GalleryBundle\Form\GalleryType;
use Flowber\CircleBundle\Entity\Subscribers;

class EventController extends Controller
{
    public function getEventPageAction($circleId)
    {  
        $user=$this->getUser(); 
        $circle = $this->getDoctrine()->getRepository('FlowberCircleBundle:Circle')->find($circleId);
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $circleId);
        $privacy = $this->container->get('flowber_circle.circle')->getPrivacy($circleId);
        $eventInfo = $this->container->get('flowber_event.event')->getEventInfos($circleId, $user->getProfile()->getId());
        
        //preparing new form for a post
        $postWithPictures = new Post();
        $postWithPicturesForm = $this->createForm(new PostWithPicturesType(), $postWithPictures);              
           
        // preparing new Gallery
        $newGroupGallery = new Gallery();
        $newGalleryForm = $this->createForm(new GalleryType(), $newGroupGallery);
        
        if($role=='cantsee'){
            $posts = null;
            $postForm = null;
            $CommentArray = null;
        }
        else {
            $postRepository = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post');
            $posts = $posts = $this->container->get('flowber_post.post')->getCirclePosts($circle, $user);

            $post = new Post();
            $postForm = $this->createForm(new PostType, $post)->createView();
            $CommentArray = array();

            foreach ($posts as $post)
            {
                $comment = new Comment();
                $commentForm = $this->createForm(new CommentType, $comment);
                $CommentArray[] = $commentForm->createView();
            }            
        }
        

        $mailToCreatorForm = $this->createForm(new PrivateMessageOnlyType, new PrivateMessage);
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        
        $userProfileInfos = $this->container->get("flowber_profile.profile")->getProfileInfos($user->getProfile()->getId());
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;    
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        return $this->render('FlowberEventBundle:Default:event.html.twig', 
            array(
                'user' => $userProfileInfos,
                'role' => $role,
                'navbar' => $navbar,
                'circle' => $eventInfo,
                'mailToCreatorForm' => $mailToCreatorForm->createView(),
                'messageForm' => $privateMessageForm->createView(),
                'postForm' => $postForm,
                'posts' => $posts,
                'commentForm' => $CommentArray,
                'postWithPicturesForm' => $postWithPicturesForm->createView(),
                'newGalleryForm' => $newGalleryForm->createView(),
            )
        );
    }
    
    public function getParticipantsPageAction($id){
        $user=$this->getUser();  
        $eventInfo = $this->container->get('flowber_circle.circle')->getCoverInfos($id, $user->getProfile()->getId());
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $id);
        
        $members = $this->container->get("flowber_event.event")->getEventMembers($id);
        $memberInfos = $this->container->get("flowber_profile.profile")->getFriendsFromList($members, $user->getProfile()->getId());
        //die(var_dump($members));
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav; 
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        return $this->render('FlowberEventBundle:Default:eventMember.html.twig', 
                array( 
                'role' => $role,
                'members' => $memberInfos,
                'messageForm' => $privateMessageForm->createView(),
                'circle' => $eventInfo,
                'navbar' => $navbar));
    }
    
    public function getGalleryPageAction($id){
        $user=$this->getUser();  
        $eventInfo = $this->container->get('flowber_circle.circle')->getCoverInfos($id, $user->getProfile()->getId());
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $id);
        
        $members = $this->container->get("flowber_event.event")->getEventMembers($id);
        $memberInfos = $this->container->get("flowber_profile.profile")->getFriendsFromList($members, $user->getProfile()->getId());
        //die(var_dump($members));
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav; 
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        return $this->render('FlowberEventBundle:Default:eventGallery.html.twig', 
                array( 
                'role' => $role,
                //'members' => $memberInfos,
                'messageForm' => $privateMessageForm->createView(),
                'circle' => $eventInfo,
                'navbar' => $navbar));
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
                $event->setCreatedBy($user->getProfile());  
                
            }  else{
                $error = true;
            }
            
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){
                    $profilePicture->addGallery($event->getProfileGallery());
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
                    $coverPicture->addGallery($event->getCoverGallery());
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
                
                $subscriber = new Subscribers();
                $subscriber->setCircle($this->container->get("flowber_circle.circle")->getCircle($event->getId()));
                $subscriber->setSubscriber($this->container->get("flowber_circle.circle")->getCircle($user->getProfile()->getId()));
                $subscriber->setRole('admin');
                $subscriber->setStatut($this->container->get("flowber_circle.circle")->getClass($event->getId()));
                $subscriber->setMessage('');
                
                $em->persist($subscriber);
                $em->flush();    
                
                // all good, back to profile page
                return $this->redirect($this->generateUrl('api_get_circle',array('circleId' => $event->getId())));
            }
        }
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;   
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        return $this->render('FlowberEventBundle:Default:createEvent.html.twig', array(
            'eventForm' => $eventForm->createView(),
            'navbar' => $navbar,
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
        ));
    }
    
    public function getEditEventAction($circleId)
    {
        $user=$this->getUser();
        $event = $this->container->get('flowber_event.event')->getEvent($circleId);        
      //  $groupInfos = $this->container->get('flowber_event.event')->getEventInfos($group);
        $eventInfo = $this->container->get('flowber_event.event')->getEventInfos($event, $user->getProfile()->getId());
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
                $event->setCreatedBy($user->getProfile());  
                
            }  else{
                $error = true;
            }
            
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){
                    $profilePicture->addGallery($event->getProfileGallery());
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
                    $coverPicture->addGallery($event->getCoverGallery());
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
                return $this->redirect($this->generateUrl('api_get_circle',array('circleId' => $event->getId())));
            }
        }
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;   
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        return $this->render('FlowberEventBundle:Default:editEvent.html.twig', array(
            'eventForm' => $eventForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
            'circle' => $eventInfo,
            'navbar' => $navbar,
            //'coverInfo' => $coverInfo,
        ));
    }
    
    public function getAllEventsPageAction($id)
    {
        $user=$this->getUser();  
        $eventInfo = $this->container->get('flowber_circle.circle')->getCoverInfos($id, $user->getProfile()->getId());
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $id);
        $events = $this->container->get('flowber_event.event')->getEvents($user->getProfile()->getId(), $user->getProfile()->getId());

        $members = $this->container->get("flowber_event.event")->getEventMembers($id);
        $memberInfos = $this->container->get("flowber_profile.profile")->getFriendsFromList($members, $user->getProfile()->getId());
        //die(var_dump($members));
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav; 
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        return $this->render('FlowberEventBundle:Default:allEvent.html.twig', 
                array( 
                'role' => $role,
                'events' => $events,
                'messageForm' => $privateMessageForm->createView(),
                'circle' => $eventInfo,
                'navbar' => $navbar));
    }
    
    public function getEventSearchPageAction(){
        $user = $this->getUser();        
        $events = $this->container->get('flowber_event.event')->getAllEvents($user->getProfile()->getId());
       
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());       
        return $this->render('FlowberEventBundle:Default:eventSearch.html.twig', 
                array('navbar' => $navbar,
                'events' => $events
                ));
    }
}
