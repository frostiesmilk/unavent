<?php

namespace Flowber\EventBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;

class EventManager extends BaseManager {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getEvent($id)
    {
        $event = $this->getEventRepository()->find($id);
      
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        }   
        
        return $event;
    } 
    
    public function getProfilePicture($event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        }   
        
        $event = $this->getEvent($event);
        $profilePicture = $event->getProfilePicture();
        
        if (empty($profilePicture)) {
            $profilePicture = 'assets/images/ProfileBundle/Default/profilePictureDefault.png';
        } else {
            $profilePicture = $profilePicture->getWebPath();
        }
        
        return $profilePicture;
    }   

    public function getCoverPicture($event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        }   
        $event = $this->getEvent($event);
        $coverPicture = $event->getCoverPicture();
        
        if (empty($coverPicture)) {
            $coverPicture = 'assets/images/ProfileBundle/Default/coverPictureDefault.png';
        } else {
            $coverPicture = $coverPicture->getWebPath();
        }
        
        return $coverPicture;
    } 
    
    public function getCoverInfos($event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        }   
        
        $coverInfos = new ArrayCollection();
        
        $coverInfos['title'] = $event->getTitle($event);
        $coverInfos['subtitle'] = $event->getSubtitle($event);
        $coverInfos['coverPicture'] = $this->getCoverPicture($event);
        $coverInfos['profilePicture'] = $this->getProfilePicture($event);
     
        return $coverInfos;
    }  
    
    public function getEventInfos($event)
    {
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        } 
        $eventInfo = $this->getEventRepository()->getInfosEvent($event);
        $eventInfo['coverPicture'] = $this->getCoverPicture($event);
        $eventInfo['profilePicture'] = $this->getProfilePicture($event);
        $eventInfo['participantsNumber'] = $this->getEventRepository()->getParticipantsNumber($event);
        $eventInfo['participantsNames'] = $this->getEventRepository()->getParticipantsNames($event);
        $count=0;
        $eventInfo['categories']= new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($event->getCategories() as $category){
            $eventInfo['categories'][$count]=$category->getTitle(); 
            $count++;
        }
        //die(var_dump($eventInfo));
     
        return $eventInfo;
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
    
    public function getEventRepository()
    {
        return $this->em->getRepository('FlowberEventBundle:Event');
    }  
    
}