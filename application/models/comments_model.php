<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'comment_model' . EXT;

class Comments_model extends Comment_model {

	private $comments;

	function __construct()
	{
		parent::__construct();
	}

		/* get - set methods */
	public function comments()
	{
		return isset($this->comments) ? $this->comments : $this->get_comments();
	}

		/* Database get methods */
	private function get_comments()
	{
		if ( ! $this->article_id())
			return FALSE;

		$this->load->database();
		$this->db->from('articles')->where('articles.ID', $this->article_id)
				->join('comments', 'articles.ID = comments.article_id');
		$res = $this->db->get();

		$comments = array();
		foreach ($res->result() as $comment)
		{
			$this->id($comment->ID);
			$this->article_id($comment->article_id);
			$this->author($comment->author);
			$this->body($comment->body);
			$this->date($comment->date);
			array_push($comments, $this->get_comment_data('show'));
		}
		$this->comments = $comments;
		return $this->comments;
	}
}

/* End of file comments_model.php */
/* Location: ./application/models/comments_model.php */ 
