<?php

	namespace AppBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

	use StoreBundle\Entity\Usuario;
	use StoreBundle\Form\UsuarioType;

	/**
	* 
	* @Security("has_role('ROLE_ADMIN')")
	*/
	class UsuarioController extends Controller {


		/**
		* @Route("/usuario", name="nuevo_usuario")
		* @Method({"GET","POST"})
		*/		
		public function nuevoAction(Request $request) {

			$usuario = new Usuario ();
			$form = $this->createForm(UsuarioType::class, $usuario);

			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid() ) {
				$em = $this->getDoctrine()->getManager();
				$usuario->setHabilitado(true);
				$usuario->setBorrado(false);
				$em->persist($usuario);
            	$em->flush(); 
            	$request->getSession()->getFlashBag()->add('estado', 'El usuario fue creado correctamente');
            	return $this->redirectToRoute('lista_usuarios', array('pagina' => 1));	
			}
			return $this->render('usuario/ingresarUsuario.html.twig', array('form' => $form->createView()));
		}

		/**
		* @Route("/usuarios/{pagina}", name="lista_usuarios", defaults={"pagina" = 1})
		* 
		*/
		public function listadoUsuariosAction($pagina) {
			$cantidadPaginas;
			$usuarios = $this->getDoctrine()->getManager()->getRepository('StoreBundle:Usuario')->listarTodos($pagina,$cantidadPaginas);
			return $this->render('usuario/listadoDeUsuarios.html.twig',array('paginaActual' => $pagina, 'cantidadDePaginas' => $cantidadPaginas, 'usuarios' => $usuarios));
		}

		private function createDeleteForm(Usuario $usuario) {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eliminar_usuario', array('id' => $usuario->getId())))
            ->setMethod('DELETE')
            ->getForm();
        }

		/**
		* @Route("/editar/usuario/{id}", name="editar_usuario")
		*
		*/
		public function editarAction(Request $request, Usuario $usuario) {
			//Si el usuario ya fue borrado no puede editarse
			if ($usuario->getBorrado()) {
				return $this->redirectToRoute('lista_usuarios', array('pagina' => 1));
			}
			$form = $this->createForm(UsuarioType::class, $usuario);
			$deleteForm = $this->createDeleteForm($usuario);
			$form->remove('password');
			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($usuario);
				$em->flush();
				$request->getSession()->getFlashBag()->add('estado', 'El usuario fue actualizado correctamente');
            	return $this->redirectToRoute('lista_usuarios', array('pagina' => 1));
			}
			return $this->render('usuario/modificarUsuario.html.twig', array('form' => $form->createView(),
				'delete_form' => $deleteForm->createView()));
		}

        /**
        * @Route("/usuario/{id}", name="eliminar_usuario")
        * @Method("DELETE")
        */
        //Para que el usuario pueda eliminarse no debe tener asociado a un responsable
        public function eliminarUsuarioAction(Request $request, Usuario $usuario) {
        	if (!$usuario->getBorrado()) {
        		if (!$usuario->getResponsable()) {
        			$usuario->setBorrado(true);
        			$em = $this->getDoctrine()->getManager();
        			$em->persist($usuario);
        			$em->flush();
        			$request->getSession()->getFlashBag()->add('estado', 'El usuario fue eliminado correctamente');	
        		}
        		else {
        			$request->getSession()->getFlashBag()->add('estado', 'El usuario no puede ser eliminado porque tiene asignado un responsable');	
        		}
        		
        	}
        	return $this->redirectToRoute('lista_usuarios', array('pagina' => 1));
        }
	}