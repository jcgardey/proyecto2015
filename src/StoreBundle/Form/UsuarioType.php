<?php
	
	namespace StoreBundle\Form;

	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;

	use Doctrine\ORM\EntityRepository;
	
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\EmailType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;

	use Symfony\Component\OptionsResolver\OptionsResolver;

	class UsuarioType extends AbstractType {

		public function buildForm(FormBuilderInterface $builder, array $options) {

			$builder
				->add('username', TextType::class)
				->add('mail',EmailType::class)
				->add('password', PasswordType::class, array('always_empty' => false))
				->add('enviar', SubmitType::class)
				->add('rol',ChoiceType::class, array(
					'choices' => array (
						'Administrador' => 'ROLE_ADMIN',
						'Gestion' => 'ROLE_GESTION',
						'Consulta' => 'ROLE_CONSULTA'
						),
					'choices_as_values' => true
				));
		}

		public function configureOptions(OptionsResolver $resolver) {
			$resolver->setDefaults(array('data_class' => 'StoreBundle\Entity\Usuario'));
		}
	}