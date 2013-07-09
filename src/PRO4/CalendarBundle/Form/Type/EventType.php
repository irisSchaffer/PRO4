<?php
namespace PRO4\CalendarBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PRO4\ProjectBundle\Entity\DepartmentRepository;
use PRO4\MainBundle\Form\Type\QueryBuilderDependentType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;


class EventType extends AbstractType {
	private $departmentChoice;

	public function __construct(array $departmentChoice) {
    	$this->departmentChoice = $departmentChoice;
    }

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$required = false;
		if(isset($options["attr"]["required"])) {
			$required = $options["attr"]["required"];
		}

		$builder->add("title", "text", array("label" => "Title"));
		$builder->add("description", "textarea", array("label" => "Description"));
		$builder->add('departmentId', 'choice', array(
			    'choices' => $this->departmentChoice,
    			'empty_value' => "Select Department",
    			'required' => $required,
    			'label' => "Department",
			));
		$builder->add('allDay', 'checkbox', array(
			'label' => "All Day"
		));

		$builder->add("date", "date", array("label" => "Date"));
		$builder->add("time", "time", array("label" => "Time"));

	}

	public function getName() {
		return 'event';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array (
			'data_class' => 'PRO4\CalendarBundle\Entity\Event',			
		));
	}
}