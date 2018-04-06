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

	use Symfony\Component\OptionsResolver\OptionsResolver;

	class ResponsableType extends AbstractType {

		public function buildForm(FormBuilderInterface $builder, array $options) {

			//se utilza el usuario asignado al responsable (en caso de que el responsable ya exista) para que ese usuario 
			// pueda estar entre los usuarios asignables.
			$usuario = $options['usuario'];
			
			$builder
				->add('nombre',TextType::class, array('required' => false))
				->add('apellido',TextType::class)
				->add('tipo',ChoiceType::class, array(
					'choices'=> array(
						'Madre' => 'Madre',
						'Padre' => 'Padre',
						'Tutor' => 'Tutor'
					),
					'choices_as_values' => true
				))
				->add('fechaNacimiento',DateType::class, array(
					'widget' => 'single_text',
					'format' => 'dd-MM-yyyy',
					'invalid_message' => 'fecha.invalida',
					'placeholder' => 'Ingrese la fecha en formato DD-MM-YYYY'
				))
				->add('sexo',ChoiceType::class, array (
					'choices'=> array(
						'Masculino' => 'Masculino',
						'Femenino' => 'Femenino'
					),
					'choices_as_values' => true
				))
				->add('mail',EmailType::class, array('required' => false))
				->add('telefono', TextType::class)
				->add('direccion', TextType::class)
				->add('usuario',EntityType::class, array(
					'class' => 'StoreBundle:Usuario',
					'choice_label' => function ($usuario) {
						$rol = explode("_",$usuario->getRol());
						return $usuario->getUsername().' '.$rol[1];
					},
					'query_builder' => function (EntityRepository $er) use ($usuario) {
						$qb = $er->createQueryBuilder('u');
						$qb->add('from','StoreBundle:Usuario u');
						$qb->add('where','u.borrado=false and u.rol != :r and (u NOT IN (select us FROM StoreBundle:Usuario us JOIN us.responsable r) or u = :user)');	
						$qb->setParameter('r', 'ROLE_ADMIN');
						$qb->setParameter('user',$usuario);
						return $qb;
							   		
					}
				))
				->add('enviar',SubmitType::class);
		}

		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults(array('data_class' => 'StoreBundle\Entity\Responsable', 'usuario' => null));
		}
	}