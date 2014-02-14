<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('error', 'italian');
	}

	public function publish()
	{
		if ( ! $this->input->post())
			show_error($this->lang->line('error_comment_generic'));

		$post_data = $this->input->post();

		$error = FALSE;
		$this->load->library('recaptcha');
		$this->recaptcha->recaptcha_check_answer();
		if (trim($post_data['author']) == '')
			$error = 'noauthor';
		elseif (trim($post_data['body']) == '')
			$error = 'nobody';
		elseif (strlen($post_data['body']) > MAX_COMMENT_LENGTH)
			$error = 'toolong';
		elseif ( ! $this->recaptcha->getIsValid())
			$error = 'recaptcha';

		if ($error)
		{
			$this->load->library('session');
			$this->session->set_userdata(array(
				'author'	=> $post_data['author'],
				'body'		=> $post_data['body'] . ' ',
			));
			redirect("article/{$post_data['article_id']}/$error/#error");
		}

		$this->load->model('Comment_model');
		$this->Comment_model->article_id($post_data['article_id']);
		$this->Comment_model->author($post_data['author']);
		$this->Comment_model->body($post_data['body']);

		if ($this->Comment_model->insert() === FALSE)
			show_error($this->lang->line('error_comment_generic'));
		else
		{
			$id = $this->Comment_model->id();
			redirect("article/{$post_data['article_id']}#$id");
		}
	}

}

/* End of file comment.php */
/* Location: ./application/controllers/comment.php */ 
  
 
 
