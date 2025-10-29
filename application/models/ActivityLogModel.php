<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActivityLogModel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function getData($start, $length,$searchValue = null) {
		$this->db->select('activity_log.*, users.email,CONCAT(users.first_name, " ", users.last_name) AS full_name, users.mobile');
		$this->db->from('activity_log');
		$this->db->join('users', 'users.user_id = activity_log.user_id', 'left');

		// Apply search filter
		if ($searchValue) {
			if ($searchValue == 'today') {
				$this->db->where('DATE(activity_log.created_at)', date('Y-m-d'));
			} elseif ($searchValue == 'last_3_days') {
				$this->db->where('DATE(activity_log.created_at) >=', date('Y-m-d', strtotime('-3 days')));
				$this->db->where('DATE(activity_log.created_at) <=', date('Y-m-d'));
			} elseif ($searchValue == 'last_week') {
				$this->db->where('DATE(activity_log.created_at) >=', date('Y-m-d', strtotime('-7 days')));
				$this->db->where('DATE(activity_log.created_at) <=', date('Y-m-d'));
			} elseif ($searchValue == 'last_month') {
				$this->db->where('DATE(activity_log.created_at) >=', date('Y-m-d', strtotime('-30 days')));
				$this->db->where('DATE(activity_log.created_at) <=', date('Y-m-d'));
			} elseif ($searchValue == 'last_3_month') {
				$this->db->where('DATE(activity_log.created_at) >=', date('Y-m-d', strtotime('-90 days')));
				$this->db->where('DATE(activity_log.created_at) <=', date('Y-m-d'));
			} elseif ($searchValue == 'last_6_month') {
				$this->db->where('DATE(activity_log.created_at) >=', date('Y-m-d', strtotime('-180 days')));
				$this->db->where('DATE(activity_log.created_at) <=', date('Y-m-d'));
			} elseif ($searchValue == 'last_1_year') {
				$this->db->where('DATE(activity_log.created_at) >=', date('Y-m-d', strtotime('-365 days')));
				$this->db->where('DATE(activity_log.created_at) <=', date('Y-m-d'));
			} else {
				// If it's not a predefined range, search other columns
				$this->db->like('users.email', $searchValue);
				$this->db->or_like('users.first_name', $searchValue);
				$this->db->or_like('users.last_name', $searchValue);
			}
		}

		$this->db->order_by('activity_log.id', 'desc');
		$this->db->limit($length, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function getTotalCount() {
		return $this->db->count_all('activity_log');
	}

	public function getFilteredCount() {
		// Implement your filtering logic if needed
		return $this->getTotalCount();
	}


	public function createActiviyt($action,$userId='') {

		$ipAddress = $this->input->ip_address();
		$ipinfoUrl = "https://ipinfo.io/{$ipAddress}/json";
		$ipinfoResponse = file_get_contents($ipinfoUrl);
		$ipinfoData = json_decode($ipinfoResponse, true);

		// Extract country information
		$country = isset($ipinfoData['country']) ? $ipinfoData['country'] : '';

		$browserName = $this->getBrowserName($this->input->user_agent());

		$data = array(
			'user_id' =>($userId)?$userId:$this->session->userdata('user_id'),
			'action_message' => $action,
			'ip' => $this->input->ip_address(), // Get user IP address
			'browser_name' => ($browserName!='Unknown')?$browserName:'APP-BROWSER', // Get browser details
			'country_code' =>$country, // Get browser details
		);

		$this->db->insert('activity_log', $data);
	}

	private function getBrowserName($userAgent) {
		$browserName = "Unknown";

		// Check for common browsers
		if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
			$browserName = 'Internet Explorer';
		} elseif (strpos($userAgent, 'Edge') !== false) {
			$browserName = 'Microsoft Edge';
		} elseif (strpos($userAgent, 'Firefox') !== false) {
			$browserName = 'Mozilla Firefox';
		} elseif (strpos($userAgent, 'Chrome') !== false) {
			$browserName = 'Google Chrome';
		} elseif (strpos($userAgent, 'Safari') !== false) {
			$browserName = 'Safari';
		} elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
			$browserName = 'Opera';
		}

		return $browserName;
	}

}	
