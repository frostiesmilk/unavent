<?php

namespace Flowber\PrivateMessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;

class DefaultController extends Controller
{
    public function messageReceivedAction()
    {
        $user = $this->getUser();
        
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        
        $messagesReceived = $userReposit->getReceivedMessages($user);
        $numberReceivedMessages = $userReposit->getCountReceiveddMessages($user);
        $numberSendMessages = $userReposit->getCountSendMessages($user);
        $numberDeletedMessages = $userReposit->getCountDeletedMessages($user);
        
        return $this->render('FlowberPrivateMessageBundle:Default:allPrivateMessageReceived.html.twig', array(
            "userMessages"=>$messagesReceived,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages,
            "type"=>'received',
            ));
    }
    
    public function messageSendAction()
    {
        $user = $this->getUser();
        
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        
        $messagesSend = $userReposit->getSendMessages($user);
        $numberReceivedMessages = $userReposit->getCountReceiveddMessages($user);
        $numberSendMessages = $userReposit->getCountSendMessages($user);
        $numberDeletedMessages = $userReposit->getCountDeletedMessages($user);

        return $this->render('FlowberPrivateMessageBundle:Default:allPrivateMessageSend.html.twig', array(
            "userMessages"=>$messagesSend,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages,
            "type"=>'send',
                ));
    }
    
    public function messageDeletedAction()
    {
        $user = $this->getUser();
        
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        
        $messagesDeleted = $userReposit->getDeletedMessages($user);
        $numberReceivedMessages = $userReposit->getCountReceiveddMessages($user);
        $numberDeletedMessages = $userReposit->getCountDeletedMessages($user);
        $numberSendMessages = $userReposit->getCountSendMessages($user);
        
        return $this->render('FlowberPrivateMessageBundle:Default:allPrivateMessageDeleted.html.twig', array(
            "userMessages"=>$messagesDeleted,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages,
            "type"=>'deleted',
                ));
    }
    
    public function deleteMessageAction($id, $type)
    { 
        $message = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage')->find($id);

        if ($type == 's' || $type == 'r') {
            $message->setStatut(3);
        }
        
        if ($type == 'd') {
            $message->setStatut(0);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
        
        if ($type == 's') 
            return $this->redirect($this->generateUrl('flowber_private_message_send_homepage'));
        if ($type == 'r')
            return $this->redirect($this->generateUrl('flowber_private_message_received_homepage'));
        if ($type == 'd')
            return $this->redirect($this->generateUrl('flowber_private_message_deleted_homepage'));          
    }
    
    public function readMessageAction($id)
    {
        $message = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage')->find($id);
        
        if ($message->getStatut() == '1'){
            $message->setStatut(2);
        }
        else if ($message->getStatut() == '2'){
            $message->setStatut(1);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
        
        return $this->redirect($this->generateUrl('flowber_private_message_received_homepage'));
    }
    
    public function getMessageAction($id)
    {
        $message = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage')->find($id);
        
        $user = $this->getUser();
        
        $userReposit = $this->getDoctrine()->getManager()->getRepository('FlowberUserBundle:User');
        
        $numberReceivedMessages = $userReposit->getCountReceiveddMessages($user);
        $numberDeletedMessages = $userReposit->getCountDeletedMessages($user);
        $numberSendMessages = $userReposit->getCountSendMessages($user);
        
        return $this->render('FlowberPrivateMessageBundle:Default:oneMessage.html.twig', array(
            "message"=>$message,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages
            ));
    }
}