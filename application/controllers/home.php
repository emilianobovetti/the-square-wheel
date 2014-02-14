<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Articles_model');
		$this->lang->load('error', 'italian');
	}
	
	public function index($page = 1)
	{
		$this->Articles_model->get($page, 'preview');
		$articles = $this->Articles_model->articles();

			/* Pagination system */
		$this->load->library('pagination');
		$config['base_url'] = base_url();
		$config['per_page'] = ARTICLES_PER_PAGE;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 1;
		$config['anchor_class'] = 'class="pagination_item" ';
		$config['cur_tag_open'] = '<span class="pagination_item">';
		$config['cur_tag_close'] = '</span>';
		$config['total_rows'] = $this->Articles_model->total_articles();
		$this->pagination->initialize($config);
		
		$pagination_data = $this->admin();
		$pagination_data['pagination'] = $this->pagination->create_links();
		
		if ($articles === FALSE)
			show_error($this->lang->line('error_article_page_not_exists'));

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

/* End of file home.php */
/* Location: ./application/controllers/home.php */ 
