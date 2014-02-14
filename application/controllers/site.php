<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($name = NULL)
	{
		if ( ! $name)
			show_404('site/');

		$this->load->helper('file');
		$this->load->helper('parsedown');

		$path = STATIC_PAGES_PATH . $name . '.md';

		if ( ($page_body = read_file($path)) === FALSE)
			show_404('site/' . $name);

		$static_data = array(
			'body'	=> parse($page_body),
		);

		$head_data['title'] = ucfirst($name);

		$this->load->view('template/head', $head_data);
		$this->load->view('show/navigation', $this->admin());
		$this->load->view('show/static', $static_data);
		$this->load->view('template/coda');
	}
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */  
