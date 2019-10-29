<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("reports_model");
		$this->load->model("tickets_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		$this->template->loadData("activeLink", 
			array("report" => array("general" => 1)));

		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
	}

	public function index($type=0) 
	{
		$type = intval($type);
		$this->template->loadData("activeLink", 
			array("report" => array("general" => 1)));

		if(!isset($_POST['start_date'])) {
			$range1 = date("m/d/Y", time() - (3600*24*7));
			$range2 = date("m/d/Y");
		} else {
			$range1 = $this->common->nohtml($this->input->post("start_date"));
			$range2 = $this->common->nohtml($this->input->post("end_date"));
		}

		$dates = $this->common->getDatesFromRange($range1, $range2, "d-n-Y");
		$results = array();
		$results2 = array();

		if($type == 0) {
			foreach($dates as $date) 
			{
				$count = $this->tickets_model->get_tickets_for_day($date);
				$results[] = array(
					"date" => $date,
					"count" => $count
					);
			}
		}
		if($type == 1) {
			foreach($dates as $date) 
			{
				$count = $this->tickets_model->get_tickets_for_day_closed($date);
				$results2[] = array(
					"date" => $date,
					"count" => $count
					);
			}
		}
		if($type == 2) {
			foreach($dates as $date) 
			{
				$count = $this->tickets_model->get_tickets_for_day($date);
				$results[] = array(
					"date" => $date,
					"count" => $count
					);
			}
			foreach($dates as $date) 
			{
				$count = $this->tickets_model->get_tickets_for_day_closed($date);
				$results2[] = array(
					"date" => $date,
					"count" => $count
					);
			}
		}

		if($results) {
			$dates = $results;
		} elseif($results2) {
			$dates = $results2;
		}

		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>'
		);
		
		$this->template->loadContent("reports/index.php", array(
			"results" => $results,
			"results2" => $results2,
			"dates" => $dates,
			"type" => $type
			)
		);
	}

	public function ratings() 
	{
		$this->template->loadData("activeLink", 
			array("report" => array("ratings" => 1)));
		$this->template->loadContent("reports/ratings.php", array(
			)
		);
	}

	public function rating_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("tickets.last_reply_timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"tickets.title" => 0
				 ),
				 1 => array(
				 	"tickets.rating" => 0
				 ),
				 2 => array(
				 	"tickets.status" => 0
				 ),
				 3 => array(
				 	"tickets.categoryid" => 0
				 ),
				 6 => array(
				 	"tickets.last_reply_timestamp" => 0
				 ),
			)
		);

	
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_tickets_rating_total()
			);
			$tickets = $this->tickets_model->get_rating_tickets($this->datatables);
		
	

		foreach($tickets->result() as $r) {
			$rating = "";
			for($i=1;$i<=5;$i++) {
			    if($i > $r->rating) {
			      $rating.='<span class="glyphicon glyphicon-star-empty click" id="ticket'. $i .'"></span>';
			    }else {
			      $rating.='<span class="glyphicon glyphicon-star click" id="ticket'. $i .'"></span>';
			    }
  			}

			if($r->status == 0) {
				$status = lang("ctn_465");
			} elseif($r->status == 1) {
				$status = lang("ctn_466");
			} elseif($r->status == 2) {
				$status = lang("ctn_467");
			}

			if(isset($r->client_username)) {
		        $user = $this->common->get_user_display(array("username" => $r->client_username, "avatar" => $r->client_avatar, "online_timestamp" => $r->client_online_timestamp));
		    } else {
		        $user = '<div class="user-box-avatar"><img src="'.base_url().$this->settings->info->upload_path_relative.'/guest.png'.'" data-toggle="tooltip" data-placement="bottom" title="'.$r->guest_email.'"></div>';
		    }

			
			$this->datatables->data[] = array(
				$r->title,
				$rating,
				$status,
				$r->cat_name,
				$user,
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				date($this->settings->info->date_format,$r->last_reply_timestamp),
				'<a href="'.site_url('tickets/view/' . $r->ID).'" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_459").'">'.lang("ctn_459").'</a> <a href="'.site_url("tickets/edit_ticket/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tickets/delete_ticket/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function users() 
	{
		$this->template->loadData("activeLink", 
			array("report" => array("users" => 1)));
		$this->template->loadContent("reports/users.php", array(
			)
		);
	}

	public function user_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.username", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"avgrating" => 0
				 ),
				 2 => array(
				 	"total" => 0
				 ),
			)
		);

	
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_user_ratings_total()
			);
			$tickets = $this->tickets_model->get_user_ratings($this->datatables);
		

		foreach($tickets->result() as $r) {
			$rating = "";
			for($i=1;$i<=5;$i++) {
			    if($i > $r->avgrating) {
			      $rating.='<span class="glyphicon glyphicon-star-empty click" id="ticket'. $i .'"></span>';
			    }else {
			      $rating.='<span class="glyphicon glyphicon-star click" id="ticket'. $i .'"></span>';
			    }
  			}
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$rating,
				$r->total
			);
		}

		echo json_encode($this->datatables->process());
	}

}

?>