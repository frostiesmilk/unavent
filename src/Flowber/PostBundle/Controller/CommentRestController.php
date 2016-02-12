<?php

namespace Flowber\PostBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentRestController extends Controller
{   
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
