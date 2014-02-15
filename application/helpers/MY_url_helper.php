<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once UTF8_PATH . 'portable-utf8.php';

function url_encode( $str )
{
	$out = '';
	for ($i = 0, $len = utf8_strlen($str); $i < $len; $i++)
	{
		$char = utf8_access($str, $i);
		if (($char >= '0' AND $char <= '9')
				OR ($char >= 'A' AND $char <= 'Z')
				OR ($char >= 'a' AND $char <= 'z'))
			$out .= $char;
		else
			$out .= '.' . utf8_ord($char);
	}
	return $out;
}

function url_decode( $str )
{
	$chr_func = create_function('$matches',
		'return utf8_chr((int) substr($matches[0], 1));'
	);
	return preg_replace_callback(
		'/\.[0-9]+/',
		$chr_func,
		$str
	);
}

function print_tags( $tags, $array_key = NULL)
{
	$links = '';
	foreach ($tags as $tag)
	{
		if ($array_key !== NULL)
			$tag = $tag[$array_key];
		
		$link = site_url('tag/' . url_encode($tag));
		$links .= "<span class=\"tag\"><a href=\"$link\">$tag</a></span>\n";
	}
	return $links;
}

function get_upload_link( $file_name )
{
	return '/resources/' . $file_name;
}

/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */
