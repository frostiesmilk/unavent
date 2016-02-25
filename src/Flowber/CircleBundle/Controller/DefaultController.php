<?php

namespace Flowber\CircleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $test = $this->container->get('flowber_circle.circle')->getTest();
        die(var_dump($test));
        return $this->render('FlowberCircleBundle:Default:index.html.twig', array('name' => $name));
    }
}
