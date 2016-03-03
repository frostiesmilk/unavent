<?php

namespace Flowber\CircleBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as ResponseView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

use Flowber\LikeBundle\Entity\Likes;
use Flowber\NotificationBundle\Entity\Notification;
use Flowber\PostBundle\Entity\Post;

class CircleRestController extends Controller
{   
    /**
     * 
     * @param type $circleId
     *     
     */
    function getCircleAction ($circleId){
        $circle = $this->container->get("flowber_circle.circle")->getCircle($circleId);
        $name = explode('\\', get_class($circle));
        $circleClassName = strtolower(end($name));
        
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
}
