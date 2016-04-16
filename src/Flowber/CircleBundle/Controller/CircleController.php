<?php

namespace Flowber\CircleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CircleController extends Controller
{
    function getRequestPageAction (){
        $profile = $this->getUser()->getProfile();  
        
        $user=$this->getUser();  
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav; 
        
        $requestInfos = $this->container->get('flowber_circle.circle')->getRequestInfos($profile->getId());

        return $this->render('FlowberCircleBundle:Default:getRequest.html.twig', 
            array(
                'requests' => $requestInfos,
                'navbar' => $navbar                
            )
        );
    }
}
