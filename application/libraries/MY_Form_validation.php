<?php

class MY_Form_validation extends CI_Form_validation
{
	/**
	 * @inheritdoc
	 */
	public function run($group = '')
	{
		$result = parent::run($group);

		if ($result === false && $this->CI->input->method() === 'post') {
			$this->set_session();
			$this->CI->session->set_userdata('is_validation_redirect', true);
		} else {
			$this->CI->input->flush_old();
			$this->CI->session->unset_userdata('is_validation_redirect');
		}

		return $result;
	}

	public function validate($group = '', $redirect = null)
	{
		$this->CI->load->helper('url');

		$result = $this->run($group);

		if ($redirect === null) {
			$redirect = previous_url();
		}

		if ($result === false) {
			redirect($redirect);
		}

		$this->CI->input->flush_old();
		$this->CI->session->unset_userdata('is_validation_redirect');
	}

	protected function set_session()
	{
		$data = $this->_field_data;

		$data = array_pluck($data, 'postdata');

		$this->CI->input->set_old($data);
	}
}
