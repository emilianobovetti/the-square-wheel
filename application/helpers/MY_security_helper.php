<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PHPASS_PATH . 'PasswordHash.php';

function do_hash($str)
{
	$hasher = new PasswordHash(8, FALSE);
	return $hasher->HashPassword($str);
}

function check_hash($hashedPassword, $str)
{
	$hasher = new PasswordHash(8, FALSE);
	return $hasher->CheckPassword($str, $hashedPassword);
}

/* End of file MY_security_helper.php */
/* Location: ./application/helpers/MY_security_helper.php */