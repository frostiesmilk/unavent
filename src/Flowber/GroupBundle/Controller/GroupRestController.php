<?php

namespace Flowber\GroupBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GroupRestController extends Controller
{
    /**
     * 
     * @param type $groupId
     * @return type
     * @throws type
     * 
     * @View(serializerGroups={"Default"})
     */
    public function getGroupAction($groupId){
        $group = $this->getDoctrine()->getRepository('FlowberGroupBundle:Groups')->find($groupId);
        if(!is_object($group)){
            throw $this->createNotFoundException();
        }
        //die(var_dump($group));
        return $group;
    }
}
