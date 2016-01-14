<?php

namespace Flowber\PostBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PostRestController extends Controller
{
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
