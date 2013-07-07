<?php
namespace PRO4\ToDoListBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PRO4\ProjectBundle\Entity\DepartmentRepository;
use Doctrine\ORM\QueryBuilder;

use Symfony\Bridge\Doctrine\RegistryInterface;


class ToDoListType extends AbstractType {
	
	const ADD = 0;
	const EDIT = 1;
	
	private $queryBuilder;
	private $action;

    public function __construct($action, QueryBuilder $queryBuilder) {
    	$this->action = $action;
    	$this->queryBuilder = $queryBuilder;
    }
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$required = false;
		if(isset($options["attr"]["required"])) {
			$required = $options["attr"]["required"];
		}

		$builder->add("name", "text", array("label" => "Name"));
		$builder->add('department', 'entity', array(
			    'class' => 'PRO4ProjectBundle:Department',
			    'property' => 'name',
			    'query_builder' => $this->queryBuilder,
    			'empty_value' => "Select Department",
    			'required' => $required,
    			'label' => "Department",
			));
			
		if($this->action === ToDoListType::EDIT) {
			$builder->add('completed', 'checkbox', array("label" => "Completed"));
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