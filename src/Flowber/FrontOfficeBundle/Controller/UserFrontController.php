<?php

namespace Flowber\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserFrontController extends Controller
{   
    /**
     * Show SignIn homepage
     * @return type
     */
    public function getHomeConnectionPageAction()
    {
        return $this->render('FlowberFrontOfficeBundle:pages:homeConnectionPage.html.twig');
    }
    
    /**
     * Show SignUp page for details
     * @param type 
     * @return type
     */
    public function getSignUpDetailsPageAction(Request $request)
    {
        return $this->render('FlowberFrontOfficeBundle:pages:signUpDetailsPage.html.twig');
    }
}
