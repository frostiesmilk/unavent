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
        $phone = new Phone;
        $formPhone = $this->createForm(new PhoneType, $phone);
        $postal = new PostalAddress;
        $formPostal = $this->createForm(new PostalAddressType, $postal);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $formPhone->bind($request);
            $formPostal->bind($request);

            if ($formPhone->isValid() && $formPostal->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($phone);
                $em->persist($postal);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_user_homepage_bis'));
            }
        }
        
        return $this->render('FlowberUserBundle:Default:index.html.twig', 
                array(
                    'formPhone' => $formPhone->createView(), 
                    'formPostal' => $formPostal->createView()
                ));
    }
    
    public function index2Action()
    {   

        return $this->render('FlowberUserBundle:Default:test2.html.twig');
    }
}
