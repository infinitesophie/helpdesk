<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tickets extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("tickets_model");
		$this->load->model("home_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));
		
		$this->template->loadData("activeLink", 
			array("ticket" => array("general" => 1)));

		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager", "ticket_worker"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
	}

	public function index($catid=0) 
	{
		$catid = intval($catid);
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->template->loadData("activeLink", 
			array("ticket" => array("general" => 1)));

		if($this->common->has_permissions(array("admin", "ticket_manager"), 
			$this->user)) {
			$categories = $this->tickets_model->get_categories();
		} else {
			$categories = $this->tickets_model->get_user_categories($this->user->info->ID);
		}

		$views = $this->tickets_model->get_custom_views($this->user->info->ID);
		$statuses = $this->tickets_model->get_custom_statuses();
		
		$this->template->loadContent("tickets/index.php", array(
			"page" => "index",
			"catid" => $catid,
			"categories" => $categories,
			"views" => $views,
			"statuses" => $statuses
			)
		);
	}

	public function merge_ticket($ticketid) 
	{
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$recent_tickets = $this->tickets_model->get_recent_tickets(20);

		$this->template->loadContent("tickets/merge_ticket.php", array(
			"ticket" => $ticket,
			"tickets" => $recent_tickets
			)
		);
	}

	public function merge_ticket_pro($ticketid) 
	{
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$merge_replies = intval($this->input->post("merge_replies"));
		$merge_user = intval($this->input->post("merge_user"));
		$merge_history = intval($this->input->post("merge_history"));
		$merge_ticket = intval($this->input->post("merge_ticket"));
		$merge_files = intval($this->input->post("merge_files"));


		$primary_ticketid = intval($this->input->post("primary_ticketid"));
		if($primary_ticketid == 0) {
			$primary_ticketid = intval($this->input->post("ticket_id"));
		}
		$pticket = $this->tickets_model->get_ticket($primary_ticketid);
		if($pticket->num_rows() == 0) {
			$this->template->error(lang("error_127"));
		}
		$pticket = $pticket->row();

		if($merge_replies) {
			// Update replies of merge ticket to primary ticket
			$this->tickets_model->update_all_ticket_replies($ticket->ID, array(
				"ticketid" => $pticket->ID
				)
			);
		}

		if($merge_user) {
			// Replace pticket user with ticket user
			// if guest, replace guest + password
			$this->tickets_model->update_ticket($pticket->ID, array(
				"userid" => $ticket->userid,
				"guest_email" => $ticket->guest_email,
				"guest_password" => $ticket->guest_password
				)
			);
		}

		if($merge_history) {
			$this->tickets_model->update_all_ticket_history($ticket->ID, array(
				"ticketid" => $pticket->ID
				)
			);
		}

		if($merge_ticket) {
			$this->tickets_model->update_ticket($pticket->ID, array(
				"title" => $ticket->title,
				"body" => $ticket->body,
				"assignedid" => $ticket->assignedid,
				"priority" => $ticket->priority,
				"categoryid" => $ticket->categoryid
				)
			);


			$this->tickets_model->delete_custom_field_data($pticket->ID);

			// Custom fields
			$this->tickets_model->update_all_ticket_cf($ticket->ID, array(
				"ticketid" => $pticket->ID
				)
			);
		}

		if($merge_files) {
			$this->tickets_model->update_all_ticket_files($ticket->ID, array(
				"ticketid" => $pticket->ID
				)
			);
		}

		// Delete ticket
		$this->tickets_model->delete_ticket($ticket->ID);

		$this->session->set_flashdata("globalmsg", lang("success_72"));
		redirect(site_url("tickets/view/" . $pticket->ID));
	}

	public function assign_user($ticketid, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		if($ticket->assignedid > 0) {
			$this->template->error(lang("error_112"));
		}

		$this->tickets_model->update_ticket($ticketid, array(
			"assignedid" => $this->user->info->ID
			)
		);

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" => $this->user->info->username . " " . lang("ctn_661"),
			"timestamp" => time(),
			"ticketid" => $ticketid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_56"));
		redirect(site_url("tickets/view/" . $ticket->ID));
	}

	public function assign_user_pro($ticketid) 
	{
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$username = $this->common->nohtml($this->input->post("username"));
		$user = $this->user_model->get_user_by_username($username);
		if($user->num_rows() == 0) {
			$this->template->error(lang("error_52"));
		}

		$user = $user->row();

		$this->tickets_model->update_ticket($ticketid, array(
			"assignedid" => $user->ID
			)
		);

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" => $user->username . " " . lang("ctn_662"),
			"timestamp" => time(),
			"ticketid" => $ticketid
			)
		);

		$this->user_model->increment_field($user->ID, "noti_count", 1);
		$this->user_model->add_notification(array(
			"userid" => $user->ID,
			"url" => "tickets/view/" . $ticketid,
			"timestamp" => time(),
			"message" => lang("ctn_663"),
			"status" => 0,
			"fromid" => $this->user->info->ID,
			"username" => $user->username,
			"email" => $user->email,
			"email_notification" => $user->email_notification
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_64"));
		redirect(site_url("tickets/view/" . $ticket->ID));
	}

	public function assigned($catid=0) 
	{
		$catid = intval($catid);
		$this->template->loadData("activeLink", 
			array("ticket" => array("ass" => 1)));

		if($this->common->has_permissions(array("admin", "ticket_manager"), 
			$this->user)) {
			$categories = $this->tickets_model->get_categories();
		} else {
			$categories = $this->tickets_model->get_user_categories($this->user->info->ID);
		}

		$views = $this->tickets_model->get_custom_views($this->user->info->ID);

		$statuses = $this->tickets_model->get_custom_statuses();
		
		$this->template->loadContent("tickets/index.php", array(
			"page" => "assigned",
			"catid" => $catid,
			"categories" => $categories,
			"views" => $views,
			"statuses" => $statuses
			)
		);
	}

	public function your($catid=0) 
	{
		$catid = intval($catid);
		$this->template->loadData("activeLink", 
			array("ticket" => array("your" => 1)));

		if($this->common->has_permissions(array("admin", "ticket_manager"), 
			$this->user)) {
			$categories = $this->tickets_model->get_categories();
		} else {
			$categories = $this->tickets_model->get_user_categories($this->user->info->ID);
		}

		$views = $this->tickets_model->get_custom_views($this->user->info->ID);

		$statuses = $this->tickets_model->get_custom_statuses();
		
		$this->template->loadContent("tickets/index.php", array(
			"page" => "your",
			"categories" => $categories,
			"catid" => $catid,
			"views" => $views,
			"statuses" => $statuses,
			)
		);
	}

	public function archived($catid=0) 
	{
		$catid = intval($catid);
		$this->template->loadData("activeLink", 
			array("ticket" => array("archived" => 1)));

		if($this->common->has_permissions(array("admin", "ticket_manager"), 
			$this->user)) {
			$categories = $this->tickets_model->get_categories();
		} else {
			$categories = $this->tickets_model->get_user_categories($this->user->info->ID);
		}

		$views = $this->tickets_model->get_custom_views($this->user->info->ID);

		$statuses = $this->tickets_model->get_custom_statuses();
		
		$this->template->loadContent("tickets/index.php", array(
			"page" => "archived",
			"categories" => $categories,
			"catid" => $catid,
			"views" => $views,
			"statuses" => $statuses,
			)
		);
	}

	public function ticket_page($page, $catid=0, $mode = 0) 
	{

		// get custom view
		$custom_view = $this->tickets_model
			->get_custom_view($this->user->info->custom_view, 
				$this->user->info->ID);

		$catid = intval($catid);
		$this->load->library("datatables");

		$this->datatables->set_default_order("tickets.last_reply_timestamp", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"tickets.ID" => 0
				 ),
				 1 => array(
				 	"tickets.title" => 0
				 ),
				 2 => array(
				 	"tickets.priority" => 0
				 ),
				 3 => array(
				 	"tickets.status" => 0
				 ),
				 4 => array(
				 	"tickets.categoryid" => 0
				 ),
				 7 => array(
				 	"tickets.last_reply_timestamp" => 0
				 ),
			)
		);

		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_tickets_total($catid, $custom_view, $this->datatables)
			);
			$tickets = $this->tickets_model->get_tickets($catid, $this->datatables, $custom_view);
		} elseif($page == "your") {
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_tickets_your_total($this->user->info->ID, $catid, $custom_view, $this->datatables)
			);
			$tickets = $this->tickets_model->get_tickets_your($this->user->info->ID, $catid, $this->datatables, $custom_view);
		} elseif($page == "assigned") {
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_tickets_assigned_total($this->user->info->ID, $catid, $custom_view, $this->datatables)
			);
			$tickets = $this->tickets_model->get_tickets_assigned($this->user->info->ID, $catid, $this->datatables, $custom_view);
		} elseif($page == "archived") {
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_tickets_archived_total($catid, $custom_view, $this->datatables)
			);
			$tickets = $this->tickets_model->get_tickets_archived($catid, $this->datatables, $custom_view);
		} elseif($page == "user") {
			// In this scenario, $catid is userid
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_tickets_user_total($catid, $this->datatables)
			);
			$tickets = $this->tickets_model->get_tickets_user($catid, $this->datatables);
		}
		
		$prioritys = array(0 => "<span class='label label-info'>".lang("ctn_429")."</span>", 1 => "<span class='label label-primary'>".lang("ctn_430")."</span>", 2=> "<span class='label label-warning'>".lang("ctn_431")."</span>", 3 => "<span class='label label-danger'>".lang("ctn_432")."</span>");

		foreach($tickets->result() as $r) {
			
			if(!isset($r->status_name)) {
				$status = lang("ctn_46");
			} else {
				$status = $r->status_name; 
			}

			if(isset($r->client_username)) {
		        $user = $this->common->get_user_display(array("username" => $r->client_username, "avatar" => $r->client_avatar, "online_timestamp" => $r->client_online_timestamp));
		        $user_plain = $r->client_username;
		    } else {
		        $user = '<div class="user-box-avatar"><img src="'.base_url().$this->settings->info->upload_path_relative.'/guest.png'.'" data-toggle="tooltip" data-placement="bottom" title="'.$r->guest_email.'"></div>';
		        $user_plain = $r->guest_email;
		    }

		    if($r->last_reply_userid ==0) {
		    	$last_reply = $user . date($this->settings->info->date_format,$r->last_reply_timestamp);
		    	$last_reply_plain = $user_plain . " " .date($this->settings->info->date_format,$r->last_reply_timestamp);
		    } else {
		    	$last_reply =  $this->common->get_user_display(array("username" => $r->lr_username, "avatar" => $r->lr_avatar, "online_timestamp" => $r->lr_online_timestamp)) . date($this->settings->info->date_format,$r->last_reply_timestamp);
		    	$last_reply_plain = $r->lr_username . " ". date($this->settings->info->date_format,$r->last_reply_timestamp);
		    }

			if($mode == 0) {
				$this->datatables->data[] = array(
					$r->ID,
					'<a href="'.site_url('tickets/view/' . $r->ID).'">'.$r->title.'</a>',
					$prioritys[$r->priority],
					array("name"=>$status, "statusid" => $r->status),
					$r->cat_name,
					$user,
					$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
					$last_reply,
					'<a href="'.site_url('tickets/view/' . $r->ID).'" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_459").'">'.lang("ctn_459").'</a> <a href="'.site_url("tickets/edit_ticket/" . $r->ID).'" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tickets/delete_ticket/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-sm" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
				);
			} else {
				// Export mode
				$this->datatables->data[] = array(
					$r->ID,
					$r->title,
					$prioritys[$r->priority],
					$status,
					$r->cat_name,
					$user_plain,
					$r->username,
					$last_reply_plain,
					''
				);
			}
		}

		echo json_encode($this->datatables->process());
	}

	public function change_status() 
	{
		$ticketid = intval($this->input->get("ticketid"));
		$status = intval($this->input->get("status"));
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->jsonError(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user has access
		$this->check_ticket_access($ticket);

		$status = $this->tickets_model->get_custom_status($status);
		if($status->num_rows() == 0) {
			$this->template->error(lang("error_113"));
		}
		$status = $status->row();

		if($status->close) {
			$close_ticket = date("d-n-Y");
			$close_timestamp = time();

			if($ticket->userid == 0) {
				$username = $ticket->guest_email;
				$email = $ticket->guest_email;
				$first_name = $ticket->guest_email;
				$last_name = "";
			} else {
				$username = $ticket->client_username;
				$email = $ticket->client_email;
				$first_name = $ticket->first_name;
				$last_name = $ticket->last_name;
			}

			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("close_ticket", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $username,
				"[SITE_URL]" => site_url(),
				"[SITE_NAME]" =>  $this->settings->info->site_name,
				"[FIRST_NAME]" => $first_name,
				"[LAST_NAME]" => $last_name,
				"[TICKET_URL]" => site_url("client/view_ticket/" . $ticket->ID)
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $email);

		} else {
			$close_ticket = "";
			$close_timestamp = 0;
		}

		$this->tickets_model->update_ticket($ticketid, array(
			"status" => $status->ID,
			"close_ticket_date" => $close_ticket,
			"close_timestamp" => $close_timestamp
			)
		);

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_664") . " " . $status->name,
			"timestamp" => time(),
			"ticketid" => $ticketid
			)
		);

		echo json_encode(array("success" => 1, "close" => $status->close, "name" => $status->name));
		exit();
	}

	public function view($id) 
	{
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$this->template->loadData("activeLink", 
			array("ticket" => array("general" => 1)));

		// Check user has access
		$this->check_ticket_access($ticket);

		$files = $this->tickets_model->get_ticket_files($id);

		$replies = $this->tickets_model->get_ticket_replies($id);


		$user_fields = null;
		if($ticket->userid > 0) {
			$user_fields = $this->user_model->get_custom_fields_answers(array(
				), $ticket->userid);
		}

		$ticket_fields = $this->tickets_model->get_custom_fields_for_ticket($id);
		$canned = $this->tickets_model->get_all_canned_responses();

		$history = $this->tickets_model->get_ticket_history_limit($id, 6);

		$statuses = $this->tickets_model->get_custom_statuses();
		
		$this->template->loadContent("tickets/view_ticket.php", array(
			"ticket" => $ticket,
			"files" => $files,
			"replies" => $replies,
			"user_fields" => $user_fields,
			"ticket_fields" => $ticket_fields,
			"canned" => $canned,
			"history" => $history,
			"statuses" => $statuses
			)
		);
	}

	public function notify_ticket($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("errpr_6"));
		}
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" =>  lang("ctn_675"),
			"timestamp" => time(),
			"ticketid" => $id
			)
		);

		if($ticket->userid > 0) {
			$this->user_model->increment_field($ticket->userid, "noti_count", 1);
			$this->user_model->add_notification(array(
				"userid" => $ticket->userid,
				"url" => "tickets/view/" . $id,
				"timestamp" => time(),
				"message" => lang("ctn_674"),
				"status" => 0,
				"fromid" => $this->user->info->ID
				)
			);

			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("ticket_reminder", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $ticket->username,
				"[GUEST_PASS]" => "*******",
				"[SITE_URL]" => site_url(),
				"[SITE_NAME]" =>  $this->settings->info->site_name,
				"[FIRST_NAME]" => $ticket->first_name,
				"[LAST_NAME]" => $ticket->last_name,
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $ticket->client_email);
		} else {
			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("ticket_reminder", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $ticket->guest_email,
				"[GUEST_PASS]" => $ticket->guest_password,
				"[SITE_URL]" => site_url(),
				"[SITE_NAME]" =>  $this->settings->info->site_name,
				"[FIRST_NAME]" => $ticket->guest_email,
				"[LAST_NAME]" => ""
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $ticket->guest_email);
		}

		$this->session->set_flashdata("globalmsg", lang("success_69"));
		redirect(site_url("tickets/view/" . $id));
	}

	public function print_view($id) 
	{
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$this->template->loadData("activeLink", 
			array("ticket" => array("general" => 1)));

		// Check user has access
		$this->check_ticket_access($ticket);

		$files = $this->tickets_model->get_ticket_files($id);

		$replies = $this->tickets_model->get_ticket_replies($id);


		$user_fields = null;
		if($ticket->userid > 0) {
			$user_fields = $this->user_model->get_custom_fields_answers(array(
				), $ticket->userid);
		}

		$ticket_fields = $this->tickets_model->get_custom_fields_for_ticket($id);
		$canned = $this->tickets_model->get_all_canned_responses();
		
		$this->template->loadAjax("tickets/print_ticket.php", array(
			"ticket" => $ticket,
			"files" => $files,
			"replies" => $replies,
			"user_fields" => $user_fields,
			"ticket_fields" => $ticket_fields,
			"canned" => $canned
			),1
		);
	}

	private function check_ticket_access($ticket) 
	{
		// Check user has access
		if($ticket->userid != $this->user->info->ID 
			&& $ticket->assignedid != $this->user->info->ID) {
			$goodFlag = 0;
			// Check the user is in the category's assigned user group
			$groups = $this->tickets_model->get_category_groups($ticket->categoryid);
			if($groups->num_rows() > 0) {
				// Check if the user is any of these groups
				$groupids = array();
				foreach($groups->result() as $r) {
					$groupids[] = $r->groupid;
				}

				$userG = $this->tickets_model->get_user_groups($groupids, $this->user->info->ID);
				if($userG->num_rows() > 0) {
					$goodFlag = 1;
				}
			}

			if(!$goodFlag) {
				// Check 
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		}
	}

	public function ticket_reply($id) 
	{
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user has access
		$this->check_ticket_access($ticket);

		$body = $this->lib_filter->go($this->input->post("body"));
		if(empty($body)) {
			$this->template->error(lang("error_100"));
		}
		$assign = intval($this->input->post("assign"));

		$this->load->library("upload");

		$file_count = intval($this->input->post("file_count"));
		$file_data = array();
		$files_flag = 0;
		if($this->settings->info->enable_ticket_uploads) {
			for($i=1;$i<=$file_count;$i++) {
				if (isset($_FILES['user_file_'. $i]) && $_FILES['user_file_' . $i]['size'] > 0) {
					$this->upload->initialize(array(
					   "upload_path" => $this->settings->info->upload_path,
				       "overwrite" => FALSE,
				       "max_filename" => 300,
				       "encrypt_name" => TRUE,
				       "remove_spaces" => TRUE,
				       "allowed_types" => $this->settings->info->file_types,
				       "max_size" => $this->settings->info->file_size,
						)
					);

					if ( ! $this->upload->do_upload('user_file_' . $i))
		            {
		                    $error = array('error' => $this->upload->display_errors());

		                    $this->template->error(lang("error_98") . "<br /><br />" .
		                    	 $this->upload->display_errors());
		            }

		            $data = $this->upload->data();
		            $files_flag = 1;
		            $file_data[] = array(
		            	"upload_file_name" => $data['file_name'],
		            	"file_type" => $data['file_type'],
		            	"extension" => $data['file_ext'],
		            	"file_size" => $data['file_size'],
		            	"timestamp" => time()
		            	);
		        }
			}
		}

		$new_message_id_hash = md5(rand(1,100000000)."fhhfh".time());

		// Add
		$replyid = $this->tickets_model->add_ticket_reply(array(
			"ticketid" => $id,
			"userid" => $this->user->info->ID,
			"body" => $body,
			"timestamp" => time(),
			"files" => $files_flag,
			"hash" => $new_message_id_hash
			)
		);

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"replyid" => $replyid,
				"ticketid" => $id,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $this->user->info->ID
				)
			);
		}

		$assignedid = $ticket->assignedid;
		if($assign) {
			$assignedid = $this->user->info->ID;
		}

		
		// Update last reply
		$this->tickets_model->update_ticket($ticket->ID, array(
			"last_reply_userid" => $this->user->info->ID,
			"last_reply_timestamp" => time(),
			"last_reply_string" => date($this->settings->info->date_format, time()),
			"assignedid" => $assignedid
			)
		);

		// Notification
		if($ticket->userid == $this->user->info->ID) {
			// Client is posting
			// Check for status update
			if($this->settings->info->client_status > 0) {
				// Update last reply
				$this->tickets_model->update_ticket($ticket->ID, array(
					"status" => $this->settings->info->client_status
					)
				);
			}


			// Alert assigned user of new reply
			if($ticket->assignedid > 0) {
				$this->user_model->increment_field($ticket->assignedid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $ticket->assignedid,
					"url" => "tickets/view/" . $ticket->ID,
					"timestamp" => time(),
					"message" => lang("ctn_609"),
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"username" => $ticket->username,
					"email" => $ticket->email,
					"email_notification" => $ticket->email_notification
					)
				);
			}
		} elseif($this->user->info->ID == $ticket->assignedid) {
			// Staff
			// Check for status update
			if($this->settings->info->staff_status > 0) {
				// Update last reply
				$this->tickets_model->update_ticket($ticket->ID, array(
					"status" => $this->settings->info->staff_status
					)
				);
			}
			// Alert user of new reply
			if($ticket->userid > 0) {
				$this->user_model->increment_field($ticket->userid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $ticket->userid,
					"url" => "client/view_ticket/" . $ticket->ID,
					"timestamp" => time(),
					"message" => lang("ctn_609"),
					"status" => 0,
					"fromid" => $this->user->info->ID
					)
				);
			}
		} else {
			// Check for status update
			if($this->settings->info->staff_status > 0) {
				// Update last reply
				$this->tickets_model->update_ticket($ticket->ID, array(
					"status" => $this->settings->info->staff_status
					)
				);
			}

			// Alert both in case of random user reply
			if($ticket->userid > 0) {
				$this->user_model->increment_field($ticket->userid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $ticket->userid,
					"url" => "client/view_ticket/" . $ticket->ID,
					"timestamp" => time(),
					"message" => lang("ctn_609"),
					"status" => 0,
					"fromid" => $this->user->info->ID
					)
				);
			}
			if($ticket->assignedid > 0) {
				$this->user_model->increment_field($ticket->assignedid, "noti_count", 1);
				$this->user_model->add_notification(array(
					"userid" => $ticket->assignedid,
					"url" => "tickets/view/" . $ticket->ID,
					"timestamp" => time(),
					"message" => lang("ctn_609"),
					"status" => 0,
					"fromid" => $this->user->info->ID,
					"username" => $ticket->username,
					"email" => $ticket->email,
					"email_notification" => $ticket->email_notification
					)
				);
			}
		}

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_665"),
			"timestamp" => time(),
			"ticketid" => $ticket->ID
			)
		);

		if($ticket->userid != $this->user->info->ID) {
			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("ticket_reply", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			if(isset($ticket->client_username)) {
				$username = $ticket->client_username;
				$email = $ticket->client_email;
				$first_name = $ticket->first_name;
				$last_name = $ticket->last_name;
			} else {
				$username = $ticket->guest_email;
				$email = $ticket->guest_email;
				$first_name = $ticket->guest_email;
				$last_name = "";
			}

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $username,
				"[SITE_URL]" => site_url(),
				"[TICKET_BODY]" => $body,
				"[TICKET_ID]" => $id,
				"[SITE_NAME]" =>  $this->settings->info->site_name,
				"[IMAP_TICKET_REPLY_STRING]" => $this->settings->info->imap_reply_string,
				"[IMAP_TICKET_ID]" => $this->settings->info->imap_ticket_string,
				"[FIRST_NAME]" => $first_name,
				"[LAST_NAME]" => $last_name,
				),
			$email_template->message);

			$headers = array(
				"References" => $ticket->message_id_hash,
				"In-Reply-To" => $ticket->message_id_hash,
				"Message-ID" => $new_message_id_hash
				);
			$this->common->send_email($this->settings->info->ticket_title . " [ID: " . $id . "]: " . $ticket->title,
				 $email_template->message, $email, $headers);
		}

		$this->session->set_flashdata("globalmsg", lang("success_45"));
		redirect(site_url("tickets/view/" . $id));
	}

	public function edit_ticket_reply($id) 
	{
		$this->template->loadData("activeLink", 
			array("tickets" => array("general" => 1)));

		$id = intval($id);
		$reply = $this->tickets_model->get_ticket_reply($id);
		if($reply->num_rows() == 0) {
			$this->template->error(lang("error_101"));
		}

		$reply = $reply->row();

		if($reply->userid != $this->user->info->ID) {
			// Check user has admin rights
			// Check 
			if(!$this->common->has_permissions(array(
				"admin", "ticket_manager"), $this->user)) {
				$this->template->error(lang("error_85"));
			}
		}
		$this->template->loadContent("tickets/edit_ticket_reply.php", array(
			"reply" => $reply
			)
		);
	}

	public function edit_ticket_reply_pro($id) 
	{
		$id = intval($id);
		$reply = $this->tickets_model->get_ticket_reply($id);
		if($reply->num_rows() == 0) {
			$this->template->error(lang("error_101"));
		}
		$reply = $reply->row();

		if($reply->userid != $this->user->info->ID) {
			// Check user has admin rights
			// Check 
			if(!$this->common->has_permissions(array(
				"admin", "ticket_manager"), $this->user)) {
				$this->template->error(lang("error_85"));
			}
		}

		$body = $this->lib_filter->go($this->input->post("body"));
		if(empty($body)) {
			$this->template->error(lang("error_103"));
		}

		$this->load->library("upload");

		$file_count = intval($this->input->post("file_count"));
		$file_data = array();
		$files_flag = $reply->files;
		if($this->settings->info->enable_ticket_uploads) {
			for($i=1;$i<=$file_count;$i++) {
				if (isset($_FILES['user_file_'. $i]) && $_FILES['user_file_' . $i]['size'] > 0) {
					$this->upload->initialize(array(
					   "upload_path" => $this->settings->info->upload_path,
				       "overwrite" => FALSE,
				       "max_filename" => 300,
				       "encrypt_name" => TRUE,
				       "remove_spaces" => TRUE,
				       "allowed_types" => $this->settings->info->file_types,
				       "max_size" => $this->settings->info->file_size,
						)
					);

					if ( ! $this->upload->do_upload('user_file_' . $i))
		            {
		                    $error = array('error' => $this->upload->display_errors());

		                    $this->template->error(lang("error_98") . "<br /><br />" .
		                    	 $this->upload->display_errors());
		            }

		            $data = $this->upload->data();
		            $files_flag = 1;
		            $file_data[] = array(
		            	"upload_file_name" => $data['file_name'],
		            	"file_type" => $data['file_type'],
		            	"extension" => $data['file_ext'],
		            	"file_size" => $data['file_size'],
		            	"timestamp" => time()
		            	);
		        }
			}
		}

		// Add
		$this->tickets_model->update_ticket_reply($id, array(
			"body" => $body,
			"files" => $files_flag
			)
		);

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"ticketid" => $reply->ticketid,
				"replyid" => $id,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $this->user->info->ID
				)
			);
		}

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" =>  lang("ctn_651") .$reply->body . 
			"<br />".lang("ctn_652").":<br />" . $body,
			"timestamp" => time(),
			"ticketid" => $reply->ticketid
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_46"));
		redirect(site_url("tickets/view/" . $reply->ticketid));
	}

	public function delete_ticket_reply($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$reply = $this->tickets_model->get_ticket_reply($id);
		if($reply->num_rows() == 0) {
			$this->template->error(lang("error_101"));
		}
		$reply = $reply->row();

		if($reply->userid != $this->user->info->ID) {
			// Check user has admin rights
			// Check 
			if(!$this->common->has_permissions(array(
				"admin", "ticket_manager"), $this->user)) {
				$this->template->error(lang("error_85"));
			}
		}

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_653").": ". $reply->body,
			"timestamp" => time(),
			"ticketid" => $reply->ticketid
			)
		);

		$this->tickets_model->delete_ticket_reply($id);
		$this->session->set_flashdata("globalmsg", lang("success_47"));
		redirect(site_url("tickets/view/" . $reply->ticketid));
	}

	public function edit_ticket($id) 
	{
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user has access
		$this->check_ticket_access($ticket);

		$this->template->loadData("activeLink", 
			array("ticket" => array("general" => 1)));

		// Get sub-categories
		$sub_cats = null;
		$sub_cat_fields = null;
		if($ticket->cat_parent > 0) {
			$sub_cats = $this->tickets_model->get_sub_cats($ticket->cat_parent);
		}

		$sub_cat_fields = $this->tickets_model->get_custom_fields_for_cat_ticket($id, $ticket->categoryid);

		$fields = $this->tickets_model->get_custom_fields_all_cats_ticket($id);

		$categories = $this->tickets_model->get_category_no_parent();

		$files = $this->tickets_model->get_ticket_files($id);

		$statuses = $this->tickets_model->get_custom_statuses();
		
		$this->template->loadContent("tickets/edit_ticket.php", array(
			"ticket" => $ticket,
			"categories" => $categories,
			"fields" => $fields,
			"sub_cats" => $sub_cats,
			"sub_cat_fields" => $sub_cat_fields,
			"ticket_files" => $files,
			"statuses" => $statuses
			)
		);

	}

	public function edit_ticket_pro($id) 
	{
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user has access
		$this->check_ticket_access($ticket);

		$title = $this->common->nohtml($this->input->post("title"));
		$client = $this->common->nohtml($this->input->post("client"));
		$guest_email = $this->common->nohtml($this->input->post("guest_email"));
		$assigned = $this->common->nohtml($this->input->post("assigned"));
		$priority = intval($this->input->post("priority"));
		$statusid = intval($this->input->post("statusid"));
		$catid = intval($this->input->post("catid"));
		$sub_catid = intval($this->input->post("sub_catid"));
		$archived = intval($this->input->post("archived"));
		$public = intval($this->input->post("public"));

		$file_count = intval($this->input->post("file_count"));

		$body = $this->lib_filter->go($this->input->post("body"));
		$notes = $this->lib_filter->go($this->input->post("notes"));

		if(empty($title)) {
			$this->template->error(lang("error_81"));
		}

		if(empty($body)) {
			$this->template->error(lang("error_92"));
		}

		$status = $this->tickets_model->get_custom_status($statusid);
		if($status->num_rows() == 0) {
			$this->template->error(lang("error_113"));
		}
		$status = $status->row();



		if($priority < 0 || $priority > 3) {
			$this->template->error(lang("error_93"));
		}

		// Get client
		$clientid = 0;
		if(!empty($client)) {
			$user = $this->user_model->get_user_by_username($client);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_114"));
			}
			$user = $user->row();
			$clientid = $user->ID;
			$client_username = $user->username;
			$client_email = $user->email;
			$first_name = $user->first_name;
			$last_name = $user->last_name;
		}

		// Get assigned
		$assignedid = 0;
		if(!empty($assigned)) {
			$user = $this->user_model->get_user_by_username($assigned);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_115"));
			}
			$user = $user->row();
			$assignedid = $user->ID;
		}

		if($clientid == 0) {
			if($this->settings->info->enable_ticket_guests) {
				if(empty($guest_email)) {
					$this->template->error(lang("error_116"));
				}
			} else {
				$this->template->error(lang("error_117"));
			}
		}

		if($status->close) {
			$close_ticket = date("d-n-Y");
			$close_timestamp = time();

			if($clientid == 0) {
				$username = $guest_email;
				$email = $guest_email;
				$first_name = $guest_email;
				$last_name = "";
			} else {
				$username = $client_username;
				$email = $client_email;
				$first_name = $first_name;
				$last_name = $last_name;
			}

			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("close_ticket", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $username,
				"[SITE_URL]" => site_url(),
				"[SITE_NAME]" =>  $this->settings->info->site_name,
				"[FIRST_NAME]" => $first_name,
				"[LAST_NAME]" => $last_name,
				"[TICKET_URL]" => site_url("client/view_ticket/" . $ticket->ID)
				),
			$email_template->message);

			$this->common->send_email($email_template->title,
				 $email_template->message, $email);
		} else {
			$close_ticket = "";
			$close_timestamp = 0;
		}

		// Check categories
		$category = $this->tickets_model->get_category($catid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}

		$category = $category->row();

		// Check subcat
		if($sub_catid > 0) {
			$subcat = $this->tickets_model->get_category($sub_catid);
			if($subcat->num_rows() == 0) {
				$this->template->error(lang("error_96"));
			}
			$categoryid = $sub_catid;
		} else {
			$categoryid = $catid;
		}

		if($category->no_tickets && $sub_catid == 0) {
			$this->template->error(lang("error_97"));
		}

		// Custom fields
		$fields = $this->tickets_model->get_custom_fields_all_cats();
		// Process fields
		$answers = array();
		$answers = $this->custom_field_check($fields, $answers);

		// check subcat or primary cat
		if($sub_catid > 0) {
			$fields = $this->tickets_model->get_custom_fields_for_cat($sub_catid);
		} else {
			$fields = $this->tickets_model->get_custom_fields_for_cat($catid);
		}
		$answers = $this->custom_field_check($fields, $answers);

		// Upload check
		$this->load->library("upload");

		$file_data = array();
		if($this->settings->info->enable_ticket_uploads) {
			for($i=1;$i<=$file_count;$i++) {
				if (isset($_FILES['user_file_' . $i]['size']) 
					&& $_FILES['user_file_' . $i]['size'] > 0) {
					$this->upload->initialize(array(
					   "upload_path" => $this->settings->info->upload_path,
				       "overwrite" => FALSE,
				       "max_filename" => 300,
				       "encrypt_name" => TRUE,
				       "remove_spaces" => TRUE,
				       "allowed_types" => $this->settings->info->file_types,
				       "max_size" => $this->settings->info->file_size,
						)
					);

					if ( ! $this->upload->do_upload('user_file_' . $i))
		            {
		                    $error = array('error' => $this->upload->display_errors());

		                    $this->template->error(lang("error_98") . "<br /><br />" .
		                    	 $this->upload->display_errors());
		            }

		            $data = $this->upload->data();

		            $file_data[] = array(
		            	"upload_file_name" => $data['file_name'],
		            	"file_type" => $data['file_type'],
		            	"extension" => $data['file_ext'],
		            	"file_size" => $data['file_size'],
		            	"timestamp" => time()
		            	);
		        }
			}
		}

		// Create ticket
		$this->tickets_model->update_ticket($id, array(
			"title" => $title,
			"body" => $body,
			"userid" => $clientid,
			"assignedid" => $assignedid,
			"categoryid" => $categoryid,
			"status" => $statusid,
			"priority" => $priority,
			"notes" => $notes,
			"guest_email" => $guest_email,
			"close_ticket_date" => $close_ticket,
			"archived" => $archived,
			"public" => $public,
			"close_timestamp" => $close_timestamp
			)
		);

		// Wipe out all old custom field data
		$this->tickets_model->delete_custom_field_data($id);

		// Custom field data
		foreach($answers as $d) {
			$itemname = "";
			$support = 0;
			$error = "";
			if(isset($d['itemname'])) {
				$itemname = $d['itemname'];
				$support = $d['support'];
				$error = $d['error'];
			}
			$this->tickets_model->add_custom_field_data(array(
				"ticketid" => $id,
				"fieldid" => $d['fieldid'],
				"value" => $d['answer'],
				"itemname" => $itemname,
				"support" => $support,
				"error" => $error
				)
			);
		}

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"ticketid" => $id,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $this->user->info->ID
				)
			);
		}

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_666"),
			"timestamp" => time(),
			"ticketid" => $id
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_48"));
		redirect(site_url("tickets"));



	}

	public function delete_file_attachment($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$file = $this->tickets_model->get_ticket_file($id);
		if($file->num_rows() == 0) {
			$this->template->error(lang("error_107"));
		}
		$file = $file->row();

		if($file->userid != $this->user->info->ID) {
			// Check user has admin rights
			// Check 
			if(!$this->common->has_permissions(array(
				"admin", "ticket_manager"), $this->user)) {
				$this->template->error(lang("error_85"));
			}
		}

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_667"),
			"timestamp" => time(),
			"ticketid" => $file->ticketid
			)
		);

		$this->tickets_model->delete_ticket_file($id);
		$this->session->set_flashdata("globalmsg", lang("success_49"));
		redirect(site_url("tickets/view/" . $file->ticketid));
	}

	public function delete_ticket($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user has access
		$this->check_ticket_access($ticket);

		$this->tickets_model->delete_ticket($id);
		$this->session->set_flashdata("globalmsg", lang("success_57"));
		redirect(site_url("tickets"));
	}

	public function add() 
	{
		$this->template->loadData("activeLink", 
			array("ticket" => array("general" => 1)));

		$fields = $this->tickets_model->get_custom_fields_all_cats();

		$categories = $this->tickets_model->get_category_no_parent();

		$statuses = $this->tickets_model->get_custom_statuses();
		
		$this->template->loadContent("tickets/add.php", array(
			"categories" => $categories,
			"fields" => $fields,
			"statuses" => $statuses
			)
		);
	}

	public function add_pro() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$client = $this->common->nohtml($this->input->post("client"));
		$guest_email = $this->common->nohtml($this->input->post("guest_email"));
		$assigned = $this->common->nohtml($this->input->post("assigned"));
		$priority = intval($this->input->post("priority"));
		$statusid = intval($this->input->post("statusid"));
		$catid = intval($this->input->post("catid"));
		$sub_catid = intval($this->input->post("sub_catid"));
		$archived = intval($this->input->post("archived"));
		$public = intval($this->input->post("public"));

		$file_count = intval($this->input->post("file_count"));

		$body = $this->lib_filter->go($this->input->post("body"));
		$notes = $this->lib_filter->go($this->input->post("notes"));

		if(empty($title)) {
			$this->template->error(lang("error_81"));
		}

		if(empty($body)) {
			$this->template->error(lang("error_92"));
		}

		$status = $this->tickets_model->get_custom_status($statusid);
		if($status->num_rows() == 0) {
			$this->template->error(lang("error_113"));
		}
		$status = $status->row();

		if($priority < 0 || $priority > 3) {
			$this->template->error(lang("error_93"));
		}

		// Get client
		$clientid = 0;
		$client_username = "";
		if(!empty($client)) {
			$user = $this->user_model->get_user_by_username($client);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_114"));
			}
			$user = $user->row();
			$client_username = $user->username;
			$client_email = $user->email;
			$clientid = $user->ID;
			$first_name = $user->first_name;
			$last_name = $user->last_name;
		}

		// Get assigned
		$assignedid = 0;
		if(!empty($assigned)) {
			$user = $this->user_model->get_user_by_username($assigned);
			if($user->num_rows() == 0) {
				$this->template->error(lang("error_115"));
			}
			$user = $user->row();
			$assignedid = $user->ID;
		}

		if($clientid == 0) {
			if($this->settings->info->enable_ticket_guests) {
				if(empty($guest_email)) {
					$this->template->error(lang("error_116"));
				}
			} else {
				$this->template->error(lang("error_117"));
			}
		}

		// Check categories
		$category = $this->tickets_model->get_category($catid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}

		$category = $category->row();

		// Check subcat
		if($sub_catid > 0) {
			$subcat = $this->tickets_model->get_category($sub_catid);
			if($subcat->num_rows() == 0) {
				$this->template->error(lang("error_96"));
			}
			$categoryid = $sub_catid;
		} else {
			$categoryid = $catid;
		}

		if($category->no_tickets && $sub_catid == 0) {
			$this->template->error(lang("error_97"));
		}

		// Custom fields
		$fields = $this->tickets_model->get_custom_fields_all_cats();
		// Process fields
		$answers = array();
		$answers = $this->custom_field_check($fields, $answers);

		// check subcat or primary cat
		if($sub_catid > 0) {
			$fields = $this->tickets_model->get_custom_fields_for_cat($sub_catid);
		} else {
			$fields = $this->tickets_model->get_custom_fields_for_cat($catid);
		}
		$answers = $this->custom_field_check($fields, $answers);

		// Upload check
		$this->load->library("upload");

		$file_data = array();
		if($this->settings->info->enable_ticket_uploads) {
			for($i=1;$i<=$file_count;$i++) {
				if (isset($_FILES['user_file_'. $i]) 
					&& $_FILES['user_file_' . $i]['size'] > 0) {
					$this->upload->initialize(array(
					   "upload_path" => $this->settings->info->upload_path,
				       "overwrite" => FALSE,
				       "max_filename" => 300,
				       "encrypt_name" => TRUE,
				       "remove_spaces" => TRUE,
				       "allowed_types" => $this->settings->info->file_types,
				       "max_size" => $this->settings->info->file_size,
						)
					);

					if ( ! $this->upload->do_upload('user_file_' . $i))
		            {
		                    $error = array('error' => $this->upload->display_errors());

		                    $this->template->error(lang("error_98") . "<br /><br />" .
		                    	 $this->upload->display_errors());
		            }

		            $data = $this->upload->data();

		            $file_data[] = array(
		            	"upload_file_name" => $data['file_name'],
		            	"file_type" => $data['file_type'],
		            	"extension" => $data['file_ext'],
		            	"file_size" => $data['file_size'],
		            	"timestamp" => time()
		            	);
		        }
			}
		}

		// Notifications

		// Create ticket
		// Message id hash
		$message_id_hash = md5(rand(1,100000) . $title . time());
		$guest_password = $this->common->randomPassword();

		// Create ticket
		$ticketid = $this->tickets_model->add_ticket(array(
			"title" => $title,
			"body" => $body,
			"userid" => $clientid,
			"assignedid" => $assignedid,
			"timestamp" => time(),
			"categoryid" => $categoryid,
			"status" => $statusid,
			"priority" => $priority,
			"last_reply_timestamp" => time(),
			"last_reply_string" => date($this->settings->info->date_format, time()),
			"notes" => $notes,
			"message_id_hash" => $message_id_hash,
			"guest_email" => $guest_email,
			"guest_password" => $guest_password,
			"ticket_date" => date("d-n-Y"),
			"archived" => $archived,
			"public" => $public
			)
		);

		// Custom field data
		foreach($answers as $d) {
			$itemname = "";
			$support = 0;
			$error = "";
			if(isset($d['itemname'])) {
				$itemname = $d['itemname'];
				$support = $d['support'];
				$error = $d['error'];
			}
			$this->tickets_model->add_custom_field_data(array(
				"ticketid" => $ticketid,
				"fieldid" => $d['fieldid'],
				"value" => $d['answer'],
				"itemname" => $itemname,
				"support" => $support,
				"error" => $error
				)
			);
		}

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"ticketid" => $ticketid,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $this->user->info->ID
				)
			);
		}

		$this->tickets_model->add_history(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_668"),
			"timestamp" => time(),
			"ticketid" => $ticketid
			)
		);

		if($clientid > 0) {
			if(!isset($_COOKIE['language'])) {
				// Get first language in list as default
				$lang = $this->config->item("language");
			} else {
				$lang = $this->common->nohtml($_COOKIE["language"]);
			}

			// Send Email
			$email_template = $this->home_model->get_email_template_hook("ticket_creation", $lang);
			if($email_template->num_rows() == 0) {
				$this->template->error(lang("error_48"));
			}
			$email_template = $email_template->row();

			if($clientid == 0) {
				$username = $guest_email;
				$email = $guest_email;
				$first_name = $ticket->guest_email;
				$last_name = "";
			} else {
				$username = $client_username;
				$email = $client_email;
				$first_name = $first_name;
				$last_name = $last_name;
			}

			$email_template->message = $this->common->replace_keywords(array(
				"[NAME]" => $username,
				"[SITE_URL]" => site_url(),
				"[TICKET_BODY]" => $body,
				"[TICKET_ID]" => $ticketid,
				"[SITE_NAME]" =>  $this->settings->info->site_name,
				"[IMAP_TICKET_REPLY_STRING]" => $this->settings->info->imap_reply_string,
				"[IMAP_TICKET_ID]" => $this->settings->info->imap_ticket_string,
				"[FIRST_NAME]" => $first_name,
				"[LAST_NAME]" => $last_name,
				),
			$email_template->message);

			$headers = array(
				"Message-ID" => $message_id_hash
				);
			$this->common->send_email($this->settings->info->ticket_title . " [ID: " . $ticketid . "]: " . $title,
				 $email_template->message, $email, $headers);
		} else {
				// Send email
				if(!isset($_COOKIE['language'])) {
					// Get first language in list as default
					$lang = $this->config->item("language");
				} else {
					$lang = $this->common->nohtml($_COOKIE["language"]);
				}

				// Send Email
				$email_template = $this->home_model->get_email_template_hook("guest_ticket_creation", $lang);
				if($email_template->num_rows() == 0) {
					$this->template->error(lang("error_48"));
				}
				$email_template = $email_template->row();

				if($clientid == 0) {
					$username = $guest_email;
					$email = $guest_email;
					$first_name = $guest_email;
					$last_name = "";
				} else {
					$username = $client_username;
					$email = $client_email;
					$first_name = $first_name;
					$last_name = $last_name;
				}

				$email_template->message = $this->common->replace_keywords(array(
					"[NAME]" => $username,
					"[SITE_URL]" => site_url(),
					"[TICKET_BODY]" => $body,
					"[TICKET_ID]" => $ticketid,
					"[SITE_NAME]" =>  $this->settings->info->site_name,
					"[GUEST_EMAIL]" => $guest_email,
					"[GUEST_PASS]" => $guest_password,
					"[GUEST_LOGIN]" => site_url("client/tickets"),
					"[IMAP_TICKET_REPLY_STRING]" => $this->settings->info->imap_reply_string,
					"[IMAP_TICKET_ID]" => $this->settings->info->imap_ticket_string,
					"[FIRST_NAME]" => $first_name,
					"[LAST_NAME]" => $last_name,
					),
				$email_template->message);

				$headers = array(
					"Message-ID" => $message_id_hash
					);
				$this->common->send_email($this->settings->info->ticket_title . " [ID: " . $ticketid . "]: " . $title,
					 $email_template->message, $email, $headers);
			}
		$this->session->set_flashdata("globalmsg", lang("success_44"));
		redirect(site_url("tickets"));


	}

	private function custom_field_check($fields, $answers) 
	{
		foreach($fields->result() as $r) {
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && (empty($answer) && $answer !== '0') ) {
					$this->template->error(lang("error_99") . $r->name);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				$answer = $this->lib_filter->go($this->input->post("cf_" . $r->ID));

				if($r->required && (empty($answer) && $answer !== '0')) {
					$this->template->error(lang("error_99") . $r->name);
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 2) {
				// Checkbox
				$options = explode(",", $r->options);
				foreach($options as $k=>$v) {
					// Look for checked checkbox and add it to the answer if it's value is 1
					$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && (empty($answer) && $answer !== '0')) {
					$this->template->error(lang("error_99") . $r->name);
				}
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if(isset($_POST['cf_radio_' . $r->ID])) {
					$answer = intval($this->common->nohtml($this->input->post("cf_radio_" . $r->ID)));
					
					$flag = false;
					foreach($options as $k=>$v) {
						if($k == $answer) {
							$flag = true;
							$answer = $v;
						}
					}
					if($r->required && !$flag) {
						$this->template->error(lang("error_99") . $r->name);
					}
					if($flag) {
						$answers[] = array(
							"fieldid" => $r->ID,
							"answer" => $answer
						);
					}
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$this->template->error(lang("error_99") . $r->name);
				}
				if($flag) {
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			} elseif($r->type == 5) {
				// Look for simple text entry
				$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));

				if($r->required && (empty($answer) && $answer !== '0')) {
					$this->template->error(lang("error_99") . $r->name);
				}

				$this->load->library("Envato");

				$error = "";
				$timestamp = 0;
				$itemname = "";

				$result = $this->envato->check_item_code($answer);
				if(isset($result->error)) {
					$error = $result->error . " " . $result->description;
				} else {
					// Check for item name
					if(isset($result->item->name)) {
						$itemname = $result->item->name;
						$support = $result->supported_until;

						$ed = DateTime::createFromFormat('Y-m-d\TH:i:sP', $support);
						$end_date = $ed->format('Y-m-d\TH:i:s');
						$timestamp = $ed->getTimestamp();
					}
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer,
					"itemname" => $itemname,
					"support" => $timestamp,
					"error" => $error
				);
			}
		}
		return $answers;
	}

	public function get_custom_fields($catid) 
	{
		$catid = intval($catid);
		$category = $this->tickets_model->get_category($catid);
		if($category->num_rows() == 0) {
			$this->template->errori(lang("error_104"));
		}

		$fields = $this->tickets_model->get_custom_fields_for_cat($catid);
		$this->template->loadAjax("tickets/ajax_custom_fields.php", array(
				"fields" => $fields
				), 1
			);
	}

	public function get_sub_cats($parent) 
	{
		$parent = intval($parent);
		$category = $this->tickets_model->get_category($parent);
		if($category->num_rows() == 0) {
			$this->template->errori(lang("error_104"));
		}
		$category = $category->row();

		if($category->cat_parent > 0) {
			$this->template->errori(lang("error_105"));
		}

		// Get sub cats
		$sub_cats = $this->tickets_model->get_sub_cats($parent);
		if($sub_cats->num_rows() > 0) {
			$this->template->loadAjax("tickets/sub_cats.php", array(
				"categories" => $sub_cats
				), 1
			);
		} else {
			exit();
		}
	}

	public function custom_fields() 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->template->loadData("activeLink", 
			array("ticket" => array("custom" => 1)));

		
		$this->template->loadContent("tickets/custom_fields.php", array(
			)
		);
	}

	public function field_page() 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->load->library("datatables");

		$this->datatables->set_default_order("ticket_custom_fields.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"ticket_custom_fields.name" => 0
				 ),
				 1 => array(
				 	"ticket_custom_fields.type" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->tickets_model
				->get_custom_fields_total()
		);
		$fields = $this->tickets_model->get_custom_fields($this->datatables);
		

		foreach($fields->result() as $r) {
			if($r->type == 0) {
				$type = lang("ctn_357");
			} elseif($r->type == 1) {
				$type = lang("ctn_577");
			} elseif($r->type == 2) {
				$type = lang("ctn_578");
			} elseif($r->type == 3) {
				$type = lang("ctn_579");
			} elseif($r->type == 4) {
				$type = lang("ctn_580");
			} elseif($r->type == 5) {
				$type = lang("ctn_679");
			}
			
			$this->datatables->data[] = array(
				$r->name,
				$type,
				'<a href="'.site_url("tickets/edit_custom_field/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tickets/delete_custom_field/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function edit_custom_field($id) 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->template->loadExternal(
			'<link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("ticket" => array("custom" => 1)));
		$id = intval($id);
		$field = $this->tickets_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_118"));
		}

		$field = $field->row();

		$user_cats = $this->tickets_model->get_field_cats($field->ID);

		$this->template->loadContent("tickets/edit_custom_field.php", array(
			"field" => $field,
			"user_cats" => $user_cats
			)
		);

	}

	public function edit_custom_field_pro($id) 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->template->loadData("activeLink", 
			array("ticket" => array("custom" => 1)));
		$id = intval($id);
		$field = $this->tickets_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_118"));
		}
		$field = $field->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$required = intval($this->input->post("required"));
		$options = $this->common->nohtml($this->input->post("options"));
		$help_text = $this->common->nohtml($this->input->post("help_text"));

		$user_cats = $this->input->post("user_cats");
		$all_cats = intval($this->input->post("all_cats"));

		$hide_clientside = intval($this->input->post("hide_clientside"));

		if(empty($name)) {
			$this->template->error(lang("error_119"));
		}

		// Check ticket categories
		$cats= array();
		if($user_cats) {
			foreach($user_cats as $cat) {
				$cat = intval($cat);
				if($cat > 0) {
					$catr = $this->tickets_model->get_category($cat);
					if($catr->num_rows() == 0) {
						$this->template->error(lang("error_110"));
					}
				}
				$cats[] = $cat;
			}
		}

		$this->tickets_model->update_custom_field($id, array(
			"name" => $name,
			"type" => $type,
			"required" => $required,
			"options" => $options,
			"help_text" => $help_text,
			"all_cats" => $all_cats,
			"hide_clientside" => $hide_clientside
			)
		);


		$this->tickets_model->delete_custom_fields_cats($id);

		// Add custom field to cats
		foreach($cats as $catid) {
			$this->tickets_model->add_field_cats(array(
				"fieldid" => $id,
				"catid" => $catid
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_58"));
		redirect(site_url("tickets/custom_fields"));
	}

	public function delete_custom_field($id, $hash) 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$field = $this->tickets_model->get_custom_field($id);
		if($field->num_rows() == 0) {
			$this->template->error(lang("error_118"));
		}

		$this->tickets_model->delete_custom_field($id);
		$this->session->set_flashdata("globalmsg", lang("success_39"));
		redirect(site_url("tickets/custom_fields"));
	}

	public function add_custom_field() 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->template->loadExternal(
			'<link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("ticket" => array("custom" => 1)));

		$categories = $this->tickets_model->get_categories();

		
		$this->template->loadContent("tickets/add_custom_field.php", array(
			"categories" => $categories
			)
		);
	}

	public function add_custom_field_pro() 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$type = intval($this->input->post("type"));
		$required = intval($this->input->post("required"));
		$options = $this->common->nohtml($this->input->post("options"));
		$help_text = $this->common->nohtml($this->input->post("help_text"));

		$user_cats = $this->input->post("user_cats");
		$all_cats = intval($this->input->post("all_cats"));

		$hide_clientside = intval($this->input->post("hide_clientside"));

		if(empty($name)) {
			$this->template->error(lang("error_119"));
		}

		// Check ticket categories
		$cats= array();
		if($user_cats) {
			foreach($user_cats as $cat) {
				$cat = intval($cat);
				if($cat > 0) {
					$catr = $this->tickets_model->get_category($cat);
					if($catr->num_rows() == 0) {
						$this->template->error(lang("error_110"));
					}
				}
				$cats[] = $cat;
			}
		}

		$customid = $this->tickets_model->add_custom_field(array(
			"name" => $name,
			"type" => $type,
			"required" => $required,
			"options" => $options,
			"help_text" => $help_text,
			"all_cats" => $all_cats,
			"hide_clientside" => $hide_clientside
			)
		);


		// Add custom field to cats
		foreach($cats as $catid) {
			$this->tickets_model->add_field_cats(array(
				"fieldid" => $customid,
				"catid" => $catid
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_59"));
		redirect(site_url("tickets/custom_fields"));


	}

	public function categories() 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->template->loadExternal(
			'<link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);
		$this->template->loadData("activeLink", 
			array("ticket" => array("cats" => 1)));

		$categories = $this->tickets_model->get_categories();
		$user_groups = $this->user_model->get_all_user_groups();
		
		$this->template->loadContent("tickets/categories.php", array(
			"categories" => $categories,
			"user_groups" => $user_groups
			)
		);
	}

	public function cat_page() 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->load->library("datatables");

		$this->datatables->set_default_order("ticket_categories.name", "asc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 1 => array(
				 	"ticket_categories.name" => 0
				 ),
				 2 => array(
				 	"t_c.name" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->tickets_model
				->get_categories_total()
		);
		$cats = $this->tickets_model->get_categories_dt($this->datatables);
		

		foreach($cats->result() as $r) {

			if(isset($r->name2)) {
				$cat_parent = $r->name2;
			} else {
				$cat_parent = lang("ctn_46");
			}
			
			$this->datatables->data[] = array(
				'<img src="'.base_url().$this->settings->info->upload_path_relative.'/'.$r->image.'" class="cat-icon">',
				$r->name,
				$cat_parent,
				'<a href="'.site_url("tickets/edit_cat/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tickets/delete_cat/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function add_category_pro() 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$cat_parent = intval($this->input->post("cat_parent"));
		$no_tickets = intval($this->input->post("no_tickets"));

		$user_groups = $this->input->post("user_groups");
		$auto_assign = $this->common->nohtml($this->input->post("auto_assign"));

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

		$auto_assign_id=0;
		if(!empty($auto_assign)) {
			$user = $this->user_model->get_user_by_username($auto_assign);
			if($user->num_rows() == 0) {
				$this->template->error("Invalid Username for Auto Assign!");
			}
			$user = $user->row();
			$auto_assign_id = $user->ID;
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

		if($cat_parent > 0) {
			$cat = $this->tickets_model->get_category($cat_parent);
			if($cat->num_rows() == 0) {
				$this->template->error(lang("error_121"));
			}
		}



		$catid = $this->tickets_model->add_category(array(
			"name" => $name,
			"description" => $desc,
			"cat_parent" => $cat_parent,
			"image" => $image,
			"no_tickets" => $no_tickets,
			"auto_assign" => $auto_assign_id
			)
		);

		// Add custom field to cats
		foreach($groups as $groupid) {
			$this->tickets_model->add_category_group(array(
				"groupid" => $groupid,
				"catid" => $catid
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_53"));
		redirect(site_url("tickets/categories"));
	}

	public function delete_cat($id, $hash) 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$category = $this->tickets_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$this->tickets_model->delete_category($id);
		$this->session->set_flashdata("globalmsg", lang("success_60"));
		redirect(site_url("tickets/categories"));
	}

	public function edit_cat($id) 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$this->template->loadExternal(
			'<link href="'.base_url().'scripts/libraries/chosen/chosen.min.css" rel="stylesheet" type="text/css">
			<script type="text/javascript" src="'.base_url().
			'scripts/libraries/chosen/chosen.jquery.min.js"></script>'
		);
		$id = intval($id);
		$category = $this->tickets_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$category = $category->row();

		$this->template->loadData("activeLink", 
			array("ticket" => array("cats" => 1)));

		$categories = $this->tickets_model->get_categories();

		$user_groups = $this->tickets_model->get_cat_groups($id);

		$assigned_user = "";
		if($category->auto_assign > 0) {
			$user = $this->user_model->get_user_by_id($category->auto_assign);
			if($user->num_rows() > 0) {
				$user = $user->row();
				$assigned_user = $user->username;
			}
		}
		
		$this->template->loadContent("tickets/edit_cat.php", array(
			"categories" => $categories,
			"category" => $category,
			"user_groups" => $user_groups,
			"assigned_user" => $assigned_user
			)
		);
	}

	public function edit_category_pro($id) 
	{
		if(!$this->common->has_permissions(array(
			"admin", "ticket_manager"), $this->user)) {
			$this->template->error(lang("error_85"));
		}
		$id = intval($id);
		$category = $this->tickets_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}

		$category = $category->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$cat_parent = intval($this->input->post("cat_parent"));
		$no_tickets = intval($this->input->post("no_tickets"));

		$user_groups = $this->input->post("user_groups");
		$auto_assign = $this->common->nohtml($this->input->post("auto_assign"));

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

		$auto_assign_id=0;
		if(!empty($auto_assign)) {
			$user = $this->user_model->get_user_by_username($auto_assign);
			if($user->num_rows() == 0) {
				$this->template->error("Invalid Username for Auto Assign!");
			}
			$user = $user->row();
			$auto_assign_id = $user->ID;
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

		if($cat_parent > 0) {
			$cat = $this->tickets_model->get_category($cat_parent);
			if($cat->num_rows() == 0) {
				$this->template->error(lang("error_121"));
			}
			if($cat_parent == $category->ID) {
				$this->template->error(lang("error_122"));
			}
		}

		$this->tickets_model->update_category($id, array(
			"name" => $name,
			"description" => $desc,
			"image" => $image,
			"cat_parent" => $cat_parent,
			"no_tickets" => $no_tickets,
			"auto_assign" => $auto_assign_id
			)
		);

		$this->tickets_model->delete_category_groups($id);

		// Add custom field to cats
		foreach($groups as $groupid) {
			$this->tickets_model->add_category_group(array(
				"groupid" => $groupid,
				"catid" => $id
				)
			);
		}


		$this->session->set_flashdata("globalmsg", lang("success_55"));
		redirect(site_url("tickets/categories"));
	}

	public function canned_responses() 
	{
		$this->template->loadData("activeLink", 
			array("ticket" => array("canned" => 1)));
		$this->template->loadContent("tickets/canned_responses.php", array(
			)
		);
	}

	public function canned_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("canned_responses.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"canned_responses.title" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->tickets_model
				->get_canned_total()
		);
		$canned = $this->tickets_model->get_canned_responses($this->datatables);
		

		foreach($canned->result() as $r) {
			
			$this->datatables->data[] = array(
				$r->title,
				'<a href="'.site_url("tickets/edit_canned_response/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tickets/delete_canned_response/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function add_canned_response() 
	{
		$this->template->loadData("activeLink", 
			array("ticket" => array("canned" => 1)));

		$this->template->loadContent("tickets/add_canned_responses.php", array(
			)
		);
	}

	public function add_canned_response_pro() 
	{
		$title = $this->common->nohtml($this->input->post("title"));
		$body = $this->lib_filter->go($this->input->post("body"));

		if(empty($title)) {
			$this->template->error(lang("error_81"));
		}

		$this->tickets_model->add_canned_response(array(
			"title" => $title,
			"body" => $body
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_61"));
		redirect(site_url("tickets/canned_responses"));

	}

	public function edit_canned_response($id) 
	{
		$this->template->loadData("activeLink", 
			array("ticket" => array("canned" => 1)));
		
		$id = intval($id);
		$canned = $this->tickets_model->get_canned_response($id);
		if($canned->num_rows() == 0) {
			$this->template->error(lang("error_123"));
		}
		$canned = $canned->row();
		$this->template->loadContent("tickets/edit_canned_response.php", array(
			"canned" => $canned
			)
		);
	}

	public function edit_canned_response_pro($id) 
	{
		$id = intval($id);
		$canned = $this->tickets_model->get_canned_response($id);
		if($canned->num_rows() == 0) {
			$this->template->error(lang("error_123"));
		}
		$canned = $canned->row();

		$title = $this->common->nohtml($this->input->post("title"));
		$body = $this->lib_filter->go($this->input->post("body"));

		if(empty($title)) {
			$this->template->error(lang("error_81"));
		}

		$this->tickets_model->update_canned_response($id, array(
			"title" => $title,
			"body" => $body
			)
		);
		$this->session->set_flashdata("globalmsg", lang("success_62"));
		redirect(site_url("tickets/canned_responses"));
	}

	public function delete_canned_response($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$canned = $this->tickets_model->get_canned_response($id);
		if($canned->num_rows() == 0) {
			$this->template->error(lang("error_123"));
		}

		$this->tickets_model->delete_canned_response($id);
		$this->session->set_flashdata("globalmsg", lang("success_63"));
		redirect(site_url("tickets/canned_responses"));
	}

	public function ticket_history($ticketid) 
	{
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$this->template->loadContent("tickets/ticket_history.php", array(
			"ticket" => $ticket
			)
		);
	}

	public function ticket_history_page($ticketid) 
	{
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$this->load->library("datatables");

		$this->datatables->set_default_order("ticket_history.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"users.username" => 0
				 ),
				 1 => array(
				 	"ticket_history.message" => 0
				 ),
				 2 => array(
				 	"ticket_history.timestamp" => 0
				 ),
			)
		);

		$this->datatables->set_total_rows(
			$this->tickets_model
				->get_ticket_history_count($ticketid)
		);
		$history = $this->tickets_model->get_ticket_history($ticketid, $this->datatables);
		

		foreach($history->result() as $r) {
			
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->message,
				date($this->settings->info->date_format, $r->timestamp)
			);
		}

		echo json_encode($this->datatables->process());		
	}

	public function active_view($id, $page) 
	{
		$id = intval($id);
		if($id > 0) {
			$view = $this->tickets_model->get_custom_view($id, $this->user->info->ID);
			if($view->num_rows() == 0) {
				$this->template->error(lang("error_124"));
			}
		}

		$this->user_model->update_user($this->user->info->ID, array(
			"custom_view" => $id
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_65"));
		redirect(site_url("tickets/" . $page));
	}

	public function custom_views() 
	{
		$this->template->loadData("activeLink", 
			array("ticket" => array("custom_view" => 1)));
		$categories = $this->tickets_model->get_categories();
		$statuses = $this->tickets_model->get_custom_statuses();
		$this->template->loadContent("tickets/custom_views.php", array(
			"categories" => $categories,
			"statuses" => $statuses
			)
		);
	}

	public function custom_view_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("custom_views.ID", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"custom_views.name" => 0
				 ),
				 1 => array(
				 	"custom_views.status" => 0
				 ),
				 2 => array(
				 	"ticket_categories.name" => 0
				 )
			)
		);
	
		$this->datatables->set_total_rows(
			$this->tickets_model->get_custom_views_total($this->user->info->ID)
		);
		$views = $this->tickets_model->get_custom_views_dt($this->user->info->ID, $this->datatables);
		

		foreach($views->result() as $r) {
			
			if($r->status == -1) {
				$status = lang("ctn_600");
			} elseif(isset($r->status_name)) {
				$status = $r->status_name;
			} else {
				$status = lang("ctn_46");
			}

			if($r->categoryid == 0) {
				$category = lang("ctn_600");
			} else {
				$category = $r->cat_name;
			}
			
			$this->datatables->data[] = array(
				$r->name,
				$status,
				$category,
				'<a href="'.site_url("tickets/edit_custom_view/" . $r->ID).'" class="btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("tickets/delete_custom_view/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_317").'\')" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_57").'"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function edit_custom_view($id) 
	{
		$id = intval($id);
		$view = $this->tickets_model->get_custom_view($id, $this->user->info->ID);
		if($view->num_rows() == 0) {
			$this->template->error(lang("error_125"));
		}

		$this->template->loadData("activeLink", 
			array("ticket" => array("custom_view" => 1)));
		$categories = $this->tickets_model->get_categories();

		$statuses = $this->tickets_model->get_custom_statuses();
		$this->template->loadContent("tickets/edit_custom_view.php", array(
			"view" => $view->row(),
			"categories" => $categories,
			"statuses" => $statuses
			)
		);
	}

	public function edit_custom_view_pro($id) 
	{
		$id = intval($id);
		$view = $this->tickets_model->get_custom_view($id, $this->user->info->ID);
		if($view->num_rows() == 0) {
			$this->template->error(lang("error_125"));
		}

		$name = $this->common->nohtml($this->input->post("name"));
		$status = intval($this->input->post("status"));
		$categoryid = intval($this->input->post("categoryid"));
		$order_by = intval($this->input->post("order_by"));
		$order_by_type = $this->common->nohtml($this->input->post("order_by_type"));

		if($order_by_type != "asc" && $order_by_type != "desc") {
			$this->template->error(lang("error_126"));
		}

		if($status > 0) {
			$status_n = $this->tickets_model->get_custom_status($status);
			if($status_n->num_rows() == 0) {
				$this->template->error(lang("error_113"));
			}
		}

		$this->tickets_model->update_custom_view($id, array(
			"name" => $name,
			"status" => $status,
			"categoryid" => $categoryid,
			"order_by" => $order_by,
			"order_by_type" => $order_by_type
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_66"));
		redirect(site_url("tickets/custom_views"));


	}

	public function add_custom_view() 
	{
		$name = $this->common->nohtml($this->input->post("name"));
		$status = intval($this->input->post("status"));
		$categoryid = intval($this->input->post("categoryid"));
		$order_by = intval($this->input->post("order_by"));
		$order_by_type = $this->common->nohtml($this->input->post("order_by_type"));

		if($order_by_type != "asc" && $order_by_type != "desc") {
			$this->template->error(lang("error_126"));
		}

		if($status > 0) {
			$status_n = $this->tickets_model->get_custom_status($status);
			if($status_n->num_rows() == 0) {
				$this->template->error(lang("error_113"));
			}
		}

		$this->tickets_model->add_custom_view(array(
			"userid" => $this->user->info->ID,
			"name" => $name,
			"status" => $status,
			"categoryid" => $categoryid,
			"order_by" => $order_by,
			"order_by_type" => $order_by_type
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_67"));
		redirect(site_url("tickets/custom_views"));
	}

	public function delete_custom_view($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error("Invalid Hash!");
		}
		$id = intval($id);
		$view = $this->tickets_model->get_custom_view($id, $this->user->info->ID);
		if($view->num_rows() == 0) {
			$this->template->error(lang("error_125"));
		}

		$this->tickets_model->delete_custom_view($id);
		$this->session->set_flashdata("globalmsg", lang("success_68"));
		redirect(site_url("tickets/custom_views"));
	}

	public function get_usernames() 
	{
		$query = $this->common->nohtml($this->input->get("query"));

		if(!empty($query)) {
			$usernames = $this->user_model->get_usernames_user_role($query);
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

	public function get_tickets_id() 
	{
		$query = intval($this->input->get("query"));

		if(!empty($query)) {
			$tickets = $this->tickets_model->get_tickets_id($query);
			if($tickets->num_rows() == 0) {
				echo json_encode(array());
			} else {
				$array = array();
				foreach($tickets->result() as $r) {
					$item = new STDclass;
					$item->value = $r->ID;
					$item->label = "#" . $r->ID . " - " . $r->title;
					$array[] = $item;;
				}
				echo json_encode($array);
				exit();
			}
		} else {
			echo json_encode(array());
			exit();
		}
	}

	public function edit_ticket_note_pro($ticketid) 
	{
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();
		$note = $this->lib_filter->go($this->input->post("note"));

		$this->tickets_model->update_ticket($ticketid, array(
			"notes" => $note
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_77"));
		redirect(site_url("tickets/view/" . $ticketid));
	}

	public function custom_statuses() 
	{
		$this->template->loadData("activeLink", 
			array("ticket" => array("custom_statuses" => 1)));

		$statuses = $this->tickets_model->get_custom_statuses();

		$this->template->loadContent("tickets/custom_statuses.php", array(
			"statuses" => $statuses
			)
		);
	}

	public function add_custom_status() 
	{
		$name = $this->common->nohtml($this->input->post("name"));
		$color = $this->common->nohtml($this->input->post("color"));
		$text_color = $this->common->nohtml($this->input->post("text_color"));
		$close = intval($this->input->post("close"));

		if(empty($name)) {
			$this->template->error(lang("error_133"));
		}

		$this->tickets_model->add_custom_status(array(
			"name" => $name,
			"color" => $color,
			"close" => $close,
			"text_color" => $text_color
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_78"));
		redirect(site_url("tickets/custom_statuses"));
	}

	public function edit_custom_status($id) 
	{
		$id = intval($id);
		$status = $this->tickets_model->get_custom_status($id);
		if($status->num_rows() == 0) {
			$this->template->error(lang("error_134"));
		}
		$status = $status->row();

		$this->template->loadData("activeLink", 
			array("ticket" => array("custom_statuses" => 1)));

		$this->template->loadContent("tickets/edit_custom_status.php", array(
			"status" => $status
			)
		);
	}

	public function edit_custom_status_pro($id) 
	{
		$id = intval($id);
		$status = $this->tickets_model->get_custom_status($id);
		if($status->num_rows() == 0) {
			$this->template->error(lang("error_134"));
		}
		$status = $status->row();

		$name = $this->common->nohtml($this->input->post("name"));
		$color = $this->common->nohtml($this->input->post("color"));
		$text_color = $this->common->nohtml($this->input->post("text_color"));
		$close = intval($this->input->post("close"));

		if(empty($name)) {
			$this->template->error(lang("error_133"));
		}

		$this->tickets_model->update_custom_status($id, array(
			"name" => $name,
			"color" => $color,
			"close" => $close,
			"text_color" => $text_color
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_79"));
		redirect(site_url("tickets/custom_statuses"));
	}

	public function delete_custom_status($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$status = $this->tickets_model->get_custom_status($id);
		if($status->num_rows() == 0) {
			$this->template->error(lang("error_134"));
		}
		$status = $status->row();

		$this->tickets_model->delete_custom_status($id);
		$this->session->set_flashdata("globalmsg", lang("success_80"));
		redirect(site_url("tickets/custom_statuses"));
	}

}

?>