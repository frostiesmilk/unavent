<?php

namespace Flowber\GroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityRepository;

use Flowber\GalleryBundle\Form\ProfilePictureType;
use Flowber\GalleryBundle\Form\CoverPictureType;
use Flowber\GalleryBundle\Entity\Photo;
use Flowber\GroupBundle\Form\GroupsType;
use Flowber\GroupBundle\Entity\Groups;
use Flowber\PostBundle\Form\PostType;
use Flowber\PostBundle\Entity\Post;
use Flowber\PostBundle\Form\CommentType;
use Flowber\PostBundle\Entity\Comment;
use Flowber\PostBundle\Form\PostWithEventType;
use Flowber\PostBundle\Form\PostWithPicturesType;
use Flowber\PrivateMessageBundle\Entity\PrivateMessage;
use Flowber\PrivateMessageBundle\Form\PrivateMessageOnlyType;
use Flowber\PrivateMessageBundle\Form\PrivateMessageType;
use Flowber\GalleryBundle\Entity\Gallery;
use Flowber\GalleryBundle\Form\GalleryType;
use Flowber\CircleBundle\Entity\Subscribers;

use Flowber\GalleryBundle\Form\PhotoMultipleType;

class GroupController extends Controller
{
    public function getGroupAction($circleId)
    {
        $user=$this->getUser();  
                
        $circleRepository = $this->getDoctrine()->getManager()->getRepository('FlowberCircleBundle:Circle');
        $circle = $circleRepository->find($circleId);
           
        //preparing new form for a post
        $postWithEvent = new Post();        
        $postWithEventForm = $this->createForm(new PostWithEventType, $postWithEvent);    
        $postWithPictures = new Post();
        $postWithPicturesForm = $this->createForm(new PostWithPicturesType(), $postWithPictures);  
        
        //preparing optional new event profile picture
        $eventProfilePicture = new Photo();
        $eventProfilePictureForm = $this->createForm(new ProfilePictureType, $eventProfilePicture);        
        //preparing optional new event cover picture
        $eventCoverPicture = new Photo();
        $eventCoverPictureForm = $this->createForm(new CoverPictureType, $eventCoverPicture);
           
        // preparing new Gallery
        $newGroupGallery = new Gallery();
        $newGalleryForm = $this->createForm(new GalleryType(), $newGroupGallery);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST'){
            $newGalleryForm->bind($request);
            
            if($newGalleryForm->isValid()){
                $em = $this->getDoctrine()->getManager();
                
                $newGroupGallery->setCreatedBy($user->getProfile());
                
                if(empty($newGroupGallery->getTitle())){
                    $newGroupGallery->setTitle("Galerie du ".$newGroupGallery->getCreationDate()->format('Y-m-d H:i'));
                }
                $circle->addGallery($newGroupGallery);
                
                $em->persist($circle);
                
                try{
                    $em->flush();  
                    return $this->redirect($this->generateUrl(
                        'flowber_groups_gallery',
                        array('circleId' => $circle->getId(), 'galleryId' =>$newGroupGallery->getId())
                    ));
                } catch (Exception $ex) {

                }                              
            }
        }
        
        $posts = $this->container->get('flowber_post.post')->getCirclePosts($circle, $user->getProfile()->getId());
        
        // forms for comments
        $commentsForms = array();
        foreach ($posts as $post)
        {
            $comment = new Comment();
            $commentForm = $this->createForm(new CommentType, $comment);
            $commentsForms[] = $commentForm->createView();
        }
        
        // for Private Messages
        $mailToCreatorForm = $this->createForm(new PrivateMessageOnlyType, new PrivateMessage());
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);

        return $this->render('FlowberGroupBundle:Default:group.html.twig', 
            array('circle'              => $this->container->get('flowber_group.group')->getGroupInfos($circleId),
                'user'                  => $this->container->get("flowber_profile.profile")->getCurrentProfileInfos(),
                'posts'                 => $posts,
                'navbar'                => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
                'postWithPicturesForm'  => $postWithPicturesForm->createView(),
                'messageForm'           => $privateMessageForm->createView(),
                'commentForm'           => $commentsForms,
                'postWithEventForm'     => $postWithEventForm->createView(),
                'eventProfilePictureForm'   => $eventProfilePictureForm->createView(),
                'eventCoverPictureForm'     => $eventCoverPictureForm->createView(),
                'mailToCreatorForm'     => $mailToCreatorForm->createView(),
                'newGalleryForm'        => $newGalleryForm->createView(),
        ));
    }
    
    public function getCreateGroupAction()
    {
        $user = $this->getUser();        
        $error = false; // detect error while processing forms
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   
        
        // preparing profile to be edited
        $group = new Groups();
        $groupForm = $this->createForm(new GroupsType, $group);
        
        //preparing eventual new profile picture
        $profilePicture = new Photo();
        $profilePictureForm = $this->createForm(new ProfilePictureType, $profilePicture);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPictureForm = $this->createForm(new CoverPictureType, $coverPicture);
        
        $request = $this->get('request');
        
        if ($request->getMethod() == 'POST') { 
            // retrieving all forms
            $groupForm->handleRequest($request);
            $profilePictureForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);
            
            $em = $this->getDoctrine()->getManager();
            
            // processing profile edit
            if ($groupForm->isValid()) { 
                $group->setCreatedBy($user->getProfile());
                $em->persist($group);
            }else{
                $error = true;
            }
                     
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){                    
                    $profilePicture->addGallery($group->getProfileGallery());
                    $group->setProfilePicture($profilePicture);
                    $em->persist($profilePicture);
                }
            }else{
                $error = true;
            }   
            
            // processing cover picture form
            if($coverPictureForm->isValid()){
                // cover picture was submitted
                if($coverPicture->getFile() !== null){
                    $coverPicture->addGallery($group->getCoverGallery());
                    $group->setCoverPicture($coverPicture);
                    $em->persist($coverPicture);
                }
            }else{
                $error = true;
            }   
            
            // no error
            if(!$error){  
                $em->persist($group);
                 $em->flush();                
               
                $subscriber = new Subscribers();
                $subscriber->setCircle($this->container->get("flowber_circle.circle")->getCircle($group->getId()));
                $subscriber->setSubscriber($this->container->get("flowber_circle.circle")->getCircle($user->getProfile()->getId()));
                $subscriber->setRole('admin');
                $subscriber->setStatut($this->container->get("flowber_circle.circle")->getClass($group->getId()));
                $subscriber->setMessage('');
        
                $em->persist($subscriber);
                $em->flush();                
                return $this->redirect($this->generateUrl('api_get_circle',array('circleId' => $group->getId())));
            }
        }    
        
         return $this->render('FlowberGroupBundle:Default:createGroup.html.twig', array(
            'groupForm'             => $groupForm->createView(),
            'navbar'                => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
            'profilePictureForm'    => $profilePictureForm->createView(),
            'coverPictureForm'      => $coverPictureForm->createView(),
        ));
  
    }

    public function editGroupAction($circleId)
    {
        $error = false; // detect error while processing forms
        
        if (!is_object($this->getUser())) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }   

        $group = $this->container->get('flowber_group.group')->getGroup($circleId);        
        
        // preparing profile to be edited
        $groupForm = $this->createForm(new GroupsType, $group);
        
        //preparing eventual new profile picture
        $profilePicture = new Photo();
        $profilePictureForm = $this->createForm(new ProfilePictureType, $profilePicture);
        
        //preparing new cover picture
        $coverPicture = new Photo();
        $coverPictureForm = $this->createForm(new CoverPictureType, $coverPicture); 

        $request = $this->get('request');
                
        if ($request->getMethod() == 'POST') { 
            // retrieving all forms
            $groupForm->handleRequest($request);
            $profilePictureForm->handleRequest($request);
            $coverPictureForm->handleRequest($request);
            
            $em = $this->getDoctrine()->getManager();
            
            // processing profile edit
            if ($groupForm->isValid()) { 
                $em->persist($group);
            }else{
                $error = true;
            }
                     
            // processing profile picture form
            if($profilePictureForm->isValid()){
                // profile picture was submitted
                if($profilePicture->getFile() !== null){                    
                    $profilePicture->addGallery($group->getProfileGallery());
                    $group->setProfilePicture($profilePicture);
                    $em->persist($profilePicture);
                }
            }else{
                $error = true;
            }   
            
            // processing cover picture form
            if($coverPictureForm->isValid()){
                // cover picture was submitted
                if($coverPicture->getFile() !== null){
                    $coverPicture->addGallery($group->getCoverGallery());
                    $group->setCoverPicture($coverPicture);
                    $em->persist($coverPicture);
                }
            }else{
                $error = true;
            }   
            
            // no error
            if(!$error){  
                $em->persist($group);
                $em->flush();
                // all good, back to profile page
                return $this->redirect($this->generateUrl('api_get_circle',array('circleId' => $group->getId())));
            }
        } 
              
        return $this->render('FlowberGroupBundle:Default:editGroup.html.twig', array (
            'circle'                => $this->container->get('flowber_group.group')->getGroupInfos($group),
            'navbar'                => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
            'groupForm'             => $groupForm->createView(),
            'profilePictureForm'    => $profilePictureForm->createView(),
            'coverPictureForm'      => $coverPictureForm->createView(),
         ));
    }
    
    public function getGroupMembersAction($id)
    {
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        $members = $this->container->get("flowber_group.group")->getGroupMembers($id);

        return $this->render('FlowberGroupBundle:Default:groupMembers.html.twig', 
                array('circle'      => $this->container->get('flowber_group.group')->getGroupInfos($id),
                'messageForm'       => $privateMessageForm->createView(),
                'navbar'            => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
                'members'           => $this->container->get("flowber_profile.profile")->getFriendsFromList($members)
                 ));
    }
    
    public function getGroupEventsAction($id)
    {
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        $eventsId = $this->container->get("flowber_group.group")->getEvents($this->container->get("flowber_circle.circle")->getCircle($id));
        $events = $this->container->get("flowber_event.event")->getEventsFromList($eventsId);

        return $this->render('FlowberGroupBundle:Default:groupEvents.html.twig', 
                array('circle'  => $this->container->get('flowber_group.group')->getGroupInfos($id),
                'messageForm'   => $privateMessageForm->createView(),
                'events'   => $events,
                'navbar'        => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
                 ));
    }

    public function getGroupGalleriesAction($id)
    {
        $user=$this->getUser();  
                
        $circleRepository = $this->getDoctrine()->getManager()->getRepository('FlowberCircleBundle:Circle');
        $circle = $circleRepository->find($id);

//        BUG IF 2 LINES BELOW ARE PLACED HERE. WHYYYYYYYYYYYYYYYY
//        $galleries = $this->container->get("flowber_gallery.gallery")->getGalleries($this->container->get("flowber_circle.circle")->getCircle($id), $user->getProfile());
//        $galleriesId = $this->container->get("flowber_gallery.gallery")->getGalleriesId($this->container->get("flowber_circle.circle")->getCircle($id));
        
        
        // preparing new Gallery
        $newGroupGallery = new Gallery();
        $newGalleryForm = $this->createForm(new GalleryType(), $newGroupGallery);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST'){
            $newGalleryForm->bind($request);
            
            if($newGalleryForm->isValid()){
                $em = $this->getDoctrine()->getManager();
                
                $newGroupGallery->setCreatedBy($user->getProfile());
                
//                if(empty($newGroupGallery->getTitle())){
//                    $newGroupGallery->setTitle("Galerie du ".$newGroupGallery->getCreationDate()->format('Y-m-d H:i'));
//                }
                $circle->addGallery($newGroupGallery);
                
                $em->persist($circle);
                
                try{
                    $em->flush();  
                    return $this->redirect($this->generateUrl(
                        'flowber_groups_gallery',
                        array('circleId' => $circle->getId(), 'galleryId' =>$newGroupGallery->getId())
                    ));
                } catch (Exception $ex) {

                }                              
            }
        }
        
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        $galleries = $this->container->get("flowber_gallery.gallery")->getGalleries($this->container->get("flowber_circle.circle")->getCircle($id), $user->getProfile());
        $galleriesId = $this->container->get("flowber_gallery.gallery")->getGalleriesId($this->container->get("flowber_circle.circle")->getCircle($id));
        
        
        return $this->render('FlowberGroupBundle:Default:showGroupGalleries.html.twig', 
                array('circle'  => $this->container->get('flowber_group.group')->getGroupInfos($id),
                'messageForm'   => $privateMessageForm->createView(),
                'newGalleryForm' => $newGalleryForm->createView(),
                'navbar'        => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),   
                'galleries'     => $galleries,
                'galleriesId'     => $galleriesId
                 ));
    }
    
    public function getGroupGalleryAction($circleId, $galleryId)
    {       
        $newPhotosForm = $this->createFormBuilder()
                ->add('files','file',array(
                    "multiple" => "multiple",
                    "attr" => array(
                        "accept" => "image/*",                        
                    ),
                ))
                ->add('galleryId', 'hidden')
                ->add('circleId', 'hidden')
                ->getForm();
        
        $newPhotosForm->get("circleId")->setData($circleId);
        $newPhotosForm->get("galleryId")->setData($galleryId);

        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);
        $gallery = $this->getDoctrine()->getManager()->getRepository('FlowberGalleryBundle:Gallery')->find($galleryId);
                
        return $this->render('FlowberGroupBundle:Default:showGroupGallery.html.twig',
                array('circle'  => $this->container->get('flowber_group.group')->getGroupInfos($circleId),
                'messageForm'   => $privateMessageForm->createView(),
                'navbar'        => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
                'gallery'       => $gallery,
                'newPhotosForm' => $newPhotosForm->createView(),
                'galleryCanDelete' => $gallery->canDelete($this->getUser()->getProfile())));
    }

    public function getAllGroupsAction($id)
    {
        $privateMessageForm = $this->createForm(new PrivateMessageType, new PrivateMessage);

        return $this->render('FlowberGroupBundle:Default:allGroup.html.twig', 
                array(
                    'navbar'        => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
                    'messageForm'   => $privateMessageForm->createView(),
                    'circle'        => $this->container->get('flowber_circle.circle')->getCoverInfos($id),
                    'groups'        => $this->container->get('flowber_group.group')->getGroups($id)
                 ));
    }
    
    public function getSearchAction(Request $request)
    {
        $user = $this->getUser();     
        
        $searchData = array();
        $searchForm = $this->createFormBuilder($searchData)
                                ->setMethod('POST')
                                ->add('keywords', 'text', array('required' => false))                                
                                ->add('categories', 'entity', array(
                                            'required' => false,
                                            'class'    => 'FlowberFrontOfficeBundle:Category',
                                            'property' => 'title',
                                            'multiple' => true,
                                            'query_builder' => function(EntityRepository $er) {
                                                return $er->createQueryBuilder('u')
                                                    ->orderBy('u.title', 'ASC');
                                            },
                                        )
                                    )
                                ->getForm();
        $searchMode = false;
        $searchForm->handleRequest($request);
        
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData = $searchForm->getData();
            $keywordsRaw = $searchData['keywords'];        
            $keywords = trim($keywordsRaw);
            $categories = $searchData['categories'];
            
            if(!empty($keywords) || count($categories)>0){ // if search
                // search groups
                
                $searchMode = true;
                if(count($categories)<1){
                    $categories = null;
                }
                $selectedGroups = $this->getDoctrine()->getRepository("FlowberGroupBundle:Groups")->getGroupsSearch($user->getProfile(), $keywords, $categories);
                
                $groups = $this->container->get("flowber_group.group")->getGroupsInArray($selectedGroups, $user->getProfile()->getId());
            }else{ // no search, we display all groups
                $groups = $this->container->get('flowber_group.group')->getAllGroups();
            }
        }else{
            $groups = $this->container->get('flowber_group.group')->getAllGroups();
        }
                                            
        
        
        return $this->render('FlowberGroupBundle:Default:groupSearch.html.twig', 
            array(
                'searchMode'    => $searchMode,
                'navbar'        => $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos(),                    
                'groups'        => $groups,
                'searchForm'    => $searchForm->createView(),
        ));
    }
    
}
