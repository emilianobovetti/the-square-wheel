<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('error', 'italian');
	}
	
	public function index()
	{
		$this->load->helper('form');
		$data_view = array(
			'user_name'	=> array(
				'name'			=> 'name',
				'maxlength'	=> '20',
			),
			'password' => array(
				'name'			=> 'password',
				'maxlength'	=> '60',
			),
		);
		$this->load->view('template/head');
		$this->load->view('show/navigation', $this->admin());
		$this->load->view('form/login', $data_view);
		$this->load->view('template/coda');
	}

	public function check()
	{
		$this->load->model('Admin_model');
		$this->Admin_model->name( $this->input->post('name') );
		if ($this->Admin_model->login( $this->input->post('password') ) === FALSE)
			show_error($this->lang->line('error_login'));
		else
			redirect('admin');
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */ 
 
