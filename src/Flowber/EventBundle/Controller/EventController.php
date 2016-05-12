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
        $privacy = $this->container->get('flowber_circle.circle')->getPrivacy($circleId);
        $eventInfo = $this->container->get('flowber_event.event')->getEventInfos($circleId, $user->getProfile()->getId());
        $posts = null;
        $postForm = null;
        $CommentArray = array();  
        
        //preparing new form for a post
        $postWithPictures = new Post();
        $postWithPicturesForm = $this->createForm(new PostWithPicturesType(), $postWithPictures);              
           
        // preparing new Gallery
        $newGroupGallery = new Gallery();
        $newGalleryForm = $this->createForm(new GalleryType(), $newGroupGallery);
        
        if($eventInfo['role']!='cantsee'){
            $postRepository = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post');
            $posts = $posts = $this->container->get('flowber_post.post')->getCirclePosts($circle, $user);
            
            $post = new Post();
            $postForm = $this->createForm(new PostType, $post)->createView();

            foreach ($posts as $post)
            {
                $comment = new Comment();
                $commentForm = $this->createForm(new CommentType, $comment);
                $CommentArray[] = $commentForm->createView();
            }            
        }
        
        return $this->render('FlowberEventBundle:Default:event.html.twig', 
            array(
                'user' => $this->container->get("flowber_profile.profile")->getCurrentProfileInfos(),
                'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),
                'circle' => $eventInfo,
                'mailToCreatorForm' => $this->createForm(new PrivateMessageOnlyType, new PrivateMessage)->createView(),
                'messageForm' => $this->createForm(new PrivateMessageType, new PrivateMessage)->createView(),
                'postForm' => $postForm,
                'posts' => $posts,
                'commentForm' => $CommentArray,
                'postWithPicturesForm' => $postWithPicturesForm->createView(),
                'newGalleryForm' => $newGalleryForm->createView(),
            )
        );
    }
    
    public function getParticipantsPageAction($id)
    {
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
       
        $members = $this->container->get("flowber_event.event")->getEventMembers($id);
        $memberInfos = $this->container->get("flowber_profile.profile")->getFriendsFromList($members);
        
        return $this->render('FlowberEventBundle:Default:eventMember.html.twig', 
                array( 
                'members' => $memberInfos,
                'messageForm' => $privateMessageForm->createView(),
                'circle' => $this->container->get('flowber_circle.circle')->getCoverInfos($id),
                'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos()
                ));
    }
    
    public function getGalleryPageAction($id)
    {
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        
        $members = $this->container->get("flowber_event.event")->getEventMembers($id);
        $memberInfos = $this->container->get("flowber_profile.profile")->getFriendsFromList($members);
        
        return $this->render('FlowberEventBundle:Default:eventGallery.html.twig', 
                array( 
                'messageForm' => $privateMessageForm->createView(),
                'circle' => $this->container->get('flowber_circle.circle')->getCoverInfos($id),
                'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos()
                ));
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
        
        return $this->render('FlowberEventBundle:Default:createEvent.html.twig', array(
            'eventForm' => $eventForm->createView(),
            'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
        ));
    }
    
    public function getEditEventAction($circleId)
    {
        $user=$this->getUser();

        $event = $this->container->get('flowber_event.event')->getEvent($circleId);        
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
        
        return $this->render('FlowberEventBundle:Default:editEvent.html.twig', array(
            'eventForm' => $eventForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
            'circle' => $this->container->get('flowber_event.event')->getEventInfos($circleId),
            'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos()
        ));
    }
    
    public function getAllEventsPageAction($id)
    {
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        
        return $this->render('FlowberEventBundle:Default:allEvent.html.twig', 
                array( 
                'events' => $this->container->get('flowber_event.event')->getCurrentUserEvents(),
                'messageForm' => $privateMessageForm->createView(),
                'circle' => $this->container->get('flowber_circle.circle')->getCoverInfos($id),
                'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos()
                ));
    }
    
    public function getEventSearchPageAction()
    {
        $searchEventData = array();
        $searchEventForm = $this->createFormBuilder($searchEventData)
                                ->add('title', 'text')
                                ->add('eventDate', 'date',array(
                                                            'widget' => 'single_text',
                                                            'input' => 'datetime',
                                                            'format' => 'dd/MM/yyyy',
                                                            'attr' => array('class' => 'flowber_datepicker')
                                                        )
                                )
                                ->add('eventTime', 'time', array(
                                                    'widget' => 'single_text',
                                                    'input' => 'datetime',
                                                    'attr' => array('class' => 'flowber_timepicker'),
                                                    'required' => false,)
                                )
                                ->add('categories', 'category', array(
                                        'class'    => 'FlowberFrontOfficeBundle:Category',
                                        'property' => 'title',
                                        'multiple' => true,
                                        'required' => false,
                                        'query_builder' => function(EntityRepository $er) {
                                            return $er->createQueryBuilder('u')
                                                ->orderBy('u.title', 'ASC');
                                        },
                                    )
                                )
                                ->add('placeName', 'text')
                                ->add('zipcode', 'text');
                                                    
        
        $events = $this->container->get('flowber_event.event')->getAllEvents();
       
        return $this->render('FlowberEventBundle:Default:eventSearch.html.twig', 
                array(
                'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
                'events' => $events,
                'searchEventForm' => $searchEventForm->getForm(),
                ));
    }
}
