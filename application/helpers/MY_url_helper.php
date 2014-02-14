<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function print_tags( $tags, $array_key = NULL)
{
	$links = '';
	foreach ($tags as $tag)
	{
		if ($array_key !== NULL)
			$tag = $tag[$array_key];
		
		$link = site_url('tag/' . url_title($tag));
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
