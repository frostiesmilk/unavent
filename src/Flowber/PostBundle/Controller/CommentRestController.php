<?php

namespace Flowber\PostBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as ResponseView;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

class CommentRestController extends Controller
{   
    /**
     * Create new comment
     * @param Request $request
     * @param int $postId
     * @return View
     */
    public function postCommentAction(Request $request, $postId){        
        $post = $this->getDoctrine()->getManager()->getRepository('FlowberPostBundle:Post')->find($postId);
        
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        $form->bind($request);
        
        $view = new ResponseView();// preparing response
        
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            
            try{
                
                $comment->setPost($post);
                $comment->setCreatedBy($this->getUser());
                
//                if ($post->getCreatedBy() != $this->getUser()){
//                    $notification = new Notification ();
//                    $notification->setCreatedBy($this->getUser());
//                    $notification->setUser($post->getCreatedBy());
//                    $notification->setPageRoute('flowber_group_homepage');
//                    $notification->setPageId($post->getGroups()->getId());
//                    $notification->setMessage("a commenté votre post \""
//                            . $post->getMessage()
//                            . "\" dans ");
//                    $notification->setPageName($post->getGroups()->getTitle());
//                    $em->persist($notification);                
//                }
                
                $em->persist($comment);
                $em->flush();
                
            } catch (Exception $ex) {
                $repsData = array("message" => "Comment flush failed");
                $view->setData($repsData)->setStatusCode(400); // ok
                return $view;
            }
            
            $author = $comment->getCreatedBy();
            $authorInfo = array("id"        => $author->getId(),
                                "firstname" => $author->getFirstname(),
                                "surname"   => $author->getSurname());
            
            $repsData = array("commentId" => $comment->getId(), "datetimeCreated"=> $comment->getCreationDate(), "commentMessage"=>$comment->getMessage() , "author" => $authorInfo);
            $view->setData($repsData)->setStatusCode(200); // ok
            
            return $view;
        }
        
        $repsData = array('form' => $form);
        
        return $view->setDate($repsData)->setStatusCode(400); // ok
    }
    
    /**
     * Delete comment action
     * @var integer $commentId
     * @View(statusCode=204)
     */
    public function deleteCommentAction($commentId){
        $comment = $this->getDoctrine()->getRepository('FlowberPostBundle:Comment')->find($commentId);
        
        if(is_object($comment)){
            $currentCurrent = $this->getUser();
            if($currentCurrent == $comment->getCreatedBy()){ // checking if allowed to delete comment (by author)
                $comment->setDeleted();
                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();
            }
        }
    }
}
