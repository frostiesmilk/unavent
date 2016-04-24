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
use Flowber\GalleryBundle\Form\GalleryType;
use Flowber\CircleBundle\Entity\Subscribers;

class GroupController extends Controller
{
    public function getGroupAction($circleId)
    {
        $user=$this->getUser();  
        
        $groupInfo = $this->container->get('flowber_group.group')->getGroupInfos($circleId, $user->getProfile()->getId());
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $circleId);
        $postRepository = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post');      
        $circle = $this->getDoctrine()->getManager()->getRepository('FlowberCircleBundle:Circle')->find($circleId);
        
        //preparing new form for a post
        $postWithEvent = new Post();        
        $postWithEventForm = $this->createForm(new PostWithEventType, $postWithEvent);    
        $postWithPictures = new Post();
        $postWithPicturesForm = $this->createForm(new PostWithPicturesType(), $postWithPictures);  
           
        // preparing new Gallery
        $newGroupGallery = new Gallery();
        $newGalleryForm = $this->createForm(new GalleryType(), $newGroupGallery);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST'){
            $newGalleryForm->bind($request);
            
            if($newGalleryForm->isValid()){
                $em = $this->getDoctrine()->getManager();
                if(empty($newGroupGallery->getTitle())){
                    $newGroupGallery->setTitle("Galerie du ".$newGroupGallery->getCreationDate()->format('Y-m-d H:i'));
                }
                $circle->addGallery($newGroupGallery);
                
                $em->persist($circle);
                
                try{
                    $em->flush();  
                    return $this->redirect($this->generateUrl(
                        'flowber_group_gallery',
                        array('circleId' => $circle->getId(), 'galleryId' =>$newGroupGallery->getId())
                    ));
                } catch (Exception $ex) {

                }                              
            }
        }
        
        //$posts = $postRepository->getCirclePosts($circle);
        $posts = $this->container->get('flowber_post.post')->getCirclePosts($circle, $user->getProfile()->getId());
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
        $userProfileInfos = $this->container->get("flowber_profile.profile")->getProfileInfos($user->getProfile()->getId());
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;    
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());

        return $this->render('FlowberGroupBundle:Default:group.html.twig', 
            array('circle' => $groupInfo,
                'user'  =>  $userProfileInfos,
                'role' => $role,
                'posts' => $posts,
                'navbar' => $navbar,
                'postWithPicturesForm' => $postWithPicturesForm->createView(),
                'messageForm' => $privateMessageForm->createView(),
                'commentForm' => $commentsForms,
                'postWithEventForm'=> $postWithEventForm->createView(),
                'mailToCreatorForm' => $mailToCreatorForm->createView(),
                'newGalleryForm' => $newGalleryForm->createView(),
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
               
                $subscriber = new Subscribers();
                $subscriber->setCircle($this->container->get("flowber_circle.circle")->getCircle($group->getId()));
                $subscriber->setSubscriber($this->container->get("flowber_circle.circle")->getCircle($user->getProfile()->getId()));
                $subscriber->setRole('admin');
                $subscriber->setStatut($this->container->get("flowber_circle.circle")->getClass($group->getId()));
                $subscriber->setMessage('');
        
                $em->persist($subscriber);
                $em->flush();                
                return $this->redirect($this->generateUrl('api_get_circle',array('circleId' => $group->getId())));
            }
        }    
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;    
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
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
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        return $this->render('FlowberGroupBundle:Default:editGroup.html.twig', array (
            'circle' => $groupInfos,
            'navbar' => $navbar,
            'groupForm' => $groupForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
         ));
    }
    
    public function getGroupMembersAction($id){
        $user = $this->getUser();        
        $groupInfo = $this->container->get('flowber_group.group')->getGroupInfos($id, $user->getProfile()->getId());
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $id);
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);

        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;  
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        $members = $this->container->get("flowber_group.group")->getGroupMembers($id);
        $memberInfos = $this->container->get("flowber_profile.profile")->getFriendsFromList($members, $user->getProfile()->getId());

        return $this->render('FlowberGroupBundle:Default:groupMembers.html.twig', 
                array('circle' => $groupInfo,
                'role' => $role,
                'messageForm' => $privateMessageForm->createView(),
                'navbar' => $navbar,
                'members' => $memberInfos
                 ));
    }
    
    public function getGroupEventsAction($id){
        $user = $this->getUser();        
        $groupInfo = $this->container->get('flowber_group.group')->getGroupInfos($id, $user->getProfile()->getId());
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $id);
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);

        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;  
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
         return $this->render('FlowberGroupBundle:Default:groupEvents.html.twig', 
                array('circle' => $groupInfo,
                'role' => $role,
                'messageForm' => $privateMessageForm->createView(),
                'navbar' => $navbar
                 ));
    }

    public function getGroupGalleriesAction($id){
        $user = $this->getUser();        
        $groupInfo = $this->container->get('flowber_group.group')->getGroupInfos($id, $user->getProfile()->getId());
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $id);
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);

        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
         return $this->render('FlowberGroupBundle:Default:showGroupGalleries.html.twig', 
                array('circle' => $groupInfo,
                'role' => $role,
                'messageForm' => $privateMessageForm->createView(),
                'navbar' => $navbar
                 ));
    }
    
    public function getGroupGalleryAction($circleId, $galleryId){
        $user = $this->getUser();        
        $groupInfo = $this->container->get('flowber_group.group')->getGroupInfos($circleId, $user->getProfile()->getId());
        $role = $this->container->get('flowber_circle.circle')->getRole($user, $circleId);
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);

        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        $gallery = $this->getDoctrine()->getManager()->getRepository('FlowberGalleryBundle:Gallery')->find($galleryId);
        
        return $this->render('FlowberGroupBundle:Default:showGroupGallery.html.twig',
                array('circle' => $groupInfo,
                'role' => $role,
                'messageForm' => $privateMessageForm->createView(),
                'navbar' => $navbar,
                'gallery' => $gallery));
    }

    public function getAllGroupsAction($id){
        $user = $this->getUser();        
        $groups = $this->container->get('flowber_group.group')->getGroups($id, $user->getProfile()->getId());
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;  
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
         return $this->render('FlowberGroupBundle:Default:allGroup.html.twig', 
                array('navbar' => $navbar, 
                    'groups' => $groups
                 ));
    }
    
    public function getSearchAction(){
        $user = $this->getUser();        
         $groups = $this->container->get('flowber_group.group')->getAllGroups($user->getProfile()->getId());
       
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());
        
        return $this->render('FlowberGroupBundle:Default:groupSearch.html.twig', 
                array('navbar' => $navbar,
                'groups' => $groups
                ));
    }
}
