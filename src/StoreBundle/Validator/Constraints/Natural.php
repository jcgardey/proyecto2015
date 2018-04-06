<?php

	namespace StoreBundle\Validator\Constraints;

	use Symfony\Component\Validator\Constraint;

	/**
	* @Annotation
	*/
	class Natural extends Constraint {

		public $message = 'The string is not a valid natural number';
	}
