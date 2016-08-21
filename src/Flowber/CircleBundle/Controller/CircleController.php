<?php

namespace Flowber\CircleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CircleController extends Controller
{
    function getRequestPageAction (){        
        
        $navbar = $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos();
        $requestInfos = $this->container->get('flowber_circle.circle')->getCurrentUserRequestInfos();

        return $this->render('FlowberCircleBundle:Default:getRequest.html.twig', 
            array(
                'requests' => $requestInfos,
                'navbar' => $navbar                
            )
        );
    }
    
    function getNotificationPageAction (){
        
        $navbar = $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos();
        $notificationsInfos = $this->container->get('flowber_circle.circle')->getCurrentUserNotificationsInfos();

        return $this->render('FlowberCircleBundle:Default:getNotification.html.twig', 
            array(
                'notifications' => $notificationsInfos,
                'navbar' => $navbar                
            )
        );
    }
    
    /**
     * $id is Circle's ID
     * @param type $id
     */
function getCircleGalleriesAction($id, $anchor = NULL){
        $circleClassName = $this->container->get("flowber_circle.circle")->getClass($id);
        
        if(!empty($anchor)){
            return $this->redirect($this->generateUrl('flowber_'.$circleClassName.'_galleries',  array('id' => $id) )."#".$anchor);
        }

        return $this->redirect($this->generateUrl('flowber_'.$circleClassName.'_galleries',  array('id' => $id )));
        
    }
    
    function getCircleGalleryAction($circleId, $galleryId){
        $circleClassName = $this->container->get("flowber_circle.circle")->getClass($circleId);

        return $this->redirect($this->generateUrl('flowber_'.$circleClassName.'_gallery',  array('circleId' => $circleId, 'galleryId'=>$galleryId )));
        
    }
}
