<?php

namespace Flowber\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\EventBundle\Entity\Event;
use Flowber\EventBundle\Entity\EventRepository;
use Flowber\EventBundle\Form\EventType;

class DefaultController extends Controller
{
    public function eventAction($id)
    {
        
        $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('FlowberEventBundle:Event');
        $result = $repository->getInfosEvent($id);
        die(var_dump($result));        
        return $this->render('FlowberEventBundle:Default:event.html.twig', array('result' => $result));
    }
    
    public function createEventAction()
    {
        $user = $this->getUser();        

        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        $event = new \Flowber\EventBundle\Entity\Event;
        $eventForm = $this->createForm(new EventType, $event);
        
        $request = $this->get('request');
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $eventForm->handleRequest($request);
            
            if ($eventForm->isValid()) {             
                $em = $this->getDoctrine()->getManager();
                $event->setCreatedBy($user);
                $em->persist($event);
                $em->flush();              
            }         
            return $this->redirect($this->generateUrl('flowber_event_homepage'), array('id' => $event->getId()));
        }
  
        return $this->render('FlowberEventBundle:Default:create-event.html.twig', array('eventForm' => $eventForm->createView()));
    }
}
