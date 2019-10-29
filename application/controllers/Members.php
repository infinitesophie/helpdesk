<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		$this->template->loadData("activeLink", 
			array("members" => array("general" => 1)));

		// If the user does not have premium. 

		if(!$this->common->has_permissions(array("admin", "ticket_manager", 
			"ticket_worker", "knowledge_manager"), $this->user)) {
			$this->template->error("You do not have access to this page!");
		}
	}

	public function index() 
	{
		
		$this->template->loadContent("members/index.php", array(
			)
		);
	}

	public function members_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.joined", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"users.first_name" => 0
				 ),
				 2 => array(
				 	"users.last_name" => 0
				 ),
				 3 => array(
				 	"user_roles.name" => 0
				 ),
				 4 => array(
				 	"users.joined" => 0
				 ),
				 5 => array(
				 	"users.oauth_provider" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->user_model
				->get_total_members_count()
		);
		$members = $this->user_model->get_members($this->datatables);

		foreach($members->result() as $r) {
			if($r->oauth_provider == "google") {
				$provider = "Google";
			} elseif($r->oauth_provider == "twitter") {
				$provider = "Twitter";
			} elseif($r->oauth_provider == "facebook") {
				$provider = "Facebook";
			} else {
				$provider =  lang("ctn_196");
			}
			$this->datatables->data[] = array(
				$r->username,
				$r->first_name,
				$r->last_name,
				$this->common->get_user_role($r),
				date($this->settings->info->date_format, $r->joined),
				$provider
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function search() 
	{

		$search = $this->common->nohtml($this->input->post("search"));

		if(empty($search)) $this->template->error(lang("error_49"));

		$members = $this->user_model->get_members_by_search($search);
		if($members->num_rows() == 0) $this->template->error(lang("error_50"));

		$this->template->loadContent("members/search.php", array(
			"members" => $members,
			"search" => $search
			)
		);
	}

}

?>