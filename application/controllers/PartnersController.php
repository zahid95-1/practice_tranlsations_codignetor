<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PartnersController extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/LoginController
	 */
	public function currencySymbol()
	{
		$title['title']			='Currency Symbol';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/partners/currency_symbol');
		$this->load->view('includes/footer');
	}

	public function currencyGroup()
	{
		$title['title']			='Currency Group';
		$this->load->view('includes/header',$title);
		$this->load->view('includes/left_side_bar');
		$this->load->view('admin/partners/currency_group');
		$this->load->view('includes/footer');
	}
}
