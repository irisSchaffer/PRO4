<?php

namespace PRO4\CalendarBundle\Controller;

use DateTime;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Doctrine\ORM\EntityRepository;

use PRO4\CalendarBundle\Entity\Month;
use PRO4\CalendarBundle\Entity\Day;
use PRO4\CalendarBundle\Entity\Event;
use PRO4\CalendarBundle\Form\Type\EventType;

class CalendarController extends MyController
{
    public function indexAction($projectId, Request $request)
    {
    	$today = new DateTime("NOW");
        return $this->addEventAction(
			$projectId,
			$today->format('m'),
			$today->format('Y'),
			$request
    	);
    }
    
    private function showCalendar($event, $projectId, $monthNo, $year, Request $request) {
    	$project = $this->find("\PRO4\ProjectBundle\Entity\Project", $projectId);
    	$departments = $project->getDepartments()->toArray();
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	$month = new Month($monthNo, $year);
    	
    	foreach($month->getDays() AS $day) {
    		$events = $em->getRepository("PRO4CalendarBundle:Event")->findEventsForProject($day, $project, $departments)->getQuery()->getResult();
    		$day->setEvents($events);
    	}
    	
		$departmentChoice = array();
		$required = false;
		
		if($this->hasPermission("EDIT", $project)) {
			foreach($project->getDepartments() as $department) {
				$departmentChoice[$department->getDepartmentId()] = $department->getName();
			}
		} else {
			foreach($departments as $department) {
				$required = true;
				if($this->hasPermission("EDIT", $department)) {
					$departmentChoice[$department->getDepartmentId()] = $department->getName();
				}
			}
		}

		$form = $this->createForm(
    		new EventType($departmentChoice),
    		$event,
    		array("attr" => array("required" => $required))
		);

		
    	if ($request->isMethod("POST")) {
	        $form->bind($request);
	        if ($form->isValid()) {
	        	if($event->isAllDay()) {
	        		$event->setTime(null);
	        	}
	        	
	        	if($event->getDepartmentId()) {
	        		$event->setDepartment($this->find("\PRO4\ProjectBundle\Entity\Department", $event->getDepartmentId()));
	        	}
	        	
	        	if($event->getDepartment() !== null) {
	        		$this->checkPermission("EDIT", $event->getDepartment());
	        	} else {
	        		$this->checkPermission("EDIT", $event->getProject());
	        	}
	        	
	        	$em = $this->getDoctrine()->getManager();
	        	$em->persist($event);
    			$em->flush();
    			
    			$this->get('session')->getFlashBag()->add(
				    "success",
				    "You successfully saved the event."
				);
	        }
	        return $this->redirect(
	        	$this->generateUrl(
	        		"show_calendar",
	        		array(
	        			"projectId" => $projectId,
	        			"monthNo" => $monthNo,
	        			"year" => $year
	        		)
        		)
	        );
        }
    	
    	
    	return $this->render(	'PRO4CalendarBundle:Calendar:eventForm.html.twig',
								array(
									"month" => $month,
									"weekdays" => Month::$weekdays,
									"form" => $form->createView(),
									"project" => $project
								)
		);
    }
    
    public function addEventAction($projectId, $monthNo, $year, Request $request) {
    	$project = $this->find("\PRO4\ProjectBundle\Entity\Project", $projectId);
    	$newEvent = new Event();
    	$newEvent->setDate(new DateTime('NOW'));
    	$newEvent->setProject($project);
    	
    	return $this->showCalendar($newEvent, $projectId, $monthNo, $year, $request);
    }
    
    public function editEventAction($eventId, $projectId, $monthNo, $year, Request $request) {
    	$event = $this->find("\PRO4\CalendarBundle\Entity\Event", $eventId);
    	if($event->getTime() === null) {
    		$event->setAllDay(true);
    	}
    	
    	if($event->getDepartment() !== null) {
    		$event->setDepartmentId($event->getDepartment()->getDepartmentId());
    	}
    	
    	return $this->showCalendar($event, $projectId, $monthNo, $year, $request);
    }
    
    public function deleteEventAction($eventId, $projectId, $monthNo, $year) {
    	$event = $this->find("\PRO4\CalendarBundle\Entity\Event", $eventId);
    	
    	if($event->getDepartment() !== null) {
    		$this->checkPermission("EDIT", $event->getDepartment());
    	} else {
    		$this->checkPermission("EDIT", $event->getProject());
    	}
    	
    	return $this->redirect(
    		$this->generateUrl(
    			"show_calendar",
    			array(
    				"projectId" => $projectId,
					"monthNo" => $montNo,
					"year" => $year
    			)
			)
    	);
    }
}
