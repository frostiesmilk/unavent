<?php

namespace Flowber\GroupBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
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
    
    /**
     * Delete group action
     * @param type $groupId
     * @return type
     */
    public function deleteGroupAction($groupId){
        $group = $this->getDoctrine()->getRepository('FlowberGroupBundle:Group')->find($groupId);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();
        
        return $this->view(null, Codes::HTTP_NO_CONTENT);
    }
}
