<?php

namespace PRO4\UserBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use PRO4\UserBundle\Entity\User;
use PRO4\UserBundle\Form\Type\UserType;

class UserController extends MyController {
    
    public function changePasswordAction(Request $request) {
    	$user = new User();
    	
   		$form = $this->createForm(new UserType(UserType::CHANGE_PASSWORD), $user);

    	if ($request->isMethod("POST")) {
	        $form->bind($request);
	        if ($form->isValid()) {	    
	        	$curUser = $this->getUser();
	        	
	        	$factory = $this->get('security.encoder_factory');
				$encoder = $factory->getEncoder($curUser);
				$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
				$curUser->setPassword($password);
				$curUser->setSalt($user->getSalt());

   				$this->persist($curUser);
    		
    			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully changed your password."
				);
				
				return $this->redirect($this->generateUrl("change_password"));
	        }
	    }
	    return $this->render('PRO4UserBundle:User:changePassword.html.twig', array("form" => $form->createView()));
    }
}
