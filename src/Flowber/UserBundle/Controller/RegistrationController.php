<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Flowber\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Flowber\UserBundle\Entity\PostalAddress;
use Flowber\UserBundle\Form\PostalAddressType;
use Flowber\UserBundle\Entity\Phone;
use Flowber\UserBundle\Entity\User;
use Flowber\UserBundle\Form\PhoneType;

/**
 *
 * @author Equina
 */
class RegistrationController extends BaseController{
    /**
     * Tell the user his account is now confirmed
     */
    public function registerAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        if (class_exists('\Symfony\Component\Security\Core\Security')) {
            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;
        } else {
            // BC for SF < 2.6
            $authErrorKey = SecurityContextInterface::AUTHENTICATION_ERROR;
            $lastUsernameKey = SecurityContextInterface::LAST_USERNAME;
        }

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }
        
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            // BC for SF < 2.4
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }
        
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FlowberFrontOfficeBundle:pages:homeConnectionPage.html.twig',(array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'form' => $form->createView(),
        )));
    }
    
    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->generateUrl('fos_user_registration_confirmed');
            $response = new RedirectResponse($url);
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }
    
    /**
     * Registration confirmation done. Next and final step of registration.
     * @return type
     * @throws AccessDeniedException
     */
    public function confirmedAction()
    {
        $user = $this->getUser();        
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        // THE REGISTRATION IS NOW CONFIRMED
        
        // To final step
//        $response = $this->forward('FlowberUserBundle:Registration:registrationFinalStep', array(
//            'user' => $user,
//            //'targetUrl' => $this->getTargetUrlFromSession(),
//        ));
        $response = $this->redirectToRoute('flowber_registration_finishing');
        
        return $response;
    }
    
    /**
     * 
     * @param type $parameters
     * @return type
     */
    public function registrationFinalStepAction(){
        
        // user is logged after registration is confirmed
        $user = $this->getUser();
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $user->getProfile()->setTitle($user->getFirstname()." ".$user->getSurname());
        $user->getProfile()->setCreatedBy($user->getProfile());
        $em->persist($user);
        $em->flush();
        // preparing the form for last step of registration
        $phone = new Phone;
        $formPhone = $this->createForm(new PhoneType, $phone);
        $postal = new PostalAddress;
        $formPostal = $this->createForm(new PostalAddressType, $postal);

        // form processing
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $formPhone->bind($request);
            $formPostal->bind($request);

            if ($formPhone->isValid() && $formPostal->isValid()) {
                $user->addPostalAddress($postal);
                $user->addPhone($phone);
                $em->persist($phone);
                $em->persist($postal);
                $em->persist($user);

                $em->flush();
                
                // all set, redirecting to user profile!
                return $this->redirect($this->generateUrl('api_get_circle', array(
                    'circleId' => $user->getProfile()->getId()
                )));
            }
        }
        $circleInfos = $this->container->get('flowber_profile.profile')->getProfileInfos($user->getProfile()->getId());
        
        $navbar = $this->container->get('flowber_front_office.front_office')->getCurrentUserNavbarInfos();

        return $this->render('FlowberUserBundle:Default:signUpDetails.html.twig', array(
            'formPhone' => $formPhone->createView(), 
            'navbar' => $navbar,
            'formPostal' => $formPostal->createView(),
            'circle' => $circleInfos
        ));
    }
}
