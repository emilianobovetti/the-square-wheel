<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'article_model' . EXT;

class Articles_model extends Article_model {
	
	private $total_articles;

	private $articles;

	function __construct()
	{
		parent::__construct();
	}

		/* get - set methods */
	public function total_articles($restrict = NULL, $value = NULL)
	{
		return isset($this->total_articles) 
						? $this->total_articles 
						: $this->get_total_articles($restrict, $value);
	}

	public function articles()
	{
		return isset($this->articles) ? $this->articles : FALSE;
	}

		/* database get methods */
	private function get_total_articles($restrict, $value)
	{
		$this->load->database();
		switch ($restrict)
		{
			case 'tag':
				$this->db->from('links_article_tag')->where('tag_id', $value);
				$this->total_articles = $this->db->count_all_results();
				break;

			default:
				$this->total_articles = $this->db->count_all('articles');
				break;
		}
		return $this->total_articles;
	}

	private function get_info($mode)
	{
		$tmp_articles = array();
		foreach ($this->articles as $article)
		{
			$this->id($article->ID);
			$this->title($article->title);
			$this->body($article->body);
			$this->ring($article->ring);
			$this->author_id($article->author_id);
			$this->date($article->date);
			array_push($tmp_articles, $this->get_article_data($mode));
			
			$this->destroy();
		}
		$this->articles = $tmp_articles;
	}

	public function get($page, $mode = 'show')
	{
		$start_index = ($page - 1) * ARTICLES_PER_PAGE;
		if ($page < 1 OR $start_index > $this->total_articles())
			return FALSE;
		
		$this->load->database();
		$this->db->order_by('date', 'desc');
		$this->articles = $this->db->get('articles', ARTICLES_PER_PAGE, $start_index)->result();
		
		$this->get_info($mode);
	}

	public function get_by_tag($tag_name, $page, $mode = 'show')
	{
		$tag_id = $this->get_tag_id($tag_name);
		$start_index = ($page - 1) * ARTICLES_PER_PAGE;
		if ( ! $tag_id OR
						$page < 1 OR
							$start_index > $this->total_articles('tag', $tag_id))
			return FALSE;

		$this->load->database();
		$this->db->from('articles')->where('links_article_tag.tag_id', $tag_id);
		$this->db->join('links_article_tag', 'articles.ID = links_article_tag.article_id');
		$this->db->limit(ARTICLES_PER_PAGE, $start_index);
		$this->articles = $this->db->get()->result();

		$this->get_info($mode);
	}
}

/* End of file articles_model.php */
/* Location: ./application/models/articles_model.php */  
  
