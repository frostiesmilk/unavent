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
    
    public function getParametersAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:parameters.html.twig');
    }
        
    public function getEventAction($eventNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:event.html.twig', array('name' => $eventNameSlug));
    }
    
    public function getCreateEventAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:create-event.html.twig');
    }
    
    public function getLookForEventAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:look-for-event.html.twig');
    }
    
    public function getGroupAction($groupNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:group.html.twig', array('name' => $groupNameSlug));
    }
    
    public function getCreateGroupAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:create-group.html.twig');
    }
    
    public function getLookForGroupAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:look-for-group.html.twig');
    }
    
    public function getNotifAction($notifNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:notif.html.twig', array('name' => $notifNameSlug));
    }   
}

