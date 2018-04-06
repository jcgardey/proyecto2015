<?php

	namespace StoreBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\MoneyType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\NumberType;
	use Symfony\Component\Form\Extension\Core\Type\DateType;

	use Symfony\Component\OptionsResolver\OptionsResolver;

	class CuotaType extends AbstractType {

		public function buildForm(FormBuilderInterface $builder, array $options) {
			$builder
				->add('anio',ChoiceType::class,array(
					'choices' => $this->crearAnios(),
					'choices_as_values' => true
				))
				->add('mes', ChoiceType::class, array(
					'choices' => array (
						'Enero' => 'Enero',
						'Febrero' => 'Febrero',
						'Marzo' => 'Marzo',
						'Abril' => 'Abril',
						'Mayo' => 'Mayo',
						'Junio' => 'Junio',
						'Julio' => 'Julio',
						'Agosto' => 'Agosto',
						'Septiembre' => 'Septiembre',
						'Octubre' => 'Octubre',
						'Noviembre' => 'Noviembre',
						'Diciembre' => 'Diciembre'
					),
					'choices_as_values' => true
				))
				->add('numero',NumberType::class)
				->add('monto',NumberType::class)
				->add('tipo',ChoiceType::class, array(
					'choices' => array(
						'Mensual' => 'Mensual',
						'Matricula' => 'Matricula'
					),
					'choices_as_values' => true
				))
				->add('comisionCobrador',NumberType::class)
				->add('enviar',SubmitType::class);
		}

		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults(array('data_class' => 'StoreBundle\Entity\Cuota'));
		}

		private function crearAnios () {
			$añoActual = (new \DateTime())->format('Y');
			$anios=array();
			for ($i=($añoActual - 10); $i <= ($añoActual + 10) ; $i++) { 
				$anios["$i"] = "$i";
			}
			return $anios;
		}
	}