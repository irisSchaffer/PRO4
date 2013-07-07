<?php

namespace PRO4\MilestoneBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use PRO4\MilestoneBundle\Form\Type\MilestoneType;
use PRO4\MilestoneBundle\Entity\MilestonePlan;
use PRO4\MilestoneBundle\Entity\Milestone;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class MilestoneController extends MyController {
   	
   	public function addAction(Request $request, $projectId, $milestonePlanId) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
   		$milestonePlan = $this->find("PRO4\MilestoneBundle\Entity\MilestonePlan", $milestonePlanId);
   		$this->checkPermission("EDIT", $project);
   		
   		$milestone = new Milestone();
   		$milestone->setMilestonePlan($milestonePlan);
   		$milestone->setStartDate($milestonePlan->getStartDate());
   		$milestone->setEndDate($milestonePlan->getEndDate());
   		
   		$form = $this->createForm(new MilestoneType(), $milestone);
   		
   		if ($request->isMethod("POST")) {   			
	        $form->bind($request);

	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();
   				$em->persist($milestone);
    			$em->flush();
			
    			$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully added a milestone!'
				);

				return $this->redirect($this->generateUrl("milestone_plan", array("id" => $projectId)));
	        }
	    }

	    return $this->render("PRO4MilestoneBundle:Milestone:milestoneForm.html.twig", array("project" => $project, "action" => "Add", "form" => $form->createView()));	
   	}
   	
   	public function editAction(Request $request, $projectId, $milestonePlanId, $milestoneId) {
		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
   		$milestonePlan = $this->find("PRO4\MilestoneBundle\Entity\MilestonePlan", $milestonePlanId);
   		$milestone = $this->find("PRO4\MilestoneBundle\Entity\Milestone", $milestoneId);
   		
   		$this->checkPermission("EDIT", $project);
   		
   		$form = $this->createForm(new MilestoneType(), $milestone, array("disabled" => true));
   		
   		if ($request->isMethod("POST")) {   			
	        $form->bind($request);

	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();
   				$em->persist($milestone);
    			$em->flush();
			
    			$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully added a milestone!'
				);

				return $this->redirect($this->generateUrl("milestone_plan", array("id" => $projectId)));
	        }
	    }

	    return $this->render("PRO4MilestoneBundle:Milestone:milestoneForm.html.twig", array("project" => $project, "action" => "Add", "form" => $form->createView()));	
	}
   	
   	
   	public function indexAction(Request $request, $id) {   	
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $id);
   		$this->checkPermission("VIEW", $project);	
   		return $this->milestonePlanRedirect($id, "milestone_overview", "edit_milestone_plan");
	}
   	
   	public function editMilestonePlanAction(Request $request, $id) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $id);
   		$milestonePlan = $this->getMilestonePlan($id);
   		$freshlyAdded = false;
   		
   		if(!$milestonePlan) {
   			$freshlyAdded = true;
			$milestonePlan = new MilestonePlan();
	
			$milestonePlan->setProject($project);
		} 
		$showForm = $this->hasPermission("EDIT", $project);

    	$form = $this->createForm(new MilestonePlanType(), $milestonePlan);

    	if ($request->isMethod("POST")) {
   			$this->checkPermission("EDIT", $project);
   			
	        $form->bind($request);

	        if ($form->isValid()) {
	        	$em = $this->getDoctrine()->getManager();
   				$em->persist($milestonePlan);
    			$em->flush();
			
    			$this->get('session')->getFlashBag()->add(
				    'success',
				    'You successfully updated the milestone plan!'
				);
				
				if($freshlyAdded) {
					return $this->redirect($this->generateUrl("users_in_project", array("id" => $project->getId())));
				} else {
					return $this->redirect($this->generateUrl("milestone_plan", array("id" => $project->getId())));
				}
	        }
	    }

	    return $this->render("PRO4MilestoneBundle:MilestonePlan:milestonePlanForm.html.twig", array("project" => $project, "showForm" => $showForm, "form" => $form->createView()));	
   	}
   	
	private function milestonePlanRedirect($id, $success, $failure) {
		if($this->getMilestonePlan($id)) {
			return $this->redirect($this->generateUrl($success, array("id" => $id)));
   		} else {
   			return $this->redirect($this->generateUrl($failure, array("id" => $id)));
   		}
	}
	
	private function getMilestonePlan($id) {
		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $id);
		return $this->getDoctrine()->getRepository('PRO4MilestoneBundle:MilestonePlan')->findOneByProject($project);
	}
}