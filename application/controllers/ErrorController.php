<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ErrorController extends MY_Controller {

	function __construct()
	{
		parent::__construct();

		$CI = &get_instance();
		$controller = $CI->router->fetch_class();  //Controller name
		$method     = $CI->router->fetch_method();  //Method name

		$this->load->model('UserModel');
		$this->load->model('DashboardModel');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	public function NotFound()
	{
		$title['title']	='Dashboard';
		$this->load->view('errors/cli/permission_error','');
	}
}
