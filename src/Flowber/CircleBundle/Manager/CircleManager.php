<?php

namespace Flowber\CircleBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// Entities
use Flowber\CircleBundle\Entity\Circle;

class CircleManager extends BaseManager {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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
    
    public function getRequest($requestId)
    {
       $request = $this->getRequestRepository()->find($requestId);

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
    
    public function isCreator($user, $circle)
    {
        // Si on a envoyé un id, renvoyer un circle
        if (is_numeric($circle)){
            $circle = $this->getCircle($circle);
        }
        //Vérifie si le circle existe bien.
        if (!is_object($circle)) {
            throw new AccessDeniedException('This circle is not defined.');
        } 
        
        if($circle->getCreatedBy()->getId() == $user->getProfile()->getId())
            return true;
        else return false;
        
        return $isCreator;
    } 
    
    public function getRole($user, $circle)
    {
        if ($this->isCreator($user, $circle) == true){
            return "creator";
        } else if ($this->getCircleRepository()->ismember($user->getProfile()->getId(), $circle) == 1){
            return "member";
        } else if ($this->getCircleRepository()->hasSentRequest($user->getProfile(), $circle) == 1){
            return "requestSent";
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
}