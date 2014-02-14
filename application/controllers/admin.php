<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( ! $this->admin)
			redirect('home');
		$this->lang->load('error', 'italian');
	}

	private function redirect_without_post($field = NULL)
	{
		if ($this->input->post($field) === FALSE)
			show_error($this->lang->line('error_post_missing'));
	}
	
	public function index()
	{
		redirect('admin/article');
	}

	public function article($id = NULL)
	{
		$this->load->helper('form');
		$this->load->model('Article_model');

		print_r($this->input->post());

		$view_data = array(
			'ring_options' => array(
				'3'	=> '3',
				'2'	=> '2',
				'1'	=> '1',
			),
			'selected_ring' => '1',
			'title' => array(
				'name'			=> 'title',
				'maxlength'	=> '255',
			),
			'body' => array(
				'name'	=> 'body',
				'cols'	=> '90',
				'rows'	=> '23',
			),
			'tags' => array(
				'name'	=> 'tags',
				'cols'	=> '90',
				'rows'	=> '2',
			),
		);

		$this->load->view('template/head');
		$this->load->view('show/navigation', $this->admin());

		//$this->upload();

		if ($id === NULL)
			$this->new_article($view_data);
		else
			$this->edit_article($id, $view_data);

		$this->load->view('template/coda');
	}

	private function new_article($view_data)
	{
		$this->load->view('form/article', $view_data);
	}

	private function edit_article($id, $view_data)
	{
		$this->Article_model->id($id);
		if ($this->Article_model->get_by_id() === FALSE)
			show_error($this->lang->line('error_retrieving_article'));

		$article_data = $this->Article_model->get_article_data('edit');

		$view_data['article_id'] = $id;
		$view_data['selected_ring']['value'] = $article_data['ring'];
		$view_data['title']['value'] = $article_data['title'];
		$view_data['body']['value'] = $article_data['body'];
		$view_data['tags']['value'] = implode(', ', $article_data['tags']);
		$this->load->view('form/article', $view_data);
	}

	public function publish()
	{
		$this->redirect_without_post();

		$post_data = $this->input->post();

		$this->load->model('Article_model');
		$this->Article_model->ring($post_data['ring']);
		$this->Article_model->title($post_data['title']);
		$this->Article_model->body($post_data['body']);
		$this->Article_model->tags($post_data['tags']);
		if (isset($post_data['article_id']))
		{
			$this->Article_model->id($post_data['article_id']);
			$success = $this->Article_model->update();
		}
		else
			$success = $this->Article_model->insert();

		if ($success === FALSE)
			show_error($this->lang->line('error_publishing_article'));
		else
			redirect('article/' . $this->Article_model->id());
	}

	public function delete()
	{
		$this->redirect_without_post('article_id');

		$article_id = $this->input->post('article_id');

		$this->load->model('Article_model');
		$this->Article_model->id($article_id);
		if ($this->Article_model->delete() === FALSE)
			show_error($this->lang->line('error_deleting_article'));
		else
			redirect('site/delete-success');
	}

	public function edit_comment()
	{
		$this->redirect_without_post('comment_id');

		$post_data = $this->input->post();
		$this->load->model('Comment_model');
		$this->Comment_model->id($post_data['comment_id']);
		$this->Comment_model->author($post_data['author']);
		$this->Comment_model->body($post_data['body']);

		if ($this->Comment_model->update() === FALSE)
			show_error($this->lang->line('error_updating_article'));
		else
		{
			$this->Comment_model->get_by_id();	// to set article_id
			$comment_id = $this->Comment_model->id();
			$article_id = $this->Comment_model->article_id();
			redirect("article/$article_id#$comment_id");
		}
	}

	public function comments()
	{
		$this->redirect_without_post('comment_id');

		$post_data = $this->input->post();
		$this->load->model('Comment_model');
		$this->Comment_model->id($post_data['comment_id']);

		if (isset($post_data['edit']))
		{
			$this->load->helper('form');
			$this->Comment_model->get_by_id();

			$comment = $this->Comment_model->get_comment_data('edit');

			$comment_data = array(
			'comment_id'	=> $comment['id'],
			'author'			=> array(
				'name'			=> 'author',
				'maxlength'	=> '255',
				'value'			=> $comment['author'],
				),
			'body'				=> array(
				'name'	=> 'body',
				'cols'	=> '60',
				'rows'	=> '3',
				'value'	=> $comment['body'],
				),
			);

			$this->load->view('template/head');
			$this->load->view('show/navigation', $this->admin());
			$this->load->view('form/edit_comment', $comment_data);
			$this->load->view('template/coda');
		}
		elseif (isset($post_data['delete']))
		{
			$this->Comment_model->delete();
			redirect('site/delete-success');
		}
	}

	public function upload()
	{
		$this->load->helper('form');

		$config['upload_path'] = UPLOAD_PATH;
		$config['allowed_types'] = '*';
		$this->load->library('upload', $config);

		$field_name = 'file_upload';
		$upload_data = array(
			'upload_form'	=> array(
				'name'	=> $field_name,
		));

		if ($this->input->post('upload'))
		{
			if( ! $this->upload->do_upload($field_name))
			{
				$upload_data['error'] = $this->upload->display_errors();
			}
			else
			{
				$upload_data['upload_data'] = $this->upload->data();
			}
		}
		
		$this->load->view('form/upload', $upload_data);
	}

	public function logout()
	{
		$this->Admin_model->logout();
		redirect('home');
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */ 
 
 
