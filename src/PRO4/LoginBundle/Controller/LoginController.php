<?php

namespace PRO4\LoginBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

use PRO4\UserBundle\Entity\User;
use PRO4\UserBundle\Form\Type\UserType;

class LoginController extends MyController {
	
    public function indexAction(Request $request) {
    	if($this->isAuthenticatedFully()) {
    		return $this->redirect($this->generateUrl("project"));
    	} else {
    		return $this->loginAction();
    	}
    }
	
	public function logoutAction() {
		// security-layer will do the rest
	}
	
	public function loginAction()	{
		if($this->isAuthenticatedFully()) {
    		return $this->redirect($this->generateUrl("project"));
    	}
    	
		$request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
		
		$error = $error ? "E-Mail and Password do not match!" : "";
		
        return $this->render(
            'PRO4LoginBundle:Login:login.html.twig',
            array(
                "last_email" => $session->get(SecurityContext::LAST_USERNAME),
                "error"      => $error
            )
        );
	}
	
	public function registerAction(Request $request) {
		if($this->isAuthenticatedFully()) {
    		return $this->redirect($this->generateUrl("project"));
    	}
    	
    	$user = new User();
		$form = $this->createForm(new UserType(UserType::REGISTER), $user);
	
		if ($request->isMethod('POST')) {
            $form->bind($request);
			if($form->isValid()) {			
				$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($user);
				$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
				$user->setPassword($password);
				
				$this->persist($user);
				
				$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully created a new User! Check your E-Mail to activate it!'
				);
				
				return $this->redirect($this->generateUrl("login"));
			}
        }
		
		return $this->render('PRO4LoginBundle:Login:register.html.twig', array("form" => $form->createView()));
	}
}
