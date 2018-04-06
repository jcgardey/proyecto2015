<?php

	namespace StoreBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	use Doctrine\ORM\EntityRepository;
	use Doctrine\ORM\QueryBuilder;
	
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\EmailType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\Extension\Core\Type\HiddenType;

	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class AlumnoType extends AbstractType {

		public function buildForm (FormBuilderInterface $builder,array $options) {

			$builder
				->add('apellido',TextType::class)
				->add('nombre',TextType::class)
				->add('tipoDocumento',ChoiceType::class, array(
					'choices' => array(
						'DNI' => 'DNI'
					),
					'choices_as_values' => true
				))
				->add('numeroDocumento',TextType::class)
				->add('fechaNacimiento',DateType::class,array(
					'widget' => 'single_text',
					'invalid_message' => 'fecha.invalida',
					'format' => 'dd-MM-yyyy',
					'html5' => false
				))
				->add('sexo',ChoiceType::class, array(
					'choices' => array(
						'Masculino' => 'Masculino',
						'Femenino' => 'Femenino'
					),
					'choices_as_values' => true
				))
				->add('mail',EmailType::class)
				->add('latitud',HiddenType::class)
				->add('longitud',HiddenType::class)
				->add('fechaIngreso',DateType::class,array(
					'widget' => 'single_text',
					'invalid_message' => 'fecha.invalida',
					'format' => 'dd-MM-yyyy',
					'html5' => false
				))
				->add('fechaEgreso',DateType::class,array(
					'widget' => 'single_text',
					'invalid_message' => 'fecha.invalida',
					'format' => 'dd-MM-yyyy',
					'html5' => false
				))
				->add('responsables',EntityType::class, array(
					'class' => 'StoreBundle:Responsable',
					'query_builder' => function (EntityRepository $er) {
						$qb = $er->createQueryBuilder('r');
						$qb->add('from', 'StoreBundle:Responsable r');
						$qb->add('where', 'r.borrado = false');
						return $qb;
					},
					'choice_label' => function ($responsable) {
						$rol = explode('_',$responsable->getUsuario()->getRol());
						return $responsable->getNombre().' '.$responsable->getApellido().' '.$rol[1];
					},
					'multiple' => true,
					'expanded' => true
				));
		}

		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults(array('data_class' => 'StoreBundle\Entity\Alumno'));
		}

	}