<?php

namespace Flowber\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\CommentType;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\PostType;
use Flowber\PostBundle\Form\PostWithEventType;
use Flowber\NotificationBundle\Entity\Notification;

class DefaultController extends Controller
{
    public function addCommentGroupAction($post_id)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($post_id);

        $comment = new Comment;
        $commentForm = $this->createForm(new CommentType, $comment);

        $request = $this->get('request');
        
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $commentForm->handleRequest($request);
            
            if ($commentForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $comment->setPost($post);
                $comment->setCreatedBy($this->getUser());
                
                if ($post->getCreatedBy() != $this->getUser()){
                    $notification = new Notification ();
                    $notification->setCreatedBy($this->getUser());
                    $notification->setUser($post->getCreatedBy());
                    $notification->setPageRoute('flowber_group_homepage');
                    $notification->setPageId($post->getGroups()->getId());
                    $notification->setMessage("a commenté votre post \""
                            . $post->getMessage()
                            . "\" dans ");
                    $notification->setPageName($post->getGroups()->getTitle());
                    $em->persist($notification);                
                }
                
                $em->persist($comment);
                $em->flush();
                
                return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $post->getGroups()->getId())));
            }
        }
        return $this->render('FlowberPostBundle:Default:index.html.twig', array('post_id' => $post_id));
    }

    public function addCommentEventAction($post_id)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($post_id);

        $comment = new Comment;
        $commentForm = $this->createForm(new CommentType, $comment);

        $request = $this->get('request');
        
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $commentForm->handleRequest($request);
            
            if ($commentForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $comment->setPost($post);
                $comment->setCreatedBy($this->getUser());
                
                if ($post->getCreatedBy() != $this->getUser()){
                    $notification = new Notification ();
                    $notification->setCreatedBy($this->getUser());
                    $notification->setUser($post->getCreatedBy());
                    $notification->setPageRoute('flowber_event_homepage');
                    $notification->setPageId($post->getEvent()->getId());
                    $notification->setMessage("a commenté votre post \""
                            . $post->getMessage()
                            . "\" dans ");
                    $notification->setPageName($post->getEvent()->getTitle());
                    $em->persist($notification);                
                }
                
                $em->persist($comment);
                $em->flush();
                
                $em->persist($comment);
                $em->flush();
                
                return $this->redirect($this->generateUrl('flowber_event_homepage', array('id' => $post->getEvent()->getId())));
            }
        }
        return $this->render('FlowberPostBundle:Default:index.html.twig', array('post_id' => $post_id));
    }
    
    public function addPostGroupAction($group_id)
    {
        $group = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->find($group_id);
        
        $post = new Post;
        $postForm = $this->createForm(new PostType, $post);

        $request = $this->get('request');
                
        // if create post form has been submitted
        if ($request->getMethod() == 'POST') { 
            $postForm->handleRequest($request);
            
            if ($postForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $post->setCreatedBy($this->getUser());
                $post->setGroups($group);
                
                if ($group->getCreatedBy() != $this->getUser()){
                    $notification = new Notification ();
                    $notification->setCreatedBy($this->getUser());
                    $notification->setUser($group->getCreatedBy());
                    $notification->setPageRoute('flowber_group_homepage');
                    $notification->setPageId($group->getId());
                    $notification->setMessage("a ajouté un post \""
                            . $post->getMessage()
                            . "\" dans ");
                    $notification->setPageName($group->getTitle());
                    $em->persist($notification);         
                }
                
                $em->persist($post);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $group_id)));
            }
        }
        
        return $this->render('FlowberPostBundle:Default:index.html.twig', array('group_id' => $group_id));
    }
    
    public function addPostEventAction($event_id)
    {
        $event = $this->getDoctrine()->getManager()->getRepository('FlowberEventBundle:Event')->find($event_id);
        $post = new Post;
        $postForm = $this->createForm(new PostType, $post);

        $request = $this->get('request');
                
        // if create post form has been submitted
        if ($request->getMethod() == 'POST') { 
            $postForm->handleRequest($request);
            
            if ($postForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $post->setCreatedBy($this->getUser());
                $post->setEvent($event);
                
                if ($event->getCreatedBy() != $this->getUser()){
                    $notification = new Notification ();
                    $notification->setCreatedBy($this->getUser());
                    $notification->setUser($event->getCreatedBy());
                    $notification->setPageRoute('flowber_event_homepage');
                    $notification->setPageId($event->getId());
                    $notification->setMessage("a ajouté un post \""
                            . $post->getMessage()
                            . "\" dans ");
                    $notification->setPageName($event->getTitle());
                    $em->persist($notification);                
                }
                
                $em->persist($post);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_event_homepage', array('id' => $event_id)));
            }
        }
        
        return $this->render('FlowberPostBundle:Default:index.html.twig', array('event_id' => $event_id));
    }
    
    public function addPostWithEventAction($group_id)
    {
        $group = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->find($group_id);
        
        $post = new Post;
        $postForm = $this->createForm(new PostWithEventType, $post);

        $request = $this->get('request');
                
        // if create post form has been submitted
        if ($request->getMethod() == 'POST') { 
            $postForm->handleRequest($request);
            
            if ($postForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $post->setCreatedBy($this->getUser());
                $post->setGroups($group);
                
                if ($post->getCreatedBy() != $this->getUser()){
                    $notification = new Notification ();
                    $notification->setCreatedBy($this->getUser());
                    $notification->setUser($post->getCreatedBy());
                    $notification->setPageRoute('flowber_group_homepage');
                    $notification->setPageId($group->getId());
                    $notification->setMessage("a ajouté une sortie \""
                            . $post->getMessage()
                            . "\" dans ");
                    $notification->setPageName($group->getTitle());
                    $em->persist($notification);         
                }
                
                $em->persist($post);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $group_id)));
            }
        }
        
        return $this->render('FlowberPostBundle:Default:index.html.twig', array('name' => $group_id));
    }
    
    public function deletePostAction($post_id)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($post_id);
        $em = $this->getDoctrine()->getManager();
        
        if ($post->getCreatedBy = $this->getUser()){
            $post->setStatut('0');
            $em->persist($post);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $post->getGroups()->getId())));
    }
    
    
    public function deleteCommentAction($comment_id)
    {
        $comment = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Comment')->find($comment_id);
        $em = $this->getDoctrine()->getManager();
        
        if ($comment->getCreatedBy() == $this->getUser()){
            $comment->setStatut('0');
            $em->persist($comment);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $comment->getPost()->getGroups()->getId())));
    }
}
