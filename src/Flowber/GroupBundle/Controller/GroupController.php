<?php

namespace Flowber\GroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\GalleryBundle\Form\ProfilePictureType;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\GroupBundle\Form\GroupsType;
use Flowber\GroupBundle\Entity\Groups;
use Flowber\PostBundle\Form\PostType;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\CommentType;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\PostWithEventType;
use Flowber\PostBundle\Form\PostWithPicturesType;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageOnlyType;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;
use Flowber\GalleryBundle\Entity\Gallery;

class GroupController extends Controller
{
    public function getGroupAction($circleId)
    {
        $user=$this->getUser();  
        $groupInfo = $this->container->get('flowber_group.group')->getGroupInfos($circleId);
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $circleId);
        $postRepository = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post');      
        $circle = $this->getDoctrine()->getManager()->getRepository('FlowberCircleBundle:Circle')->find($circleId);
        
        //preparing new form for a post
        $newPost = new Post();
        $postWithEvent = new Post();
        $postWithPictures = new Post();
        $postForm = $this->createForm(new PostType(), $newPost);
        $postWithPicturesForm = $this->createForm(new PostWithPicturesType(), $postWithPictures);
        $postWithEventForm = $this->createForm(new PostWithEventType, $postWithEvent);      
           
        $request = $this->get('request');
        if ($request->getMethod() == 'POST'){
            $postWithEventForm->bind($request);
            
            if($postWithEventForm->isValid()){
                $em = $this->getDoctrine()->getManager();
                $userProfile = $user->getProfile();
                $postEvent = $postWithEvent->getAttachedEvent();
                $postEvent->setCreatedBy($userProfile);
                $postWithEvent->setMessage($postEvent->getTitle());
                $postWithEvent->setCreatedBy($userProfile);
                $postWithEvent->setCircle($circle);
                $postWithEvent->getAttachedEvent()->setCreatedBy($circle);

                $em->persist($postWithEvent);
                $em->persist($postEvent);
                $em->flush($postWithEvent);                
            }
        }
        
        $posts = $postRepository->getPost($circleId);
        // forms for comments
        $commentsForms = array();
        foreach ($posts as $post)
        {
            $comment = new Comment();
            $commentForm = $this->createForm(new CommentType, $comment);
            $commentsForms[] = $commentForm->createView();
        }
        
        // for Private Messages
        $mailToCreator = new PrivateMessage();
        $mailToCreatorForm = $this->createForm(new PrivateMessageOnlyType, $mailToCreator);
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);

        // user
        $userInfos = array( "id"        => $user->getId(),
                            "firstname" =>  $user->getFirstname(),
                            "surname"   =>  $user->getSurname(),
                            "profilePicture"    => $this->container->get('flowber_circle.circle')->getProfilePicture($groupInfo['idCreatedBy']));
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;    
        
        return $this->render('FlowberGroupBundle:Default:group.html.twig', 
            array('circle' => $groupInfo,
                'user'  =>  $userInfos,
                'role' => $role,
                'posts' => $posts,
                'navbar' => $navbar,
                'postForm' => $postForm->createView(),
                'postWithPicturesForm' => $postWithPicturesForm->createView(),
                'messageForm' => $privateMessageForm->createView(),
                'commentForm' => $commentsForms,
                'postWithEventForm'=> $postWithEventForm->createView(),
                'mailToCreatorForm' => $mailToCreatorForm->createView(),                    
        ));
    }
    
    public function getCreateGroupAction()
    {
        $user = $this->getUser();        
        $error = false; // detect error while processing forms
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        // preparing profile to be edited
        $group = new Groups();
        //$group = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->findOneByUser($user);
        $groupForm = $this->createForm(new GroupsType, $group);
        
        //preparing eventual new profile picture
        $profilePicture = new Photo();
        $profilePictureForm = $this->createForm(new ProfilePictureType, $profilePicture);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPictureForm = $this->createForm(new CoverPictureType, $coverPicture);
        
        $request = $this->get('request');
        
        if ($request->getMethod() == 'POST') { 
            // retrieving all forms
            $groupForm->handleRequest($request);
            $profilePictureForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);
            
            $em = $this->getDoctrine()->getManager();
            
            // processing profile edit
            if ($groupForm->isValid()) { 
                $group->setCreatedBy($user->getProfile());
                $em->persist($group);
            }else{
                $error = true;
            }
                     
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){                    
                    $profilePicture->addGallery($group->getProfileGallery());
                    $group->setProfilePicture($profilePicture);
                    $em->persist($profilePicture);
                }
            }else{
                $error = true;
            }   
            
            // processing cover picture form
            if($coverPictureForm->isValid()){
                // cover picture was submitted
                if($coverPicture->getFile() !== null){
                    $coverPicture->addGallery($group->getCoverGallery());
                    $group->setCoverPicture($coverPicture);
                    $em->persist($coverPicture);
                }
            }else{
                $error = true;
            }   
            
            // no error
            if(!$error){  
                $em->persist($group);
                $em->flush();
//                $em->persist($profile);
//                $em->flush();
                // all good, back to profile page
                return $this->redirect($this->generateUrl('api_get_circle',array('circleId' => $group->getId())));
            }
        }    
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;    
        
         return $this->render('FlowberGroupBundle:Default:createGroup.html.twig', array(
            'groupForm' => $groupForm->createView(),
            'navbar' => $navbar,
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
        ));
  
    }

    public function editGroupAction($circleId)
    {
        $user = $this->getUser();        
        $error = false; // detect error while processing forms
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   

        $group = $this->container->get('flowber_group.group')->getGroup($circleId);        
        $groupInfos = $this->container->get('flowber_group.group')->getGroupInfos($group);
        
        // preparing profile to be edited
        $groupForm = $this->createForm(new GroupsType, $group);
        
        //preparing eventual new profile picture
        $profilePicture = new Photo();
        $profilePictureForm = $this->createForm(new ProfilePictureType, $profilePicture);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPictureForm = $this->createForm(new CoverPictureType, $coverPicture); 

        $request = $this->get('request');
                
        if ($request->getMethod() == 'POST') { 
            // retrieving all forms
            $groupForm->handleRequest($request);
            $profilePictureForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);
            
            $em = $this->getDoctrine()->getManager();
            
            // processing profile edit
            if ($groupForm->isValid()) { 
                $em->persist($group);
            }else{
                $error = true;
            }
                     
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){                    
                    $profilePicture->addGallery($group->getProfileGallery());
                    $group->setProfilePicture($profilePicture);
                    $em->persist($profilePicture);
                }
            }else{
                $error = true;
            }   
            
            // processing cover picture form
            if($coverPictureForm->isValid()){
                // cover picture was submitted
                if($coverPicture->getFile() !== null){
                    $coverPicture->addGallery($group->getCoverGallery());
                    $group->setCoverPicture($coverPicture);
                    $em->persist($coverPicture);
                }
            }else{
                $error = true;
            }   
            
            // no error
            if(!$error){  
                $em->persist($group);
                $em->flush();
//                $em->persist($profile);
//                $em->flush();
                // all good, back to profile page
                return $this->redirect($this->generateUrl('api_get_circle',array('circleId' => $group->getId())));
            }
        } 
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;    
        
        return $this->render('FlowberGroupBundle:Default:editGroup.html.twig', array (
            'circle' => $groupInfos,
            'navbar' => $navbar,
            'groupForm' => $groupForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
         ));
    }
    
    public function getGroupMembersAction($id){
        $group = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->find($id);
        
        $profilePicture = null;
        $coverPicture = null;

        if ($group->getProfilePicture() != null){
            $profilePicture = $group->getProfilePicture()->getWebPath();
        }
        if ($group->getCoverPicture() != null){ 
            $coverPicture = $group->getCoverPicture()->getWebPath();
        }
        
         return $this->render('FlowberGroupBundle:Default:groupMembers.html.twig', 
                array(
                    'groupId' => $group->getId(),
                    'title' => $group->getTitle(), 
                    'subtitle' => $group->getSubtitle(), 
                    'profilePicture' => $profilePicture, 
                    'coverPicture' => $coverPicture ));
    }
    
    public function getGroupEventsAction($id){
        $group = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->find($id);
        
        $profilePicture = null;
        $coverPicture = null;

        if ($group->getProfilePicture() != null){
            $profilePicture = $group->getProfilePicture()->getWebPath();
        }
        if ($group->getCoverPicture() != null){ 
            $coverPicture = $group->getCoverPicture()->getWebPath();
        }
        
         return $this->render('FlowberGroupBundle:Default:groupEvents.html.twig', 
                array(
                    'groupId' => $group->getId(),
                    'title' => $group->getTitle(), 
                    'subtitle' => $group->getSubtitle(), 
                    'profilePicture' => $profilePicture, 
                    'coverPicture' => $coverPicture ));
    }
    
    public function groupSearchAction(){

        
         return $this->render('FlowberGroupBundle:Default:groupSearch.html.twig', 
                array());
    }
}
