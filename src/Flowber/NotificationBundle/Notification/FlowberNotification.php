<?php

// src/Flowber/NotificationBundle/Notification/FlowberNotification.php

namespace Flowber\NotificationBundle\Notification;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of FlowberNotification
 *
 * @author Marie
 */
class FlowberNotification {
    //put your code here
    protected $doctrine;
    protected $context;

    public function __construct (Registry $doctrine, SecurityContext $securityContext)
    {      
        $this->doctrine = $doctrine;
        $this->context = $securityContext;
    }
    
    public function getNotification(){        

    }
}
