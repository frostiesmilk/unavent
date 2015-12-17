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
        
        return $this->render('FlowberNotificationBundle:Default:getNotification.html.twig', 
                array(
                    'notifications' => $notifications,
                ));
    }

}
