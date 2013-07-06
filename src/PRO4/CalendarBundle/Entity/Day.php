<?php

namespace PRO4\CalendarBundle\Entity;

use DateTime;

class Day extends DateTime {
	private $events;	
	
	public function setEvents(array $events) {
		$this->events = $events;
		
		return $this;
	}
	
	public function addEvent(\PRO4\CalendarBundle\Entity\Event $event) {
		$this->events[] = $event;
		
		return $this;
	}
	
	public function getEvents() {
		return $this->events;
	}
	
	public function getWeekdayNo() {
		return (integer)($this->format('N'));
	}
}