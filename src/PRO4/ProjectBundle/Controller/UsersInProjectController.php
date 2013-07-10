<?php

namespace PRO4\ProjectBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use PRO4\ProjectBundle\Entity\Project;
use PRO4\UserBundle\Entity\User;
use PRO4\UserBundle\Form\Type\UserType;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersInProjectController extends MyController {
	
    public function indexAction(Request $request, $id) {
    	$project = $this->find("PRO4\ProjectBundle\Entity\Project", $id);
    	$this->checkPermission("VIEW", $project);
    	$users = $project->getUsers();
   		foreach($users AS $user) {
   			$user->setIsOwner($this->hasUserPermission($project, MaskBuilder::MASK_OWNER, $user));
   			if(!$user->isOwner()) {
   				$user->setIsAdmin($this->hasUserPermission($project, MaskBuilder::MASK_EDIT, $user));
   			}
   		}
    	
    	$form = $this->createFormBuilder()
	        ->add("eMail", "email",array(
			    "label"  => "E-Mail",
			    "constraints" => array(new Email(), new NotBlank())
			))
			->getForm();
    	
    	$isAdmin = $this->hasPermission("EDIT", $project);

    	
    	if ($request->isMethod('POST')) {
    		$this->checkPermission("EDIT", $project);
	        $form->bind($request);
	        if ($form->isValid()) {
	        	$data = $form->getData();
	        	$user = $this->getDoctrine()->getRepository('PRO4UserBundle:User')->findOneByEMail($data["eMail"]);
	        	if(!$user) {
	        		$user = new User();
	        		$user->setEMail($data["eMail"]);
	        		
    		    	$factory = $this->get('security.encoder_factory');
					$encoder = $factory->getEncoder($user);
					$password = $encoder->encodePassword("test1234", $user->getSalt());
    				$user->setPassword($password);
    				
   					$this->persist($user);
    				
    				$user = $this->getDoctrine()->getRepository('PRO4UserBundle:User')->findOneByEMail($data["eMail"]);
	        	}
	        	
	        	$project->addUser($user);
	            $this->addPermission($project, MaskBuilder::MASK_VIEW, $user);
	            
   				$this->persist($project);
    			
    			$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully added ' . $user->getEmail() . ' to this project!'
				);
				
				return $this->redirect($this->generateUrl("users_in_project", array("id" => $project->getId())));
	        }
	    }
    	
    	return $this->render("PRO4ProjectBundle:UsersInProject:userForm.html.twig", array("form" => $form->createView(), "project" => $project, "users" => $users));
    }
    
    public function removeAction(Request $request, $projectId, $userId) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
   		$user = $this->find("PRO4\UserBundle\Entity\User", $userId);
   		
   		if($this->hasUserPermission($project, MaskBuilder::MASK_EDIT, $user)) {
   			$this->checkPermission("OWNER", $project);
   		} else {
   			$this->checkPermission("EDIT", $project);
   		}
    	
    	if($this->getUser()->getUserId() == $userId || $this->isUserOwner($project, $user) || !$project->getUsers()->contains($user)) {
    		throw new InvalidArgumentException();
    	}
    	$project->removeUser($user);
    	$this->removePermissions($project, $user);
	            
		$this->persist($project);
    	
    	$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully removed ' . $user->getEmail() . ' from this project!'
				);
				
		return $this->redirect($this->generateUrl("users_in_project", array("id" => $project->getId())));
   	}
   	
   	public function grantAdminAction(Request $request, $projectId, $userId) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	$this->checkPermission("OWNER", $project);
    	$user = $this->find("PRO4\UserBundle\Entity\User", $userId);
    	if($this->getUser()->getUserId() == $userId || $this->isUserOwner($project, $user) || !$project->getUsers()->contains($user)) {
    		throw new InvalidArgumentException();
    	}
    	$this->makeAdmin($project, $user);
    	
    	$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully gave ' . $user->getEmail() . ' admin-rights!'
				);
				
		return $this->redirect($this->generateUrl("users_in_project", array("id" => $project->getId())));
   	}
   	
   	public function revokeAdminAction(Request $request, $projectId, $userId) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	$this->checkPermission("OPERATOR", $project);
    	$user = $this->find("PRO4\UserBundle\Entity\User", $userId);
    	if($this->getUser()->getUserId() == $userId || !$project->getUsers()->contains($user)) {
    		throw new InvalidArgumentException();
    	}
    	$this->removePermissions($project, $user);
    	$this->makeViewer($project, $user);
    	
    	$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully took admin-rights from ' . $user->getEmail() . '!'
				);
				
		return $this->redirect($this->generateUrl("users_in_project", array("id" => $project->getId())));
   	}
}
