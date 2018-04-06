<?php

	namespace StoreBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	use Doctrine\ORM\EntityRepository;
	use Doctrine\ORM\QueryBuilder;

	use Symfony\Component\Form\Extension\Core\Type\DateType;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\HiddenType;

	use StoreBundle\Form\HiddenDateType;

	use Symfony\Component\OptionsResolver\OptionsResolver;

	class PagoType extends AbstractType {

		public function buildForm(FormBuilderInterface $builder, array $options) {
			//se necesita el alumno para determinar cuales cuotas pueden pagarse
			$alumno = $options["alumno"];
			$builder
				->add('cuota',EntityType::class,array(
					'class' => 'StoreBundle:Cuota',
					'choice_label' => function ($cuota) {
						return $cuota->getTipo().', '.$cuota->getMes().' '.$cuota->getAnio().' $'.$cuota->getMonto();
					},
					'query_builder' => function(EntityRepository $er) use ($alumno) {
						$qb = $er->createQueryBuilder('c');
						$qb->add('from','StoreBundle:Cuota c,StoreBundle:Alumno a');
						$qb->add('where','a = :alu and (c.fecha >= a.fechaIngreso and (c.fecha <= a.fechaEgreso or a.fechaEgreso is null)) and  
							NOT EXISTS (SELECT p FROM StoreBundle:Pago p WHERE p.alumno = a and p.cuota = c ) ORDER BY c.fecha DESC');
						$qb->setParameter('alu',$alumno);
						return $qb;
					}
				))
				->add('fecha',DateType::class, array(
					'widget' => 'single_text',
					'format' => 'dd-MM-yyyy',
					'html5' => false
				))
				
				->add('becado',CheckboxType::class,array(
					'label' => 'Pago Becado',
					'required' => false
				))
				->add('fechaAlta',HiddenDateType::class,array(
					'data' => (new \DateTime())
				))
				->add('fechaActualizado',HiddenDateType::class,array(
					'data' => (new \DateTime())
				));


		}

		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults(array('data_class' => 'StoreBundle\Entity\Pago', 'alumno' => null));
		}

	}