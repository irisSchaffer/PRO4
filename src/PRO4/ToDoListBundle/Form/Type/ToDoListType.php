<?php
namespace PRO4\ToDoListBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PRO4\ProjectBundle\Entity\DepartmentRepository;
use PRO4\MainBundle\Form\Type\DepartmentChoiceType;
use Doctrine\ORM\QueryBuilder;

use Symfony\Bridge\Doctrine\RegistryInterface;


class ToDoListType extends DepartmentChoiceType {
	
	const ADD = 0;
	const EDIT = 1;
	
	private $action;

    public function __construct($action, array $departmentChoice) {
    	parent::__construct($departmentChoice);
    	$this->action = $action;
    }
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$required = false;
		if(isset($options["attr"]["required"])) {
			$required = $options["attr"]["required"];
		}

		$builder->add("name", "text", array("label" => "Name"));
		$builder->add('departmentId', 'choice', array(
			    'choices' => $this->getDepartmentChoice(),
    			'empty_value' => "Select Department",
    			'required' => $required,
    			'label' => "Department",
			));
			
		if($this->action === ToDoListType::EDIT) {
			$builder->add('completed', 'checkbox', array("label" => "Completed", "required" => false));
		}
	}

	public function getName() {
		return 'toDoList';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array (
			'data_class' => 'PRO4\ToDoListBundle\Entity\ToDoList',			
		));
	}
}