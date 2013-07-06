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

class ToDoListController extends MyController {
	
    public function indexAction(Request $request, $projectId) {
    	$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
    	
    	$toDoList = new ToDoList();
    	$toDoList->setProject($project);
    	$form = $this->createForm(new ToDoListType($project, $this->getDoctrine()), $toDoList, array("attr" => array("required" => false)));
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$departments = $this->getUser()->getDepartments()->toArray();    		
    	$toDoLists = $em->getRepository("PRO4ToDoListBundle:ToDoList")->findToDoListsForProject($project, $departments)->getQuery()->getResult();
    	
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
	        	$em = $this->getDoctrine()->getManager();
   				$em->persist($toDoList);
    			$em->flush();
    			
    			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully added a to-do list."
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
}
