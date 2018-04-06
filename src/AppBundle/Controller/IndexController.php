<?php
	namespace AppBundle\Controller;
	
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

	class IndexController extends Controller {

		/**
		* @Route ("/index", name="inicio")
		* @Route ("/")
		*/
		public function indexAction(Request $request) {

			if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

				return $this->render('index/home.html.twig');
			}
			else {
				return $this->render('index/index.html.twig');
			}
		}

		
		/**
		* @Route("/home", name="home")
		*/
		public function homeAction() {
			return $this->render('index/home.html.twig');
		} 

	}