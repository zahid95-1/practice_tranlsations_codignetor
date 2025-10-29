<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// Set language
		$lang = $this->session->userdata('site_lang');
		if (!$lang) {
			$lang = 'english';
		}

		$this->lang->load('translator_lang', $lang);
		$this->lang->load('form_validation_lang', $lang);
	}
}
