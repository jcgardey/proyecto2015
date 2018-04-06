<?php

	namespace StoreBundle\Form;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	class HiddenDateType extends AbstractType {

		public function getParent() {
			return 'Symfony\Component\Form\Extension\Core\Type\HiddenType';
		}

		public function buildForm(FormBuilderInterface $builder, array $options) {
			$transformer = new DateTimeToStringTransformer();
			$builder->addModelTransformer($transformer);
		}

		public function configureOptions(OptionsResolver $resolver) {
        	parent::configureOptions($resolver);   
        	$resolver->setDefaults(array('data_class' => null));
    	}  
	}