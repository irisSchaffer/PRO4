<?php

namespace PRO4\ProjectBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use PRO4\ProjectBundle\Entity\Project;
use PRO4\UserBundle\Entity\User;

use PRO4\ProjectBundle\Form\Type\DepartmentType;
use PRO4\ProjectBundle\Entity\Department;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class DepartmentController extends MyController {
	
    public function indexAction(Request $request, $projectId) {
    	$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
   		$this->checkPermission("VIEW", $project);
   		$departments = $project->getDepartments();

   		$department = new Department();
   		
   		$form = $this->createForm(new DepartmentType(), $department);

		$showForm = $this->hasPermission("EDIT", $project);
    	
    	if ($request->isMethod("POST")) {

    		$this->checkPermission("EDIT", $project);

	        $form->bind($request);
	        if ($form->isValid()) {	        		
        		$project->addDepartment($department);
        		$department->addUser($this->getUser());
	        	
        		$em = $this->getDoctrine()->getManager();
				$em->persist($project);
				$em->flush();
	        	
	        	$em = $this->getDoctrine()->getManager();
   				$em->persist($department);
    			$em->flush();
    			
				$this->makeOperator($department, $this->getUser());
				
				// add project owner as department owner
        		$users = $project->getUsers();
        		foreach($users AS $user) {
        			if($this->isUserOwner($project, $user)) {
        				$this->makeOwner($department, $user);
        			}
        		}
    		
    			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully added a department."
				);
				
				return $this->redirect($this->generateUrl("department_overview", array("projectId" => $projectId)));
	        }
	    }
	    return $this->render('PRO4ProjectBundle:Department:departmentForm.html.twig', array("form" => $form->createView(), "showForm" => $showForm, "action" => "Add" , "project" => $project, "departments" => $departments));
    }
    
     public function editAction(Request $request, $projectId, $departmentId) {
     	$department = $this->find("PRO4\ProjectBundle\Entity\Department", $departmentId);
    	$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	$departments = $project->getDepartments();
   		$this->checkPermission("EDIT", $department);
   		
   		$form = $this->createForm(new DepartmentType(), $department);
		
		$showForm = $this->hasPermission("EDIT", $department);
    	
    	if ($request->isMethod("POST")) {
    		$this->checkPermission("EDIT", $department);

	        $form->bind($request);
	        if ($form->isValid()) {
	        	
        		$em = $this->getDoctrine()->getManager();
				$em->persist($project);
				$em->flush();
	        	
	        	$em = $this->getDoctrine()->getManager();
   				$em->persist($department);
    			$em->flush();
    			
    			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully updated the department."
				);
				
				return $this->redirect($this->generateUrl("department_overview", array("projectId" => $projectId)));
	        }
	    }
	    return $this->render('PRO4ProjectBundle:Department:departmentForm.html.twig', array("form" => $form->createView(), "showForm" => $showForm, "action" => "Edit", "project" => $project, "departments" => $departments));
    }
    
    public function removeAction(Request $request, $projectId, $departmentId) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	$this->checkPermission("EDIT", $project);
    	$department = $this->find("PRO4\ProjectBundle\Entity\Department", $departmentId);

    	$project->removeDepartment($department);
    	$department->removeUser($this->getUser());
	    
	    $this->removePermissions($department, $this->getUser());
	    
        $em = $this->getDoctrine()->getManager();
        $em->remove($department);
		$em->persist($project);
		$em->flush();
    	
    	$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully removed the department!'
				);
				
		return $this->redirect($this->generateUrl("department_overview", array("projectId" => $projectId)));
   	}
   	
   	public function usersInDepartmentAction(Request $request, $projectId, $departmentId) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
   		$this->checkPermission("VIEW", $project);
   		$department = $this->find("PRO4\ProjectBundle\Entity\Department", $departmentId);
   		
   		$em = $this->getDoctrine()->getManager();
   		$form = $this->createFormBuilder()
	        ->add('user', 'entity', array(
			    'class' => 'PRO4UserBundle:User',
			    'property' => 'eMail',
			    'query_builder' => $em->getRepository("PRO4UserBundle:User")->findUsersInProjectNotInDepartment($project, $department),
    			'empty_value' => "Select User",
			))
			->getForm();
   		
   		$users = $department->getUsers();
   		foreach($users AS $user) {
   			$user->setIsOwner($this->isUserOwner($department, $user));
   			if(!$user->isOwner()) {
   				$user->setIsOperator($this->isUserOperator($department,$user));
   				if(!$user->isOperator()) {
	   				$user->setIsAdmin($this->isUserAdmin($department, $user));
	   			}
   			}
   		}
   		
   		if ($request->isMethod("POST")) {
    		$this->checkPermission("EDIT", $department);
	        $form->bind($request);
	        if ($form->isValid()) {
	        	
	        	$data = $form->getData();
	        	$user = $data["user"];
	        	
	        	$department->addUser($user);
	        	$this->makeViewer($department, $user);
	        	
	        	$em = $this->getDoctrine()->getManager();
   				$em->persist($department);
    			$em->flush();
    			
    			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully added " . $user->getEmail() . " to the department."
				);
				
				return $this->redirect($this->generateUrl("users_in_department", array("projectId" => $projectId, "departmentId" => $departmentId)));
	        }
        }
   		
   		return $this->render('PRO4ProjectBundle:Department:usersInDepartment.html.twig', array("form" => $form->createView(), "project" => $project, "department" => $department, "users" => $users));
   	}
   	
   	public function removeUserFromDepartmentAction($projectId, $departmentId, $userId) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
   		$department = $this->find("PRO4\ProjectBundle\Entity\Department", $departmentId);
   		$user = $this->find("PRO4\UserBundle\Entity\User", $userId);
   		
   		// check if user has sufficient permissions to delete a user
   		if($this->isUserAdmin($department, $user)) {
   			$this->checkPermission("OPERATOR", $department);
   		} else {
   			$this->checkPermission("EDIT", $department);
   		}
    	
    	if($this->getUser()->getUserId() == $userId || $this->isUserOperator($department, $user) || !$project->getUsers()->contains($user)) {
    		throw new InvalidArgumentException();
    	}
    	$department->removeUser($user);
    	$this->removePermissions($department, $user);
	            
        $em = $this->getDoctrine()->getManager();
		$em->persist($project);
		$em->flush();
    	
    	$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully removed ' . $user->getEmail() . ' from this department!'
				);
				
		return $this->redirect($this->generateUrl("users_in_department", array("projectId" => $project->getProjectId(), "departmentId" => $department->getDepartmentId())));
   	}
   	
   	public function grantAdminInDepartmentAction($projectId, $departmentId, $userId) {		
    	$department = $this->find("PRO4\ProjectBundle\Entity\Department", $departmentId);
   		$user = $this->find("PRO4\UserBundle\Entity\User", $userId);
   		
   		$this->checkPermission("OPERATOR", $department);
   		
    	if($this->getUser()->getUserId() == $userId || $this->isUserOperator($department, $user) || !$department->getUsers()->contains($user)) {
    		throw new InvalidArgumentException();
    	}
    	
    	$this->makeAdmin($department, $user);
    	
    	$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully gave ' . $user->getEmail() . ' admin-rights for this department!'
				);
				
		return $this->redirect($this->generateUrl("users_in_department", array("projectId" => $projectId, "departmentId" => $department->getDepartmentId())));
   	}
   	
   	public function revokeAdminInDepartmentAction($projectId, $departmentId, $userId) {
    	$department = $this->find("PRO4\ProjectBundle\Entity\Department", $departmentId);
    	$user = $this->find("PRO4\UserBundle\Entity\User", $userId);
    	
    	$this->checkPermission("OPERATOR", $department);
    	
    	if($this->getUser()->getUserId() == $userId || !$department->getUsers()->contains($user)) {
    		throw new InvalidArgumentException();
    	}
    	
    	$this->removePermissions($department, $user);
    	$this->makeViewer($department, $user);
    	
    	$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully took admin-rights from ' . $user->getEmail() . ' for this department!'
				);
				
		return $this->redirect($this->generateUrl("users_in_department", array("projectId" => $projectId, "departmentId" => $department->getDepartmentId())));
   	}
   
}
