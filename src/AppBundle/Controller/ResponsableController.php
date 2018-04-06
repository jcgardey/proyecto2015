<?php

	namespace AppBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

	use StoreBundle\Entity\Responsable;
	use StoreBundle\Form\ResponsableType;

	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/

	class ResponsableController extends Controller {


		/**
		* @Route("/responsable", name="nuevo_responsable")
		* @Method({"GET","POST"})
		*/
		public function nuevoAction(Request $request) {
			$responsable = new Responsable ();
			$form = $this->createForm(ResponsableType::class,$responsable);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$responsable->setBorrado(false);
				$em = $this->getDoctrine()->getManager();
				$em->persist($responsable);
				$em->flush();
				$request->getSession()->getFlashBag()->add('estado', 'El responsable fue creado correctamente');
				return $this->redirectToRoute('lista_responsables');
			}
			return $this->render('responsable/ingresarResponsable.html.twig',array('form' => $form->createView()));
		}

		/**
		* @Route("/responsables/{pagina}",name="lista_responsables",defaults={"pagina"=1})
		*/
		public function listaResponsablesAction($pagina) {
			$cantidadPaginas;
			$responsables = $this->getDoctrine()->getManager()->getRepository('StoreBundle:Responsable')->listarTodos($pagina,$cantidadPaginas);
			return $this->render('responsable/listadoResponsables.html.twig',array('responsables' => $responsables, 'paginaActual' => $pagina,
				'cantidadDePaginas' => $cantidadPaginas));
		}	

		/**
		* @Route("/editar/responsable/{id}", name="editar_responsable")
		*/
		public function editarAction(Request $request, Responsable $responsable) {
			if ($responsable->getBorrado()) {
				return $this->redirectToRoute('lista_responsables');
			}
			$form = $this->createForm(ResponsableType::class,$responsable,array('usuario' => $responsable->getUsuario() ));
			$deleteForm = $this->createDeleteForm($responsable);
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($responsable);
				$em->flush();
				$request->getSession()->getFlashBag()->add('estado', 'El responsable fue modificado correctamente');
				return $this->redirectToRoute('lista_responsables');
			}
			return $this->render('responsable/ingresarResponsable.html.twig',array('form' => $form->createView(),
				'delete_form' => $deleteForm->createView()));
		}

		private function createDeleteForm(Responsable $responsable) {
			return $this->createFormBuilder()
            ->setAction($this->generateUrl('eliminar_responsable', array('id' => $responsable->getId())))
            ->setMethod('DELETE')
            ->getForm();
		}

		/**
		* Un responsable no puede ser eliminado si posee alumnos a su cargo. Cuando se elimina el responsable, el usuario que tenía 
  		* asignado vuelve a estar disponible para ser asignado a otro usuario
		* @Route("/responsable/{id}", name="eliminar_responsable")
		* @Method("DELETE")
		*/
		public function eliminarAction(Request $request,Responsable $responsable) {
			if (!$responsable->getBorrado()) {
				if ($responsable->getAlumnos()->isEmpty()) {
					$responsable->setBorrado(true);
					$responsable->setUsuario(null);
					$em = $this->getDoctrine()->getManager();
					$em->persist($responsable);
					$em->flush();
					$request->getSession()->getFlashBag()->add('estado', 'El responsable fue eliminado correctamente');
				}
				else {
					$request->getSession()->getFlashBag()->add('estado', 'El responsable no puede ser eliminado ya que poseee alumnos a su cargo');
				}
			}
			return $this->redirectToRoute('lista_responsables');
		}

	}