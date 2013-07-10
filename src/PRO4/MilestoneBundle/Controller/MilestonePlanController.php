<?php

namespace PRO4\MilestoneBundle\Controller;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use PRO4\MilestoneBundle\Form\Type\MilestonePlanType;
use PRO4\MilestoneBundle\Entity\MilestonePlan;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class MilestonePlanController extends MyController {
   	
   	public function indexAction(Request $request, $id) {   	
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $id);
   		$this->checkPermission("VIEW", $project);	
   		return $this->milestonePlanRedirect($id, "milestone_overview", "edit_milestone_plan");
	}
	
	public function overviewAction($id) {
		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $id);
   		$this->checkPermission("VIEW", $project);
   		
		$milestonePlan = $this->getMilestonePlan($id);
		if(!$milestonePlan) {
			return $this->redirect($this->generateUrl("add_milestone_plan", array("id" => $id)));
		}
		
		$milestones = $milestonePlan->getMilestones();
		
		return $this->render(
			"PRO4MilestoneBundle:MilestonePlan:milestoneOverview.html.twig",
			array(
				"project" => $project,
				"milestonePlan" => $milestonePlan,
				"milestones" => $milestones
			)
		);
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
   				$this->persist($milestonePlan);
			
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