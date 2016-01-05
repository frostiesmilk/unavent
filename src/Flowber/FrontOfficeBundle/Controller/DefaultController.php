<?php

namespace Flowber\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homeAction()
    {
        if($this->getUser()!= null){ // if logged in
            return $this->redirect($this->generateUrl('flowber_profile_current_user'));
        }
        
        return $this->redirect($this->generateUrl('flowber_user_signin_homepage'));
    }
    
    public function indexAction($name)
    {
        return $this->render('FlowberFrontOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
}
