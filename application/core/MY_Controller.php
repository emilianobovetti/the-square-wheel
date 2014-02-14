<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller  {

	var $admin = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Content-Type: text/html; charset=' . config_item('charset'));
		$this->load->helper('url');
		$this->load->model('Admin_model');
		$this->admin = $this->Admin_model->read_session();
	}

	public function admin()
	{
		return array('admin' => $this->admin);
	}

}