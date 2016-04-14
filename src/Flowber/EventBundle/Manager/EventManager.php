<?php

namespace Flowber\EventBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Flowber\CircleBundle\Manager\CircleManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EventManager extends BaseManager {

    protected $em;
    protected $cm;

    public function __construct(EntityManager $em, CircleManager $cm)
    {
        $this->em = $em;
        $this->cm = $cm;
    }
    
    public function getEvent($id)
    {
        $event = $this->getEventRepository()->find($id);
       
        if (!is_object($event)) {
            throw new AccessDeniedException('This event is not defined.');
        }   
        
        return $event;
    } 

    public function getEventInfos($event)
    {
        $event = $this->getEvent($event);
        
        $circleInfos= $this->cm->getCircleInfos($event); 
        
        if ($event->getEndDate() != null){
            $circleInfos['endDate'] = 'Au ' . $event->getEndDate()->format('d/m/Y') . ' à ' . $event->getEndTime()->format('H:i:s');            
            $circleInfos['startDate'] ='Du ';           
        } else {
            $circleInfos['startDate'] = 'Le ';            
            $circleInfos['endDate'] = '';
        }
        $circleInfos['startDate'] .= $event->getStartDate()->format('d/m/Y') . ' à ' . $event->getStartTime()->format('H:i:s'); 
        
        if ( count($event->getCategories()) == 0){
            $circleInfos['categories']='Aucune catégorie';
        } else {
            $count=0;
            foreach ($event->getCategories() as $category){
                $circleInfos['categories'].=$category->getTitle(); 
                $count++;
                if ($count<count($event->getCategories())){
                   $circleInfos['categories'].=', '; 
                }
            } 
        }
        
        $circleInfos['address'] = $event->getPostalAddress()->getAddress();
        $circleInfos['name'] = $event->getPostalAddress()->getName();
        $circleInfos['zipcode'] = $event->getPostalAddress()->getZipcode();
        $circleInfos['city'] = $event->getPostalAddress()->getCity();
        $circleInfos['country'] = $event->getPostalAddress()->getCountry();
        $circleInfos['coordinates'] = $event->getPostalAddress()->getCoordinates();

        $circleInfos['participantsNumber'] = $this->getEventRepository()->getParticipantsNumber($event);
        $circleInfos['participantsNames'] = $this->getEventRepository()->getParticipantsNames($event);
             
        return $circleInfos;
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
        return $this->cm->isCreator($user, $event);
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
     public function getFriendsInEvent($circleId, $id)
    {
        $friends = $this->getEventRepository()->GetFriendsId($id);
        $members = $this->getEventRepository()->GetMembers($circleId);
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
    
    public function getEvents($circleId)
    {
        $events = $this->getEventRepository()->GetEventsId($circleId);
        if ( count($events) != 0){
            $count=0;
            foreach ($events as $eventss){
                $event = $this->getEvent($events[$count]['id']);
                $events[$count]['title'] = $event->getTitle();
                $events[$count]['city'] = $event->getPostalAddress()->getCity();
                $events[$count]['profilePicture'] = $this->cm->getProfilePicture($events[$count]['id']);
                $events[$count]['startDate'] = 'Le' . $event->getStartDate()->format('d/m/Y');
                $events[$count]['startHour'] = ' à ' . $event->getStartTime()->format('H:i:s'); 
                $events[$count]['subtitle'] = substr($event->getSubtitle(), 0, 75).'...';
                $events[$count]['members'] = $this->getEventRepository()->GetCountMembers($events[$count]['id']);
                $events[$count]['friends'] = $this->getFriendsInEvent($events[$count]['id'], $circleId);
                $count++;
            } 
        }        
        return $events;
    }   

    public function getEventsNavbar($circleId)
    {
        $events = $this->getEventRepository()->GetEventsId($circleId);
        $count=0;
        foreach ($events as $eventss){
            $event = $this->getEvent($events[$count]['id']);
            $events[$count]['title'] = $event->getTitle();
            $events[$count]['profilePicture'] = $this->cm->getProfilePicture($events[$count]['id']);
            $count++;
        } 
      
        return $events;
    }   
    
    
    public function getEventRepository()
    {
        return $this->em->getRepository('FlowberEventBundle:Event');
    }  
    
}