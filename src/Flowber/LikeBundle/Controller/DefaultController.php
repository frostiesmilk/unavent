<?php

namespace Flowber\LikeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\LikeBundle\Entity\Likes;
use Flowber\NotificationBundle\Entity\Notification;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FlowberLikeBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function likePostGroupAction($post_id)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($post_id);
        $em = $this->getDoctrine()->getManager();
        
        $like = new Likes();
        $like->setCreatedBy($this->getUser());
        $post->addLike($like);
        

        if ($post->getGroups() != null){
            $notification = new Notification ();
            $notification->setCreatedBy($this->getUser());
            $notification->setUser($post->getCreatedBy());
            $notification->setPageRoute('flowber_group_homepage');
            $notification->setPageId($post->getGroups()->getId());
            $notification->setMessage("a aimé votre post dans ");
            $notification->setPageName($post->getGroups()->getTitle());
            $em->persist($notification);
        }

        
        $em->persist($like);
        $em->persist($post);
        $em->flush();
        
        return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $post->getGroups()->getId())));
    }
    
    public function unlikePostGroupAction($like_id, $post_id)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($post_id);
        $like = $this->getDoctrine()->getManager()->getRepository('FlowberLikeBundle:Likes')->find($like_id);
        
        $em = $this->getDoctrine()->getManager();
        
        $post->removeLike($like);
        $em->persist($post);
        $em->flush();
        
        return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $post->getGroups()->getId())));
    }   
    
    public function likePostEventAction($post_id)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($post_id);
        $em = $this->getDoctrine()->getManager();
        
        $like = new Likes();
        $like->setCreatedBy($this->getUser());
        $post->addLike($like);
        

        if ($post->getGroups() == null){
            if ($post->getEvent() != null){
                $notification = new Notification ();
                $notification->setCreatedBy($this->getUser());
                $notification->setUser($post->getCreatedBy());
                $notification->setPageRoute('flowber_event_homepage');
                $notification->setPageId($post->getEvent()->getId());
                $notification->setMessage("a aimé votre post dans ");
                $notification->setPageName($post->getEvent()->getTitle());
                $em->persist($notification);
            }
        }

        
        $em->persist($like);
        $em->persist($post);
        $em->flush();
        
        return $this->redirect($this->generateUrl('flowber_event_homepage', array('id' => $post->getEvent()->getId())));
    }
    
    public function unlikePostEventAction($like_id, $post_id)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($post_id);
        $like = $this->getDoctrine()->getManager()->getRepository('FlowberLikeBundle:Likes')->find($like_id);
        
        $em = $this->getDoctrine()->getManager();
        
        $post->removeLike($like);
        $em->persist($post);
        $em->flush();
        
        return $this->redirect($this->generateUrl('flowber_event_homepage', array('id' => $post->getEvent()->getId())));
    } 
}
