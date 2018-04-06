<?php

	namespace StoreBundle\Validator\Constraints;

	use Symfony\Component\Validator\Constraint;
	use Symfony\Component\Validator\ConstraintValidator;

	class NaturalValidator extends ConstraintValidator {

		public function validate ($value, Constraint $constraint) {
			if (filter_var($value,FILTER_VALIDATE_INT,array('options' => array('min_range' => 0))) === false) {
				$this->context->buildViolation($constraint->message)->addViolation();

			}
		}
	}