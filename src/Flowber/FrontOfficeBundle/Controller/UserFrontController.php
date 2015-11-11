<?php

namespace Flowber\FrontOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserFrontController extends Controller
{   
    /**
     * Show SignIn homepage
     * @return type
     */
    public function getHomeConnectionPageAction(Request $request)
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
     * 
     * Show SignIn homepage
     * @return type
     */
    public function getHomeConnectedPageAction()
    {
        $user = $this->getUser();
        
//        $user1 = new User;
//       
//        $user1->setFirstname('Carlotta');
//        $user1->setSurname('Leherissel');
//        $user1->setUsername('carlottaleherissel');
//        $user1->setSex('femme');
//        $user1->setPassword('carlottapass');
//        $user1->setBirthdate(date_create('1991-08-21'));
//        
//        $add = new PostalAddress;
//        
//        $add->setAddress('18 bis avenue saint hilaire');
//        $add->setZipcode(94100);
//        $add->setCity('Saint Maur');
//        $add->setCountry('France');
//        $add->setName('Carlotta Leherissel');
//        
//        $addd = new MailAddress;
//        
//        $addd->setMail('carlottaleherissel@gmail.com');
//        $addd->setMain(TRUE);
//        
//        $phone = new Phone;
//   
//        $phone->setNumber('0622154589');
//        $phone->setMain(TRUE);
//        
//        $user1->addMailAddress($addd);
//        $user1->addPhone($phone);
//        $user1->addPostalAddress($add);
//        
//        $em = $this->getDoctrine()->getManager();
//        
//        $em->persist($phone);
//        $em->persist($addd);
//        $em->persist($add);
//        $em->persist($user1);
//        
//        $em->flush();
        
//        if (null === $user) {
//            return $this->render('FlowberFrontOfficeBundle:pages:homeConnectedPage.html.twig');
//        } else {
//            $user->getUsername();
        return $this->render('FlowberFrontOfficeBundle:pages:homeConnectedPage.html.twig');

//        }
        
    }    
    /**
     * Show SignUp page for details
     * @param type 
     * @return type
     */
    public function getSignUpDetailsPageAction(Request $request)
    {
        return $this->render('FlowberFrontOfficeBundle:pages:signUpDetailsPage.html.twig');
    }
}
