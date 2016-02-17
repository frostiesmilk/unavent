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
    
    public function getCircle($id)
    {
        $group = $this->getCircleRepository()->find($id);
      
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        }   
        
        return $group;
    } 
    
    public function getProfilePicture($group)
    {
        $group = $this->getCircle($group);
        
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
        $group = $this->getCircle($group);
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
    
    public function getCircleInfos($group)
    {
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        } 
        $groupInfo = $this->getCircleRepository()->getInfosGroup($group);
        $groupInfo['coverPicture'] = $this->getCoverPicture($group);
        $groupInfo['profilePicture'] = $this->getProfilePicture($group); 
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
 
    public function isCreator($user, $group)
    {
        if (!is_object($group)) {
            throw new AccessDeniedException('This group is not defined.');
        } 
        $group = $this->getCircle($group);
        if($group->getCreatedBy() == $user)
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
    
    public function getCircleRepository()
    {
        return $this->em->getRepository('FlowberGroupBundle:Groups');
    }  
    
}