<?php

namespace Flowber\CircleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CircleController extends Controller
{
    function getRequestPageAction (){        
        
        $navbar = $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos();
        $requestInfos = $this->container->get('flowber_circle.circle')->getCurrentUserRequestInfos();

        return $this->render('FlowberCircleBundle:Default:getRequest.html.twig', 
            array(
                'requests' => $requestInfos,
                'navbar' => $navbar                
            )
        );
    }
    
    function getNotificationPageAction (){
        
        $navbar = $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos();
        $requestInfos = $this->container->get('flowber_circle.circle')->getCurrentUserRequestInfos();

        return $this->render('FlowberCircleBundle:Default:getNotification.html.twig', 
            array(
                'notifications' => $requestInfos,
                'navbar' => $navbar                
            )
        );
    }
    
}
