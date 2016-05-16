<?php

namespace Flowber\CircleBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as ResponseView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Flowber\CircleBundle\Entity\Request as OneRequest;
use Flowber\CircleBundle\Entity\Subscribers;
use Flowber\CircleBundle\Entity\Notification;
use Flowber\CircleBundle\Entity\NotificationReceiver;

class CircleRestController extends Controller
{   
    /**
     * 
     * @param type $circleId
     *     
     */
    function getCircleAction ($circleId){
        $circleClassName = $this->container->get("flowber_circle.circle")->getClass($circleId);

        return $this->redirect($this->generateUrl('flowber_'.$circleClassName.'_homepage',  array('circleId' => $circleId )));
    }
    
    /**
     * 
     * @param type $circleId
     *     
     */
    function getCircleEditAction ($circleId){
        $circle = $this->container->get("flowber_circle.circle")->getCircle($circleId);
        $name = explode('\\', get_class($circle));
        $circleClassName = strtolower(end($name));
        
        return $this->redirect($this->generateUrl('flowber_'.$circleClassName.'_edit', array('circleId' => $circleId)));
    }
    
    /**
     * 
     * @param type $circleId
     *     
     */
    function postSendrequestAction ($circleId){
        $circle = $this->container->get("flowber_circle.circle")->getCircle($circleId);
        $request = new OneRequest();
        $request->addReceiver($circle->getCreatedBy());
        $request->setCircle($circle);
        $request->setSender($this->getUser()->getProfile());
        $request->setMessage("");
        $request->setRole("member");
        $request->setStatut("sent");
        $em = $this->getDoctrine()->getManager();
        $em->persist($request);
        $em->flush();
    }

        /**
     * 
     * @param type $circleId
     *     
     */
    function postDeleterequestAction ($circleId){
        $currentUser=$this->getUser()->getProfile()->getId();  

        $requestId = $this->container->get("flowber_circle.circle")->getRequestWithCircleUser($circleId, $currentUser);
        $request = $this->container->get("flowber_circle.circle")->getRequest($requestId['requestId']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($request);
        $em->flush();
    }
    
   /**
     * 
     * @param type $circleId
     *     
     */
    function postReadnotificationAction ($notificationId){
        $notification = $this->container->get("flowber_circle.circle")->getNotificationReceiver($notificationId);
        if ($notification->getStatut() == 'received'){
            $notification->setStatut('read');
        } else if ($notification->getStatut() == 'read'){
            $notification->setStatut('received');            
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($notification);
        $em->flush();
    } 
    
   /**
     * 
     * @param type $circleId
     *     
     */
    function postDeletenotificationAction ($notificationId){
        $notification = $this->container->get("flowber_circle.circle")->getNotificationReceiver($notificationId);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($notification);
        $em->flush();
    }    

   /**
     * 
     * @param type $circleId
     *     
     */
    function postAcceptrequestAction ($requestId){
        $request = $this->container->get("flowber_circle.circle")->getRequest($requestId);
         //die(var_dump($request));
        $subscriber = new Subscribers();
        $subscriber->setCircle($this->container->get("flowber_circle.circle")->getCircle($request->getCircle()));
        $subscriber->setSubscriber($this->container->get("flowber_circle.circle")->getCircle($request->getSender()));
        $subscriber->setRole($request->getRole());
        $subscriber->setStatut($this->container->get("flowber_circle.circle")->getClass($request->getCircle()));
        $subscriber->setMessage($request->getMessage());

        $em = $this->getDoctrine()->getManager();

        if ($this->container->get("flowber_circle.circle")->getClass($request->getCircle()) == "profile"){
        $subscriber2 = new Subscribers();
        $subscriber2->setSubscriber($this->container->get("flowber_circle.circle")->getCircle($request->getCircle()));
        $subscriber2->setCircle($this->container->get("flowber_circle.circle")->getCircle($request->getSender()));
        $subscriber2->setRole($request->getRole());
        $subscriber2->setMessage($request->getMessage());            
        $subscriber2->setStatut($this->container->get("flowber_circle.circle")->getClass($request->getCircle()));
        $em->persist($subscriber2);           
        } else if ($this->container->get("flowber_circle.circle")->getClass($request->getCircle()) == "profile"){
            $members = $this->container->get("flowber_event.event")->getEventMembers($request->getCircle()->getId());
            $this->container->get("flowber_circle.circle")->addNotificationList(
                $request->getSender(), 
                'participe à la sortie : ', 
                $request->getCircle()->getTitle(), 
                'api_circle', 
                $request->getCircle()->getId(),
                $members
            );
            $this->container->get("flowber_circle.circle")->addNotification(
                $this->getUser()->getProfile(),
                'a accepté la demande de participation à la sortie : ', 
                $request->getCircle()->getTitle(), 
                'api_circle', 
                $request->getCircle()->getId(),
                $request->getSender()
            );
        } else if ($this->container->get("flowber_circle.circle")->getClass($request->getCircle()) == "groups"){
            $this->container->get("flowber_circle.circle")->addNotification(
                $this->getUser()->getProfile(),
                'a accepté la demande de membre au groupe : ', 
                $request->getCircle()->getTitle(), 
                'api_circle', 
                $request->getCircle()->getId(),
                $request->getSender()
            );
        }
        
        $em->remove($request);
        $em->persist($subscriber);
        $em->flush();
    }  
    
    /**
     * 
     * @param type $circleId
     *     
     */
    function postRefuserequestAction ($requestId){
        $request = $this->container->get("flowber_circle.circle")->getRequest($requestId);
        $em = $this->getDoctrine()->getManager();
        
        $this->container->get("flowber_circle.circle")->addNotification(
            $this->getUser()->getProfile(), 
            'a refusé votre demande : ', 
            $request->getCircle()->getTitle(), 
            'api_circle', 
            $request->getCircle()->getId(),
            $request->getSender()
        );
        
        $em->remove($request);
        $em->flush();
    }       

        /**
     * 
     * @param type $circleId
     *     
     */
    function postUnsubscribeAction ($circleId){
        $currentUser=$this->getUser()->getProfile(); 

        $subscribtion = $this->container->get("flowber_circle.circle")->getSubscriptionWithCircleUser($circleId, $currentUser);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($subscribtion);
        $em->flush();
        
        if ($this->container->get("flowber_circle.circle")->getClass($circleId) == "profile"){
            $subscribtion2 = $this->container->get("flowber_circle.circle")->getSubscriptionWithCircleUser($currentUser, $circleId);
            $em->remove($subscribtion2);           
        } else if ($this->container->get("flowber_circle.circle")->getClass($circleId) == "event"){
            $members = $this->container->get("flowber_event.event")->getEventMembers($circleId);
            $this->container->get("flowber_circle.circle")->addNotificationList(
                $currentUser, 
                'ne participe plus à la sortie : ', 
                $this->container->get("flowber_circle.circle")->getTitle($circleId), 
                'api_circle', 
                $circleId,
                $members
            );     
        }
        
        $em->flush();
    }   
    
   
}
