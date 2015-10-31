<?php

namespace Flowber\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FlowberUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
