<?php

namespace Wybe\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:profile.html.twig');
    }
    
    public function signInAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:signin.html.twig');
    }
    
    public function signUpDetailsAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:signup-details-form.html.twig');
    }
    
    public function getTestAction($name)
    {
        return $this->render('WybeFrontOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
}
