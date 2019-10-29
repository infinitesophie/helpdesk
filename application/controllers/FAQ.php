<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FAQ extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("FAQ_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		$this->template->loadData("activeLink", 
			array("faq" => array("general" => 1)));

		if(!$this->common->has_permissions(array(
			"admin", "faq_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
	}

	public function index() 
	{
		$this->template->loadData("activeLink", 
			array("faq" => array("general" => 1)));

		$categories = $this->FAQ_model->get_categories();
		
		$this->template->loadContent("faq/index.php", array(
			"categories" => $categories
			)
		);
	}

	public function faq_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("faq.ID", "DESC");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"faq.question" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->FAQ_model
				->get_faq_total()
		);
		$faq = $this->FAQ_model->get_faq_dt($this->datatables);
		

		foreach($faq->result() as $r) {
			
			$this->datatables->data[] = array(
				$r->question,
				$r->cat_name,
				'<a href="'.site_url("FAQ/edit_faq/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("FAQ/delete_faq/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function add_pro() 
	{
		$question = $this->common->nohtml($this->input->post("question"));
		$answer = $this->lib_filter->go($this->input->post("answer"));
		$catid = intval($this->input->post("catid"));

		if(empty($question) || empty($answer)) {
			$this->template->error(lang("error_129"));
		}

		$cat = $this->FAQ_model->get_category($catid);
		if($cat->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$this->FAQ_model->add_faq(array(
				"question" => $question,
				"answer" => $answer,
				"catid" => $catid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_74"));
		redirect(site_url("FAQ"));
	}

	public function delete_faq($id, $hash) {
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$faq = $this->FAQ_model->get_faq($id);
		if($faq->num_rows() == 0) {
			$this->template->error(lang("error_130"));
		}

		$this->FAQ_model->delete_faq($id);
		$this->session->set_flashdata("globalmsg", lang("success_75"));
		redirect(site_url("FAQ"));
	}

	public function edit_faq($id) 
	{
		$id = intval($id);
		$faq = $this->FAQ_model->get_faq($id);
		if($faq->num_rows() == 0) {
			$this->template->error(lang("error_130"));
		}
		$faq = $faq->row();

		$this->template->loadData("activeLink", 
			array("faq" => array("general" => 1)));

		$categories = $this->FAQ_model->get_categories();
		
		$this->template->loadContent("faq/edit_faq.php", array(
			"categories" => $categories,
			"faq" => $faq
			)
		);
	}

	public function edit_faq_pro($id) 
	{
		$id = intval($id);
		$faq = $this->FAQ_model->get_faq($id);
		if($faq->num_rows() == 0) {
			$this->template->error(lang("error_130"));
		}

		$question = $this->common->nohtml($this->input->post("question"));
		$answer = $this->lib_filter->go($this->input->post("answer"));
		$catid = intval($this->input->post("catid"));

		if(empty($question) || empty($answer)) {
			$this->template->error(lang("error_129"));
		}

		$cat = $this->FAQ_model->get_category($catid);
		if($cat->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$this->FAQ_model->update_faq($id, array(
				"question" => $question,
				"answer" => $answer,
				"catid" => $catid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_76"));
		redirect(site_url("FAQ"));
	}

	public function categories() 
	{
		$this->template->loadData("activeLink", 
			array("faq" => array("cats" => 1)));


		
		$this->template->loadContent("faq/categories.php", array(
			)
		);
	}

	public function add_category_pro() 
	{
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_111"));
		}

		$catid = $this->FAQ_model->add_category(array(
			"name" => $name,
			"description" => $desc,
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_53"));
		redirect(site_url("FAQ/categories"));
	}

	public function delete_cat($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$category = $this->FAQ_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$this->FAQ_model->delete_category($id);
		$this->session->set_flashdata("globalmsg", lang("success_54"));
		redirect(site_url("FAQ/categories"));
	}

	public function edit_cat($id) 
	{
		$id = intval($id);
		$category = $this->FAQ_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$category = $category->row();

		$this->template->loadData("activeLink", 
			array("faq" => array("cats" => 1)));

		
		$this->template->loadContent("faq/edit_cat.php", array(
			"category" => $category,
			)
		);
	}

	public function edit_category_pro($id) 
	{
		$id = intval($id);
		$category = $this->FAQ_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$category = $category->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));

		if(empty($name)) {
			$this->template->error(lang("error_111"));
		}

		$this->FAQ_model->update_category($id, array(
			"name" => $name,
			"description" => $desc,
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_55"));
		redirect(site_url("FAQ/categories"));
	}

	public function cat_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("faq_categories.name", "asc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"faq_categories.name" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->FAQ_model
				->get_categories_total()
		);
		$cats = $this->FAQ_model->get_categories_dt($this->datatables);
		

		foreach($cats->result() as $r) {
			
			$this->datatables->data[] = array(
				$r->name,
				'<a href="'.site_url("FAQ/edit_cat/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("FAQ/delete_cat/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}


}

?>