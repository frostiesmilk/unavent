<?php

namespace Flowber\PostBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as ResponseView;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\PostType;
//use Symfony\Component\HttpFoundation\JsonResponse;

use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\CommentType;

class PostRestController extends Controller
{
    
    /**
     * Create new post
     * @param Request $request
     * @param int $circleId
     * @return View|array
     */
    public function postPostAction(Request $request, $circleId){
        //$view = new View();// preparing response
        $circle = $this->getDoctrine()->getManager()->getRepository('FlowberFrontOfficeBundle:Circle')->find($circleId);
        
        if(!is_object($circle)){
            return array("status"=>400, "message"=>"Circle not found");//$view-($repsData)->setStatusCode(400); // error
        }
        
        $post = new Post();
        $form = $this->createForm(new PostType(), $post);
        $form->bind($request);
               
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            
            try{
                $post->setCreatedBy($this->getUser());
                $post->setCircle($circle);
//                
//                if ($circle->getCreatedBy() != $this->getUser()){
//                    $notification = new Notification ();
//                    $notification->setCreatedBy($this->getUser());
//                    $notification->setUser($circle->getCreatedBy());
//                    $notification->setPageRoute('flowber_group_homepage');
//                    $notification->setPageId($circle->getId());
//                    $notification->setMessage("a ajouté un post \""
//                            . $post->getMessage()
//                            . "\" dans ");
//                    $notification->setPageName($circle->getTitle());
//                    $em->persist($notification);         
//                }
                
                $em->persist($post);
                $em->flush();
                
            } catch (Exception $ex) {
                $repsData = array("status"=>"error" ,"message" => "Post flush failed");
                //$view->setData($repsData)->setStatusCode(400); // ok
                return $repsData;
            }
            
            // create new comment form
            $comment = new Comment();
            $commentForm = $this->createForm(new CommentType, $comment);
            
            // render view to be sent with response
            $commentFormView = $this->renderView('FlowberPostBundle:partials:commentForm.html.twig', 
                    array("commentForm"=>$commentForm->createView()));
            
            $repsData = array(  "status"=>"success", 
                                "postId" => $post->getId(), 
                                "postMessage" => $post->getMessage(),
                                "datetimeCreated"=> $post->getCreationDate(), 
                                "commentForm"=>$commentFormView);
            //$view->setData($repsData)->setStatusCode(200); // ok
            
            return $repsData;
        }
        
        $repsData = array("status"=>"error",'form' => $form);
        
        return $repsData;//$view->setDate($repsData)->setStatusCode(400); // error
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
     * @View
     */
    public function deletePostCustomAction($postId){
        $post = $this->getDoctrine()->getRepository('FlowberPostBundle:Post')->find($postId);
        $dude = "really ".$postId ;
        // preparing response
        $view = new ResponseView();
        
        if(!is_object($post)){ return "fuck off";
            $repsData = array("message" => "post not found");
            $view->setData($repsData)->setStatusCode(400); // error
            return $view;
        }
        $dude.= "it stinks";
        $currentUser = $this->getUser();
        if($currentUser == $post->getCreatedBy()){ // checking if allowed to delete post (by author)
            $post->setDeleted();
            $em = $this->getDoctrine()->getManager();
            $dude.= "better";
            try{
                $em->persist($post);
                $em->flush();
            } catch (Exception $ex) {
                $repsData = array("message" => "flush error");
                $view->setData($repsData)->setStatusCode(400); // error
                return $view;
            }
            $dude.="great";
        }
        
        return $dude;
    }
}
