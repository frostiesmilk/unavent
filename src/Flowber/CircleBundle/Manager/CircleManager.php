<?php

namespace Flowber\CircleBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Flowber\CircleBundle\Entity\Notification;
use Flowber\CircleBundle\Entity\NotificationReceiver;

// Entities
use Flowber\CircleBundle\Entity\Circle;

class CircleManager extends BaseManager {

    protected $em;
    protected $context;
    
    public function __construct(EntityManager $em, SecurityContext $securityContext)
    {
        $this->em = $em;
        $this->context = $securityContext;    
    }
    
    public function getCurrentUserId()
    {      
        return $this->context->getToken()->getUser()->getProfile()->getId();
    }
    
    public function getCircle($id)
    {
        $circle = $this->getCircleRepository()->find($id);
      
        if (!is_object($circle)) {
            throw new AccessDeniedException('This event is not defined.');
        }   
        
        return $circle;
    } 
     
    public function getClass($id)
    {
        $circle = $this->getCircleRepository()->find($id);

        if (!is_object($circle)) {
            throw new AccessDeniedException('This event is not defined.');
        }  
        
        $name = explode('\\', get_class($circle));
        $circleClassName = strtolower(end($name));  
        
        return $circleClassName;
    } 
    
    public function getProfilePicture($circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.        
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        }   
        
        $profilePicture = $circle->getProfilePicture();
        
        if (empty($profilePicture)) {
            $profilePicture = 'assets/images/ProfileBundle/Default/profilePictureDefault.png';
        } else {
            $profilePicture = $profilePicture->getWebPath();
        }
        
        return $profilePicture;
    } 
    
    public function getPrivacy($circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.        
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        }   
        
        $privacy = $circle->getPrivacy();
                
        return $privacy;
    }   
    
    public function getCoverPicture($circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.        
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        }   

        $coverPicture = $circle->getCoverPicture();
        
        if (empty($coverPicture)) {
            $coverPicture = 'assets/images/ProfileBundle/Default/coverPictureDefault.png';
        } else {
            $coverPicture = $coverPicture->getWebPath();
        }
        
        return $coverPicture;
    }
    
    public function getTitle($circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.        
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        }   

        $title = $circle->getTitle();
       
        return $title;
    }  
    
    public function getCreatedBy($circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.        
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        }   

        $createdBy = $circle->getCreatedBy();
       
        return $createdBy;
    }    
    public function getCoverInfos($circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.        
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        }   
        
        $coverInfos = new ArrayCollection();
        
        $coverInfos['title'] = $circle->getTitle();
        $coverInfos['id'] = $circle->getId();
        $coverInfos['subtitle'] = $circle->getSubtitle();
        $coverInfos['coverPicture'] = $this->getCoverPicture($circle);
        $coverInfos['profilePicture'] = $this->getProfilePicture($circle);
        $coverInfos['role'] = $this->getCurrentUserRole($circle);
     
        return $coverInfos;
    }   
    
    public function getCircleInfos($circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        } 
        
        $circleInfos = $this->getCoverInfos($circle);
        $circleInfos['creationDate'] = 'le ' . $circle->getCreationDate()->format('d/m/Y') . ' à ' . $circle->getCreationDate()->format('H:i:s');
        $circleInfos['createdBy'] = $circle->getCreatedBy()->getTitle();
        $circleInfos['idCreatedBy'] = $circle->getCreatedBy()->getId();
        $circleInfos['description'] = $circle->getDescription();
        $circleInfos['privacy'] = $circle->getPrivacy();
     
        return $circleInfos;
    }   
    
    public function getCountRequestInfos($circle)
    {
       $requestInfos = $this->getRequestRepository()->getCountInfosRequest($circle);
       
        return $requestInfos;
    }
    
    public function getCountNotification($circle)
    {
       $numberNotification = $this->getNotificationRepository()->getNumberNotifications($circle);
       //die(var_dump($numberNotification));
        return $numberNotification;
    }   
    
    public function addNotificationList($sender, $message, $pageName, $pageRoute, $pageId, $receivers){
        $notification = new Notification ();
        $notification->setSender($sender);
        $notification->setPageRoute($pageRoute);
        $notification->setPageId($pageId);
        $notification->setMessage($message);
        $notification->setPageName($pageName);
        foreach ($receivers as $receiver){
            var_dump($receiver['id']);
            $notifReceiver = new NotificationReceiver();
            $notifReceiver->setStatut('received');
            $notifReceiver->setReceiver($this->getCircle($receiver['id']));
            $notifReceiver->setNotification($notification);
            $this->em->persist($notifReceiver);
        }
        $this->em->persist($notification);
        $this->em->flush();        
    }

    public function addNotification($sender, $message, $pageName, $pageRoute, $pageId, $receiver){
        
        $notification = new Notification ();
        $notification->setSender($sender);
        $notification->setPageRoute($pageRoute);
        $notification->setPageId($pageId);
        $notification->setMessage($message);
        $notification->setPageName($pageName);
        $notifReceiver = new NotificationReceiver();
        $notifReceiver->setStatut('received');
        $notifReceiver->setReceiver($receiver);
        $notifReceiver->setNotification($notification);
        $this->em->persist($notification);
        $this->em->persist($notifReceiver);
        $this->em->flush();
    }
    
    public function getRequestInfos($circle)
    {
       $requestInfos = $this->getRequestRepository()->getInfosRequest($circle);
       
       $count=0;
        foreach ($requestInfos as $request ){
            $requestInfos[$count]['senderName']=$this->getTitle(intval($requestInfos[$count]['senderId']));
            $requestInfos[$count]['senderPic']=$this->getProfilePicture(intval($requestInfos[$count]['senderId']));
            $requestInfos[$count]['circleName']=$this->getTitle(intval($requestInfos[$count]['circleId']));
            $requestInfos[$count]['circleClass']=$this->getClass(intval($requestInfos[$count]['circleId']));
            $count++;
        }
        return $requestInfos;
    } 
    
    public function getCurrentUserRequestInfos()
    {
        return $this->getRequestInfos($this->getCurrentUserId());
    } 
    
    public function getNotificationsInfos($circle)
    {
       $notificationsInfos = $this->getNotificationRepository()->getInfosNotifications($circle);
       
       $count=0;
        foreach ($notificationsInfos as $notif ){
            $notificationsInfos[$count]['senderName']=$this->getTitle(intval($notif['senderId']));
            $notificationsInfos[$count]['senderPic']=$this->getProfilePicture(intval($notif['senderId']));
            $notificationsInfos[$count]['pagePic']=$this->getProfilePicture(intval($notif['pageId']));
            $count++;
        }
        
        return $notificationsInfos;
    } 
    
    public function getCurrentUserNotificationsInfos()
    {
        return $this->getNotificationsInfos($this->getCurrentUserId());
    }      
    
    public function getRequest($requestId)
    {
       $request = $this->getRequestRepository()->find($requestId);

        return $request;
    }  
    
    public function getNotificationReceiver($notifId)
    {
       $request = $this->em->getRepository('FlowberCircleBundle:NotificationReceiver')->find($notifId);

        return $request;
    }  
    
    public function getRequestWithCircleUser($circleId, $currentUser)
    {
       $request = $this->getRequestRepository()->getRequestWithCircleUser($circleId, $currentUser);

        return $request;
    }  
    
    public function getSubscriptionWithCircleUser($circleId, $currentUser)
    {
        $circle = $this->getCircle($circleId);    
        
        $request = $this->getRequestRepository()->getSubscriptionWithCircleUser($circle, $currentUser);

        return $request;
    }  
    
    public function isCreator($userId, $circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        } 
        
        if($circle->getCreatedBy()->getId() == $userId)
            return true;
        else return false;
        
        return $isCreator;
    } 
    
    public function isCreatorCircle($user, $circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        } 
        
        if($circle->getCreatedBy()->getId() == $user->getId())
            return true;
        else return false;
        
        return $isCreator;
    }    
    
    public function getRole($user, $circle)
    {
        if ($this->isCreator($user->getProfile()->getId(), $circle) == true){
            return "creator";
        } else if ($this->getCircleRepository()->ismember($user->getProfile()->getId(), $circle) == 1){
            return "member";
        } else if ($this->getCircleRepository()->hasSentRequest($user->getProfile(), $circle) == 1){
            return "requestSent";
        }
        if ($this->getPrivacy($circle)=="private"){
            return "cantsee";
        }
        return "no";
    }
    
    public function getCurrentUserRole($circle)
    {
        if ($this->isCreator($this->getCurrentUserId(), $circle) == true){
            return "creator";
        } else if ($this->getCircleRepository()->ismember($this->getCurrentUserId(), $circle->getId()) == 1){
            return "member";
        } else if ($this->getCircleRepository()->hasSentRequest($this->getCurrentUserId(), $circle->getId()) == 1){
            return "requestSent";
        }
        if ($this->getPrivacy($circle)=="private"){
            return "cantsee";
        }
        return "no";
    }   
    
    public function getRoleCircle($user, $circle)
    {
        if ($this->isCreatorCircle($user, $circle) == true){
            return "creator";
        } else if ($this->getCircleRepository()->ismember($user->getId(), $circle) == 1){
            return "member";
        } else if ($this->getCircleRepository()->hasSentRequest($user, $circle) == 1){
            return "requestSent";
        }
        if ($this->getPrivacy($circle)=="private"){
            return "cantsee";
        }
        return "no";
    }    
    
    public function getCircleRepository()
    {
        return $this->em->getRepository('FlowberCircleBundle:Circle');
    }  
     
    public function getRequestRepository()
    {
        return $this->em->getRepository('FlowberCircleBundle:Request');
    }   
    
    public function getNotificationRepository()
    {
        return $this->em->getRepository('FlowberCircleBundle:Notification');
    }        
}