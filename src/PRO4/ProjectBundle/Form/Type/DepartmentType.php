<?php
namespace PRO4\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PRO4\MainBundle\Form\Type\ColorPickerType;

class DepartmentType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		if(!isset($options["attr"]["disabled"])) {
			 $options["attr"]["disabled"] = false;
		}
		
		$builder->add("name", "text", array("label" => "name", "disabled" => $options["attr"]["disabled"]));
		$builder->add("color", new ColorPickerType(), array("label" => "Color", "disabled" => $options["attr"]["disabled"]));
		
	}

	public function getName() {
		return 'department';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array (
			'data_class' => 'PRO4\ProjectBundle\Entity\Department',			
		));
	}
}