<?php

namespace Flowber\FrontOfficeBundle\Manager;

use Flowber\FrontOfficeBundle\Entity\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Flowber\CircleBundle\Manager\CircleManager;
use Flowber\PrivateMessageBundle\Manager\PrivateMessageManager;
use Flowber\EventBundle\Manager\EventManager;
use Flowber\GroupBundle\Manager\GroupManager;

class FrontOfficeManager extends BaseManager {

    protected $circleManager;
    protected $eventManager;
    protected $groupManager;
    protected $pmManager;
    protected $context;
    
    public function __construct(SecurityContext $securityContext, CircleManager $circleManager, EventManager $eventManager, GroupManager $groupManager, PrivateMessageManager $pmManager)
    {
        $this->circleManager = $circleManager;
        $this->eventManager = $eventManager;
        $this->groupManager = $groupManager;
        $this->pmManager = $pmManager;
        $this->context = $securityContext;
    }

    public function getCurrentUserId(){   
        return $this->context->getToken()->getUser()->getProfile()->getId();
    }
    
    public function getCurrentUserNavbarInfos(){
        $eventsNav = $this->eventManager->getEventsNavbar($this->getCurrentUserId());
        $groupsNav = $this->groupManager->getGroupsNavbar($this->getCurrentUserId());
        $navbar['event'] = $eventsNav;
        $navbar['requestNumber'] = $this->circleManager->getCountRequestInfos($this->getCurrentUserId());
        $navbar['messageNumber'] = $this->pmManager->getNumberMessageNotRead($this->getCurrentUserId());
        $navbar['notificationNumber'] = $this->circleManager->getCountNotification($this->getCurrentUserId());
        $navbar['group'] = $groupsNav;
        
        return $navbar;
    }

}