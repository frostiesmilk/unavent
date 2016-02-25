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
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageOnlyType;

class GroupController extends Controller
{
    public function getGroupAction($id)
    {
        $user=$this->getUser();
        $group = $this->container->get('flowber_group.group')->getGroup($id);        
        $groupInfos = $this->container->get('flowber_group.group')->getGroupInfos($group);
        $isCreator = $this->container->get('flowber_circle.circle')->isCreator($user, $group);

        $circle = $this->container->get('flowber_circle.circle')->getCircleInfos((int)$id);
        
        $postRepository = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post');
        $posts = $postRepository->getPost($id);   
        
        //preparing new form for a post
        $post = new Post();
        $postwithEvent = new Post();
        $postForm = $this->createForm(new PostType(), $post);
        $postWithEventForm = $this->createForm(new PostWithEventType, $postwithEvent);
        
        $CommentArray = array();

        foreach ($posts as $post)
        {
            $comment = new Comment();
            $commentForm = $this->createForm(new CommentType, $comment);
            $CommentArray[] = $commentForm->createView();
        }
        
        // for Private Messages
        $mailToCreator = new PrivateMessage();
        $mailToCreatorForm = $this->createForm(new PrivateMessageOnlyType, $mailToCreator);
        
        $userInfos = array( "id"        => $user->getId(),
                            "firstname" =>  $user->getFirstname(),
                            "surname"   =>  $user->getSurname(),
                            "profilePicture"    => $user->getProfile()->getProfilePicture());
        
        return $this->render('FlowberGroupBundle:Default:group.html.twig', 
                array('circle' => $groupInfos,
                    'isCreator' => $isCreator,
                    'postForm' => $postForm->createView(),
                    'commentForm' => $CommentArray,
                    'postWithEventForm'=> $postWithEventForm->createView(),
                    'posts' => $posts,
                    'mailToCreatorForm' => $mailToCreatorForm->createView(),
                    'user'  =>  $userInfos,
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
                $group->setCreatedBy($user);
                $em->persist($group);
            }else{
                $error = true;
            }
                     
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){                    
                    //$profilePicture->addGallery($group->getProfileGallery());
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
                    //$coverPicture->addGallery($group->getCoverGallery());
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
                return $this->redirect($this->generateUrl('flowber_group_homepage',array('id' => $group->getId())));
            }
        }        
         return $this->render('FlowberGroupBundle:Default:createGroup.html.twig', array(
            'groupForm' => $groupForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
        ));
  
    }

    public function editGroupAction($id)
    {
        $user = $this->getUser();        
        $error = false; // detect error while processing forms
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   

        $group = $this->container->get('flowber_group.group')->getGroup($id);        
        $groupInfos = $this->container->get('flowber_group.group')->nb($group);
        
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
                $group->setCreatedBy($user);
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
                return $this->redirect($this->generateUrl('flowber_group_homepage',array('id' => $group->getId())));
            }
        } 
        
        return $this->render('FlowberGroupBundle:Default:editGroup.html.twig', array (
            'circle' => $groupInfos,
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
