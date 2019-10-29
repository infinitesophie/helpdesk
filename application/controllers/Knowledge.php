<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Knowledge extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("knowledge_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		$this->template->loadData("activeLink", 
			array("knowledge" => array("general" => 1)));

		if(!$this->common->has_permissions(array(
			"admin", "knowledge_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
	}

	public function index() 
	{
		$this->template->loadData("activeLink", 
			array("knowledge" => array("general" => 1)));
		
		$this->template->loadContent("knowledge/index.php", array(
			)
		);
	}

	public function article_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("knowledge_articles.last_updateded_timestamp", "DESC");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"knowledge_articles.title" => 0
				 ),
				 1 => array(
				 	"users.username" => 0
				 ),
				 2 => array(
				 	"knowledge_categories.name" => 0
				 ),
				 3 => array(
				 	"knowledge_articles.last_updated_timestamp" => 0
				 ),
				 
			)
		);

		$this->datatables->set_total_rows(
			$this->knowledge_model
				->get_articles_total()
		);
		$articles = $this->knowledge_model->get_articles($this->datatables);
		

		foreach($articles->result() as $r) {
			
			$this->datatables->data[] = array(
				$r->title,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->catname,
				date($this->settings->info->date_format, $r->last_updated_timestamp),
				'<a href="'.site_url("knowledge/edit_article/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("knowledge/delete_article/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function edit_article($id) 
	{
		$id = intval($id);
		$article = $this->knowledge_model->get_article($id);
		if($article->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$article = $article->row();

		$this->template->loadData("activeLink", 
			array("knowledge" => array("general" => 1)));

		$categories = $this->knowledge_model->get_categories();

		$this->template->loadContent("knowledge/edit_article.php", array(
			"article" => $article,
			"categories" => $categories
			)
		);
	}

	public function edit_article_pro($id) 
	{
		$id = intval($id);
		$article = $this->knowledge_model->get_article($id);
		if($article->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$article = $article->row();

		$name = $this->common->nohtml($this->input->post("title"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$catid = intval($this->input->post("catid"));

		if(empty($name)) {
			$this->template->error(lang("error_109"));
		}

		$category = $this->knowledge_model->get_category($catid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$this->knowledge_model->update_article($id, array(
			"title" => $name,
			"body" => $desc,
			"catid" => $catid,
			"last_updated_timestamp" => time()
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_50"));
		redirect(site_url("knowledge"));
	}

	public function delete_article($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) 
		{
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$article = $this->knowledge_model->get_article($id);
		if($article->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}

		$this->knowledge_model->delete_article($id);
		$this->session->set_flashdata("globalmsg", lang("success_51"));
		redirect(site_url("knowledge"));
	}

	public function add() 
	{

		$this->template->loadData("activeLink", 
			array("knowledge" => array("general" => 1)));

		$categories = $this->knowledge_model->get_categories();
		
		$this->template->loadContent("knowledge/add.php", array(
			"categories" => $categories
			)
		);	
	}

	public function add_pro() 
	{
		$name = $this->common->nohtml($this->input->post("title"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$catid = intval($this->input->post("catid"));

		if(empty($name)) {
			$this->template->error(lang("error_109"));
		}

		$category = $this->knowledge_model->get_category($catid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$this->knowledge_model->add_article(array(
			"title" => $name,
			"body" => $desc,
			"userid" => $this->user->info->ID,
			"timestamp" => time(),
			"catid" => $catid,
			"last_updated_timestamp" => time()
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_52"));
		redirect(site_url("knowledge"));
	}

	public function categories() 
	{
		$this->template->loadExternal(
			'<link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("knowledge" => array("cats" => 1)));

		$categories = $this->knowledge_model->get_categories();

		$user_groups = $this->user_model->get_all_user_groups();
		
		$this->template->loadContent("knowledge/categories.php", array(
			"categories" => $categories,
			"user_groups" => $user_groups
			)
		);
	}

	public function add_category_pro() 
	{
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$category_parent = intval($this->input->post("category_parent"));

		if($category_parent > 0) {
			$category = $this->knowledge_model->get_category($category_parent);
			if($category->num_rows() == 0) {
				$this->template->error(lang("error_110"));
			}
		}

		$user_groups = $this->input->post("user_groups");

		// Check ticket categories
		$groups= array();
		if($user_groups) {
			foreach($user_groups as $groupid) {
				$groupid = intval($groupid);
				if($groupid > 0) {
					$group = $this->user_model->get_user_group($groupid);
					if($group->num_rows() == 0) {
						$this->template->error(lang("error_120"));
					}
				}
				$groups[] = $groupid;
			}
		}

		$this->load->library("upload");

		// Image
		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "png|jpeg|jpg|gif",
		       "max_size" => $this->settings->info->file_size,
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21")
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= "default_cat.png";
		}

		if(empty($name)) {
			$this->template->error(lang("error_111"));
		}

		$catid = $this->knowledge_model->add_category(array(
			"name" => $name,
			"description" => $desc,
			"image" => $image,
			"parent_category" => $category_parent
			)
		);

		// Add custom field to cats
		foreach($groups as $groupid) {
			$this->knowledge_model->add_category_group(array(
				"groupid" => $groupid,
				"catid" => $catid
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_53"));
		redirect(site_url("knowledge/categories"));
	}

	public function delete_cat($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$category = $this->knowledge_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$this->knowledge_model->delete_category($id);
		$this->session->set_flashdata("globalmsg", lang("success_54"));
		redirect(site_url("knowledge/categories"));
	}

	public function edit_cat($id) 
	{
		$this->template->loadExternal(
			'<link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);
		$id = intval($id);
		$category = $this->knowledge_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$category = $category->row();

		$this->template->loadData("activeLink", 
			array("knowledge" => array("cats" => 1)));

		$categories = $this->knowledge_model->get_categories();

		$user_groups = $this->knowledge_model->get_cat_groups($id);
		
		$this->template->loadContent("knowledge/edit_cat.php", array(
			"category" => $category,
			"categories" => $categories,
			"user_groups" => $user_groups
			)
		);
	}

	public function edit_category_pro($id) 
	{
		$id = intval($id);
		$category = $this->knowledge_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$category = $category->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));

		$category_parent = intval($this->input->post("category_parent"));

		$user_groups = $this->input->post("user_groups");

		// Check ticket categories
		$groups= array();
		if($user_groups) {
			foreach($user_groups as $groupid) {
				$groupid = intval($groupid);
				if($groupid > 0) {
					$group = $this->user_model->get_user_group($groupid);
					if($group->num_rows() == 0) {
						$this->template->error(lang("error_120"));
					}
				}
				$groups[] = $groupid;
			}
		}

		if($category_parent > 0) {
			$categoryo = $this->knowledge_model->get_category($category_parent);
			if($categoryo->num_rows() == 0) {
				$this->template->error(lang("error_110"));
			}
		}

		$this->load->library("upload");

		// Image
		if ($_FILES['userfile']['size'] > 0) {
			$this->upload->initialize(array( 
		       "upload_path" => $this->settings->info->upload_path,
		       "overwrite" => FALSE,
		       "max_filename" => 300,
		       "encrypt_name" => TRUE,
		       "remove_spaces" => TRUE,
		       "allowed_types" => "png|jpeg|jpg|gif",
		       "max_size" => $this->settings->info->file_size,
		    ));

		    if (!$this->upload->do_upload()) {
		    	$this->template->error(lang("error_21")
		    	.$this->upload->display_errors());
		    }

		    $data = $this->upload->data();

		    $image = $data['file_name'];
		} else {
			$image= $category->image;
		}

		if(empty($name)) {
			$this->template->error(lang("error_111"));
		}

		$this->knowledge_model->update_category($id, array(
			"name" => $name,
			"description" => $desc,
			"image" => $image,
			"parent_category" => $category_parent
			)
		);

		$this->knowledge_model->delete_category_groups($id);

		// Add custom field to cats
		foreach($groups as $groupid) {
			$this->knowledge_model->add_category_group(array(
				"groupid" => $groupid,
				"catid" => $id
				)
			);
		}


		$this->session->set_flashdata("globalmsg", lang("success_55"));
		redirect(site_url("knowledge/categories"));
	}

	public function cat_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("knowledge_categories.name", "asc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"knowledge_categories.name" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->knowledge_model
				->get_categories_total()
		);
		$cats = $this->knowledge_model->get_categories_dt($this->datatables);
		

		foreach($cats->result() as $r) {
			
			$this->datatables->data[] = array(
				'<img src="'.base_url().$this->settings->info->upload_path_relative.'/'.$r->image.'" class="cat-icon">',
				$r->name,
				'<a href="'.site_url("knowledge/edit_cat/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("knowledge/delete_cat/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

}

?>