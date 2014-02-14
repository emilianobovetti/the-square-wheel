<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PARSEDOWN_PATH . 'Parsedown.php';

function parse( $text )
{
	$search = array(
		'/preview',
	);

	$replace = array(
		'',
	);

	$text = str_replace($search, $replace, $text);
	return Parsedown::instance()->parse($text);
}

function preview( $text )
{
	$index = strpos($text, '/preview');
	return ( ! $index) ? $text : substr($text, 0, $index);
}

/* End of file parsedown_helper.php */
/* Location: ./application/helpers/parsedown_helper.php */ 
