<?php

namespace Flowber\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\CommentType;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\PostType;

class DefaultController extends Controller
{
    public function addCommentAction($post_id)
    {
//        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($post_id);
        $postRepository = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post');
        
        $post = $postRepository->getReceivedMessages($user);        
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
                $em->persist($comment);
                $em->flush();
                
                return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $post->getGroups()->getId())));
            }
        }
        return $this->render('FlowberPostBundle:Default:index.html.twig', array('post_id' => $post_id));
    }
    
    public function addPostAction($group_id)
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
                $em->persist($post);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_group_homepage', array('id' => $group_id)));
            }
        }
        
        return $this->render('FlowberPostBundle:Default:index.html.twig', array('post_id' => $post_id));
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
