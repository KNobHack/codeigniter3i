<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Untuk menentukan url mana yang sebelumnya di akses
 */
class PreviousUri
{
	private $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	public function set($session_name = 'previous_url')
	{
		if ($this->CI->input->method() === 'get') {

			$this->CI->load->helper('url');
			$this->CI->load->library('session');

			$current_url = current_url();
			$this->CI->session->set_flashdata($session_name, $current_url);
		}
	}

	public function get($session_name = 'previous_url')
	{
		return $this->CI->session->flashdata($session_name);
	}
}
