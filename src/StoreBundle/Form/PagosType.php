<?php

	namespace StoreBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\CollectionType;
	use StoreBundle\Form\PagoType;

	use Symfony\Component\OptionsResolver\OptionsResolver;

	class PagosType extends AbstractType {

		public function buildForm(FormBuilderInterface $builder, array $options) {
			$alumno = $options["alumno"];

			$builder
				->add('pagos',CollectionType::class, array(

					'entry_type' => PagoType::class,
					'entry_options' => array('alumno' => $alumno),
					'allow_add' => true,
					'allow_delete' => true,
					'by_reference' => false,
				));
		}

		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults(array('data_class' => 'StoreBundle\Entity\Alumno', 'alumno' => null));
		}
	}