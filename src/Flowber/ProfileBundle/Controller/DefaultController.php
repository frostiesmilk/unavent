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
    /**
     * Main profile edit form
     * @return type
     * @throws AccessDeniedException
     */
    public function getEditProfileAction()
    {
        $user = $this->getUser();
        $profile = $this->getDoctrine()->getManager()->getRepository('FlowberProfileBundle:Profile')->findOneByUser($user);

        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        $profileForm = $this->createForm(new ProfileType, $profile);
        
        $coverPicture = new Photo();
        $coverPictureForm = $this->createFormBuilder($coverPicture)
            ->add('file',           'file', array(
                    'required' => false,
                    'data_class' => null))
            //->add('save', 'submit', array('label' => 'Changer de photo de couverture'))
            ->getForm();
        
        $request = $this->get('request');
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $profileForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);
            
            if ($profileForm->isValid()) {
                
                // processing cover picture
//                $uploadedFiles = $request->files->get('flowber_profilebundle_profile');//->get('flowber_gallerybundle_photo');
//            
//                
//                $uploadedCoverPicture = $uploadedFiles['coverPicture'];
//                $uploadedCoverPicture = $uploadedCoverPicture['file'];
//  
//                if(!isset($uploadedCoverPicture)){
//                    die("no picture to be uploaded");
//                }else{
//                    if()
//                    $profile->getCoverPicture()->addGallery($profile->getCoverGallery());
//                }
                
                
                $em = $this->getDoctrine()->getManager();
                
                $em->persist($profile);
                $em->flush();

                
            }
            
            if($coverPictureForm->isValid()){
                $em = $this->getDoctrine()->getManager();
                $coverPicture->addGallery($profile->getCoverGallery());
                $profile->setCoverPicture($coverPicture);

                $em->persist($coverPicture);
                $em->persist($profile);
                $em->flush();
            }
            
            return $this->redirect($this->generateUrl('flowber_current_user_profile'));
        }
  
        return $this->render('FlowberProfileBundle:Default:profileEditMain.html.twig', array('profileForm' => $profileForm->createView(), 'coverPictureForm' => $coverPictureForm->createView()));
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
            throw new NotFoundHttpException("Le profil de l'utilisateur".$profile->getUser()->getFirstname()." n'existe pas.");
        }            

        return $this->render('FlowberProfileBundle:Default:profile.html.twig', array('user' => $user, "profile" => $profile));
    }
}
