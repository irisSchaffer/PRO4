<?php
namespace PRO4\FileBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PRO4\ProjectBundle\Entity\DepartmentRepository;
use Doctrine\ORM\QueryBuilder;

use Symfony\Bridge\Doctrine\RegistryInterface;


class MyFileType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder->add("name", "text", array("label" => "Name"));
		$builder->add("file", "file", array("label" => "File"));
		$builder->add("description", "textarea", array("label" => "Description", "required" => false));
			
	}

	public function getName() {
		return 'myFile';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array (
			'data_class' => 'PRO4\FileBundle\Entity\File',			
		));
	}
}