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

    public function getEventInfos($eventId, $current)
    {
        if(empty($eventId)){
            return null;
        }
        
        $event = $this->getEvent($eventId);
        
        $circleInfos= $this->cm->getCircleInfos($event); 
        
        if ($event->getEndDate() != null){
            $circleInfos['endDate'] = 'Au ' . $event->getEndDate()->format('d/m/Y');
            if(!empty($event->getEndTime())){
                $circleInfos['endDate'].= ' à ' . $event->getEndTime()->format('H:i:s');  
            }
                      
            $circleInfos['startDate'] ='Du ';           
        } else {
            $circleInfos['startDate'] = 'Le ';            
            $circleInfos['endDate'] = '';
        }
        
        $circleInfos['startDate'] .= $event->getStartDate()->format('d/m/Y');
        if(!empty($event->getStartTime())){
            $circleInfos['startDate'] .= ' à ' . $event->getStartTime()->format('H:i:s'); 
        }        
        
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
        
        $circleInfos['id'] = $event->getId();
        $circleInfos['members'] = $this->getEventRepository()->GetCountMembers($event->getId());
        $circleInfos['friends'] = $this->getFriendsInEvent($event->getId(), $current);
        if (count($event->getPostalAddress()) != 0){
            $circleInfos['address'] = $event->getPostalAddress()->getAddress();
            $circleInfos['name'] = $event->getPostalAddress()->getName();
            $circleInfos['zipcode'] = $event->getPostalAddress()->getZipcode();
            $circleInfos['city'] = $event->getPostalAddress()->getCity();
            $circleInfos['country'] = $event->getPostalAddress()->getCountry();
            $circleInfos['coordinates'] = $event->getPostalAddress()->getCoordinates();
        }
        else {
            $circleInfos['address'] = '';
            $circleInfos['name'] = '';
            $circleInfos['zipcode'] = '';
            $circleInfos['city'] = '';
            $circleInfos['country'] = '';
            $circleInfos['coordinates'] = '';            
        }
        
        return $circleInfos;
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
    
    public function getEventMembers($circleId)
    {
        $members = $this->getEventRepository()->GetMembers($circleId);

        return $members;
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
    
    public function getEventsFromList($events, $currentCircleId)
    {
        if ( count($events) != 0){
            $count=0;
            foreach ($events as $eventss){
                $event = $this->getEvent($events[$count]['id']);
                $events[$count]['title'] = $event->getTitle();
                if (count($event->getPostalAddress()) != 0){
                    $events[$count]['city'] = $event->getPostalAddress()->getCity();
                }
                else {
                    $events[$count]['city'] = '';        
                }
                $events[$count]['profilePicture'] = $this->cm->getProfilePicture($events[$count]['id']);
                $events[$count]['startDate'] = 'Le' . $event->getStartDate()->format('d/m/Y');
                
                if(empty($event->getStartTime())){
                    $events[$count]['startTime'] = "";
                }else{
                    $events[$count]['startTime'] = ' à ' . $event->getStartTime()->format('H:i:s'); 
                }
                
                $events[$count]['subtitle'] = substr($event->getSubtitle(), 0, 75).'...';
                $events[$count]['members'] = $this->getEventRepository()->GetCountMembers($events[$count]['id']);
                $events[$count]['friends'] = $this->getFriendsInEvent($events[$count]['id'], $currentCircleId);
                $events[$count]['role'] = $this->cm->getRoleCircle($this->cm->getCircle($currentCircleId), $events[$count]['id']);
                $count++;
            } 
        }        
        return $events;
    }   
    
    public function getEvents($circleId, $current){
        
        $events = $this->getEventRepository()->GetEventsId($circleId);
        
        return $this->getEventsFromList($events, $current);
    }

    public function getPostEvent($postId, $currentUser){
        
    }
    
    public function getEventsNavbar($circleId)
    {
        $events = $this->getEventRepository()->GetFourEventsId($circleId);
        $count=0;
        foreach ($events as $eventss){
            $event = $this->getEvent($events[$count]['id']);
            $events[$count]['title'] = $event->getTitle();
            $events[$count]['profilePicture'] = $this->cm->getProfilePicture($events[$count]['id']);
            $count++;
        } 
      
        return $events;
    }   
    
    public function getAllEvents($current)
    {
        $events = $this->getEventRepository()->GetAllEventsId();
        $count=0;
        foreach ($events as $eventss){
            $event = $this->getEvent($events[$count]['id']);
            $events[$count]['title'] = $event->getTitle();
            if (count($event->getPostalAddress()) != 0){
                $events[$count]['city'] = $event->getPostalAddress()->getCity();
            }
            else {
                $events[$count]['city'] = '';        
            }            
            $events[$count]['profilePicture'] = $this->cm->getProfilePicture($events[$count]['id']);
            $events[$count]['startDate'] = 'Le' . $event->getStartDate()->format('d/m/Y');
            $events[$count]['startHour'] = ' à ' . $event->getStartTime()->format('H:i:s'); 
            $events[$count]['subtitle'] = substr($event->getSubtitle(), 0, 75).'...';
            $events[$count]['members'] = $this->getEventRepository()->GetCountMembers($events[$count]['id']);
            $events[$count]['friends'] = $this->getFriendsInEvent($events[$count]['id'], $current);
            $events[$count]['role'] = $this->cm->getRoleCircle($this->cm->getCircle($current), $events[$count]['id']);
            $count++;
        } 

        return $events;
    }
    
    public function getEventRepository()
    {
        return $this->em->getRepository('FlowberEventBundle:Event');
    }  
    
}
