<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article_model extends CI_Model {
	
	protected $id;

	protected $title;

	protected $body;

	protected $ring;

	protected $author_id;

	protected $author;

	protected $date;

	protected $tags;

	function __construct()
	{
		parent::__construct();
	}

	public function destroy()
	{
		unset($this->id);
		unset($this->title);
		unset($this->body);
		unset($this->ring);
		unset($this->author_id);
		unset($this->author);
		unset($this->date);
		unset($this->tags);
		unset($this);
	}

		/* get - set methods */
	public function id($value = NULL)
	{
		if ($value)
			$this->id = intval($value);
		return isset($this->id) ? $this->id : FALSE;
	}

	public function title($value = NULL)
	{
		if ($value)
			$this->title = $value;
		return isset($this->title) ? $this->title : FALSE;
	}

	public function body($value = NULL)
	{
		if ($value)
			$this->body = $value;
		return isset($this->body) ? $this->body : FALSE;
	}

	public function ring($value = NULL)
	{
		if ($value)
			$this->ring = intval($value);
		return isset($this->ring) ? $this->ring : FALSE;
	}

	public function author_id($value = NULL)
	{
		if ($value)
		{
			$this->author_id = $value;
			return $this->author_id;
		}
		return isset($this->author_id) ? $this->author_id : FALSE;
	}

	public function date($value = NULL)
	{
		if ($value)
			$this->date = $value;
		return isset($this->date) ? $this->date : FALSE;
	}

	public function tags($values = NULL)
	{
		if ($values)
			$this->tags = explode(',', $values);
		return isset($this->tags) ? $this->tags : $this->get_tags_name();
	}

	public function author()
	{
		if ($this->author_id() === FALSE)
			return FALSE;

		if (isset($this->author))
			return $this->author;

		$this->load->model('Admin_model');
		$this->Admin_model->id($this->author_id);
		$this->author = $this->Admin_model->get_admin_name();
		return $this->author;
	}

		/* get data */
	public function get_article_data($mode = 'show')
	{
		$this->load->helper('parsedown');
		$this->set_tags_and_author();

		$data = array(
			'id'			=> $this->id,
			'title'		=> $this->title,
			'body'		=> $this->body,
			'ring'		=> $this->ring,
			'author'	=> $this->author,
			'date'		=> $this->date,
			'tags'		=> $this->tags,
		);

		switch ($mode) {
			case 'edit':
				break;

			case 'preview':
				$data['body'] = preview($data['body']);

			case 'show': default:
				$data['body'] = parse($data['body']);
				$data['date'] =  date('d/m/Y H:i', strtotime($data['date']));
				break;
		}

		return $data;
	}
		/* Insert methods */
	private function get_tags_id()
	{
		$tags_id = array();
		foreach ($this->tags as $tag)
		{
			$tag = trim($tag);

			$this->db->from('tags')->where('name', $tag)->limit(1);
			$res = $this->db->get();
			if ($res->num_rows != 0)
			{
				$tag_id = $res->row()->ID;
			}
			else
			{
				$this->db->insert('tags', array('name' => $tag));
				$tag_id = $this->db->insert_id();
			}
			array_push($tags_id, $tag_id);
		}
		return $tags_id;
	}

	private function create_tag_links()
	{
		if ($this->id() === FALSE OR $this->tags === FALSE)
			return FALSE;

		$tags_id = $this->get_tags_id();
		$data = array();
		foreach ($tags_id as $tag_id)
			array_push($data, array(
				'article_id'	=> $this->id,
				'tag_id'			=> $tag_id,
			));
		$this->db->insert_batch('links_article_tag', $data);
		return TRUE;
	}

	public function insert()
	{
		if ( ! ($this->title() AND $this->body() AND 
						$this->ring() AND $this->get_author_id() AND
						$this->tags()) )
			return FALSE;

		$this->load->database();
		$data = array(
			'title'			=> $this->title,
			'body'			=> $this->body,
			'ring'			=> $this->ring,
			'author_id'	=> $this->author_id,
			'date'			=> date('Y-m-d H:i:s'),
		);
		$this->db->insert('articles', $data);
		$this->id = $this->db->insert_id();
		$this->create_tag_links();
		return TRUE;
	}

		/* update methods */
	public function update()
	{
		if ( ! ($this->id() AND $this->title() AND 
						$this->body() AND $this->ring() AND
						$this->tags()) )
			return FALSE;

		$this->load->database();
		$this->load->helper('utf8');
		$data = array(
			'title'			=> $this->title,
			'body'			=> $this->body,
			'ring'			=> $this->ring,
		);

		$this->db->where('ID', $this->id)->update('articles', $data);
		$this->delete_tag_links();
		$this->create_tag_links();
		return TRUE;
	}

		/* delete methods */
	private function delete_tag_links()
	{
		if ( ! $this->id())
			return FALSE;

		$this->db->delete('links_article_tag', array('article_id' => $this->id));
		return TRUE;
	}

	public function delete()
	{
		if ( ! $this->id())
			return FALSE;

		$this->delete_tag_links();
		$this->db->delete('articles', array('ID' => $this->id));
		return TRUE;
	}

		/* database get methods */
	private function get_tags_name()
	{
		if ( ! $this->id())
			return FALSE;
		$this->load->database();
		$this->db->select('tags.name');
		$this->db->from('articles');
		$this->db->where('articles.ID', $this->id);
		$this->db->join('links_article_tag', 'articles.ID = links_article_tag.article_id');
		$this->db->join('tags', 'links_article_tag.tag_id = tags.ID');
		$res = $this->db->get()->result();
		$this->tags = array();
		foreach($res as $tag)
			array_push($this->tags, $tag->name);
		return $this->tags;
	}

	private function set_tags_and_author()
	{
		if ($this->id() !== FALSE)
			$this->tags();

		if ($this->author_id() !== FALSE)
			$this->author();
	}

	public function get_by_id()
	{
		$this->load->database();
		$this->db->from('articles')->where('ID', $this->id)->limit(1);
		$res = $this->db->get();
		if ($res->num_rows == 0)
			return FALSE;
		$article = $res->row();
		$this->title = $article->title;
		$this->body = $article->body;
		$this->ring = $article->ring;
		$this->author_id = $article->author_id;
		$this->date = $article->date;
		$this->set_tags_and_author();
		return TRUE;
	}

	public function get_last_id()
	{
		$this->load->database();
		$this->db->select_max('ID')->from('articles');
		$res = $this->db->get()->row();
		$this->id($res->ID);
	}

	public function get_all_tags()
	{
		$this->load->database();
		return $this->db->get('tags')->result_array();
	}

	public function get_tag_id($name)
	{
		$this->load->database();
		$this->db->select('ID')->from('tags')->like('name', $name)->limit(1);
		$res = $this->db->get();
		if ($res->num_rows == 0)
			return FALSE;
		return $res->row()->ID;
	}

		/* Session methods */
	private function get_author_id()
	{
		$this->load->model('Admin_model');
		if ($this->Admin_model->read_session() === FALSE)
			return FALSE;
		$this->author_id = $this->Admin_model->id();
		return $this->author_id;
	}
}

/* End of file article_model.php */
/* Location: ./application/models/article_model.php */  
