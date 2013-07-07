<?php
namespace PRO4\ToDoListBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PRO4\ProjectBundle\Entity\DepartmentRepository;
use Doctrine\ORM\QueryBuilder;

use Symfony\Bridge\Doctrine\RegistryInterface;


class MyFileType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder->add("name", "text", array("label" => "Name"));
		$builder->add('department', 'entity', array(
			    'class' => 'PRO4ProjectBundle:Department',
			    'property' => 'name',
			    'query_builder' => $this->getQueryBuilder(),
    			'empty_value' => "Select Department",
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