<?php

namespace Flowber\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\UserBundle\Entity\Friendship;
use Flowber\NotificationBundle\Entity\Notification;
use \Flowber\PrivateMessageBundle\Entity\PrivateMessage;

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
        
        $message = new PrivateMessage();
        $message->setUserFrom($iAm);
        $message->setUserTo($user);
        $message->setMessage($iAm->getFirstName()." ".$iAm->getSurname()." vous a envoyé un un clin d'oeil");
        $message->setSubject("Vous avez reçu un clin d'oeil !");
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();        
        
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $id,
        )));
    }

    public function acceptFriendRequestAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        $friendship = $userReposit->getFriendship($user, $this->getUser());
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
    
    public function declineFriendRequestAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        $friendship = $userReposit->getFriendship($user, $this->getUser());
        
        if($friendship->getStatut() == "send"){
            $em = $this->getDoctrine()->getManager();
            $em->remove($friendship);
            $em->flush();  
        }
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $user->getId()
        )));
    }
    
    public function cancelFriendRequestAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        $friendship = $userReposit->getFriendship($this->getUser(), $user);
       
        if($friendship->getStatut() == "send"){
            $em = $this->getDoctrine()->getManager();
            $em->remove($friendship);
            $em->flush();  
        }
      
        return $this->redirect($this->generateUrl('flowber_user_profile', array(
            'id' => $user->getId()
        )));
    }
    
    public function unfriendAction($id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User')->find($id);
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        $friendship1 = $userReposit->getFriendship($this->getUser(), $user);
        $friendship2 = $userReposit->getFriendship($user, $this->getUser());
       
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
