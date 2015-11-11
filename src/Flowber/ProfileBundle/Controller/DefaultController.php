<?php

namespace Flowber\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function getModifProfilePageAction()
    {
        return $this->render('FlowberFrontOfficeBundle:pages:ModifProfile.html.twig');
    }
}
