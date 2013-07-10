<?php

namespace PRO4\ToDoListBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityRepository;

use PRO4\ToDoListBundle\Entity\ToDoList;
use PRO4\ToDoListBundle\Form\Type\ToDoListType;

use PRO4\ToDoListBundle\Entity\ListItem;
use PRO4\ToDoListBundle\Form\Type\ListItemType;

use PRO4\ProjectBundle\Entity\Project;
use PRO4\ProjectBundle\Entity\Department;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use InvalidArgumentException;

class ToDoListController extends MyController {
	
	private function showToDoLists($action, $toDoList, $projectId, Request $request) {
		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$departments = $this->getUser()->getDepartments()->toArray();    		
    	$toDoLists = $em->getRepository("PRO4ToDoListBundle:ToDoList")->findToDoListsForProject($project, $departments)->getQuery()->getResult();
    	
    	$departmentChoice = array();
    	
		$required = !$this->hasPermission("EDIT", $project);
		
		foreach($project->getDepartments() as $department) {
			if($this->hasPermission("EDIT", $department)) {
				$departmentChoice[$department->getDepartmentId()] = $department->getName();
			}
		}
    	
    	$form = $this->createForm(
    		new ToDoListType($action, $departmentChoice),
    		$toDoList,
    		array("attr" => array("required" => $required))
		);
    	
    	$forms = array();
    	
    	foreach($toDoLists as $tdList) {
    		$item = new ListItem();
    		$item->setToDoList($tdList);
			$itemForm = $this->createForm(new ListItemType(), $item);
			$forms[] = $itemForm->createView();
    	}

    	if ($request->isMethod("POST")) {
	        $form->bind($request);
	        if ($form->isValid()) {	 
	        	
	        	// set department according to departmentId 
	        	$toDoList->setDepartment(($toDoList->getDepartmentId() ? $this->find("\PRO4\ProjectBundle\Entity\Department", $toDoList->getDepartmentId()) : null));
	        	
	        	if($department = $toDoList->getDepartment() !== null) {
	        		$this->checkPermission("EDIT", $department);
	        	} else {
	        		$this->checkPermission("EDIT", $toDoList->getProject());
	        	}
	        	       	
   				$em->persist($toDoList);
    			$em->flush();
    			
    			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully saved a to-do list."
				);
				
				return $this->redirect($this->generateUrl("to_do_lists", array("projectId" => $projectId)));
	        }
        }
    	
        return $this->render('PRO4ToDoListBundle:ToDoList:toDoListForm.html.twig',
        	array(
				"form" => $form->createView(),
				"forms" => $forms,
				"project" => $project,
				"toDoLists" => $toDoLists,
			)
		);
	}
	
    public function indexAction(Request $request, $projectId) {
    	$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	$toDoList = new ToDoList();
    	$toDoList->setProject($project);
    	
  		return $this->showToDoLists(ToDoListType::ADD, $toDoList, $projectId, $request);
    }
    
    public function editToDoListAction($projectId, $toDoListId, Request $request) {
    	$toDoList = $this->find("PRO4\ToDoListBundle\Entity\ToDoList", $toDoListId);
    	if($toDoList->getDepartment() !== null) {
    		$toDoList->setDepartmentId($toDoList->getDepartment()->getDepartmentId());
    	}
    	
    	return $this->showToDoLists(ToDoListType::EDIT, $toDoList, $projectId, $request);
    }
}
