<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Extended Output Class
 *
 * @package CodeIgniter
 * @subpackage Libraries
 * @category Input
 * @author Fany Muhammad Fahmi Kamilah
 */
class MY_Output extends CI_Output
{
	public function json($data, $status = 200)
	{
		$this
			->set_content_type('application/json')
			->set_output(json_encode($data))
			->set_status_header($status);
	}
}
