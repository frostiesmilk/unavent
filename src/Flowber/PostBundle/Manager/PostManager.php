<?php

namespace Flowber\PostBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Flowber\PostBundle\Entity\Post;

class PostManager extends BaseManager {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getPost($id)
    {
        $post = $this->getPostRepository()->find($id);
      
        if (!is_object($post)) {
            throw new AccessDeniedException('This post is not defined.');
        }   
        
        return $post;
    } 

    public function getPostInfos($pPost){
        if(is_numeric($pPost)){
            $post = $this->getPost($pPost);
        }
        
        $post = $pPost;
        
        if(!is_object($post)){
            return false;
        }
        
        $postInfos = array(
            "id"        => $post->getId(),
            //"creator"   => 
        );
    }
    
    public function getPostProfilePicture($post)
    {
        $post = $this->getPost($post);
        $profilePicture = $post->getProfilePicture();
        
        if (empty($profilePicture)) {
            $profilePicture = 'assets/images/ProfileBundle/Default/profilePictureDefault.png';
        } else {
            $profilePicture = $profilePicture->getWebPath();
        }
        
        return $profilePicture;
    }   

    public function getCoverPicture($event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        }   
        $event = $this->getEvent($event);
        $coverPicture = $event->getCoverPicture();
        
        if (empty($coverPicture)) {
            $coverPicture = 'assets/images/ProfileBundle/Default/coverPictureDefault.png';
        } else {
            $coverPicture = $coverPicture->getWebPath();
        }
        
        return $coverPicture;
    } 
    
    public function getCoverInfos($event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        }   
        
        $coverInfos = new ArrayCollection();
        
        $coverInfos['title'] = $event->getTitle($event);
        $coverInfos['subtitle'] = $event->getSubtitle($event);
        $coverInfos['coverPicture'] = $this->getCoverPicture($event);
        $coverInfos['profilePicture'] = $this->getProfilePicture($event);
     
        return $coverInfos;
    } 
    
}