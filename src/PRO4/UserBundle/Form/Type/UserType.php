<?php
namespace PRO4\UserBundle\Form\Type;

use Symfony\Component\Security\Core\Validator\Constraints as Constraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {
	
	const CHANGE_PASSWORD = 1;
	const REGISTER = 2;
	const EMAIL_ONLY = 3;
	private $mode;

	
	public function __construct($mode) {
		$this->mode = $mode;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		if($this->mode !== UserType::CHANGE_PASSWORD) {
			$builder->add("eMail", "email", array("label" => "Email"));
		}
		
		if($this->mode !== UserType::EMAIL_ONLY) {
			if($this->mode !== UserType::REGISTER) {
				$builder->add("oldPassword", "password",
					array(
						"label" => "Old Password",
						"mapped" => false,
						"constraints" => array(new Constraints\UserPassword()
					)
				));
			}
			
			$builder->add("password", "repeated",
				array(
					"type" => "password",
					"invalid_message" => "The password fields must match.",
					"first_options"  => array("label" => "Password"),
					"second_options" => array("label" => "Confirm Password"),
				)
			);
		}
		
		
	}

	public function getName() {
		return "user";
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array (
			"data_class" => "PRO4\UserBundle\Entity\User",			
		));
	}
}