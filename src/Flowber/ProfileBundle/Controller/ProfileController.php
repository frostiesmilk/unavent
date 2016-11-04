<?php

namespace Flowber\ProfileBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Flowber\ProfileBundle\Form\ProfileType;
use Flowber\GalleryBundle\Entity\Gallery;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GalleryBundle\Form\GalleryType;
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
        $profileCircle = $user->getProfile();
        $error = false; // detect error while processing forms
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        // preparing profile to be edited
        $profile = $this->container->get('flowber_profile.profile')->getProfile($circleId);
        $profileForm = $this->createForm(new ProfileType, $profile);
        
        //preparing eventual new profile picture
        $profilePicture = new Photo();
        $profilePicture->setCreatedBy($profileCircle);
        $profilePictureForm = $this->createForm(new ProfilePictureType, $profilePicture);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPicture->setCreatedBy($profileCircle);
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
                // all good, back to profile page
                return $this->redirect($this->generateUrl('api_get_circle', array('circleId' => $circleId)));
            }
        }

        return $this->render('FlowberProfileBundle:Default:editProfile.html.twig', 
                array('circle' => $this->container->get('flowber_profile.profile')->getProfileInfos($circleId),
                    'profileForm' => $profileForm->createView(),
                    'userForm' => $userForm->createView(),
                    'profilePictureForm'=>$profilePictureForm->createView(),
                    'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),
                    'coverPictureForm'=>$coverPictureForm->createView()
                ));
    }
    
    public function getUserProfileAction(Request $request, $circleId) 
    {
        // retrieve profile
        $circle = $this->getDoctrine()->getManager()->getRepository('FlowberCircleBundle:Circle')->find($circleId);
        //die(var_dump($circle));
        // for private messages
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        
        // for adding new gallery in profile
        $newGroupGallery = new Gallery();
        $newGalleryForm = $this->createForm(new GalleryType(), $newGroupGallery);
        
        // new Gallery request
        $newGalleryForm->handleRequest($request);        
        if($newGalleryForm->isValid()){
            $em = $this->getDoctrine()->getManager();
            $circle->addGallery($newGroupGallery);
            $newGroupGallery->setCreatedBy($this->getUser()->getProfile());
            $em->persist($circle);
            
            try{
                $em->flush();
            } catch (Exception $ex) {

            }
            
            $galleryId = $newGroupGallery->getId();
            
            return $this->redirect($this->generateUrl('flowber_profile_gallery', array('circleId' => $circleId, 'galleryId' => $galleryId)));
        }
        
        // Retrieve profile galleries
        $galleries = $this->container->get("flowber_gallery.gallery")->getGalleries($circle, $this->getUser()->getProfile());
        
        return $this->render('FlowberProfileBundle:Default:profile.html.twig', 
                array(
                    'circle' => $this->container->get('flowber_profile.profile')->getProfileInfos($circleId),
                    'friends' => $this->container->get('flowber_profile.profile')->getFriends($circleId),
                    'galleries' => $galleries,
                    'groups' => $this->container->get('flowber_group.group')->getGroups($circleId),
                    'events' => $this->container->get('flowber_event.event')->getEvents($circleId),
                    'navbar' => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),
                    'messageForm' => $privateMessageForm->createView(),
                    'newGalleryForm' => $newGalleryForm->createView(),
                ));
    }
    
    public function getProfileGalleryAction($circleId, $galleryId){
        //$privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        $user = $this->getUser();
        
        $newPhotosForm = $this->createFormBuilder()
                ->add('files','file',array(
                    "multiple" => "multiple",
                    "attr" => array(
                        "accept" => "image/*",                        
                    ),
                ))
                ->add('galleryId', 'hidden')
                ->add('circleId', 'hidden')
                ->getForm();
        
        $newPhotosForm->get("circleId")->setData($circleId);
        $newPhotosForm->get("galleryId")->setData($galleryId);
        
        $gallery = $this->getDoctrine()->getManager()->getRepository('FlowberGalleryBundle:Gallery')->find($galleryId);
        
        return $this->render('FlowberProfileBundle:Default:profileGallery.html.twig',
            array(
                'circle'  => $this->container->get('flowber_profile.profile')->getProfileInfos($circleId),
                //'messageForm'   => $privateMessageForm->createView(),
                'navbar'        => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
                'gallery'       => $gallery,
                'newPhotosForm' => $newPhotosForm->createView(),
                'galleryCanDelete' => $gallery->canDelete($user->getProfile()),
            )
        );
    }
    
    public function getCurrentUserProfileAction(){
        return $this->getUserProfileAction($this->getUser()->getId());
    }
    
    public function getSearchUserPageAction(Request $request){
        $user = $this->getUser();
        
        $navbar = $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos();
        
        $searchData = array();
        $searchForm = $this->createFormBuilder($searchData)
                                ->setMethod('GET')
                                ->add('keywords', 'text', array('required' => true))
                                ->getForm();
        
        $searchForm->handleRequest($request);
        $searchMode = false;
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchMode = true;
            $profileRepository = $this->getDoctrine()->getRepository('FlowberProfileBundle:Profile');
                  
            $searchData = $searchForm->getData();
            $selectProfiles = $profileRepository->getProfilesByTitleSearch($searchData['keywords'], $user->getProfile());
            
        }
        
        $friends = array();
        if($searchMode){ // if search mode
            $friends = $this->container->get('flowber_profile.profile')->getFriendsFromList($selectProfiles, $user->getProfile()->getId());
        }else{
            //$events = $this->container->get('flowber_event.event')->getAllEvents();
            
        }
        
        return $this->render('FlowberProfileBundle:Default:searchUserPage.html.twig',
            array(
                //'messageForm'   => $privateMessageForm->createView(),
                'searchMode'    => $searchMode,
                'navbar'        => $navbar,
                'friends'       => $friends,
                'searchForm'    => $searchForm->createView(),
            )
        );
    }
}
