<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller 
{
	var $debug;

	var $o_protocol_path = "";
	var $o_protocol_email = "";
	var $o_protocol_pass = "";
	var $o_protocol = 1;
	var $o_protocol_ssl = 1;
	var $o_disable_cert = false;

	var $connect_option = 0;

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("admin_model");
		$this->load->model("user_model");
	}

	private function connect() 
	{
		if($this->connect_option) {
			include(APPPATH . "/libraries/IMap.php");

			$imapPath = $this->o_protocol_path;
			$username = $this->o_protocol_email;
			$password = $this->o_protocol_pass;

			if($this->o_protocol ==1) {
				$protocol = "imap";
			} elseif($this->o_protocol == 2) {
				$protocol = "pop3";
			}
			if($this->o_protocol_ssl) {
				$ssl = "/ssl";
			} else {
				$ssl = "";
			}

			if($this->o_disable_cert) {
				$cert = "/novalidate-cert";
			} else {
				$cert = "";
			}

			$host = $this->o_protocol_path . "/" . $protocol . $ssl . $cert;

			$imap = new IMap("{" .$host. "}INBOX", $username, $password);
			return $imap;
		} else {
			include(APPPATH . "/libraries/IMap.php");

			$imapPath = $this->settings->info->protocol_path;
			$username = $this->settings->info->protocol_email;
			$password = $this->settings->info->protocol_password;

			if($this->settings->info->protocol ==1) {
				$protocol = "imap";
			} elseif($this->settings->info->protocol == 2) {
				$protocol = "pop3";
			}
			if($this->settings->info->protocol_ssl) {
				$ssl = "/ssl";
			} else {
				$ssl = "";
			}

			if($this->settings->info->disable_cert) {
				$cert = "/novalidate-cert";
			} else {
				$cert = "";
			}

			$host = $this->settings->info->protocol_path . "/" . $protocol . $ssl . $cert;

			$imap = new IMap("{" .$host. "}INBOX", $username, $password);
			return $imap;
		}
	}

	public function ticket_create() 
	{
		$enable_debug = 0;
		$debug = "";
		$this->load->model("tickets_model");

		$imap = $this->connect();
		
		$emails = $imap->search(array(
				"unseen" => 1
				)
			);

		if($emails) {
			$debug .="Count: " . count($emails);
			foreach($emails as $mail) {
				$header = $imap->get_header_info($mail);
				$message = $imap->getmsg($mail);
				if(isset($message['htmlmsg']) && !empty($message['htmlmsg'])) {
					$body = $message['htmlmsg'];
				} elseif(isset($message['plainmsg']) && !empty($message['plainmsg'])) {
					$body = $message['plainmsg'];
				} else {
					$body = "";
				}

				$header->subject = mb_decode_mimeheader($header->subject);

				if($message['charset'] != "UTF-8") {
					$body = mb_convert_encoding($body, 'utf-8', $message['charset']);
				}

				if(strpos($header->subject, $this->settings->info->ticket_title . " [ID:") === false) {

					// Check title doesn't match.
					// No match = no reply.

					// Now we need to extract ticket id.
					$pos = strpos($body, $this->settings->info->imap_ticket_string);
					if($pos === false) {
						// New ticket creation
						$debug .="Found new email. Creating ticket ...";

						$body = $imap->extract_gmail_message($body);
						$body = $imap->extract_outlook_message($body);

						$body = $this->strip_html_tags($body);
						$body = strip_tags($body, "<br><p>");

						// Ticket variables
						$title = $header->subject;
						$body = $body;
						$clientid = 0;
						$assignedid = 0;
						$categoryid = $this->settings->info->default_category;
						$status = $this->settings->info->default_status;
						$priority = 0;
						$notes = lang("ctn_660");
						$guest_email = $header->from;

						// Get category
						if($categoryid > 0) {
							$category = $this->tickets_model->get_category($categoryid);
							if($category->num_rows() == 0) {
								$categoryid = 0;
							} else {
								$category = $category->row();
								$assignedid = $category->auto_assign;
							}
						}

						// Create ticket
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
							"status" => $status,
							"priority" => $priority,
							"last_reply_timestamp" => time(),
							"last_reply_string" => date($this->settings->info->date_format, time()),
							"notes" => $notes,
							"message_id_hash" => $message_id_hash,
							"guest_email" => $guest_email,
							"guest_password" => $guest_password,
							"ticket_date" => date("d-n-Y")
							)
						);

						// Alert users of new ticket for this category
						if($clientid == 0) {
							$msg = lang("ctn_607");
						} else {
							$msg = lang("ctn_608");
						}
						$users = $this->tickets_model->get_users_from_groups($categoryid);
						foreach($users->result() as $r) {
							$this->user_model->increment_field($r->ID, "noti_count", 1);
								$this->user_model->add_notification(array(
									"userid" => $r->ID,
									"url" => "tickets/view/" . $ticketid,
									"timestamp" => time(),
									"message" => $msg,
									"status" => 0,
									"fromid" => $clientid,
									"username" => $r->username,
									"email" => $r->email,
									"email_notification" => $r->email_notification
									)
								);
						}

						// Send reply
						// Send email
						$this->load->model("home_model");
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

						$username = $guest_email;
						$email = $guest_email;
						

						$email_template->message = $this->common->replace_keywords(array(
							"[NAME]" => $username,
							"[FIRST_NAME]" => $username,
							"[LAST_NAME]" => "",
							"[SITE_URL]" => site_url(),
							"[TICKET_BODY]" => $body,
							"[TICKET_ID]" => $ticketid,
							"[SITE_NAME]" =>  $this->settings->info->site_name,
							"[GUEST_EMAIL]" => $guest_email,
							"[GUEST_PASS]" => $guest_password,
							"[GUEST_LOGIN]" => site_url("client/tickets"),
							"[IMAP_TICKET_REPLY_STRING]" => $this->settings->info->imap_reply_string,
							"[IMAP_TICKET_ID]" => $this->settings->info->imap_ticket_string
							),
						$email_template->message);

						$headers = array(
							"Message-ID" => $message_id_hash
							);
						$this->common->send_email($this->settings->info->ticket_title . " [ID: " . $ticketid . "]: " . $title,
							 $email_template->message, $email, $headers);
						$debug .= "... Sending Email ... ";

						$this->tickets_model->add_history(array(
							"userid" => 0,
							"message" => lang("ctn_658"),
							"timestamp" => time(),
							"ticketid" => $ticketid
							)
						);
						// Mark as read
						$imap->mark_as_read($mail);
						continue;
					}
				}
			}
		}

		if($enable_debug) {
			echo "DEBUG OUTPUT: <br />";
			echo $debug;
		}

		exit();
	}

	public function ticket_replies() 
	{
		$enable_debug = 0;
		$debug = "";
		$this->load->model("tickets_model");
		$imap = $this->connect();

		$emails = $imap->search(array(
			"subject" => $this->settings->info->ticket_title . " [ID:",
			"unseen" => 1
			)
		);

		if($emails) {
			$debug .="Count: " . count($emails);
			foreach($emails as $mail) {
				$header = $imap->get_header_info($mail);
				$message = $imap->getmsg($mail);
				if(isset($message['htmlmsg']) && !empty($message['htmlmsg'])) {
					$body = $message['htmlmsg'];
				} elseif(isset($message['plainmsg']) && !empty($message['plainmsg'])) {
					$body = $message['plainmsg'];
				} else {
					$body = "";
				}

				$header->subject = mb_decode_mimeheader($header->subject);

				// Now we need to extract ticket id.
				$pos = strpos($body, $this->settings->info->imap_ticket_string);
				if($pos === false) {
					// New ticket creation
					$debug .="Unable to find ticket id.";

					// Mark as read
					$imap->mark_as_read($mail);
					continue;
				} else {
					$ticketid = $this->get_ticket_id($body);
				}

				if($message['charset'] != "UTF-8") {
					$body = mb_convert_encoding($body, 'utf-8', $message['charset']);
				}


				// Strip old text from body
				$body = strstr($body, $this->settings->info->imap_reply_string, true);

				// GMAIL SUPPORT
				$body = $imap->extract_gmail_message($body);
				$body = $imap->extract_outlook_message($body);

				$body = $this->strip_html_tags($body);
				$body = strip_tags($body, "<br><p>");

				// Look up a ticket in our system
				$ticket = $this->tickets_model->get_ticket($ticketid);
				if($ticket->num_rows() == 0) {
					$debug .="NO Ticket";
					// Mark as read
					$imap->mark_as_read($mail);
					continue;
				}
				$ticket = $ticket->row();
				if(isset($ticket->client_email) && !empty($ticket->client_email)) {
					$email = $ticket->client_email;
				} else {
					$email = $ticket->guest_email;
				}

				if(strcasecmp($email, $header->from) == 0) {
					// Match
					// Post ticket reply
					// Add
					$replyid = $this->tickets_model->add_ticket_reply(array(
						"ticketid" => $ticketid,
						"userid" => $ticket->userid,
						"body" => $body,
						"timestamp" => time(),
						)
					);

					// Update last reply
					$this->tickets_model->update_ticket($ticket->ID, array(
						"last_reply_userid" => $ticket->userid,
						"last_reply_timestamp" => time(),
						"last_reply_string" => date($this->settings->info->date_format, time())
						)
					);

					if($this->settings->info->client_status > 0) {
						// Update last reply
						$this->tickets_model->update_ticket($ticket->ID, array(
							"status" => $this->settings->info->client_status
							)
						);
					}
					$debug .="Message added";
					$imap->mark_as_read($mail);

					$this->tickets_model->add_history(array(
							"userid" => 0,
							"message" => lang("ctn_659"),
							"timestamp" => time(),
							"ticketid" => $ticket->ID
							)
						);

					// Notification
					// Alert assigned user of new reply
					if($ticket->assignedid > 0) {
						$this->user_model->increment_field($ticket->assignedid, "noti_count", 1);
						$this->user_model->add_notification(array(
							"userid" => $ticket->assignedid,
							"url" => "tickets/view/" . $ticket->ID,
							"timestamp" => time(),
							"message" => lang("ctn_612"),
							"status" => 0,
							"fromid" => $ticket->userid,
							"username" => $ticket->username,
							"email" => $ticket->email,
							"email_notification" => $ticket->email_notification
							)
						);
					}
				} else {
					$debug .="From email does not match ticket db.";
					// Mark as read
					$imap->mark_as_read($mail);
					continue;
				}
			}
		}

		if($enable_debug) {
			echo "DEBUG OUTPUT: <br />";
			echo $debug;
		}

		exit();
	}

	private function get_ticket_id($body) 
	{
		$ticket = trim(strstr($body, $this->settings->info->imap_ticket_string));
		$ticketid = substr($ticket, 
			strlen($this->settings->info->imap_ticket_string),
			strlen($ticket)
		);
		$ticketid = intval(strstr(trim($ticketid), " ", true));
		return $ticketid;
	}

	private function strip_html_tags($str){
	    $str = preg_replace('/(<|>)\1{2}/is', '', $str);
	    $str = preg_replace(
	        array(// Remove invisible content
	            '@<head[^>]*?>.*?</head>@siu',
	            '@<style[^>]*?>.*?</style>@siu',
	            '@<script[^>]*?.*?</script>@siu',
	            '@<noscript[^>]*?.*?</noscript>@siu',
	            ),
	        "", //replace above with nothing
	        $str );
	    
	    return $str;
	}

	public function auto_close() 
	{
		$this->load->model("home_model");
		$this->load->model("tickets_model");

		// Get all tickets not already closed
		$tickets = $this->tickets_model->get_tickets_not_closed();
		foreach($tickets->result() as $ticket) {
			if($ticket->last_reply_timestamp < (time() - (3600*24*7)) ) {
				// Close
				$status = $this->tickets_model->get_close_custom_status();
				if($status->num_rows() == 0) {
					$statusid = 0;
				}
				$status = $status->row();
				$statusid = $status->ID;

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

				$this->tickets_model->update_ticket($ticket->ID, array(
					"status" => $statusid,
					"close_ticket_date" => $close_ticket,
					"close_timestamp" => $close_timestamp
					)
				);
			}
		}
		exit();
	}

}