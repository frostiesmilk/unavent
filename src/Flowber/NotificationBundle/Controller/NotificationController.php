<?php

namespace Flowber\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends Controller
{
    public function getNotificationAction()
    {
        $notifications = $this->container->get('flowber_notification.notification')->getNotification($this->getDoctrine(), $this); 
        
        foreach($notifications as $notification){
            $notification->setStatut('2');
        }
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;    
        
        return $this->render('FlowberNotificationBundle:Default:getNotification.html.twig', 
                array(
                    'notifications' => $notifications,
                    'navbar' => $navbar
                ));
    }

}
