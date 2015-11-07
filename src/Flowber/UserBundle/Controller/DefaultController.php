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
//        $user1 = new User();
//        $user1->setFirstname("marie");
//        $user1->setSurname("boivent");
//        $user1->setBirthdate(new \DateTime('12/05/1992'));
//        $user1->setPassword("password");
//
//        $user2 = new User();
//        $user2->setFirstname("equina");
//        $user2->setSurname("you");
//        $user2->setBirthdate(new \DateTime('04/21/1991'));
//        $user2->setPassword("password");   
//        
//        $user3 = new User();
//        $user3->setFirstname("pierre");
//        $user3->setSurname("boivent");
//        $user3->setBirthdate(new \DateTime("12/02/1991"));
//        $user3->setPassword("password");
//        
//        $address1 = new PostalAddress();
//        $address1->setAddress("12 rue montfermeil");
//        $address1->setZipcode(12451);
//        $address1->setCity("joliville");
//        $address1->setCountry("france");
//        
//        $address2 = new PostalAddress();
//        $address2->setAddress("this is an address");
//        $address2->setZipcode(94210);
//        $address2->setCity("citycity");
//        $address2->setCountry("fraaance"); 
//        
//        $mail1 = new MailAddress();
//        $mail1->setMail("boivanmet@mafil.com");
//        $mail1->setMain(TRUE);
//
//        $mail2 = new MailAddress();
//        $mail2->setMail("coucou@hitmail.com.com");
//        $mail2->setMain(FALSE);
//        
//        $mail3 = new MailAddress();
//        $mail3->setMail("boiventmarie@gmail.com");
//        $mail3->setMain(TRUE);
//        
//        $phone1 = new Phone();
//        $phone1->setNumber("0608166386");
//        $phone1->setMain(TRUE);
//        
//        $phone2 = new Phone();
//        $phone2->setNumber("0155972868");
//        $phone2->setMain(TRUE);
//        
//        $phone3 = new Phone();
//        $phone3->setNumber("0622188581");
//        $phone3->setMain(TRUE);
//        
//        $user1->addMailAddress($mail1);
//        $user1->addPhone($phone1);
//        $user1->addPostalAddress($address1);
//        
//        $user2->addMailAddress($mail2);
//        $user2->addPhone($phone2);
//        $user2->addPostalAddress($address2);
//        
//        $user3->addMailAddress($mail3);
//        $user3->addPhone($phone3);
//        $user3->addPostalAddress($address1);
//        
//        $em = $this->getDoctrine()->getManager();
//        
//        $em->persist($user1);
//        $em->persist($user2);
//        $em->persist($user3);
//
//        $em->persist($mail1);
//        $em->persist($mail2);
//        $em->persist($mail3);
//
//        $em->persist($address1);
//        $em->persist($address2);
//        
//        $em->persist($phone1);
//        $em->persist($phone2);
//        $em->persist($phone3);
//        
//        $em->flush();
    
//          $em = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
//        
//          $user = $em->find(1);
        
        $phone = new Phone;
        $address = new PostalAddress;
        $mail = new MailAddress;
        
        $form = $this->createForm(new PhoneType, $phone);
        $form2 = $this->createForm(new PostalAddressType, $address);
        $form3 = $this->createForm(new MailAddressType, $mail);
        
//        $request = $this->get('request');
//            
//        if ($request->getMethod() == 'POST') {
//            $form->bind($request);
//
//            if ($form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($user);
//                $em->flush();
//
//            }
//        }
        
        return $this->render('FlowberUserBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),
         ));
    }
}
