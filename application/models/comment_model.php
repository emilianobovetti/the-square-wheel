<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends CI_Model {
	
	protected $id;

	protected $article_id;

	protected $author;

	protected $body;

	protected $date;

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

	public function article_id($value = NULL)
	{
		if ($value)
			$this->article_id = intval($value);
		return isset($this->article_id) ? $this->article_id : FALSE;
	}

	public function author($value = NULL)
	{
		if ($value)
			$this->author = trim($value);
		return isset($this->author) ? $this->author : FALSE;
	}

	public function body($value = NULL)
	{
		if ($value)
			$this->body = trim($value);
		return isset($this->body) ? $this->body : FALSE;
	}

	public function date($value = NULL)
	{
		if ($value)
			$this->date = $value;
		return isset($this->date) ? $this->date : FALSE;
	}

		/* get data */
	public function get_comment_data($mode = 'show')
	{
		$data = array(
			'id'					=> $this->id,
			'article_id'	=> $this->article_id,
			'author'			=> $this->author,
			'body'				=> $this->body,
			'date'				=> $this->date,
		);
		
		switch ($mode) {
			case 'edit':
				break;
			
			case 'show': default:
				$data['date'] =  date('d/m/Y H:i', strtotime($data['date']));
				break;
		}
		
		return $data;
	}

		/* insert methods */
	public function insert()
	{
		if ( ! ($this->article_id() AND $this->author() AND 
						$this->body()) )
			return FALSE;

		$this->load->database();
		$data = array(
			'article_id'	=> $this->article_id,
			'author'			=> $this->author,
			'body'				=> $this->body,
			'date'				=> date('Y-m-d H:i:s'),
		);
		$this->db->insert('comments', $data);
		$this->id = $this->db->insert_id();
		return TRUE;
	}

		/* get methods */
	public function get_by_id()
	{
		$this->load->database();
		$this->db->from('comments')->where('ID', $this->id)->limit(1);
		$res = $this->db->get();
		if ($res->num_rows == 0)
			return FALSE;
		$comment = $res->row();
		$this->article_id = $comment->article_id;
		$this->author = $comment->author;
		$this->body = $comment->body;
		$this->date = $comment->date;
		return TRUE;
	}

		/* update methods */
	public function update()
	{
		if ( ! ($this->id() AND
						$this->author() AND $this->body()))
			return FALSE;

		$this->load->database();
		$data = array(
			'author'	=> $this->author,
			'body'		=> $this->body,
		);
		$this->db->where('ID', $this->id)->update('comments', $data);

		return TRUE;
	}
		/* delete methods */
	public function delete()
	{
		if ( ! $this->id())
			return FALSE;

		$this->db->delete('comments', array('ID' => $this->id));
		return TRUE;
	}
}

/* End of file comment_model.php */
/* Location: ./application/models/comment_model.php */  
