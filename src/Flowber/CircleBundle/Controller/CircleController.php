<?php

namespace Flowber\CircleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CircleController extends Controller
{
    function getRequestPageAction (){
        $profile = $this->getUser()->getProfile();  
        
        $requestInfos = $this->container->get('flowber_circle.circle')->getRequestInfos($profile->getId());

        return $this->render('FlowberCircleBundle:Default:getRequest.html.twig', 
            array(
                'requests' => $requestInfos,
            )
        );
    }
}
