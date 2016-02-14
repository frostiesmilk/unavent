<?php

namespace Flowber\GroupBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;

class GroupManager extends BaseManager {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getGroup($id)
    {
        $group = $this->getGroupRepository()->find($id);
      
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        }   
        
        return $group;
    } 
    
    public function getProfilePicture($group)
    {
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        }   
        
        $group = $this->getGroup($group);
        $profilePicture = $group->getProfilePicture();
        
        if (empty($profilePicture)) {
            $profilePicture = 'assets/images/ProfileBundle/Default/profilePictureDefault.png';
        } else {
            $profilePicture = $profilePicture->getWebPath();
        }
        
        return $profilePicture;
    }   

    public function getCoverPicture($group)
    {
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        }   
        $group = $this->getGroup($group);
        $coverPicture = $group->getCoverPicture();
        
        if (empty($coverPicture)) {
            $coverPicture = 'assets/images/ProfileBundle/Default/coverPictureDefault.png';
        } else {
            $coverPicture = $coverPicture->getWebPath();
        }
        
        return $coverPicture;
    } 
    
    public function getCoverInfos($group)
    {
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        }   
        
        $coverInfos = new ArrayCollection();
        
        $coverInfos['title'] = $group->getTitle($group);
        $coverInfos['subtitle'] = $group->getSubtitle($group);
        $coverInfos['coverPicture'] = $this->getCoverPicture($group);
        $coverInfos['profilePicture'] = $this->getProfilePicture($group);
     
        return $coverInfos;
    }  
    
    public function getGroupInfos($group)
    {
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        } 
        $groupInfo = $this->getGroupRepository()->getInfosGroup($group);
        $groupInfo['coverPicture'] = $this->getCoverPicture($group);
        $groupInfo['profilePicture'] = $this->getProfilePicture($group); 
        //$groupInfo['participantsNumber'] = $this->getGroupRepository()->getParticipantsNumber($group);
        //$groupInfo['participantsNames'] = $this->getGroupRepository()->getParticipantsNames($group);
        $count=0;
        $groupInfo['categories']= new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($group->getCategories() as $category){
            $groupInfo['categories'][$count]=$category->getTitle(); 
            $count++;
        }
        //die(var_dump($groupInfo));
     
        return $groupInfo;
    }    

    public function getEventParticipantsNames($event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        } 
        $participantsNames = $this->getEventRepository()->getParticipantsNames($event);
        die(var_dump($participantsNames));

        return $participantsNames;
    }   
    
    public function isParticipant($user, $event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        } 
        
        $isParticipant = $this->getEventRepository()->isParticipant($user, $event);
        if ($isParticipant != 0)
            return true;
        else return false;
     
        return $isParticipant;
    }  
 
    public function isCreator($user, $event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        } 
        $event = $this->getEvent($event);
        if($event->getCreatedBy() == $user)
            return true;
        else return false;
        
        return $isCreator;
    }  
 
    public function isAdmin($user, $event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event doesn\'t exist.');
        } 
        
        $isAdmin = $this->getEventRepository()->isAdmin($user, $event);
        if ($isAdmin != 0)
            return true;
        else return false;
     
        return $isAdmin;
    }  
    
    public function getGroupRepository()
    {
        return $this->em->getRepository('FlowberGroupBundle:Groups');
    }  
    
}