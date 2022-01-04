<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('previous_url')) {
	/**
	 * Ambil URl sebelumnya yang pernah diakses
	 * 
	 * @param string $session_name nama session
	 */
	function previous_url($session_name = 'previous_url')
	{
		return (isset($_SESSION[$session_name])) ? $_SESSION[$session_name] : '/';
	}
}
