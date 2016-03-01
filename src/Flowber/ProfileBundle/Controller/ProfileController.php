<?php

namespace Flowber\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Flowber\ProfileBundle\Form\ProfileType;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GalleryBundle\Form\ProfilePictureType;
use Flowber\UserBundle\Form\EditUserType;

class ProfileController extends Controller
{
    /**
     * Main profile edit form
     * @return type
     * @throws AccessDeniedException
     */
    public function getEditProfileAction() {
        $user = $this->getUser();        
        $error = false; // detect error while processing forms
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        // preparing profile to be edited
        $profile = $this->getDoctrine()->getManager()->getRepository('FlowberProfileBundle:Profile')->findOneByUser($user);
        $profileForm = $this->createForm(new ProfileType, $profile);
        
        //preparing eventual new profile picture
        $profilePicture = new Photo();
        $profilePictureForm = $this->createForm(new ProfilePictureType, $profilePicture);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPictureForm = $this->createForm(new CoverPictureType, $coverPicture);
     
        
        $userForm = $this->createForm(new EditUserType, $user);
        
        $request = $this->get('request');
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            // retrieving all forms
            $profileForm->handleRequest($request);
            $userForm->handleRequest($request);
            $profilePictureForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);
            
            $em = $this->getDoctrine()->getManager();
            
            // processing profile edit
            if (!$profileForm->isValid()) {
                $error = true;
            }
            
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){                    
                    $profilePicture->addGallery($profile->getProfileGallery());
                    $profile->setProfilePicture($profilePicture);
                    
                    $em->persist($profilePicture);
                }
            }else{
                $error = true;
            }   
            
            // processing cover picture form
            if($coverPictureForm->isValid()){
                // cover picture was submitted
                if($coverPicture->getFile() !== null){
                    $coverPicture->addGallery($profile->getCoverGallery());
                    $profile->setCoverPicture($coverPicture);
                    $em->persist($coverPicture);
                }
            }else{
                $error = true;
            }   
            
            // no error
            if(!$error){ 
                $em->persist($profile); // very important
                $em->flush();
//                $em->persist($profile);
//                $em->flush();
                // all good, back to profile page
                return $this->redirect($this->generateUrl('flowber_profile_current_user'));
            }
        }
  
        return $this->render('FlowberProfileBundle:Default:editProfile.html.twig', 
                array('user' => $this->container->get('flowber_profile.profile')->getCoverInfos($user),
                    'profileForm' => $profileForm->createView(),
                    'userForm' => $userForm->createView(),
                    'profilePictureForm'=>$profilePictureForm->createView(),
                    'coverPictureForm'=>$coverPictureForm->createView()));
    }
    
    /**
     * Get logged user profile
     * @return type
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function getCurrentUserProfileAction() {
        $user = $this->getUser();
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        $profile = $this->container->get('flowber_profile.profile')->getProfileInfos($user);
        $friends = $this->container->get('flowber_profile.profile')->getFriendsResume($user);

        if (empty($profile)) {
            throw new NotFoundHttpException("Le profil de l'utilisateur ".$profile->getUser()->getFirstname()." n'existe pas.");
        }    

        return $this->render('FlowberProfileBundle:Default:currentUserProfile.html.twig', 
                array(
                    'circle' => $profile,
                    'friends' => $friends,
                ));
    }
    
    
    public function getUserProfileAction($id) {
        $currentUser = $this->getUser();
        $user = $this->container->get('flowber_profile.profile')->getUser($id);        
        
        // Si on veut afficher son profil
        if ($currentUser == $user){
            return $this->redirect($this->generateUrl('flowber_profile_current_user'));
        }
        
        $profile = $this->container->get('flowber_profile.profile')->getProfileInfos($user);
        $friends = $this->container->get('flowber_profile.profile')->getFriendsResume($user);
        
        $friendshipReposit = $this->container->get('flowber_profile.profile')->getFriendshipRepository(); 
        
        $isFriend = $friendshipReposit->isFriendWithMe($user, $this->getUser());
        $requestFriend = $friendshipReposit->hasSentAFriendRequest($user, $this->getUser());
        
        if ($requestFriend != 0){
            $this->addFlash( 'addFriend',$user->getFirstName()." ". $user->getSurName()." a envoyé une demande d'ami.");
        } 
        if ($friendshipReposit->hasSentAFriendRequest($this->getUser(), $user) != 0){
            $this->addFlash('success', "Votre demande d'ami à ". $user->getFirstName()." ". $user->getSurName()." a bien été envoyée.");
            $requestFriend=-1;
        }
//      $notifications = $this->container->get('flowber_notification.notification')->getNotification($this->getDoctrine(), $this); 
        
        $privateMessage = new PrivateMessage;
        $privateMessageForm = $this->createForm(new PrivateMessageType, $privateMessage);

        // END IF A PRIVATE MESSAGE HAS BEEN SENT 

        return $this->render('FlowberProfileBundle:Default:userProfile.html.twig', 
                array(
                    'circle' => $profile,
                    'messageForm' => $privateMessageForm->createView(),
                    'isFriend' => $isFriend,
                    'sendRequest' => $requestFriend,
                    'friends' => $friends,
                ));
    }
}
