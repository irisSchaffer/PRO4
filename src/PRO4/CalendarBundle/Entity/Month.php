<?php

namespace PRO4\CalendarBundle\Entity;

class Month {
	private static $monthNames = {"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"};
	
	private $firstDay;
	private $days;
	private $month;
	private $year;
	
	public function __construct($month, $year) {
		$this->month = $month;
		$this->year = $year;
		$this->firstDay = new DateTime();
		$this->firstDay->setDate($year, $month, 1);
		
		$this->init();
	}	
	
	public function init() {
		$amountDays = $this->firstDay->format('t');
		for($i = 0; $i < $amountDays; ++i) {
			$day = new Day();
			$day->setDate($year, $month, $i + 1);
		}
	}

	public funtion getDays() {
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
	
	public function getMonthName() {
		return Month::monthNames[$this->month];
	}

}