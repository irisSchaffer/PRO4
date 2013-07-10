<?php

namespace PRO4\CalendarBundle\Entity;

use DateTime;

class Month {
	public static $monthNames = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	public static $weekdays = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	
	private $firstDay;
	private $days;
	private $month;
	private $year;
	
	public function __construct($month, $year) {
		$this->month = (int)$month;
		$this->year = (int)$year;
		$this->firstDay = new DateTime();
		$this->firstDay->setDate($year, $month, 1);
		
		$this->init();
	}	
	
	public function init() {
		$amountDays = $this->getAmountDays();
		for($i = 0; $i < $amountDays; ++$i) {
			$day = new Day();
			$day->setDate($this->year, $this->month, $i + 1);
			
			$this->days[] = $day;
		}
	}

	public function getDays() {
		return $this->days;
	}
		
	public function setYear($year) {
		$this->year = $year;
		
		return $this;
	}
	
	public function getYear() {
		return $this->year;
	}
	
	public function setMonth($month) {
		$this->month = $month;
		
		return $this;
	}
	
	public function getMonth() {
		return $this->month;
	}
	
	public function getAmountDays() {
		return $this->firstDay->format('t');
	}
	
	public function getMonthName() {
		return Month::$monthNames[$this->month - 1];
	}
	
	public function getLastMonth() {
		$year;
		$month;
		if($this->month === 1) {
			$year = $this->year - 1;
			$month = 12;
		} else {
			$year = $this->year;
			$month = $this->month - 1;
		}
		
		return new Month($month, $year);
	}
	
	public function getNextMonth() {
		$year;
		$month;
		if($this->month === 12) {
			$year = $this->year + 1;
			$month = 1;
		} else {
			$year = $this->year;
			$month = $this->month + 1;
		}
		
		return new Month($month, $year);
	}

}