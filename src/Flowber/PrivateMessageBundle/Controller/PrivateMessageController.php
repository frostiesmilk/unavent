<?php

namespace Flowber\PrivateMessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;

class PrivateMessageController extends Controller
{
    public function getAllMessagesReceivedAction()
    {
        $user = $this->getUser();
        $messages = $this->container->get('flowber_privateMessage.privateMessage')->getMessageReceived($user->getProfile()->getId());
        $number = $this->container->get('flowber_privateMessage.privateMessage')->getMessagesNumber($user->getProfile()->getId());
       //On rajoute l'id re receiver à l'ensemble des informations pour pouvoir modifier le statut
        $messagesInfos= null;
        $count=0;
        foreach ($messages as $message ){
            $messagesInfos[$count] = array_merge($message, $this->container->get('flowber_privateMessage.privateMessage')->getPrivateMessageRepository()->getReceiversId($message['messageId'], $user->getProfile()->getId()));
            $count++;
        }
        //die(var_dump($messagesInfos));
        // $numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
        //$numberSendMessages = $pmReposit->getSentMessagesNumber($user);
        //$numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);
        
        return $this->render('FlowberPrivateMessageBundle:Default:allPrivateMessage.html.twig', array(
            "messages"=>$messagesInfos,
            "number"=>$number,
            "type"=>'received',
            ));
    }
    
    public function getAllMessagesSentAction()
    {
        $user = $this->getUser();
        
        $messagesInfos = $this->container->get('flowber_privateMessage.privateMessage')->getMessageSent($user->getProfile()->getId());
        $number = $this->container->get('flowber_privateMessage.privateMessage')->getMessagesNumber($user->getProfile()->getId());
        $count=0;
        foreach ($messagesInfos as $message ){
            $messagesInfos[$count]['receiverMessageId'] = null;
            $count++;
        }  
       // die(var_dump($messagesInfos));
        //$messagesSend = $pmReposit->getSentMessages($user);
        //$numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
        //$numberSendMessages = $pmReposit->getSentMessagesNumber($user);
        //$numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);

        return $this->render('FlowberPrivateMessageBundle:Default:allPrivateMessage.html.twig', array(
            "messages"=>$messagesInfos,
            "number"=>$number,
            "type"=>'send',
                ));
    }
    
    public function getAllMessagesDeletedAction()
    {
        $user = $this->getUser();
        
        $messagesInfos = $this->container->get('flowber_privateMessage.privateMessage')->getMessagedeleted($user->getProfile()->getId());
        $number = $this->container->get('flowber_privateMessage.privateMessage')->getMessagesNumber($user->getProfile()->getId());
        $count=0;
                // die(var_dump($messagesInfos));
       foreach ($messagesInfos as $message ){
            if ($message['is']=='receivedDeleted'){
                $messagesInfos[$count] = array_merge($messagesInfos[$count], $this->container->get('flowber_privateMessage.privateMessage')->getPrivateMessageRepository()->getReceiversId($message['messageId'], $user->getProfile()->getId()));
            } else { 
                $messagesInfos[$count]['receiverMessageId'] = null;
            }
            $count++;
        }  
                //die(var_dump($messagesInfos));

//        $messagesDeleted = $pmReposit->getDeletedMessages($user);
//        $numberReceivedMessages = $pmReposit->getReceiveddMessagesNumber($user);
//        $numberDeletedMessages = $pmReposit->getDeletedMessagesNumber($user);
//        $numberSendMessages = $pmReposit->getSentMessagesNumber($user);
        
        return $this->render('FlowberPrivateMessageBundle:Default:allPrivateMessage.html.twig', array(
            "messages"=>$messagesInfos,
            "number"=>$number,
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
    
    public function getMessageSentAction($id)
    {
        $user = $this->getUser();
        $messageInfo = $this->container->get('flowber_privateMessage.privateMessage')->getMessageReceivedCompleted($id);
        $messageInfo['receiverMessageId']=null;

        $number = $this->container->get('flowber_privateMessage.privateMessage')->getMessagesNumber($user->getProfile()->getId());            

        return $this->render('FlowberPrivateMessageBundle:oneMessage:oneMessageSent.html.twig', array(
            "message"=>$messageInfo,
            "number"=>$number,
            "type"=>'sent'
            ));
    }

    public function getMessageSentDeletedAction($id)
    {
        $user = $this->getUser();
        $messageInfo = $this->container->get('flowber_privateMessage.privateMessage')->getMessageReceivedCompleted($id);

        $messageInfo['receiverMessageId']=null;
        $number = $this->container->get('flowber_privateMessage.privateMessage')->getMessagesNumber($user->getProfile()->getId());            

        return $this->render('FlowberPrivateMessageBundle:oneMessage:oneMessageSentDeleted.html.twig', array(
            "message"=>$messageInfo,
            "number"=>$number,
            "type"=>'sentDeleted'
            ));
    }

    public function getMessageReceivedAction($id)
    {
        $user = $this->getUser();
        $messageInfo = $this->container->get('flowber_privateMessage.privateMessage')->getMessageReceivedCompleted($id);

        //On rajoute l'id re receiver à l'ensemble des informations pour pouvoir modifier le statut
        $messageInfo = array_merge($messageInfo, $this->container->get('flowber_privateMessage.privateMessage')->getPrivateMessageRepository()->getReceiversId($messageInfo['messageId'], $user->getProfile()->getId()));
        $message = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:Receiver')->find($messageInfo['receiverMessageId']);
        if ($message->getStatut() == 2){
            $message->setStatut(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
        }


        $number = $this->container->get('flowber_privateMessage.privateMessage')->getMessagesNumber($user->getProfile()->getId());            
        $privateMessage = new PrivateMessage;
        $privateMessage->setSubject("RE : ".$messageInfo['subject']);
        $privateMessageForm = $this->createForm(new PrivateMessageType, $privateMessage);
        
        return $this->render('FlowberPrivateMessageBundle:oneMessage:oneMessageReceived.html.twig', array(
            "message"=>$messageInfo,
            "number"=>$number,
            'messageForm' => $privateMessageForm->createView(),
            "type"=>'received'
            ));
    }

    public function getMessageReceivedDeletedAction($id)
    {
        $user = $this->getUser();
        $messageInfo = $this->container->get('flowber_privateMessage.privateMessage')->getMessageReceivedCompleted($id);

        //On rajoute l'id re receiver à l'ensemble des informations pour pouvoir modifier le statut
        $messageInfo = array_merge($messageInfo, $this->container->get('flowber_privateMessage.privateMessage')->getPrivateMessageRepository()->getReceiversId($messageInfo['messageId'], $user->getProfile()->getId()));
        $message = $this->getDoctrine()->getManager()->getRepository('FlowberPrivateMessageBundle:Receiver')->find($messageInfo['receiverMessageId']);
        if ($message->getStatut() == 2){
            $message->setStatut(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
        }

        $number = $this->container->get('flowber_privateMessage.privateMessage')->getMessagesNumber($user->getProfile()->getId());            
        
        return $this->render('FlowberPrivateMessageBundle:oneMessage:oneMessageReceivedDeleted.html.twig', array(
            "message"=>$messageInfo,
            "number"=>$number,
            "type"=>'send'
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
