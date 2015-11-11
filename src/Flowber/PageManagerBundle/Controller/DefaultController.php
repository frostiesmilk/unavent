<?php

namespace Flowber\PageManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FlowberPageManagerBundle:Default:index.html.twig', array('name' => $name));
    }
}
