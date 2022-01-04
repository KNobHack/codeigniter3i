<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Extended Input Class
 *
 * Pre-processes global input data for security
 *
 * @package CodeIgniter
 * @subpackage Libraries
 * @category Input
 * @author Fany Muhammad Fahmi Kamilah
 */
class MY_Input extends CI_Input
{
	public function old($index = null, $xss_clean = null)
	{
		$from_post = $this->post($index, $xss_clean);

		if (!empty($from_post)) {
			return $from_post;
		}

		$from_session = $this->_get_old();

		return $this->_fetch_from_array($from_session, $index, $xss_clean);
	}

	public function is_validation_redirect()
	{
		return (isset($_SESSION['is_validation_redirect'])) ? $_SESSION['is_validation_redirect'] : false;
	}

	protected function _get_old()
	{
		$old = [];
		foreach ($_SESSION as $key => $value) {
			if (str_starts_with($key, 'form_old_')) {
				$key = str_replace('form_old_', '', $key);
				$old[$key] = $value;
			}
		}

		return $old;
	}

	public function set_old($array)
	{
		$CI = &get_instance();
		$CI->load->library('session');

		$new_data = [];
		foreach ($array as $key => $value) {
			if ($value === '') {
				continue;
			}

			$new_data['form_old_' . $key] = $value;
		}
		$data = $new_data;

		$CI->session->set_userdata($data);
	}

	public function flush_old($index = null)
	{
		$CI = &get_instance();
		$CI->load->library('session');

		if ($index !== null) {
			if ($CI->session->has_userdata('form_old_' . $index)) {
				$CI->session->unset_userdata('form_old_' . $index);
			}
			return;
		}

		foreach ($this->_get_old() as $key => $value) {
			if ($CI->session->has_userdata('form_old_' . $key)) {
				$CI->session->unset_userdata('form_old_' . $key);
			}
		}
	}
}
