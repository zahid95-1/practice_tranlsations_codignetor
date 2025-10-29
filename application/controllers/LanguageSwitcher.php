<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LanguageSwitcher extends MY_Controller {

	public function switchLang($language = "") {
		$allowed_languages = ['english', 'chinese'];
		if (in_array($language, $allowed_languages)) {
			$this->session->set_userdata('site_lang', $language);
		}
		// Redirect back to the previous page
		redirect($_SERVER['HTTP_REFERER']);
	}
}
