<?php

namespace Flowber\LikeBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as ResponseView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

use Flowber\LikeBundle\Entity\Likes;
use Flowber\NotificationBundle\Entity\Notification;
use Flowber\PostBundle\Entity\Post;

class LikeRestController extends Controller
{   
    /**
     * Add Like for post
     * @var integer $postId
     * @View
     */
    public function postLikePostAction(Request $request){
        
        $postId = $request->request->get('postId');
        $user = $this->getUser();
        
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($postId);
        $em = $this->getDoctrine()->getManager();
        
//        $like = new Likes();
//        $like->setCreatedBy($this->getUser());
//        $post->addLike($like);

        // manage Notification
        if ($post->getCircle() != null){
            //$notification = new Notification ();
           // $notification->setCreatedBy($this->getUser()->getProfile());
            //$notification->setUser($post->getCreatedBy());
            //$notification->setPageRoute('flowber_group_homepage');
            //$notification->setPageId($post->getCircle()->getId());
            //$notification->setMessage("a aimé votre post dans ");
            //$notification->setPageName($post->getCircle()->getTitle());
            //$em->persist($notification);
        }

        // preparing response
        $view = new ResponseView();
                
        $likedAdded = $this->container->get('flowber_like.like')->addLikePost($post, $user->getProfile());
        
        if($likedAdded){
            $repsData = array("likeId" => $likedAdded);
            $view->setData($repsData)->setStatusCode(200); // ok
        }else{
            $repsData = array("message" => "Error Add Like");
            $view->setData($repsData)->setStatusCode(400); // error           
        }
        return $view;
    }
    
    /**
     * Delete Like action
     * @var integer $likeId
     * @View(statusCode=204)
     */
    public function deleteLikeAction($likeId){
        $like = $this->getDoctrine()->getRepository('FlowberLikeBundle:Likes')->find($likeId);
        
        if(is_object($like)){
            $currentCircle = $this->getUser()->getProfile();
            if($currentCircle == $like->getCreatedBy()){ // checking if allowed to delete post (by author)
                $em = $this->getDoctrine()->getManager();
                $em->remove($like);
                $em->flush();
            }
        }
    }
}
