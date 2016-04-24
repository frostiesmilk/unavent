<?php

namespace Flowber\PostBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Flowber\CircleBundle\Manager\CircleManager;
use Flowber\CircleBundle\Entity\Circle;
use Flowber\EventBundle\Manager\EventManager;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Entity\Post;
use Flowber\UserBundle\Entity\User;

class PostManager extends BaseManager {

    protected $em;
    protected $cm;
    protected $eventmanager;

    public function __construct(EntityManager $em, CircleManager $cm, EventManager $eventmanager)
    {
        $this->em = $em;
        $this->cm = $cm;
        $this->eventmanager = $eventmanager;
    }
    
    public function getCirclePosts(Circle $circle, $currentUser)
    {
        $posts = $this->getPostRepository()->getCirclePosts($circle, $currentUser);        
        $infos = [];
        
        foreach($posts AS $post){
            $infos[] = $this->getPostInfos($post, $currentUser);
        }
        
        return $infos;
    }
    
    public function getPostInfos(Post $post, $currentUser)
    {
        $infos = [];
        
        $infos["id"] = $post->getId();
        $infos["attachedEvent"] = null;
        if(!empty($post->getAttachedEvent())){
            $infos["attachedEvent"] = $this->eventmanager->getEventInfos($post->getAttachedEvent()->getId(), $currentUser);
        }
        
        $infos["circle"] = array("id" => $post->getCircle()->getId());
        $infos["createdBy"] = $this->cm->getCircleInfos($post->getCreatedBy());
        $infos["creationDate"] = $post->getCreationDate();
        $infos["deleteDate"] = $post->getDeleteDate();
        $infos["gallery"] = $post->getGallery();
        $infos["likes"] = $post->getLikes();
        $infos["message"] = $post->getMessage();
        $infos["status"] = $post->getStatus();
        $infos["comments"] = [];
        foreach($post->getComments() AS $comment){
            $infos["comments"][]= $this->getCommentInfos($comment);
        }
        
        return $infos;
    }
    
    public function getCommentInfos(Comment $comment){
        $infos = [];
        
        $infos["id"] = $comment->getId();
        $infos["createdBy"] = $this->cm->getCircleInfos($comment->getCreatedBy());
        $infos["creationDate"] = $comment->getCreationDate();
        $infos["deleteDate"] = $comment->getDeleteDate();        
        $infos["likes"] = $comment->getLikes();
        $infos["message"] = $comment->getMessage();    
        
        return $infos;
    }
    
    public function getPostRepository(){
        return $this->em->getRepository('FlowberPostBundle:Post');
    }
}