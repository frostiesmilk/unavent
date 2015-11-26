<?php

namespace Flowber\PrivateMessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\UserBundle\Repository\UserRepository;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        $userMessages = $userReposit->getReceivedMessages($user);
        
        // affiche ce qu'il y a dans l'objet $userMessages
        //die(var_dump($userMessages));
        
        return $this->render('FlowberPrivateMessageBundle:Default:privateMessage.html.twig', array("userMessages"=>$userMessages));
    }
}
