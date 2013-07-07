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

class ListItemController extends MyController {
    
    public function addItemAction(Request $request, $projectId, $toDoListId) {
    	$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	$toDoList = $this->find("PRO4\ToDoListBundle\Entity\ToDoList", $toDoListId);
    	
    	
    	$this->checkPermission("VIEW", $project);
    	if($department = $toDoList->getDepartment() !== null) {
    		$this->checkPermission("VIEW", $department);
    	}
		if($toDoList->getProject() !== $project) {
    		throw new InvalidArgumentException();
    	}
    	
    	$item = new ListItem();
    	$item->setToDoList($toDoList);
		$form = $this->createForm(new ListItemType(), $item);
		
    	if ($request->isMethod("POST")) {
	        $form->bind($request);
	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();
	        	$em->persist($item);
    			$em->flush();
    			
    			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully added an item to to-do list " . $toDoList->getName() . "."
				);
	        }
        }

    	return $this->redirect($this->generateUrl("to_do_lists", array("projectId" => $projectId)));
    }
    
    public function completeItemAction($projectId, $toDoListId, $itemId) {
    	return $this->itemAction("completed", "complete", $projectId, $toDoListId, $itemId);
    }
    
    public function undoItemAction($projectId, $toDoListId, $itemId) {
    	$this->checkEditPermissions($toDoListId);
    	
    	return $this->itemAction("undid", "undo", $projectId, $toDoListId, $itemId);
    }
    
    public function deleteItemAction($projectId, $toDoListId, $itemId) {

		$this->checkEditPermissions($toDoListId);
    	
    	return $this->itemAction("deleted", "delete", $projectId, $toDoListId, $itemId);
    }
    
    private function itemAction($text, $function, $projectId, $toDoListId, $itemId) {
    	$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	$toDoList = $this->find("PRO4\ToDoListBundle\Entity\ToDoList", $toDoListId);
    	$item = $this->find("PRO4\ToDoListBundle\Entity\ListItem", $itemId);
    	
    	$this->checkPermission("VIEW", $project);
    	if($department = $toDoList->getDepartment() !== null) {
    		$this->checkPermission("VIEW", $department);
    	}
    	
    	if($toDoList->getProject() !== $project || $item->getToDoList() !== $toDoList) {
    		throw new InvalidArgumentException();
    	}
    	
    	$item->$function();
    	$em = $this->getDoctrine()->getManager();
		$em->persist($item);
		$em->flush();
		
		$this->get('session')->getFlashBag()->add(
				    "success",
				    "You " .  $text . " an item."
				);
		
		return $this->redirect($this->generateUrl("to_do_lists", array("projectId" => $projectId)));
    }
    
    private function checkEditPermissions($toDoListId) {
    	$toDoList = $this->find("PRO4\ToDoListBundle\Entity\ToDoList", $toDoListId);
    	
    	if($department = $toDoList->getDepartment() !== null) {
    		$this->checkPermission("EDIT", $department);
    	} else {
    		$this->checkPermission("EDIT", $toDoList->getProject());
    	}
    }
}
