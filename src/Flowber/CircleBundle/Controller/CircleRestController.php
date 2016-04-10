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

class CircleRestController extends Controller
{   
    /**
     * 
     * @param type $circleId
     *     
     */
    function getCircleAction ($circleId){
        $circleClassName = $this->container->get("flowber_circle.circle")->getClass($circleId);

        
        return $this->redirect($this->generateUrl('flowber_'.$circleClassName.'_homepage', array('circleId' => $circleId)));
        die(var_dump($circleClassName));
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
        die(var_dump($circleClassName));
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
    function postAcceptrequestAction ($requestId){
        $request = $this->container->get("flowber_circle.circle")->getRequest($requestId);
        
        $subscriber = new Subscribers();
        $subscriber->setCircle($this->container->get("flowber_circle.circle")->getCircle($request->getCircle()));
        $subscriber->setSubscriber($this->container->get("flowber_circle.circle")->getCircle($request->getSender()));
        $subscriber->setRole($request->getRole());
        $subscriber->setMessage($request->getMessage());

        $em = $this->getDoctrine()->getManager();

        if ($this->container->get("flowber_circle.circle")->getClass($request->getCircle()) == "profile"){
        $subscriber2 = new Subscribers();
        $subscriber2->setSubscriber($this->container->get("flowber_circle.circle")->getCircle($request->getCircle()));
        $subscriber2->setCircle($this->container->get("flowber_circle.circle")->getCircle($request->getSender()));
        $subscriber2->setRole($request->getRole());
        $subscriber2->setMessage($request->getMessage());            
        $em->persist($subscriber2);           
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
        
        if ($this->container->get("flowber_circle.circle")->getClass($circleId) == "profile"){
            $subscribtion2 = $this->container->get("flowber_circle.circle")->getSubscriptionWithCircleUser($currentUser, $circleId);
            $em->remove($subscribtion2);           
        }
        
        $em->remove($subscribtion);
        $em->flush();
    }   
    
   
}
