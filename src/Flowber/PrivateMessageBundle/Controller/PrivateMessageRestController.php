<?php

namespace Flowber\PrivateMessageBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as ResponseView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

use Flowber\LikeBundle\Entity\Likes;
use Flowber\NotificationBundle\Entity\Notification;
use Flowber\PostBundle\Entity\Post;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;
use Flowber\PrivateMessageBundle\Form\PrivateMessageOnlyType;

class PrivateMessageRestController extends Controller
{   
    /**
     * 1send a private message
     * @var integer $postId
     * @View
     */
    public function postPrivateMessageAction(Request $request, $circleId){    
        $em = $this->getDoctrine()->getManager();
        $circle = $this->container->get('flowber_circle.circle')->getCircle($circleId);

        $mailToCreator = new PrivateMessage();
        $mailToCreatorForm = $this->createForm(new PrivateMessageOnlyType, $mailToCreator);    
        
        $mailToCreatorForm->handleRequest($request);

        // preparing response
        $view = new ResponseView();
        
        // mail to creator has been submitted
        if ($mailToCreatorForm->isValid()) {
            $user = $this->getUser();  
            $userTo = $circle->getCreatedBy();

            // setting subject, sender and destination
            $subject = "[".$circle->getTitle()."] : Nouveau message privé de ".$user->getFirstname()." ".$user->getSurname();
            $mailToCreator->setSubject($subject);
            $mailToCreator->setUserFrom($user);
            $mailToCreator->setUserTo($userTo);

            $em->persist($mailToCreator);
            $em->flush();

            try{ // flush
                $repsData = array("circleId" => $circle->getId());
                $view->setData($repsData)->setStatusCode(200); // ok
            } catch (Exception $ex) {
                $repsData = array("message" => "flush error");
                $view->setData($ex)->setStatusCode(400); // error
            }
        }else{ // bad form
            $repsData = array("message" => "form error");
            $view->setData($ex)->setStatusCode(400); // error
        }
        
        return $view;
    }
    
        /**
     * 1send a private message
     * @var integer $postId
     * @View
     */
    public function postPrivateMessageTitleAction(Request $request, $circleId){    
        $em = $this->getDoctrine()->getManager();
        $circle = $this->container->get('flowber_circle.circle')->getCircle($circleId);

        $mailToCreator = new PrivateMessage();
        $mailToCreatorForm = $this->createForm(new PrivateMessageType, $mailToCreator);    
        
        $mailToCreatorForm->handleRequest($request);

        // preparing response
        $view = new ResponseView();
        
        // mail to creator has been submitted
        if ($mailToCreatorForm->isValid()) {
            $user = $this->getUser();  
            $userTo = $circle->getCreatedBy();

            // setting subject, sender and destination
            $subject = "[".$circle->getTitle()."] : Nouveau message privé de ".$user->getFirstname()." ".$user->getSurname();
            $mailToCreator->setSubject($subject);
            $mailToCreator->setUserFrom($user);
            $mailToCreator->setUserTo($userTo);

            $em->persist($mailToCreator);
            $em->flush();

            try{ // flush
                $repsData = array("circleId" => $circle->getId());
                $view->setData($repsData)->setStatusCode(200); // ok
            } catch (Exception $ex) {
                $repsData = array("message" => "flush error");
                $view->setData($ex)->setStatusCode(400); // error
            }
        }else{ // bad form
            $repsData = array("message" => "form error");
            $view->setData($ex)->setStatusCode(400); // error
        }
        
        return $view;
    }
}
