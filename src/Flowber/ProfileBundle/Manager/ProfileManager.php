<?php

namespace Flowber\ProfileBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;

class ProfileManager extends BaseManager {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getUser($id)
    {
        $user = $this->getUserRepository()->find($id);
      
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        return $user;
    } 

    public function getUserName($user)
    {
        return $user->getFirstname().' '.$user->getSurname();
    } 
    
    public function getProfile($user)
    {
        $profile = $this->getProfileRepository()->findOneByUser($user);
        
        if (empty($profile)) {
            throw new NotFoundHttpException("Le profil de l'utilisateur".$profile->getUser()->getFirstname()." n'existe pas.");
        } 
        
        return $profile;
    }
    
    public function getProfilePicture($user)
    {
        $profile = $this->getProfile($user);
        $profilePicture = $profile->getProfilePicture();
        
        if (empty($profilePicture)) {
            $profilePicture = 'assets/images/ProfileBundle/Default/profilePictureDefault.png';
        } else {
            $profilePicture = $profilePicture->getWebPath();
        }
        
        return $profilePicture;
    }   

    public function getCoverPicture($user)
    {
        $profile = $this->getProfile($user);
        $coverPicture = $profile->getCoverPicture();
        
        if (empty($coverPicture)) {
            $coverPicture = 'assets/images/ProfileBundle/Default/coverPictureDefault.png';
        } else {
            $coverPicture = $coverPicture->getWebPath();
        }
        
        return $coverPicture;
    } 
    
    public function getCoverInfos($user)
    {
        $coverInfos = new ArrayCollection();
        
        $coverInfos['subtitle'] = $this->getProfileRepository()->getSubtitle($user);
        $coverInfos['coverPicture'] = $this->getCoverPicture($user);
        $coverInfos['profilePicture'] = $this->getProfilePicture($user);
     
        return $coverInfos;
    }   

    public function getProfileInfos($user)
    {
        $postalAdress = $user->getMainPostalAddress();
        $profileInfos = $this->getProfileRepository()->getProfileInfos($user);      
        $profileInfos['coverPicture'] = $this->getCoverPicture($user);
        $profileInfos['profilePicture'] = $this->getProfilePicture($user);
        $profileInfos['city'] = $postalAdress->getCity();
        $profileInfos['zipcode'] = $postalAdress->getZipcode();
        $profileInfos['hobbies'] = $user->getProfile($user)->getHobbies();

        return $profileInfos;
    } 
    
    public function getFriendsResume($user)
    {
        $friendsRepository = $this->getFriendshipRepository();
        $friends = $friendsRepository->getFriendsForProfile($user);
        $count=0;
        foreach ($friends as $friend){
            $friends[$count]['friendNumber']=$friendsRepository->getFriendsNumber($friends[$count]['id']); 
            $friends[$count]['profilePicture']=$this->getProfilePicture($friends[$count]['id']); 
            $count++;
//            $friendsRepository->getFriendsNumber($this->getUser($friend['id']));
            
        }
        
        return $friends;
    } 
    
    public function getFriendshipRepository()
    {
        return $this->em->getRepository('FlowberUserBundle:Friendship');
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