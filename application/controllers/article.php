<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Article_model');
	}

	public function index($id = NULL)
	{
		if ( ! $id)
			redirect('article/last');
		else
			redirect('article/' . $id);
	}

	public function last()
	{
		$this->id();
	}

	public function id($id = NULL, $error = NULL)
	{
		$this->load->helper('form');
		//$this->load->helper('utf8');
		$this->load->model('Comments_model');

		if ( ! $id)
		{
			$this->Article_model->get_last_id();
		}
		else
		{
			$this->Article_model->id($id);
		}
		
		if( ! $this->Article_model->get_by_id())
			show_404('article/' . $id);

		$article_data = $this->Article_model->get_article_data();
		$comment_data = array(
			'article_id'	=> $article_data['id'],
			'author'			=> array(
				'name'			=> 'author',
				'maxlength'	=> '255',
				'id'				=> 'comment_author_input',
			),
			'body'				=> array(
				'name'	=> 'body',
				'cols'	=> '60',
				'rows'	=> '10',
				'id'		=> 'comment_body_input',
			),
		);

			/* Gestione errori */
		$error_msg = FALSE;
		if ($error)
		{
			$this->load->library('session');
			$comment_data['author']['value'] = $this->session->userdata('author');
			$comment_data['body']['value'] = $this->session->userdata('body');
			if ($comment_data['author']['value'] OR $comment_data['body']['value'])
			{
				$error_msg = array('message' => $error);
			}
			else
			{
				redirect("article/$id", 'refresh');
			}
			$this->session->unset_userdata(array('author' => '', 'body' => ''));
		}

		$this->Comments_model->article_id($this->Article_model->id());
		$comments = $this->Comments_model->comments();

			/* Recaptcha */
		$this->load->library('recaptcha');
		$comment_data['recaptcha_html'] = $this->recaptcha->recaptcha_get_html();
		$comment_data['page'] = 'article';

			/* Views */
		$article_data['admin'] = $this->admin;
		$head_data['description'] = $article_data['title'];

		$this->load->view('template/head', $head_data);
		$this->load->view('show/navigation', $this->admin());
		$this->load->view('show/article', $article_data);
		$this->load->view('show/comment_error', $error_msg);
		$this->load->view('form/comment', $comment_data);

		if ($comments !== FALSE)
		{
			foreach ($comments as $comment)
			{
				$this->load->view('show/comment', $comment);
				if ($this->admin === TRUE)
					$this->load->view('form/manage_comment', $comment);
			}
		}
		$this->load->view('template/coda');
	}
}

/* End of file article.php */
/* Location: ./application/controllers/article.php */ 
