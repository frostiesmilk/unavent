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
    public function getEditProfileAction($circleId) {
        $user = $this->getUser();        
        $error = false; // detect error while processing forms
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        // preparing profile to be edited
        $profile = $this->container->get('flowber_profile.profile')->getProfile($circleId);
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
                return $this->redirect($this->generateUrl('api_get_circle', array('circleId' => $circleId)));
            }
        }
  
        return $this->render('FlowberProfileBundle:Default:editProfile.html.twig', 
                array('circle' => $this->container->get('flowber_profile.profile')->getProfileInfos($circleId),
                    'profileForm' => $profileForm->createView(),
                    'userForm' => $userForm->createView(),
                    'role'=> 'no',
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
        //die(var_dump($profile));
        
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
    
    
    public function getUserProfileAction($circleId) {
        $currentUser = $this->getUser();
        $circleInfos = $this->container->get('flowber_profile.profile')->getProfileInfos($circleId);
        $circleUser = $this->container->get('flowber_profile.profile')->getUser($circleId);        
        $role = $this->container->get('flowber_circle.circle')->getRole($currentUser, $circleUser->getProfile()->getId());

        $privateMessage = new PrivateMessage;
        $privateMessageForm = $this->createForm(new PrivateMessageType, $privateMessage);

        // END IF A PRIVATE MESSAGE HAS BEEN SENT 

        return $this->render('FlowberProfileBundle:Default:profile.html.twig', 
                array(
                    'circle' => $circleInfos,
                    'role' => $role,
                    'messageForm' => $privateMessageForm->createView(),
                    'friends' => null,
                ));
    }
}
