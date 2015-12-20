<?php

namespace Flowber\GroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\GalleryBundle\Form\ProfilePictureType;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GroupBundle\Form\GroupsType;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\PostBundle\Form\PostType;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\CommentType;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\PostWithEventType;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageOnlyType;

class DefaultController extends Controller
{
    public function groupAction($id)
    {
        $group = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->find($id);
//        $posts = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->findByGroups($group);
        
        $postRepository = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post');
        $posts = $postRepository->getPostFromGroup($id);   
        
        $profilePicture = null;
        $coverPicture = null;

        if ($group->getProfilePicture() != null){
            $profilePicture = $group->getProfilePicture()->getWebPath();
        }
        if ($group->getCoverPicture() != null){ 
            $coverPicture = $group->getCoverPicture()->getWebPath();
        }
        
        //preparing new form for a post
        $post = new Post();
        $postwithEvent = new Post();
        $postForm = $this->createForm(new PostType, $post);
        $postWithEventForm = $this->createForm(new PostWithEventType, $postwithEvent);
        
        $CommentArray = array();

        foreach ($posts as $post)
        {
            $comment = new Comment();
            $commentForm = $this->createForm(new CommentType, $comment);
            $CommentArray[] = $commentForm->createView();
        }
        
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
                $userTo = $group->getCreatedBy();
                
                // setting subject, sender and destination
                $subject = "[".$group->getTitle()."] : Nouveau message privé de ".$user->getFirstname()." ".$user->getSurname();
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
                return $this->redirectToRoute('flowber_group_homepage', array('id'=>$group->getId()));
            }else{
                // message bag
                $this->addFlash(
                    'error',
                    "Une erreur est survenue lors de l'envoi du message."
                );
            }
        }
        
        return $this->render('FlowberGroupBundle:Default:group.html.twig', 
                array('title' => $group->getTitle(), 
                    'subtitle' => $group->getSubtitle(), 
                    'description' => $group->getDescription(), 
                    'categories' => $group->getCategories(), 
                    'firstname' => $group->getCreatedBy()->getFirstname(), 
                    'surname' => $group->getCreatedBy()->getSurname(), 
                    'id' => $group->getId(), 
                    'groupId' => $group->getId(), 
                    'profilePicture' => $profilePicture, 
                    'coverPicture' => $coverPicture,
                    'postForm' => $postForm->createView(),
                    'commentForm' => $CommentArray,
                    'postWithEventForm'=> $postWithEventForm->createView(),
                    'posts' => $posts,
                    'mailToCreatorForm' => $mailToCreatorForm->createView()
            ));
    }
    
    public function createGroupAction()
    {
        $user = $this->getUser();        
        $error = false; // detect error while processing forms
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        // preparing profile to be edited
        $group = new \Flowber\GroupBundle\Entity\Groups;
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
         return $this->render('FlowberGroupBundle:Default:createGroup.html.twig', array(
            'groupForm' => $groupForm->createView(),
            'profilePictureForm' => $profilePictureForm->createView(),
            'coverPictureForm' => $coverPictureForm->createView(),
        ));
  
    }
    
    public function AllGroupsAction()
    {
        $user = $this->getUser();        

        $allGroup = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->findByCreatedBy($user);
        
        return $this->render('FlowberGroupBundle:Default:allGroup.html.twig', array(
            'allGroup' => $allGroup,
        ));
    }
    
    public function groupGalleriesAction($id){
        $group = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->find($id);
        
        $profilePicture = null;
        $coverPicture = null;

        if ($group->getProfilePicture() != null){
            $profilePicture = $group->getProfilePicture()->getWebPath();
        }
        if ($group->getCoverPicture() != null){ 
            $coverPicture = $group->getCoverPicture()->getWebPath();
        }
        
         return $this->render('FlowberGroupBundle:Default:groupGalleries.html.twig', 
                array(
                    'groupId' => $group->getId(),
                    'title' => $group->getTitle(), 
                    'subtitle' => $group->getSubtitle(), 
                    'profilePicture' => $profilePicture, 
                    'coverPicture' => $coverPicture ));
    }
    
    public function groupMembersAction($id){
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
    
    public function groupEventsAction($id){
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
}