<?php

namespace Flowber\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Flowber\ProfileBundle\Entity\Profile;
use Flowber\ProfileBundle\Form\ProfileType;


class DefaultController extends Controller
{
    public function getProfileModifierAction()
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
            $profileForm->bind($request);
            
            if ($profileForm->isValid()) {
                $profile->getCoverPicture()->upload();
                $em = $this->getDoctrine()->getManager();
                $em->persist($profile);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_profile'));
          }
        }
  
        return $this->render('FlowberProfileBundle:Default:ModifProfile.html.twig', array('profileForm' => $profileForm->createView()));
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
