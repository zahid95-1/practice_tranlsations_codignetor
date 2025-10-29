<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MarketingController extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	public function index()
	{
		$title['title']					='Manage Email';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/marketing/email_template');
		$this->load->view('includes/footer');
	}

	public function createTemplate()
	{
		$title['title']					='Create Template';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/marketing/create_email_template');
		$this->load->view('includes/footer');
	}
}
