<?php

namespace Flowber\PrivateMessageBundle\Manager;

use Doctrine\ORM\EntityManager;
use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PrivateMessageManager extends BaseManager {

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function getMessageReceived($user)
    {

    } 

    
    public function getPrivateMessageRepository()
    {
        return $this->em->getRepository('FlowberPrivateMessageBundle:PrivateMessage');
    }  
    
}