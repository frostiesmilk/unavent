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
    
    /*
     * Récupère le nombre d'ami du cercle $id qui sont membre du groupe $circleId
     */
    public function getFriendsInGroup($circleId, $id)
    {
        $friends = $this->getGroupRepository()->GetFriendsId($id);
        $members = $this->getGroupRepository()->GetMembers($circleId);
       
        $nbFriend=0;
        if ( count($members) != 0){
            $count=0;
            foreach ($members as $member){
                if ( count($friends) != 0){
                    $count2=0;
                    foreach ($friends as $friend){
                        if ($friend==$member){
                            $nbFriend++;
                        }
                        $count2++;
                    } 
                }        
                $count++;
            } 
        }        

        return $nbFriend;
    } 
    
    public function getGroups($circleId)
    {
        $groups = $this->getGroupRepository()->GetGroupsId($circleId);
        if ( count($groups) != 0){
            $count=0;
            foreach ($groups as $group){
                $groups[$count]['title'] = $this->cm->getCircle($groups[$count]['id'])->getTitle();
                $groups[$count]['profilePicture'] = $this->cm->getProfilePicture($groups[$count]['id']);
                 $subtitle = substr($this->cm->getCircle($groups[$count]['id'])->getSubtitle(), 0, 75).'...';                
                $groups[$count]['subtitle'] = $subtitle;
                $groups[$count]['members'] = $this->getGroupRepository()->GetCountMembers($groups[$count]['id']);
                $groups[$count]['friends'] = $this->getFriendsInGroup($groups[$count]['id'], $circleId);
                $count++;
            } 
        }        

        return $groups;
    }

    public function getGroupsNavbar($circleId)
    {
        $groups = $this->getGroupRepository()->GetFourGroupsId($circleId);
        $count=0;
        foreach ($groups as $group){
            $groups[$count]['title'] = $this->cm->getCircle($groups[$count]['id'])->getTitle();
            $groups[$count]['profilePicture'] = $this->cm->getProfilePicture($groups[$count]['id']);
            $count++;
        }   

        return $groups;
    }
    
    public function getGroupRepository()
    {
        return $this->em->getRepository('FlowberGroupBundle:Groups');
    }  
    
}