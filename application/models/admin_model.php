<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
	
	private $id;

	private $name;

	private $email;

	function __construct()
	{
		parent::__construct();
	}

		/* get - set methods */
	public function id($value = NULL)
	{
		if ($value)
			$this->id = intval($value);
		return isset($this->id) ? $this->id : FALSE;
	}

	public function name($value = NULL)
	{
		if ($value)
			$this->name = trim($value);
		return isset($this->name) ? $this->name : FALSE;
	}

	public function email($value = NULL)
	{
		if ($value)
		{
			$this->load->helper('utf8');
			$this->email = utf8_strtolower(trim($value));
		}
		return isset($this->email) ? $this->email : FALSE;
	}

		/* Database methods */
	public function exists()
	{
		$this->load->database();

		$this->db->from('admins')->where('name', $this->name)->limit(1);
		if ($this->db->get()->num_rows() == 1)
			return TRUE;
		$this->db->from('admins')->where('email', $this->email)->limit(1);
		return $this->db->get()->num_rows() == 1 ? TRUE : FALSE;
	}

	public function new_admin($password)
	{
		if ($this->name() === FALSE OR $this->email() === FALSE OR $this->exists())
			return FALSE;

		$this->load->database();
		$this->load->helper('security');

		$data = array(
			'name'			=> $this->name,
			'password'	=> do_hash($password),
			'email'			=> $this->email,
		);
		$this->db->insert('admins', $data);
		$this->id = $this->db->insert_id();
		return TRUE;
	}

	private function select_where($field, $value)
	{
		if ( ! $value) return FALSE;

		$this->load->database();
		$this->db->from('admins')->where($field, $value)->limit(1);
		$res = $this->db->get();
		return $res->num_rows == 0 ? FALSE : $res->row();
	}

	public function get_admin_name()
	{
		if ($this->id() === FALSE)
			return FALSE;

		$admin_name = $this->select_where('ID', $this->id);
		return ( ! $admin_name) ? FALSE : $admin_name->name;
	}

		/* Session methods */
	public function login($password)
	{
		$admin_data = $this->select_where('name', $this->name);
		if ($admin_data === FALSE)
			return FALSE;

		$this->load->helper('security');
		if ( ! check_hash($admin_data->password, $password))
			return FALSE;
		$this->load->library('session');
		$this->session->set_userdata(array(
			'ID'		=> $admin_data->ID,
			'name'	=> $admin_data->name,
			'email'	=> $admin_data->email,
		));
		return TRUE;
	}

	public function read_session()
	{
		$this->load->library('session');
		if ( ! $this->session->userdata('ID'))
			return FALSE;
		$this->id = intval($this->session->userdata('ID'));
		$this->name = $this->session->userdata('name');
		$this->email = $this->session->userdata('email');
		return TRUE;
	}

	public function logout()
	{
		/*
		$this->load->library('session');
		$array_items = array(
			'ID'		=> '',
			'name'	=> '',
			'email'	=> '',
		);
		$this->session->unset_userdata($array_items);
		*/
		
		$this->session->sess_destroy();
	}
}

/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */ 