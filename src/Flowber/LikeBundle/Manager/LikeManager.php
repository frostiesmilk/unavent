<?php

namespace Flowber\LikeBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
//use Flowber\UserBundle\Entity\User;
//use Flowber\PostBundle\Entity\Post;
use Flowber\LikeBundle\Entity\Likes;

class LikeManager extends BaseManager {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getLikeRepository()
    {
        return $this->em->getRepository('FlowberLikeBundle:Likes');
    }  
    
    public function getPostRepository()
    {
        return $this->em->getRepository('FlowberPostBundle:Post');
    }  
    
    public function addLikePost($pPost, $user){
        
        $post = $pPost;
        
        if(!is_object($post)){
            return false;
        }
        
        $hasLiked = $this->getPostRepository()->hasUserLikedPost($post, $user);
        
        if($hasLiked){
            return false;
        }
                
        $like = new Likes();
        $like->setCreatedBy($user);
        $post->addLike($like);
        $this->em->persist($like);
        $this->em->persist($post);
        
        try{
            $this->em->flush();
        } catch (Exception $ex) {
            return false;
        }
        
        return $like->getId();
    }
    
}