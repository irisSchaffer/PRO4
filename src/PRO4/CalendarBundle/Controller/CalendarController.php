<?php

namespace PRO4\CalendarBundle\Controller;

use DateTime;

use PRO4\MainBundle\Controller\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use PRO4\CalendarBundle\Entity\Month;
use PRO4\CalendarBundle\Entity\Day;
use PRO4\CalendarBundle\Entity\Event;

class CalendarController extends MyController
{
    public function indexAction($projectId)
    {
    	$today = new DateTime("NOW");
        return $this->showCalendarAction($projectId, $today->format('m'), $today->format('Y'));
    }
    
    public function showCalendarAction($projectId, $monthNo, $year) {
    	$month = new Month($monthNo, $year);
    	
    	return $this->render(	'PRO4CalendarBundle:Calendar:index.html.twig',
								array(
									"month" => $month,
									"weekdays" => Month::$weekdays,
								)
		);
    }
}
