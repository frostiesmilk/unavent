<?php

namespace Flowber\PrivateMessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;

class PrivateMessageController extends Controller
{
    public function getAllMessagesReceivedAction($circleId)
    {
        $user = $this->getUser();
        
        $pmReposit = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage');
        
        $messages = $pmReposit->getReceivedMessages($circleId);
        $numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
        $numberSendMessages = $pmReposit->getSentMessagesNumber($user);
        $numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);
        
        return $this->render('FlowberPrivateMessageBundle:Default:allPrivateMessageReceived.html.twig', array(
            "userMessages"=>$messagesReceived,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages,
            "type"=>'received',
            ));
    }
    
    public function getAllMessagesSendAction()
    {
        $user = $this->getUser();
        
        $pmReposit = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage');
        
        $messagesSend = $pmReposit->getSentMessages($user);
        $numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
        $numberSendMessages = $pmReposit->getSentMessagesNumber($user);
        $numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);

        return $this->render('FlowberPrivateMessageBundle:Default:allPrivateMessageSend.html.twig', array(
            "userMessages"=>$messagesSend,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages,
            "type"=>'send',
                ));
    }
    
    public function getAllMessagesDeletedAction()
    {
        $user = $this->getUser();
        
        $pmReposit = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage');
        
        $messagesDeleted = $pmReposit->getDeletedMessages($user);
        $numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
        $numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);
        $numberSendMessages = $pmReposit->getSentMessagesNumber($user);
        
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
    
    public function getMessageReceivedAction($circleId)
    {
       // $message = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage')->find($id);
        
        $user = $this->getUser();
        
        $pmReposit = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage');
        $messages = $pmReposit->getReceivedMessages($circleId);
        
//        $numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
//        $numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);
//        $numberSendMessages = $pmReposit->getSentMessagesNumber($user);
        
//        $message->setStatut('1');
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($message);
//        $em->flush();
                
        $privateMessage = new PrivateMessage;
        //$privateMessage->setSubject('RE : '.$message->getSubject());
        $privateMessageForm = $this->createForm(new PrivateMessageType, $privateMessage);

        $request = $this->get('request');
        
        // if form has been submitted
        if ($request->getMethod() == 'POST') { 
            $privateMessageForm->handleRequest($request);
            
            if ($privateMessageForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $privateMessage->setUserFrom($this->getUser());
                $privateMessage->setUserTo($message->getUserFrom());
                $em->persist($privateMessage);
                $em->flush();

                return $this->redirect($this->generateUrl('flowber_profile_current_user'));
            }
        }
        
        return $this->render('FlowberPrivateMessageBundle:Default:oneMessageReceived.html.twig', array(
            "message"=>$message,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages,
            'messageForm' => $privateMessageForm->createView()
            ));
    }
    
    public function getMessageSendAction($id)
    {
        $message = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage')->find($id);
        
        $user = $this->getUser();
        
        $pmReposit = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage');
        
        $numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
        $numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);
        $numberSendMessages = $pmReposit->getSentMessagesNumber($user);
                
        return $this->render('FlowberPrivateMessageBundle:Default:oneMessageSend.html.twig', array(
            "message"=>$message,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages,
            ));
    }
    
    public function getMessageDeletedAction($id)
    {
        $message = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage')->find($id);
        
        $user = $this->getUser();
        
        $pmReposit = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:PrivateMessage');
        
        $numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
        $numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);
        $numberSendMessages = $pmReposit->getSentMessagesNumber($user);
        
        return $this->render('FlowberPrivateMessageBundle:Default:oneMessageDeleted.html.twig', array(
            "message"=>$message,
            "numberDeletedMessages"=>$numberDeletedMessages,
            "numberReceiveddMessages"=>$numberReceivedMessages,
            "numberSendMessages"=>$numberSendMessages,
            ));
    }
}
