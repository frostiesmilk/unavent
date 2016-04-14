<?php

namespace Flowber\GroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\GroupBundle\Entity\Membership;
use Flowber\NotificationBundle\Entity\Notification;
use \Flowber\PrivateMessageBundle\Entity\PrivateMessage;

class MembershipController extends Controller
{
    /**
     * Show SignIn homepage
     * @return type
     */
    public function addMemberAction($id)
    {
        $circle = $this->container->get('flowber_group.group')->getCircle($id);        
        $iAm = $this->getUser();
        
        if (!is_object($circle)) {
            throw new AccessDeniedException('This group does not exist.');
        }   
        
        $membership = new Membership();
        $membership->setUser($iAm);
        $membership->setGroup($circle);
        $membership->setStatut('send');
        $membership->setRole('member');
        
//        $notification = new Notification ();
//        $notification->setCreatedBy($iAm);
//        $notification->setUser($user);
//        $notification->setPageRoute('flowber_group_homepage');
//        $notification->setPageId($iAm->getId());
//        $notification->setMessage("souhaite devenir membre du groupe");
                
        $em = $this->getDoctrine()->getManager();
        $em->persist($membership);
//        $em->persist($notification);
        $em->flush();        
        
        return $this->redirect($this->generateUrl('flowber_group_homepage', array(
            'id' => $id,
        )));
    }

    public function acceptMemberRequestAction($id)
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
    
    public function declineMemberRequestAction($id)
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
    
    public function cancelMemberRequestAction($id)
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
    
    public function unMemberAction($id)
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
