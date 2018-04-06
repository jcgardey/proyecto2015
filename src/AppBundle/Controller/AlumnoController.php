<?php

	namespace AppBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

	use StoreBundle\Form\AlumnoType;
	use StoreBundle\Form\PagosType;
	use StoreBundle\Entity\Alumno;

	class AlumnoController extends Controller {

		
		/**
		 * @Route("/alumno", name="nuevo_alumno") 
		 * @Security("has_role('ROLE_ADMIN')")
		 * @Method({"GET","POST"})
		 */
		public function nuevoAction(Request $request) {
			$alumno = new Alumno ();
			$form = $this->createForm(AlumnoType::class, $alumno);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$alumno->setBorrado(false);
				$alumno->setFechaAlta(new \DateTime());
				$em = $this->getDoctrine()->getManager();
				$em->persist($alumno);
				$em->flush();
				$request->getSession()->getFlashBag()->add('estado','El alumno fue ingresado correctamente');
				return $this->redirectToRoute('lista_alumnos');
			}
			return $this->render('alumno/ingresarAlumno.html.twig',array('form' => $form->createView() ));	
		}

		/**
		* @Route("/alumnos/{pagina}", name="lista_alumnos", defaults={"pagina" = 1})
		* @Security("has_role('ROLE_ADMIN')")
		*/
		public function listadoAlumnosAction() {
			$cantidadDePaginas;
			$alumnos = $this->getDoctrine()->getManager()->getRepository('StoreBundle:Alumno')->listarTodos($pagina,$cantidadDePaginas);
			return $this->render('alumno/listadoAlumnos.html.twig', array('alumnos' => $alumnos, 'paginaActual' => $pagina, 
				'cantidadDePaginas' => $cantidadDePaginas));
		}

		/**
		* @Route("/editar/alumno/{id}", name="editar_alumno")
		* @Security("has_role('ROLE_ADMIN')")
		*/
		public function editarAction(Request $request,Alumno $alumno) {
			if (!$alumno->getBorrado()) {
				$form = $this->createForm(AlumnoType::class, $alumno);
				$form->handleRequest($request);
				$formDelete = $this->createDeleteForm($alumno);
				if ($form->isSubmitted() && $form->isValid()) {
					$em = $this->getDoctrine()->getManager();
					$em->persist($alumno);
					$em->flush();
					$request->getSession()->getFlashBag()->add('estado','El alumno fue actualizado correctamente');
					return $this->redirectToRoute('lista_alumnos');
				}
				return $this->render('alumno/ingresarAlumno.html.twig',array('form' => $form->createView(),
				'delete_form' => $formDelete->createView() ));
			}
			else {
				return $this->redirectToRoute('lista_alumnos');
			}
		}

		private function createDeleteForm(Alumno $alumno) {
			return $this->createFormBuilder()
				->setAction($this->generateUrl('eliminar_alumno', array('id' => $alumno->getId())))
	            ->setMethod('DELETE')
	            ->getForm();
		}

		/**
		* @Route("/alumno/{id}", name="eliminar_alumno")
		* @Security("has_role('ROLE_ADMIN')")
		* @Method({"DELETE"})
		*/
		public function eliminarAction(Request $request, Alumno $alumno) {
			if (!$alumno->getBorrado()) {
				$alumno->setResponsables(null);
				$alumno->setBorrado(true);
				$em = $this->getDoctrine()->getManager();
				$em->persist($alumno);
				$em->flush();
				$request->getSession()->getFlashBag()->add('estado','El alumno fue eliminado correctamente');
			}
			return $this->redirectToRoute('lista_alumnos');
		}

		/**
		* @Route ("/alumno/{id}/pagar")
		* @Security("has_role('ROLE_GESTION')")
		*/
		public function registrarPagosAction(Request $request,Alumno $alumno) {
			if ($alumno->getBorrado()) {
				return $this->redirectToRoute('lista_alumnos');
			}
			$form = $this->createForm(PagosType::class,$alumno,array('alumno' => $alumno));
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($alumno);
				$em->flush();
				$request->getSession()->getFlashBag()->add('estado', 'Los pagos fueron registrados correctamente');
				return $this->redirectToRoute('lista_alumnos');
			}
			$cuotasRegistradas = $this->getDoctrine()->getManager()->getRepository('StoreBundle:Cuota')->cuotasRegistradasDeAlumno($alumno);
			return $this->render('alumno/listadoCuotasDeUnAlumno.html.twig',array('cuotasRegistradas' => $cuotasRegistradas, 
				'form' => $form->createView() ));				
		}
	}