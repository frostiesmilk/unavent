<?php

namespace Wybe\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:profile.html.twig');
    }
    
    public function getSignInAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:signin.html.twig');
    }
    
    public function getSignUpDetailsAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:signup-details-form.html.twig');
    }
    
    public function getTestAction($name)
    {
        return $this->render('WybeFrontOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function getEventAction($eventNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:event.html.twig', array('name' => $eventNameSlug));
    }
}
