<?php

namespace Flowber\PrivateMessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\UserBundle\Repository\UserRepository;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        
        $userReposit = $this->getDoctrine()->getManager()->getRepository('UserRepository');
        $userReposit->getReceivedMessages($user);
        
        return $this->render('FlowberPrivateMessageBundle:Default:privateMessage.html.twig');
    }
}
