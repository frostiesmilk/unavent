<?php

namespace Flowber\PostBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\PostType;

class PostRestController extends Controller
{
    
    /**
     * Create new post
     * @param Request $request
     * @return View
     */
    public function postPostAction(Request $request){
        $post = new Post();
        $form = $this->createForm(new PostType(), $post);
        $form->bind($request);
        
        $view = new ResponseView();// preparing response
        
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            
            try{
                $em->persist($post);
                $em->flush();
                
            } catch (Exception $ex) {
                $repsData = array("message" => "Post flush failed");
                $view->setData($repsData)->setStatusCode(400); // ok
                return $view;
            }
            
            $repsData = array("postId" => $post->getId(), "datetimeCreated"=> $post->getCreationDate());
            $view->setData($repsData)->setStatusCode(200); // ok
            
            return $view;
        }
        
        $repsData = array('form' => $form);
        
        return $view->setDate($repsData)->setStatusCode(400); // ok
    }
    
    /**
     * 
     * @param type $postId
     * @return type
     * @throws type
     * 
     * @View(serializerGroups={"Default"})
     */
    public function getPostAction($postId){
        $post = $this->getDoctrine()->getRepository('FlowberPostBundle:Post')->find($postId);
        if(!is_object($post)){
            throw $this->createNotFoundException();
        }
        //die(var_dump($group));
        return $post;
    }
    
    /**
     * Delete post action
     * @var integer $postId
     * @View(statusCode=204)
     */
    public function deletePostCustomAction($postId){
        $post = $this->getDoctrine()->getRepository('FlowberPostBundle:Post')->find($postId);
        
        if(is_object($post)){
            $currentCurrent = $this->getUser();
            if($currentCurrent == $post->getCreatedBy()){ // checking if allowed to delete post (by author)
                $post->setDeleted();
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
            }
        }
    }
}
