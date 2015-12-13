<?php

namespace Flowber\LikeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FlowberLikeBundle:Default:index.html.twig', array('name' => $name));
    }
}
