<?php

namespace Wybe\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Wybe\FrontOfficeBundle\Entity\User;
use Wybe\FrontOfficeBundle\Entity\PostalAddress;
use Wybe\FrontOfficeBundle\Entity\MailAddress;
use Wybe\FrontOfficeBundle\Entity\Phone;
use Wybe\FrontOfficeBundle\Entity\Subtitle;
use Wybe\FrontOfficeBundle\Entity\Description;
use Wybe\FrontOfficeBundle\Entity\Job;
use Wybe\FrontOfficeBundle\Entity\Hobby;
use Wybe\FrontOfficeBundle\Entity\Groups;
use Wybe\FrontOfficeBundle\Entity\Event;
use Twig_Extension_StringLoader;

class DefaultController extends Controller
{
    public function getPageTestAction(){
        $twig = $this->get('twig');
        $twig->addExtension(new Twig_Extension_StringLoader());
        
        $widgetView = 'WybeFrontOfficeBundle:Default:widgetTest.html.twig';
        $name = "marie";
        $data = array("widgetView"=>$widgetView, "name"=>$name);
        
        return $this->render('WybeFrontOfficeBundle:Default:templateTest.html.twig', array("data"=>$data));
    }
    
    public function indexAction()
    {  
        //$addd = new Description;
        //$phone = new Phone;
        //$phone->setNumber('0655555555');
        //$phone->setMain(TRUE);
        
       // $addd->setText('deeeessscccc');
        //$sub = new Subtitle;
//        //$sub->setText('subtitle test avec equina');
//        $job = new Job;
//        $job->setDateBegin(new \DateTime("26-10-2015"));
//        $job->setDateEnd(new \DateTime("27-10-2015"));
//        $job->setJob('this is a new job');
//        
//        $hobby = new Hobby;
//        $hobby->setCategory('cat');
//        $hobby->setDescription('jaime ça et ça et ça');
//        $hobby->setPercent(80);
        
           // $gr = new Groups;
            //$gr->setTitle('gorup title');
            //$gr->setSubtitle('gorup subtitle');
            //$ev = new Event;
           // $ev->setTitle('hahatitle');
        
//               // On récupère l'EntityManager
      //  $em = $this->getDoctrine()->getManager()->getRepository('WybeFrontOfficeBundle:User');
        
            // $user = $em->find(7);
             //$user->addGroup($gr);
//           $user->addJob($job);
           // $user->addEvent($ev);
        
        //$user->addPhone($phone);
          
        //$addd->setUser($user);

       //$em = $this->getDoctrine()->getManager();
        // Étape 1 : On « persiste » l'entité
//         $em->persist($job);
       // $em->persist($ev);


        // Étape 2 : On « flush » tout ce qui a été persisté avant
       // $em->flush();
        
        return $this->render('WybeFrontOfficeBundle:Default:profile.html.twig');
    }
    
    public function getSignInAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:signin.html.twig');
    }
    
    public function getSignUpDetailsAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:signup-details-form.html.twig');
    }
    
    public function getTestAction($name)
    {
        return $this->render('WybeFrontOfficeBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function getParametersAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:parameters.html.twig');
    }
        
    public function getEventAction($eventNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:event.html.twig', array('name' => $eventNameSlug));
    }
    
    public function getEventMemberAction($eventNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:event-member.html.twig', array('name' => $eventNameSlug));
    }
    
    public function getEventGalleryAction($eventNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:event-gallery.html.twig', array('name' => $eventNameSlug));
    }    
    public function getCreateEventAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:create-event.html.twig');
    }
    
    public function getLookForEventAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:look-for-event.html.twig');
    }
    
    public function getGroupAction($groupNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:group.html.twig', array('name' => $groupNameSlug));
    }
    
    public function getGroupMemberAction($groupNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:group-member.html.twig', array('name' => $groupNameSlug));
    }    
    
    public function getGroupEventAction($groupNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:group-event.html.twig', array('name' => $groupNameSlug));
    }    
    
    public function getGroupGalleryAction($groupNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:group-gallery.html.twig', array('name' => $groupNameSlug));
    }    
    
    public function getCreateGroupAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:create-group.html.twig');
    }
    
    public function getLookForGroupAction()
    {
        return $this->render('WybeFrontOfficeBundle:Default:look-for-group.html.twig');
    }
    
    public function getNotifAction($notifNameSlug, Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:notif.html.twig', array('name' => $notifNameSlug));
    }   
    
    
    public function getAllMessagesAction(Request $oRequest)
    {
        return $this->render('WybeFrontOfficeBundle:Default:messages-all.html.twig');
    }   
    
    public function getMessageAction(Request $oRequest, $messageId)
    {
        return $this->render('WybeFrontOfficeBundle:Default:messages-single.html.twig');
    }   
}

