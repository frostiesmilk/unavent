<?php

namespace Flowber\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function getProfileModifierAction()
    {
        return $this->render('FlowberProfileBundle:Default:ModifProfile.html.twig');
    }
    
    public function getProfileAction()
    {
        $user = $this->getUser();
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        return $this->render('FlowberProfileBundle:Default:profile.html.twig', array('user' => $user));
    }
}
