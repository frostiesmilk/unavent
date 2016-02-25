<?php

namespace Flowber\GroupBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\CircleBundle\Manager\CircleManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;

class GroupManager extends BaseManager {

    protected $em;
    protected $cm;

    public function __construct(EntityManager $em, CircleManager $cm)
    {
        $this->em = $em;
        $this->cm = $cm;
    }
    
    public function getGroup($id)
    {
        $group = $this->getGroupRepository()->find($id);
      
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        }   
        
        return $group;
    } 
    
    public function getGroupInfos($group)
    {
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        } 
        $groupInfo = $this->getGroupRepository()->getInfosGroup($group);
        $groupInfo['coverPicture'] = $this->cm->getCoverPicture($group);
        $groupInfo['profilePicture'] = $this->cm->getProfilePicture($group); 
        //$groupInfo['participantsNumber'] = $this->getCircleRepository()->getParticipantsNumber($group);
        //$groupInfo['participantsNames'] = $this->getCircleRepository()->getParticipantsNames($group);
        $count=0;
        $groupInfo['categories']= new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($group->getCategories() as $category){
            $groupInfo['categories'][$count]=$category->getTitle(); 
            $count++;
        }
        //die(var_dump($groupInfo));
     
        return $groupInfo;
    }    
    
    public function getGroupRepository()
    {
        return $this->em->getRepository('FlowberGroupBundle:Groups');
    }  
    
}