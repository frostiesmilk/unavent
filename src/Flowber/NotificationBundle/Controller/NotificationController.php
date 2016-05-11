<?php

namespace Flowber\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends Controller
{
    public function getNotificationAction()
    {
        $user=$this->getUser()->getProfile();  

        $notifications = $this->doctrine->getManager()
                ->getRepository('FlowberNotificationBundle:Notification')
                ->findByUser($user, array('creationDate' => 'desc'));  
        
        die(var_dump($notifications));
                
        
        foreach($notifications as $notification){
            $notification->setStatut('2');
        }
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;    
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($user->getProfile()->getId());  
        
        return $this->render('FlowberNotificationBundle:Default:getNotification.html.twig', 
                array(
                    'notifications' => $notifications,
                    'navbar' => $navbar
                ));
    }

}
