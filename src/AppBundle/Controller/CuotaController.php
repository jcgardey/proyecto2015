<?php

	namespace AppBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

	use StoreBundle\Form\CuotaType;
	use StoreBundle\Entity\Cuota;

	/**
	 * @Security("has_role('ROLE_GESTION')")
	 */
	class CuotaController extends Controller {

		/**
		 * @Route("/cuota", name="nueva_cuota")
		 * @Method({"POST","GET"})
		 */
		public function nuevoAction(Request $request) {
			$cuota = new Cuota();
			$form = $this->createForm(CuotaType::class, $cuota);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$cuota->setFechaAlta(new \DateTime());
				$cuota->setBorrado(false);
				$this->guardarCuota($cuota);
				$request->getSession()->getFlashBag()->add('estado', 'La cuota fue ingresada correctamente');
				return $this->redirectToRoute('lista_cuotas');
			}
			return $this->render('cuota/ingresarCuota.html.twig',array('form' => $form->createView() ));
		}

		private function crearFecha($mes, $anio) {
			$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
			$mesesEnNumeros=array('01','02','03','04','05','06','07','08','09','10','11','12');
			$mesNumerico = str_replace($meses,$mesesEnNumeros,$mes);
			return \DateTime::createFromFormat('m-Y',$mesNumerico.'-'.$anio);
		}

		
		/**
		 * @Route("/cuotas/{pagina}", name="lista_cuotas", defaults={"pagina":1})
		 */
		public function listadoCuotasAction($pagina) {
			$cantidadDePaginas;
			$cuotas = $this->getDoctrine()->getManager()->getRepository('StoreBundle:Cuota')->listarTodas($pagina,$cantidadDePaginas);
			return $this->render('cuota/listadoCuotas.html.twig',array('paginaActual' => $pagina, 'cantidadDePaginas' => $cantidadDePaginas,
				'cuotas' => $cuotas));
		}

		/**
		 * @Route("/editar/cuota/{id}", name="editar_cuota")
		 */
		public function editarAction(Request $request, Cuota $cuota) {
			if ($cuota->getBorrado()) {
				return $this->redirectToRoute('lista_cuotas');
			}
			$form = $this->createForm(CuotaType::class,$cuota);
			$formDelete = $this->createDeleteForm($cuota);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$this->guardarCuota($cuota);
				$request->getSession()->getFlashBag()->add('estado', 'La cuota fue ingresada correctamente');	
				return $this->redirectToRoute('lista_cuotas');
			}
			return $this->render('cuota/ingresarCuota.html.twig',array('form' => $form->createView(),
				'delete_form' => $formDelete->createView() ));	
		}

		private function createDeleteForm($cuota) {
			return $this->createFormBuilder()
						->setMethod('DELETE')
						->setAction($this->generateUrl('eliminar_cuota',array('id' => $cuota->getId() )))
						->getForm();
		}

		/**
		 * @Route("/cuota/{id}", name="eliminar_cuota")
		 * @Method({"DELETE"})
		 */
		public function eliminarAction(Request $request,Cuota $cuota) {
			if (!$cuota->getBorrado()) {
				$cuota->setBorrado(true);
				$em = $this->getDoctrine()->getManager();
				$em->persist($cuota);
				$em->flush();
				$request->getSession()->getFlashBag()->add('estado','La cuota fue eliminada correctamente');
			}
			return $this->redirectToRoute('lista_cuotas');
		}

		private function guardarCuota($cuota) {
			//Se crea una fecha en funcion del mes y del año ingresados para despues poder ordenar las cuotas por mes y año
			$fechaCuota = $this->crearFecha($cuota->getMes(),$cuota->getAnio());
			$cuota->setFecha($fechaCuota);
			$em = $this->getDoctrine()->getManager();
			$em->persist($cuota);
			$em->flush();
		}
	}