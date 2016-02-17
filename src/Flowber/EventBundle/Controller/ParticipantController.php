<?php

namespace Flowber\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\NotificationBundle\Entity\Notification;
use \Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\EventBundle\Entity\Participants;

class ParticipantController extends Controller
{
        /**
     * Show SignIn homepage
     * @return type
     */
    public function addParticipantAction($id)
    {
        $circle = $this->container->get('flowber_event.event')->getCircle($id);        
        $iAm = $this->getUser();
        
        if (!is_object($circle)) {
            throw new AccessDeniedException('This event does not exist.');
        }   
        
        $participants = new Participants();
        $participants->setUser($iAm);
        $participants->setEvent($circle);
        $participants->setStatut('send');
        $participants->setRole('member');
        
//        $notification = new Notification ();
//        $notification->setCreatedBy($iAm);
//        $notification->setUser($user);
//        $notification->setPageRoute('flowber_group_homepage');
//        $notification->setPageId($iAm->getId());
//        $notification->setMessage("souhaite devenir membre du groupe");
                
        $em = $this->getDoctrine()->getManager();
        $em->persist($participants);
//        $em->persist($notification);
        $em->flush();        
        
        return $this->redirect($this->generateUrl('flowber_event_homepage', array(
            'id' => $id,
        )));
    }

    public function acceptParticipantRequestAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $friendshipReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:Friendship');
        $friendship = $friendshipReposit->getFriendship($user, $this->getUser());
        $iAm = $this->getUser();
        
        if($friendship->getStatut() == "send"){
            $friendship->setStatut("ok");
            $friendshipReverse = new Friendship();
            $friendshipReverse->setUser($this->getUser());
            $friendshipReverse->setFriend($user);
            $friendshipReverse->setStatut('ok');
            
            $notification = new Notification ();
            $notification->setCreatedBy($iAm);
            $notification->setUser($user);
            $notification->setPageRoute('flowber_user_profile');
            $notification->setPageId($iAm->getId());
            $notification->setMessage("a accepté votre demande d'ami");
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($friendship);
            $em->persist($notification);
            $em->persist($friendshipReverse);
            $em->flush();  
        }
      

        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $user->getId()
        )));
    }
    
    public function declineParticipantRequestAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $friendshipReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:Friendship');
        $friendship = $friendshipReposit->getFriendship($user, $this->getUser());
        
        if($friendship->getStatut() == "send"){
            $em = $this->getDoctrine()->getManager();
            $em->remove($friendship);
            $em->flush();  
        }
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $user->getId()
        )));
    }
    
    public function cancelParticipantRequestAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $friendshipReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:Friendship');
        $friendship = $friendshipReposit->getFriendship($this->getUser(), $user);
       
        if($friendship->getStatut() == "send"){
            $em = $this->getDoctrine()->getManager();
            $em->remove($friendship);
            $em->flush();  
        }
      
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $user->getId()
        )));
    }
    
    public function unParticipantAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $friendshipReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:Friendship');
        $friendship1 = $friendshipReposit->getFriendship($this->getUser(), $user);
        $friendship2 = $friendshipReposit->getFriendship($user, $this->getUser());
       
        if($friendship1->getStatut() == "ok" and $friendship2->getStatut() == "ok"){
            $em = $this->getDoctrine()->getManager();
            $em->remove($friendship1);
            $em->remove($friendship2);
            $em->flush();  
        }
      
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $user->getId()
        )));
    }
}
