<?php
namespace PRO4\CalendarBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PRO4\ProjectBundle\Entity\DepartmentRepository;

use Symfony\Bridge\Doctrine\RegistryInterface;


class EventType extends AbstractType {
	
	private $project;
	private $doctrine;

    public function __construct(\PRO4\ProjectBundle\Entity\Project $project, RegistryInterface $doctrine) {
    	$this->project = $project;
        $this->doctrine = $doctrine;
    }
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$required = false;
		if(isset($options["attr"]["required"])) {
			$required = $options["attr"]["required"];
		}

		$builder->add("title", "text", array("label" => "Title"));
		$builder->add("description", "textarea", array("label" => "Description"));
		$builder->add('department', 'entity', array(
			    'class' => 'PRO4ProjectBundle:Department',
			    'property' => 'name',
			    'query_builder' => $this->doctrine->getRepository('PRO4ProjectBundle:Department')->findDepartmentsInProject($this->project),
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