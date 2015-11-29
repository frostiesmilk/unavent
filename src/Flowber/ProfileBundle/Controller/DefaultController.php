<?php

namespace Flowber\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Flowber\ProfileBundle\Entity\Profile;
use Flowber\ProfileBundle\Form\ProfileType;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\GalleryBundle\Form\PhotoType;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GalleryBundle\Form\ProfilePictureType;

class DefaultController extends Controller
{
    /**
     * Main profile edit form
     * @return type
     * @throws AccessDeniedException
     */
    public function getEditProfileAction()
    {
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
        
        $request = $this->get('request');
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            // retrieving all forms
            $profileForm->handleRequest($request);
            $profilePictureForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);
            
            // processing profile edit
            if ($profileForm->isValid()) {   
                $em = $this->getDoctrine()->getManager();
                
                $em->persist($profile);
                $em->flush();
            }else{
                $error = true;
            }
                     
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){
                    $em = $this->getDoctrine()->getManager();
                    $profilePicture->addGallery($profile->getProfileGallery());
                    $profile->setProfilePicture($profilePicture);
                    $em->persist($profile);
                    $em->persist($profilePicture);
                    $em->flush();
                }
            }else{
                $error = true;
            }   
            
            // processing cover picture form
            if($coverPictureForm->isValid()){
                // cover picture was submitted
                if($coverPicture->getFile() !== null){
                    $em = $this->getDoctrine()->getManager();
                    $coverPicture->addGallery($profile->getCoverGallery());
                    $profile->setCoverPicture($coverPicture);

                    $em->persist($coverPicture);
                    $em->persist($profile);
                    $em->flush();
                }
            }else{
                $error = true;
            }   
            
            // no error
            if(!$error){         
//                
//                $em->persist($profile);
//                $em->flush();
                // all good, back to profile page
                return $this->redirect($this->generateUrl('flowber_current_user_profile'));
            }
        }
  
        return $this->render('FlowberProfileBundle:Default:profileEditMain.html.twig', 
                array('profileForm' => $profileForm->createView(),
                    'profilePictureForm'=>$profilePictureForm->createView(),
                    'coverPictureForm'=>$coverPictureForm->createView()));
    }
    
    /**
     * Profile Cover Picture form
     * @return type
     * @throws AccessDeniedException
     */
    public function editProfileCoverPictureAction(){
        // retrieve user
        $user = $this->getUser();        

        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        // retrieve user profile
        $profile = $this->getDoctrine()->getManager()->getRepository('FlowberProfileBundle:Profile')->findOneByUser($user);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPictureForm = $this->createFormBuilder($coverPicture)
            ->add('file',           'file', array(
                    'required' => false,
                    'data_class' => null))
            //->add('save', 'submit', array('label' => 'Changer de photo de couverture'))
            ->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') { 
            $coverPictureForm->handleRequest($request);
        
            if($coverPictureForm->isValid()){
                $em = $this->getDoctrine()->getManager();
                $coverPicture->addGallery($profile->getCoverGallery());
                $profile->setCoverPicture($coverPicture);

                $em->persist($coverPicture);
                $em->persist($profile);
                $em->flush();
            }
            // back to profile page
            return $this->redirect($this->generateUrl('flowber_current_user_profile'));
        }
        
        // render form
        return $this->render('FlowberProfileBundle:Default:profileEditCoverPicture.html.twig', array('coverPictureForm' => $coverPictureForm->createView()));
    }
    
    /**
     * Get logged user profile
     * @return type
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function getCurrentUserProfileAction()
    {
        $user = $this->getUser();
      
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   

        $profile = $this->getDoctrine()->getManager()->getRepository('FlowberProfileBundle:Profile')->findOneByUser($user);
        
        if (empty($profile)) {
            throw new NotFoundHttpException("Le profil de l'utilisateur ".$profile->getUser()->getFirstname()." n'existe pas.");
        }            

        return $this->render('FlowberProfileBundle:Default:myProfile.html.twig', array('user' => $user, "profile" => $profile));
    }
    
    public function getUserProfileAction($id)
    {

        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);

      
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   

        $profile = $this->getDoctrine()->getManager()->getRepository('FlowberProfileBundle:Profile')->findOneByUser($user);
        
        if (empty($profile)) {
            throw new NotFoundHttpException("Le profil de l'utilisateur".$profile->getUser()->getFirstname()." n'existe pas.");
        }            
        
        $privateMessage = new PrivateMessage;
        $privateMessageForm = $this->createForm(new PrivateMessageType, $privateMessage);

        $request = $this->get('request');
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $privateMessageForm->handleRequest($request);
            
            if ($privateMessageForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $privateMessage->setUserFrom($this->getUser());
                $privateMessage->addUserTo($user);
                $em->persist($privateMessage);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_current_user_profile'));
            }
        }
          
        return $this->render('FlowberProfileBundle:Default:profile.html.twig', 
                array(
                    'user' => $user, 
                    'profile' => $profile,
                    'messageForm' => $privateMessageForm->createView()
                ));
    }
}
