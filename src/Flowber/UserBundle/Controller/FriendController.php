<?php

namespace Flowber\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\UserBundle\Entity\Friendship;
use Flowber\NotificationBundle\Entity\Notification;


class FriendController extends Controller
{
    /**
     * Show SignIn homepage
     * @return type
     */
    public function addFriendAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $iAm = $this->getUser();
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        $friendship = new Friendship();
        $friendship->setUser($iAm);
        $friendship->setFriend($user);
        $friendship->setStatut('send');
        
        $notification = new Notification ();
        $notification->setCreatedBy($iAm);
        $notification->setUser($user);
        $notification->setPageRoute('flowber_user_profile');
        $notification->setPageId($iAm->getId());
        $notification->setMessage("souhaite être votre ami");
                
        $em = $this->getDoctrine()->getManager();
        $em->persist($friendship);
        $em->persist($notification);
        $em->flush();        
        
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $id,
        )));
    }
    
    public function sendWinkAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $iAm = $this->getUser();
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        $friendship = new Friendship();
        $friendship->setUser($iAm);
        $friendship->setFriend($user);
        $friendship->setStatut('send');
        $em = $this->getDoctrine()->getManager();
        $em->persist($friendship);
        $em->flush();        
        
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => '4',
        )));
    }

    public function acceptFriendRequestAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        $friendship = $userReposit->getFriendship($user, $this->getUser());
       
        if($friendship->getStatut() == "send"){
            $friendship->setStatut("ok");
            $friendshipReverse = new Friendship();
            $friendshipReverse->setUser($this->getUser());
            $friendshipReverse->setFriend($user);
            $friendshipReverse->setStatut('ok');
            $em = $this->getDoctrine()->getManager();
            $em->persist($friendship);
            $em->persist($friendshipReverse);
            $em->flush();  
        }
      
        
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $user->getId()
        )));
    }
    public function declineFriendRequestAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        $friendship = $userReposit->getFriendship($user, $this->getUser());
        die(var_dump($friendship));
        
        if($friendship->getStatut() == "send"){
            $friendship->setStatut("cancel");
            $em = $this->getDoctrine()->getManager();
            $em->persist($friendship);
            $em->flush();  
        }
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $user->getId()
        )));
    }
}
