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

use IllegalArgumentException;

class MilestoneController extends MyController {
   	
   	public function addAction(Request $request, $projectId, $milestonePlanId) {
   		$milestonePlan = $this->find("PRO4\MilestoneBundle\Entity\MilestonePlan", $milestonePlanId);
   		
   		$milestone = new Milestone();
   		$milestone->setMilestonePlan($milestonePlan);
   		$milestone->setStartDate($milestonePlan->getStartDate());
   		$milestone->setEndDate($milestonePlan->getEndDate());

	    return $this->milestoneAction("You successfully added a milestone", $projectId, $milestonePlan, $milestone, $request);
   	}
   	
   	private function milestoneAction($message, $projectId, MilestonePlan $milestonePlan, Milestone $milestone, Request $request) {
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
   		
   		$this->checkPermission("EDIT", $project);
   		
   		if($milestonePlan->getProject() !== $project) {
   			throw new IllegalArgumentException();
   		}
   		
   		$form = $this->createForm(new MilestoneType(), $milestone);
   		
   		if ($request->isMethod("POST")) {   			
	        $form->bind($request);

	        if ($form->isValid()) {
   				$this->persist($milestone);
			
    			$this->get('session')->getFlashBag()->add(
				    'success',
				    $message
				);

				return $this->redirect($this->generateUrl("milestone_plan", array("id" => $projectId)));
	        }
	    }
		
		$milestones = $milestonePlan->getMilestones();
		
		return $this->render(
			"PRO4MilestoneBundle:Milestone:milestoneForm.html.twig",
			array(
				"project" => $project,
				"milestonePlan" => $milestonePlan,
				"milestones" => $milestones,
				"form" => $form->createView(),
			)
		);
   	}
   	
   	public function editAction(Request $request, $projectId, $milestonePlanId, $milestoneId) {
   		$milestonePlan = $this->find("PRO4\MilestoneBundle\Entity\MilestonePlan", $milestonePlanId);
   		$milestone = $this->find("PRO4\MilestoneBundle\Entity\Milestone", $milestoneId);
   		
   		if($milestone->getMilestonePlan() !== $milestonePlan) {
   			throw new InvalidArgumentException();
   		}
   		
	    return $this->milestoneAction("You successfully edited a milestone", $projectId, $milestonePlan, $milestone, $request);
	}
	
	public function deleteAction(Request $request, $projectId, $milestonePlanId, $milestoneId) {
		$milestonePlan = $this->find("PRO4\MilestoneBundle\Entity\MilestonePlan", $milestonePlanId);
   		$milestone = $this->find("PRO4\MilestoneBundle\Entity\Milestone", $milestoneId);
   		$project = $this->find("PRO4\ProjectBundle\Entity\Project", $projectId);
   		
   		$this->checkPermission("EDIT", $project);
   		
   		if($milestone->getMilestonePlan() !== $milestonePlan || $milestonePlan->getProject() !== $project) {
   			throw new InvalidArgumentException();
   		}
   		
   		$this->remove($milestone);
   		
   		$this->get('session')->getFlashBag()->add(
				    'success',
				    "You successfully deleted a milestone"
				);
   		
   		return $this->redirect($this->generateUrl("milestone_plan", array("id" => $projectId)));
	}
}