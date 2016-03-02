<?php

namespace Flowber\ProfileBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as ResponseView;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;


class ProfileRestController extends Controller
{   
    /**
     * 
     * @param type $profileId
     * @return type
     */
    function getProfileAction ($profileId){
        $circle = $this->container->get("flowber_circle.circle")->getCircle($profileId);
        $name = explode('\\', get_class($circle));
        $circleClassName = end($circleName);
        
        die(var_dump(end($circleClassName)));
    }
}
