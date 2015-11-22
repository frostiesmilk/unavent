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

class DefaultController extends Controller
{
    public function getEditProfileAction()
    {
        $user = $this->getUser();
        $profile = $this->getDoctrine()->getManager()->getRepository('FlowberProfileBundle:Profile')->findOneByUser($user);

        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        $profileForm = $this->createForm(new ProfileType, $profile);
        
        $request = $this->get('request');
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $profileForm->handleRequest($request);
            
            if ($profileForm->isValid()) {
                
                // processing cover picture
                $coverPictureData = $request->files;//->get('flowber_gallerybundle_photo');
                die(var_dump($coverPictureData));
                $uploadedCoverPicture = $coverPictureData['file'];//$request->get('coverPicture');
                if(!is_file($uploadedCoverPicture)){
                    die("no picture to be uploaded");
                }else{
                    $profile->getCoverPicture()->addGallery($profile->getCoverGallery());
                }
//                    $profile->getCoverPicture()->upload();
//                    $profile->getCoverPicture()->addGallery($profile->getCoverGallery());
//                }
                
                $em = $this->getDoctrine()->getManager();
                
                $em->persist($profile);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_current_user_profile'));
          }
        }
  
        return $this->render('FlowberProfileBundle:Default:profileEdit.html.twig', array('profileForm' => $profileForm->createView()));
    }
    
    public function getCurrentUserProfileAction()
    {
        $user = $this->getUser();
      
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   

        $profile = $this->getDoctrine()->getManager()->getRepository('FlowberProfileBundle:Profile')->findOneByUser($user);
        
        if (empty($profile)) {
            throw new NotFoundHttpException("Le profil de l'utilisateur".$profile->getUser()->getFirstname()." n'existe pas.");
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
