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
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($circleId);
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($circleId);
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav; 
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($profile->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($profile->getId());
        
        return $this->render('FlowberProfileBundle:Default:editProfile.html.twig', 
                array('circle' => $this->container->get('flowber_profile.profile')->getProfileInfos($circleId),
                    'profileForm' => $profileForm->createView(),
                    'userForm' => $userForm->createView(),
                    'role'=> 'no',
                    'profilePictureForm'=>$profilePictureForm->createView(),
                    'navbar' => $navbar,
                    'coverPictureForm'=>$coverPictureForm->createView()));
    }
    
    public function getUserProfileAction($circleId) {
        $currentUser = $this->getUser();
        $circleInfos = $this->container->get('flowber_profile.profile')->getProfileInfos($circleId);
        $friends = $this->container->get('flowber_profile.profile')->getFriends($circleId, $currentUser->getProfile()->getId());
        $groups = $this->container->get('flowber_group.group')->getGroups($circleId, $currentUser->getProfile()->getId());
        $events = $this->container->get('flowber_event.event')->getEvents($circleId, $currentUser->getProfile()->getId());
        $circleUser = $this->container->get('flowber_profile.profile')->getUser($circleId);        
        $privateMessage = new PrivateMessage;
        $privateMessageForm = $this->createForm(new PrivateMessageType, $privateMessage);
        $role = $this->container->get('flowber_circle.circle')->getRole($currentUser, $circleUser->getProfile()->getId());
        
        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($circleId);
        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($circleId);
        $navbar['event'] = $eventsNav;
        $navbar['group'] = $groupsNav;
        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($currentUser->getProfile()->getId());
        $navbar['messageNumber'] = $this->container->get('flowber_privateMessage.privateMessage')->getNumberMessageNotRead($currentUser->getProfile()->getId());
        
        // END IF A PRIVATE MESSAGE HAS BEEN SENT 

        return $this->render('FlowberProfileBundle:Default:profile.html.twig', 
                array(
                    'circle' => $circleInfos,
                    'role' => $role,
                    'messageForm' => $privateMessageForm->createView(),
                    'friends' => $friends,
                    'groups' => $groups,
                    'events' => $events,
                    'navbar' => $navbar
                ));
    }
}
