<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('error', 'italian');
	}

	public function name($value = NULL, $page = 1)
	{
		if ( ! $value)
			show_404('/tag');

		$this->load->model('Articles_model');
		$tag_name = str_replace('-', ' ', $value);
		$this->Articles_model->get_by_tag($tag_name, $page, 'preview');
		$articles = $this->Articles_model->articles();

			/* Pagination system */
		$this->load->library('pagination');
		$config['base_url'] = site_url("/tag/$value");
		$config['per_page'] = ARTICLES_PER_PAGE;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 3;
		$config['anchor_class'] = 'class="pagination_item" ';
		$config['cur_tag_open'] = '<span class="pagination_item">';
		$config['cur_tag_close'] = '</span>';

		$config['total_rows'] = $this->Articles_model->total_articles();
		$this->pagination->initialize($config);
		
		$pagination_data = $this->admin();
		$pagination_data['pagination'] = $this->pagination->create_links();
		
		if ($articles === FALSE)
			show_error($this->lang->line('error_tag_missing'));
		if (empty($articles))
			show_error($this->lang->line('error_no_tag_articles'));

		$this->load->view('template/head');
		$this->load->view('show/navigation', $pagination_data);
		$this->load->view('show/tags', array('tags' => $this->Articles_model->get_all_tags()));
		foreach ($articles as $article)
		{
			$article['admin'] = $this->admin;
			$article['preview'] = TRUE;
			$this->load->view('show/article', $article);
		}
		$this->load->view('template/coda');
	}

}

/* End of file tag.php */
/* Location: ./application/controllers/tag.php */ 
 
