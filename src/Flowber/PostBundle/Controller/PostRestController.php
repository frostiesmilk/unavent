<?php

namespace Flowber\PostBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\PostType;
use Flowber\PostBundle\Form\GroupPostType;
//use Symfony\Component\HttpFoundation\JsonResponse;

class PostRestController extends Controller
{
    
    /**
     * Create new post
     * @param Request $request
     * @param int $groupId
     * @return View
     */
    public function postGroupPostAction(Request $request, $groupId){
        $group = $this->getDoctrine()->getManager()->getRepository('FlowberGroupBundle:Groups')->find($group_id);
        
        if(!is_object($group)){
            return array("");
        }
        
        $post = new Post();
        $form = $this->createForm(new GroupPostType(), $post);
        $form->bind($request);
        
        $view = new ResponseView();// preparing response
        
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            
            try{
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
