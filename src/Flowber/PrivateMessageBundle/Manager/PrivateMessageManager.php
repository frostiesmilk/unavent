<?php

namespace Flowber\PrivateMessageBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Flowber\CircleBundle\Manager\CircleManager;

class PrivateMessageManager extends BaseManager {

    protected $em;
    protected $cm;

    public function __construct(EntityManager $em, CircleManager $cm)
    {
        $this->em = $em;
        $this->cm = $cm;
    }
    
    public function getMessageReceived($circleId)
    {
        $pmReposit = $this->getPrivateMessageRepository();
        
        $messages = $pmReposit->getReceivedMessages($circleId);
        
        $count = 0;
        foreach ($messages as $message ){
            $messages[$count]['circleFromProfilePicture'] = $this->cm->getProfilePicture($message['circleFromId']);
            $messages[$count]['circleFromTitle'] = $this->cm->getTitle($message['circleFromId']);
            $messages[$count]['is'] = 'received';
            $count++;
        }

        return $messages;
    } 
    
    public function getMessageReceivedCompleted($id)
    {
        $pmReposit = $this->getPrivateMessageRepository();
        
        $message = $pmReposit->getOneReceivedMessage($id);
        $receivers = $pmReposit->getReceiversMessages($message['messageId']);
        $message['circleFromTitle'] = $this->cm->getTitle($message['circleFromId']);
        $message['is'] = 'sent';

        $count2=0;
        foreach ($receivers as $receiver ){
            $message['receivers'][$count2]['name']=$this->cm->getTitle($receiver['receiverId']);
            $message['receivers'][$count2]['id']=$receiver['receiverId'];
            $count2++;
        }

        return $message;
    } 
    
    public function getMessageSent($circleId)
    {
        $pmReposit = $this->getPrivateMessageRepository();
        $messages = $pmReposit->getSentMessages($circleId);

        $count = 0;
        foreach ($messages as $message ){         
            $receivers = $pmReposit->getReceiversMessages($message['messageId']);
            $messages[$count]['circleFromTitle'] = $this->cm->getTitle($message['circleFromId']);
            $messages[$count]['is'] = 'sent';
            
            $count2=0;
            $messages[$count]['receiversName']='';
            foreach ($receivers as $receiver ){
                $messages[$count]['receivers'][$count2]=$this->cm->getTitle($receiver['receiverId']);
                $count2++;
                if ($count2 <= 3) {
                    $messages[$count]['receiversName'].=$this->cm->getTitle($receiver['receiverId']);                
                    if ($count2<count($receivers) && $count2 <3 ){
                       $messages[$count]['receiversName'].=', '; 
                    }
                }
                else if ($count2 == 4){
                    $messages[$count]['receiversName'].='...'; 
                }
            }
            $count++;
        }

        return $messages;
    } 

    public function getMessageReceivedDeleted($circleId)
    {
        $pmReposit = $this->getPrivateMessageRepository();
        
        $messages = $pmReposit->getReceivedDeletedMessages($circleId);
        $count = 0;
        
        foreach ($messages as $message ){
            $messages[$count]['circleFromProfilePicture'] = $this->cm->getProfilePicture($message['circleFromId']);
            $messages[$count]['circleFromTitle'] = $this->cm->getTitle($message['circleFromId']);
            $messages[$count]['is'] = 'receivedDeleted';
            
            $receivers = $pmReposit->getReceiversMessages($message['messageId']);
            $messages[$count]['receiversName']='';
            $count2=0;
            foreach ($receivers as $receiver ){
                $messages[$count]['receivers'][$count2]=$this->cm->getTitle($receiver['receiverId']);
                $count2++;
                if ($count2 <= 3) {
                    $messages[$count]['receiversName'].=$this->cm->getTitle($receiver['receiverId']);                
                    if ($count2<count($receivers) && $count2 <3 ){
                       $messages[$count]['receiversName'].=', '; 
                    }
                }
                else if ($count2 == 4){
                    $messages[$count]['receiversName'].='...'; 
                }
                
            }
            $count++;
        }

        return $messages;
    } 

    public function getMessageSentDeleted($circleId)
    {
        $pmReposit = $this->getPrivateMessageRepository();
        
        $messages = $pmReposit->getSentDeletedMessages($circleId);
        $count = 0;
        
        foreach ($messages as $message ){
            $messages[$count]['circleFromProfilePicture'] = $this->cm->getProfilePicture($message['circleFromId']);
            $messages[$count]['circleFromTitle'] = $this->cm->getTitle($message['circleFromId']);
            $messages[$count]['is'] = 'sentDeleted';
            
            $receivers = $pmReposit->getReceiversMessages($message['messageId']);
            $messages[$count]['receiversName']='';           
            $count2=0;
            foreach ($receivers as $receiver ){
                $messages[$count]['receivers'][$count2]=$this->cm->getTitle($receiver['receiverId']);
                $count2++;
                if ($count2 <= 3) {
                    $messages[$count]['receiversName'].=$this->cm->getTitle($receiver['receiverId']);                
                    if ($count2<count($receivers) && $count2 <3 ){
                       $messages[$count]['receiversName'].=', '; 
                    }
                }
                else if ($count2 == 4){
                    $messages[$count]['receiversName'].='...'; 
                }
            }
            $count++;
        }
        
        return $messages;
    } 
    
    public function getMessageDeleted($circleId)
    {        
        $messagesR = $this->getMessageReceivedDeleted($circleId);
        $messagesD = $this->getMessageSentDeleted($circleId);
        
        $messages = array_merge($messagesR, $messagesD);
      
        return $messages;
    }   

    public function getMessagesNumber($circleId)
    {        
        $pmReposit = $this->getPrivateMessageRepository();

        $number['received'] = $pmReposit->getCountReceivedMessages($circleId);
        $number['sent'] = $pmReposit->getCountSentMessages($circleId);
        $number['deleted'] = $pmReposit->getCountReceivedDeletedMessages($circleId) + $pmReposit->getCountSentDeletedMessages($circleId);
      
        return $number;
    }   
    
    public function getPrivateMessageRepository()
    {
        return $this->em->getRepository('FlowberPrivateMessageBundle:PrivateMessage');
    }  
    
}