<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		if (defined('REQUEST') && REQUEST == "external") {
	        return;
	    }
		$this->template->loadData("activeLink", 
			array("home" => array("general" => 1)));
		$this->load->model("user_model");
		$this->load->model("home_model");
		$this->load->model("tickets_model");
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
	}

	public function index()
	{
		if(!$this->common->has_permissions(array("admin", "admin_settings",
			"admin_members", "admin_payment", "admin_announcements",
		 "ticket_manager", "ticket_worker", "knowledge_manager",
		 "faq_manager"), $this->user)) {
			$this->template->error(lang("error_2"));
		}
		if (defined('REQUEST') && REQUEST == "external") {
	        return;
	    }
		$new_members = $this->user_model->get_new_members(5);
		$months = array();

		// Graph Data
		$current_month = date("n");
		$current_year = date("Y");

		$months = array();
		$current_month = 12;
		$current_year = date("Y");

		// First month
		for($i=11;$i>=0;$i--) {
			// Get month in the past
			$new_month = $current_month - $i;
			
			
			// Get month name using mktime
			$timestamp = mktime(0,0,0,$new_month,1,$current_year);
			$count = $this->tickets_model->get_tickets_for_month($new_month, $current_year);
			$months[] = array(
				"date" => date("F", $timestamp),
				"count" => $count
				);
		}
		$open_tickets = $months;

		$months = array();
		$current_month = 12;
		$current_year = date("Y");

		// First month
		for($i=11;$i>=0;$i--) {
			// Get month in the past
			$new_month = $current_month - $i;
			
			
			// Get month name using mktime
			$timestamp = mktime(0,0,0,$new_month,1,$current_year);
			$count = $this->tickets_model->get_tickets_for_month_closed($new_month, $current_year);
			$months[] = array(
				"date" => date("F", $timestamp),
				"count" => $count
				);
		}
		$close_tickets = $months;


		


		$stats = $this->home_model->get_home_stats();
		if($stats->num_rows() == 0) {
			$this->template->error(lang("error_24"));
		} else {
			$stats = $stats->row();
			if($stats->timestamp < time() - $this->settings->info->cache_time) {
				$stats = $this->get_fresh_results($stats);
				// Update Row
				$this->home_model->update_home_stats_array(array(
					"total_tickets" => $stats->total_tickets,
					"total_assigned_tickets" => $stats->total_assigned_tickets,
					"tickets_today" => $stats->tickets_today,
					"timestamp" => time()
					)
				);
			}
		}

		$your_tickets = $this->tickets_model->get_tickets_your_limit($this->user->info->ID, 0, 5);
		$assigned_tickets = $this->tickets_model->get_tickets_assigned_limit($this->user->info->ID, 0, 5);

		$this->template->loadExternal(
			'<script type="text/javascript" src="'
			.base_url().'scripts/libraries/Chart.min.js" /></script>'
		);

		$online_count = $this->user_model->get_online_count();

		$this->template->loadContent("home/index.php", array(
			"new_members" => $new_members,
			"stats" => $stats,
			"online_count" => $online_count,
			"open_tickets" => $open_tickets,
			"close_tickets" => $close_tickets,
			"your_tickets" => $your_tickets,
			"assigned_tickets" => $assigned_tickets
			)
		);
	}

	private function get_fresh_results($stats) 
	{
		$data = new STDclass;

		$data->total_tickets = $this->tickets_model->get_tickets_total_no_view(0);
		$data->total_assigned_tickets = $this->tickets_model->get_tickets_assigned_total_no_view($this->user->info->ID, 0);
		$data->tickets_today = $this->tickets_model->get_tickets_today(date("d-n-Y"));

		return $data;
	}

	public function change_language() 
	{	
		$languages = $this->config->item("available_languages");
		if(!isset($_COOKIE['language'])) {
			$lang = "";
		} else {
			$lang = $_COOKIE["language"];
		}
		$this->template->loadContent("home/change_language.php", array(
			"languages" => $languages,
			"user_lang" => $lang
			)
		);
	}

	public function change_language_pro() 
	{
		$lang = $this->common->nohtml($this->input->post("language"));
		$languages = $this->config->item("available_languages");
		
		if(!array_key_exists($lang, $languages)) {
			$this->template->error(lang("error_25"));
		}

		setcookie("language", $lang, time()+3600*7, "/");
		$this->session->set_flashdata("globalmsg", lang("success_14"));
		redirect(site_url());
	}

	public function get_usernames() 
	{
		$query = $this->common->nohtml($this->input->get("query"));

		if(!empty($query)) {
			$usernames = $this->user_model->get_usernames($query);
			if($usernames->num_rows() == 0) {
				echo json_encode(array());
			} else {
				$array = array();
				foreach($usernames->result() as $r) {
					$array[] = $r->username;
				}
				echo json_encode($array);
				exit();
			}
		} else {
			echo json_encode(array());
			exit();
		}
	}

	public function load_notifications() 
	{
		$notifications = $this->user_model
			->get_notifications($this->user->info->ID);
		$this->template->loadAjax("home/ajax_notifications.php", array(
			"notifications" => $notifications
			),0
		);	
	}

	public function load_notifications_unread() 
	{
		$notifications = $this->user_model
			->get_notifications_unread($this->user->info->ID);
		$this->template->loadAjax("home/ajax_notifications.php", array(
			"notifications" => $notifications
			),0
		);	
	}

	public function load_notification($id)
	{
		$notification = $this->user_model
			->get_notification($id, $this->user->info->ID);
		if($notification->num_rows() == 0) {
			$this->template->error(lang("error_108"));
		}
		$noti = $notification->row();
		if(!$noti->status) {
			$this->user_model->update_notification($id, array(
				"status" => 1
				)
			);
			$this->user_model->update_user($this->user->info->ID, array(
				"noti_count" => $this->user->info->noti_count - 1
				)
			);
		}

		// redirect
		redirect(site_url($noti->url));
	}

	public function read_all_noti($hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error("Invalid Hash!");
		}
		$noti = $this->user_model->get_all_unread_noti($this->user->info->ID);
		foreach($noti->result() as $r) {
			$this->user_model->update_notification($r->ID, array(
				"status" => 1
				)
			);
		}

		$this->user_model->update_user($this->user->info->ID, array(
			"noti_count" => 0
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_73"));
		redirect(site_url("home/notifications"));
	}

	public function notifications() 
	{
		$this->template->loadContent("home/notifications.php", array(
			)
		);	
	}

	public function notifications_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("user_notifications.timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 2 => array(
				 	"user_notifications.timestamp" => 0
				 )
			)
		);
		$this->datatables->set_total_rows(
			$this->user_model
			->get_notifications_all_total($this->user->info->ID)
		);
		$notifications = $this->user_model
			->get_notifications_all($this->user->info->ID, $this->datatables);



		foreach($notifications->result() as $r) {
			$msg = '<a href="'.site_url("profile/" . $r->username).'">'.$r->username.'</a> ' . $r->message;
			if($r->status !=1) {
				$msg .='<label class="label label-danger">'.lang("ctn_610").'</label>';
			}

			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$msg,
				date($this->settings->info->date_format, $r->timestamp),
				'<a href="'.site_url("home/load_notification/" . $r->ID).'" class="btn btn-primary btn-xs">'.lang("ctn_459").'</a>'
			);
		}
		echo json_encode($this->datatables->process());
	}

}

?>