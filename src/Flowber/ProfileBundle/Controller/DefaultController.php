<?php

namespace Flowber\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Flowber\ProfileBundle\Entity\Profile;
use Flowber\ProfileBundle\Form\ProfileType;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\GalleryBundle\Form\PhotoType;

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
        
        $coverGalleryForm = $this->createFormBuilder($profile->getCoverGallery())
                ->add("photos", new PhotoType(), array("required"=>false))
                ->getForm();
        
        
        $request = $this->get('request');
        // if form has been submitted
        if ($request->getMethod() == 'POST') { die("no picture to be uploaded");
            $profileForm->bind($request);
            
            if ($profileForm->isValid()) {
                
                // processing cover picture
//                $uploadedCoverPicture = $request->get('coverPicture');
//                if(isset($uploadedCoverPicture)){
//                    die("no picture to be uploaded");
//                    $profile->getCoverPicture()->upload();
//                    $profile->getCoverPicture()->addGallery($profile->getCoverGallery());
//                }
                
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($profile);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_current_user_profile'));
          }
        }
  
        return $this->render('FlowberProfileBundle:Default:profileEdit.html.twig', array('profileForm' => $profileForm->createView(), 'coverGalleryForm' => $coverGalleryForm->createView()));
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

        return $this->render('FlowberProfileBundle:Default:profile.html.twig', array('user' => $user, "profile" => $profile));
    }
}
