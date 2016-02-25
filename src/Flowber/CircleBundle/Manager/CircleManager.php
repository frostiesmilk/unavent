<?php

namespace Flowber\CircleBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $circleInfos['createdBy'] = $circle->getCreatedBy()->getFirstname() . ' ' . $circle->getCreatedBy()->getSurname();
        $circleInfos['idCreatedBy'] = $circle->getCreatedBy()->getId();
        $circleInfos['description'] = $circle->getDescription();
        $circleInfos['privacy'] = $circle->getPrivacy();
     
        return $circleInfos;
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
        
        if($circle->getCreatedBy() == $user)
            return true;
        else return false;
        
        return $isCreator;
    }    
    
    public function getCircleRepository()
    {
        return $this->em->getRepository('FlowberCircleBundle:Circle');
    }  
    
}