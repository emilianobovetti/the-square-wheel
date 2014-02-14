<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once UTF8_PATH . 'portable-utf8.php';

function utf8_htmlentities( $str )
{
	return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

/* End of file utf8_helper.php */
/* Location: ./application/helpers/utf8_helper.php */ 
