<?php

namespace PRO4\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PRO4\ProjectBundle\Entity\DepartmentRepository;
use PRO4\MainBundle\Form\Type\QueryBuilderDependentType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;


class DepartmentChoiceType extends AbstractType {
	private $departmentChoice;

	public function __construct(array $departmentChoice) {
    	$this->departmentChoice = $departmentChoice;
    }

	public function getDepartmentChoice() {
		return $this->departmentChoice;
	}
	
	public function setDepartmentChoice(array $departmentChoice) {
		$this->departmentChoice = $departmentChoice;
		
		return $this;
	}
	
	public function getName() {
		return 'department_choice';
	}
}