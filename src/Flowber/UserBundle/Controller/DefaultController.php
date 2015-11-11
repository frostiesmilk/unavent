<?php

namespace Flowber\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\UserBundle\Entity\User;
use Flowber\UserBundle\Entity\PostalAddress;
use Flowber\UserBundle\Entity\MailAddress;
use Flowber\UserBundle\Entity\Phone;
use Flowber\UserBundle\Form\UserType;
use Flowber\UserBundle\Form\PostalAddressType;
use Flowber\UserBundle\Form\MailAddressType;
use Flowber\UserBundle\Form\PhoneType;

class DefaultController extends Controller
{
    public function indexAction()
    {      
        return $this->render('FlowberUserBundle:Default:index.html.twig');
    }
}
