<?php

namespace PRO4\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($projectId)
    {
    	
        return $this->showCalendarAction($projectId, );
    }
    
    public function showCalendarAction($projectId, $month, $year) {
    	
    }
}
