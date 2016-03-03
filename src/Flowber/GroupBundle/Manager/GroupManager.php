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
        $group = $this->getGroup($group);
        
        $circleInfos= $this->cm->getCircleInfos($group); 
        
        //$groupInfo['participantsNumber'] = $this->getCircleRepository()->getParticipantsNumber($group);
        //$groupInfo['participantsNames'] = $this->getCircleRepository()->getParticipantsNames($group);
        $count=0;
        $circleInfos['categories']= new \Doctrine\Common\Collections\ArrayCollection();

        if ( count($group->getCategories()) == 0){
            $circleInfos['categories']='Aucune catégorie';
        } else {
            $count=0;
            foreach ($group->getCategories() as $category){
                $circleInfos['categories'].=$category->getTitle(); 
                $count++;
                if ($count<count($group->getCategories())){
                   $circleInfos['categories'].=', '; 
                }
            } 
        }
        //die(var_dump($groupInfo));
     
        return $circleInfos;
    }    
    
    public function getGroupRepository()
    {
        return $this->em->getRepository('FlowberGroupBundle:Groups');
    }  
    
}