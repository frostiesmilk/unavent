<?php

namespace Flowber\ProfileBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\CircleBundle\Manager\CircleManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Entities
use Flowber\UserBundle\Entity\User;

class ProfileManager extends BaseManager {

    protected $em;
    protected $cm;

    public function __construct(EntityManager $em, CircleManager $cm)
    {
        $this->em = $em;
        $this->cm = $cm;
   }
    
    public function getUser($profileId)
    {
        $profile = $this->getProfile($profileId);
      
        if (!is_object($profile)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        return $profile->getUSer();
    } 
 
    public function getUserName($user)
    {
        return $user->getFirstname().' '.$user->getSurname();
    } 
    
    public function getProfile($profileId)
    {
        $profile = $this->getProfileRepository()->find($profileId);

        if (empty($profile)) {
            throw new NotFoundHttpException("Le profil de l'utilisateur".$profile->getUser()->getFirstname()." n'existe pas.");
        } 
        
        return $profile;
    }
    
    public function getProfileInfos($profileId)
    {
        $profile = $this->getProfile($profileId);
        $user = $this->getUser($profileId);
        
        $profileInfos = $this->cm->getCoverInfos($profile);
        $profileInfos['title'] = $this->getUserName($user);
        $profileInfos['description'] = $profile->getDescription();
        $profileInfos['job'] = $profile->getJob();
        $profileInfos['creationDate'] = 'le ' . $profile->getCreationDate()->format('d/m/Y') . ' à ' . $profile->getCreationDate()->format('H:i:s');
        $profileInfos['idCreatedBy'] = $profile->getCreatedBy()->getId();
        $profileInfos['birthdate'] = $user->getBirthdate()->format('d/m/Y');
        $profileInfos['sex'] = $user->getSex();
        $profileInfos['id'] = $profile->getId();
        
        $postalAdress = $user->getMainPostalAddress($user);
        if (!is_object($postalAdress)){
            $profileInfos['city'] = '';
            $profileInfos['zipcode'] = '';           
        } else {
            $profileInfos['city'] = $postalAdress->getCity();
            $profileInfos['zipcode'] = $postalAdress->getZipcode();
        }
        $count = 0;
        $profileInfos['hobbies']=new ArrayCollection();
        if ( count($profile->getHobbies()) != 0){
            foreach ($profile->getHobbies() as $hobby){
                $profileInfos['hobbies'][$count]=new ArrayCollection();
                $profileInfos['hobbies'][$count]['title'] = $hobby->getCategory()->getTitle();
                $profileInfos['hobbies'][$count]['percent'] = $hobby->getPercent();
                $profileInfos['hobbies'][$count]['description'] = $hobby->getDescription();
                $count++;
            } 
        }
                
        return $profileInfos;
    } 
    
    public function getCurrentProfileInfos()
    {
        return $this->getProfileInfos($this->cm->getCurrentUserId());
    } 
    
    public function getFriends($profileId, $current)
    {
        $friends = $this->getProfileRepository()->GetFriendsId($profileId);
        if ( count($friends) != 0){
            $count=0;
            foreach ($friends as $friend){
                $friends[$count]['name'] = $this->getUserName($this->getUser($friends[$count]['id']));
                $friends[$count]['profilePicture'] = $this->cm->getProfilePicture($friends[$count]['id']);
                $subtitle = substr($this->getProfile($friends[$count]['id'])->getSubtitle(), 0, 75).'...';                
                $friends[$count]['subtitle'] = $subtitle;
                $friends[$count]['numberFriend'] = $this->getProfileRepository()->GetCountFriend($friends[$count]['id']);
                $friends[$count]['numberEvent'] = $this->getProfileRepository()->GetCountEvent($friends[$count]['id']);
                $friends[$count]['numberGroup'] = $this->getProfileRepository()->GetCountGroup($friends[$count]['id']);
                $friends[$count]['role'] = $this->cm->getRoleCircle($this->cm->getCircle($current), $friends[$count]['id']);
                $count++;
            } 
        }        
        
        return $friends;
    }
    
    public function getFriendsFromList($friends)
    {
        $current = $this->cm->getCurrentUserId();
        if ( count($friends) != 0){
            $count=0;
            foreach ($friends as $friend){
                $friends[$count]['name'] = $this->getUserName($this->getUser($friends[$count]['id']));
                $friends[$count]['profilePicture'] = $this->cm->getProfilePicture($friends[$count]['id']);
                $subtitle = substr($this->getProfile($friends[$count]['id'])->getSubtitle(), 0, 75).'...';                
                $friends[$count]['subtitle'] = $subtitle;
                $friends[$count]['numberFriend'] = $this->getProfileRepository()->GetCountFriend($friends[$count]['id']);
                $friends[$count]['numberEvent'] = $this->getProfileRepository()->GetCountEvent($friends[$count]['id']);
                $friends[$count]['numberGroup'] = $this->getProfileRepository()->GetCountGroup($friends[$count]['id']);
                $friends[$count]['role'] = $this->cm->getRoleCircle($this->cm->getCircle($current), $friends[$count]['id']);
                $count++;
            } 
        }        
        
        return $friends;
    }
        
    public function getProfileRepository()
    {
        return $this->em->getRepository('FlowberProfileBundle:Profile');
    }

    public function getUserRepository()
    {
        return $this->em->getRepository('FlowberUserBundle:User');
    }    
}
