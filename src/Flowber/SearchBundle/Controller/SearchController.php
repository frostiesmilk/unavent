<?php

namespace Flowber\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchAllAction(Request $request)
    {
        // user
        $user = $this->getUser();
        $userProfileInfos = $this->container->get("flowber_profile.profile")->getProfileInfos($user->getProfile()->getId());

        // for navbar
//        $eventsNav = $this->container->get("flowber_event.event")->getEventsNavbar($user->getProfile()->getId());
//        $groupsNav = $this->container->get("flowber_group.group")->getGroupsNavbar($user->getProfile()->getId());
//        $navbar['event'] = $eventsNav;
//        $navbar['group'] = $groupsNav;    
//        $navbar['requestNumber'] = $this->container->get('flowber_circle.circle')->getCountRequestInfos($user->getProfile()->getId());
        $navbar = $this->container->get("flowber_front_office.front_office")->getCurrentUserNavbarInfos();
        
        // keywords
        $keywordsRaw = $request->query->get('keywords');        
        $keywords = trim($keywordsRaw);        
        
        // search events
        $selectedEvents = $this->getDoctrine()->getRepository("FlowberEventBundle:Event")->getEventsByTitleSearch($keywords, $user->getProfile());
        $resultEvents = $this->container->get("flowber_event.event")->getEventsFromList($selectedEvents, $user->getProfile()->getId());
        
        // search groups
        $selectedGroups = $this->getDoctrine()->getRepository("FlowberGroupBundle:Groups")->getGroupsByTitleSearch($keywords, $user->getProfile());
        $resultGroups = $this->container->get("flowber_group.group")->getGroupsInArray($selectedGroups, $user->getProfile()->getId());
        
        // search people
        $selectProfiles = $this->getDoctrine()->getRepository("FlowberProfileBundle:Profile")->getProfilesByTitleSearch($keywords, $user->getProfile());
        $profiles = $this->container->get('flowber_profile.profile')->getFriendsFromList($selectProfiles, $user->getProfile()->getId());

        return $this->render('FlowberSearchBundle:Default:showAllResults.html.twig', 
                array(
                    'user'      =>  $userProfileInfos,
                    'navbar'    =>  $navbar,
                    'events'    =>  $resultEvents,
                    'groups'    =>  $resultGroups,
                    'friends'    =>  $profiles,
                ));
    }
}
