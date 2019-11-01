<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("tickets_model");
		$this->load->model("knowledge_model");
		$this->load->model("funds_model");
		$this->load->model("home_model");
		$this->load->model("FAQ_model");
		$this->load->model("documentation_model");
		$this->load->model("fcm_model");
		$this->template->set_error_view("error/client_error.php");
		$this->template->set_layout("layout/client_layout2.php");

		if($this->settings->info->require_login) {
			if(!$this->user->loggedin) {
				redirect(site_url("login"));
			}
			if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
					$this->user->info->premium_time < time()) ) {
				$this->session->set_flashdata("globalmsg", lang("success_29"));
				redirect(site_url("funds/plans"));
			}
		}
	}

	public function index() 
	{
		$fields = $this->tickets_model->get_custom_fields_all_cats();
		$categories = $this->tickets_model->get_category_no_parent();

		$articles = $this->knowledge_model->get_recent_articles(4);

		$public_tickets = $this->tickets_model->get_recent_public_tickets(5);

		$faq = $this->FAQ_model->get_recent_faq(5);

		$this->template->loadExternal(
			'<script src="https://www.google.com/recaptcha/api.js"></script>'
		);

		$cap = null;
		if($this->settings->info->captcha_ticket && !$this->settings->info->google_recaptcha) {
			$this->load->helper("captcha");
			$rand = rand(4000,100000);
			$_SESSION['sc'] = $rand;
			$vals = array(
			    'word' => $rand,
			    'img_path' => './images/captcha/',
	    		'img_url' => base_url() . 'images/captcha/',
			    'img_width' => 150,
			    'img_height' => 30,
			    'expiration' => 7200
			    );

			$cap = create_captcha($vals);
		}

		$this->template->loadContent("client/index.php", array(
			"cap" => $cap,
			"articles" => $articles,
			"fields" => $fields,
			"categories" => $categories,
			"public_tickets" => $public_tickets,
			"faq" => $faq
			)
		);
	}

	public function ajax_check_ticket() 
	{
		$formData = $this->input->post("formData");
		parse_str($formData, $data);

		foreach($data as $key=>$value) {
            if(isset($_POST[$key])) {
                $data[$key] = $_POST[$key];
            }
        }

      
		$config = $this->config->item("cookieprefix");

		$field_errors = array();

		$title = $this->common->nohtml($data["title"]);

		  if($this->settings->info->price_per_ticket > 0) {
        	if($this->user->info->points < $this->settings->info->price_per_ticket) {
        		$this->template->error(lang("error_145"));
        	}
        }


		$guest_email = "";
		if(isset($data['guest_email'])) {
			$guest_email = $this->common->nohtml($data["guest_email"]);
		}
		$priority = intval($data["priority"]);
		$catid = intval($data["catid"]);

		$sub_catid = 0;
		if(isset($data['sub_catid'])) {
			$sub_catid = intval($data["sub_catid"]);
		}

		$file_count = 0;
		if(isset($data['file_count'])) {
			$file_count = intval($data["file_count"]);
		}

		$body = $this->lib_filter->go($this->input->post("form_body"));


		if($this->settings->info->captcha_ticket) {
			if (!$this->settings->info->google_recaptcha) {
				$captcha = $this->common->nohtml($data["captcha"]);
				if ($captcha != $_SESSION['sc']) {
					$field_errors['captcha'] = lang("error_55");
				}
			}
		}

		if(empty($title)) {
			$field_errors['title'] = lang("error_81");
		}

		if(empty($body)) {
			$field_errors['body'] = lang("error_92");
		}

		if($priority < 0 || $priority > 3) {
			$field_errors['priority'] = lang("error_93");
		}

		// Get client
		$clientid = 0;
		if($this->user->loggedin) {
			$clientid = $this->user->info->ID;
			$client_username = $this->user->info->username;
			$client_email = $this->user->info->email;
			$client_first_name = $this->user->info->first_name;
			$client_last_name = $this->user->info->last_name;
		}

		if($clientid == 0) {
			if($this->settings->info->enable_ticket_guests) {
				if(empty($guest_email)) {
					$this->template->jsonError(lang("error_94"));
				}
			} else {
				$this->template->jsonError(lang("error_95"));
			}
		}

		// Check categories
		$category = $this->tickets_model->get_category($catid);
		if($category->num_rows() == 0) {
			$field_errors['catid'] =  lang("error_87");
		} else {

			$category = $category->row();

			// Check subcat
			if($sub_catid > 0) {
				$subcat = $this->tickets_model->get_category($sub_catid);
				if($subcat->num_rows() == 0) {
					$field_errors['sub_catid'] = lang("error_96");
				}
				$categoryid = $sub_catid;
			} else {
				$categoryid = $catid;
			}

			if($category->no_tickets && $sub_catid == 0) {
				$field_errors['catid'] = lang("error_97");
			}
		}

		// Custom fields
		$fields = $this->tickets_model->get_custom_fields_all_cats();
		// Process fields
		$answers = array();
		$a = $this->custom_field_check($fields, $answers, $data, true);
		$answers = $a['answers'];

		$field_errors = array_merge($field_errors, $a['field_errors']);

		// check subcat or primary cat
		if($sub_catid > 0) {
			$fields = $this->tickets_model->get_custom_fields_for_cat($sub_catid);
		} else {
			$fields = $this->tickets_model->get_custom_fields_for_cat($catid);
		}
		$a = $this->custom_field_check($fields, $answers, $data,true);
		$answers = $a['answers'];

		$field_errors = array_merge($field_errors, $a['field_errors']);

		// Display errors
		if(empty($field_errors)) {
			echo json_encode(array("success" => 1));
		} else {
			echo json_encode(array("field_errors" => 1,"fieldErrors" => $field_errors));
		}
		exit();
	}

	public function change_language() 
	{	

		$languages = $this->config->item("available_languages");
		if(!isset($_COOKIE['language'])) {
			$lang = "";
		} else {
			$lang = $_COOKIE["language"];
		}
		$this->template->loadContent("client/change_language.php", array(
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

	public function faq() 
	{
		if(!$this->settings->info->enable_faq) {
			$this->template->error(lang("error_128"));
		}
		$categories = $this->FAQ_model->get_categories();
		$this->template->loadContent("client/faq.php", array(
			"categories" => $categories
			)
		);
	}

	public function view_faq($id) 
	{
		if(!$this->settings->info->enable_faq) {
			$this->template->error(lang("error_128"));
		}
		$id = intval($id);
		$category = $this->FAQ_model->get_category($id);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_110"));
		}
		$category = $category->row();

		$categories = $this->FAQ_model->get_categories();

		$faq = $this->FAQ_model->get_faq_all($category->ID);
		$this->template->loadContent("client/view_faq.php", array(
			"categories" => $categories,
			"category" => $category,
			"faq" => $faq
			)
		);
	}

	public function funds() 
	{
		$this->template->loadData("activeLink", 
			array("funds" => array("general" => 1)));
		if(!$this->settings->info->payment_enabled) {
			$this->template->error(lang("error_60"));
		}

		if(!empty($this->settings->info->stripe_secret_key) && !empty($this->settings->info->stripe_publish_key)) {
			// Stripe
			require_once(APPPATH . 'third_party/stripe/init.php');

			$stripe = array(
			  "secret_key"      => $this->settings->info->stripe_secret_key,
			  "publishable_key" => $this->settings->info->stripe_publish_key
			);

			\Stripe\Stripe::setApiKey($stripe['secret_key']);
		} else {
			$stripe = null;
		}

		$this->template->loadContent("client/funds.php", array(
			"stripe" => $stripe
			)
		);
	}

	public function plans() 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		$this->template->loadData("activeLink", 
			array("funds" => array("plans" => 1)));
		if(!$this->settings->info->payment_enabled) {
			$this->template->error(lang("error_60"));
		}

		$plans = $this->funds_model->get_plans();
		$this->template->loadContent("client/plans.php", array(
			"plans" => $plans
			)
		);
	}

	public function buy_plan($id, $hash) 
	{
		if(!$this->user->loggedin) {
			redirect(site_url("login"));
		}
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}
		$id = intval($id);
		$plan = $this->funds_model->get_plan($id);
		if($plan->num_rows() == 0) $this->template->error(lang("error_61"));
		$plan = $plan->row();

		// Check user has dolla
		if($this->user->info->points < $plan->cost) {
			$this->template->error(lang("error_62"));
		}

		if($this->user->info->premium_time == -1) {
			$this->template->error(lang("error_63"));
		}

		if($plan->days > 0) {
			$premium_time = $this->user->info->premium_time;
			$time_added = (24*3600) * $plan->days;

			// Check to see if user currently has time.
			if($premium_time > time()) {
				// If plan does not equal current one, then we reset 
				// the timer 
				if($this->user->info->premium_planid != $plan->ID) {
					$premium_time = time() + $time_added;
				} else {
					$premium_time = $premium_time + $time_added;
				}
			} else {
				$premium_time = time() + $time_added;
			}
		} else {
			// Unlimited Time modifier
			$premium_time = -1;
		}

		$this->user->info->points = $this->user->info->points - $plan->cost;

		$this->user_model->update_user($this->user->info->ID, array(
			"premium_time" => $premium_time,
			"points" => $this->user->info->points,
			"premium_planid" => $plan->ID
			)
		);

		$this->funds_model->update_plan($id, array(
			"sales" => $plan->sales + 1
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_28"));
		redirect(site_url("client/plans"));
	}

	public function get_articles() 
	{
		$query = $this->common->nohtml($this->input->get("query"));

		if(!empty($query)) {
			$articles = $this->knowledge_model->get_articles_title($query);
			if($articles->num_rows() == 0) {
				return 0;
			} else {
				$this->template->loadAjax("client/get_articles.php", array(
					"articles" => $articles,
					), 1
				);
			}
		} else {
			return 0;
		}
	}

	public function rate_ticket($ticketid, $hash) 
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

		$userid = 0;

		// Check user logged in
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
			if($ticket->userid != $this->user->info->ID) {
				// Check admin permission
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		} else {
			if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
				// Check valid
				if($ticket->ID != $_SESSION['ticketid']) {
					$this->template->error(lang("error_84"));
				}
				if($ticket->message_id_hash != $_SESSION['ticketpass']) {
					$this->template->error(lang("error_84"));
				}
			} else {
				$this->template->error(lang("error_84"));
			}
		}

		$rating = intval($this->input->get("rating"));
		if($rating > 5) $rating =5;
		if($rating < 1) $rating =1;

		$this->tickets_model->update_ticket($ticketid, array(
			"rating" => $rating
			)
		);

		$this->tickets_model->add_history(array(
			"userid" => $userid,
			"message" => lang("ctn_655") . ": " . $rating . " " . lang("ctn_656"),
			"timestamp" => time(),
			"ticketid" => $ticketid
			)
		);

		echo 1;
		exit();
	}

	public function knowledge() 
	{
		if(!$this->settings->info->enable_knowledge) {
			$this->template->error(lang("error_128"));
		}
		$categories = $this->knowledge_model->get_categories_no_parents();
		$articles = $this->knowledge_model->get_recent_articles(4);
		$this->template->loadContent("client/knowledge.php", array(
			"categories" => $categories,
			"articles" => $articles
			)
		);
	}

	public function view_knowledge($id) 
	{
		if(!$this->settings->info->enable_knowledge) {
			$this->template->error(lang("error_128"));
		}
		$id = intval($id);
		$article = $this->knowledge_model->get_article($id);
		if($article->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$article = $article->row();

		// Check for groups
		$groups = $this->knowledge_model->get_category_groups($article->catid);
		if($groups->num_rows() > 0) {
			$groupids = array();
			foreach($groups->result() as $r) {
				$groupids[] = $r->groupid;
			}

			if($this->user->loggedin) {
				$userid = $this->user->info->ID;
			} else {
				$userid = 0;
			}


			$member = $this->knowledge_model->get_user_groups($groupids, $userid);
			if($member->num_rows() ==0) $this->template->error(lang("error_143"));
		}

		$user_vote = $this->knowledge_model->get_user_vote($id, $_SERVER['REMOTE_ADDR']);

		$this->template->loadContent("client/view_knowledge.php", array(
			"article" => $article,
			"user_vote" => $user_vote
			)
		);
	}

	public function knowledge_vote($id, $vote, $hash) 
	{
		$id = intval($id);
		$article = $this->knowledge_model->get_article($id);
		if($article->num_rows() == 0) {
			$this->template->error(lang("error_86"));
		}
		$article = $article->row();

		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$user_vote = $this->knowledge_model->get_user_vote($id, $_SERVER['REMOTE_ADDR']);
		if($user_vote->num_rows() > 0) {
			$this->template->error(lang("error_144"));
		}

		if($vote == 1) {
			$article->useful_yes = $article->useful_yes + 1;
		}
		$article->useful_total = $article->useful_total+1;

		$this->knowledge_model->add_vote(array(
			"articleid" => $id,
			"IP" => $_SERVER['REMOTE_ADDR']
			)
		);

		$this->knowledge_model->update_article($id, array(
			"useful_yes" => $article->useful_yes,
			"useful_total" => $article->useful_total
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_89"));
		redirect(site_url("client/view_knowledge/" . $id));
	}

	public function view_knowledge_cat($catid) 
	{
		if(!$this->settings->info->enable_knowledge) {
			$this->template->error(lang("error_128"));
		}
		$catid = intval($catid);
		$category = $this->knowledge_model->get_category($catid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}
		$category = $category->row();

		// Check for groups
		$groups = $this->knowledge_model->get_category_groups($catid);
		if($groups->num_rows() > 0) {
			$groupids = array();
			foreach($groups->result() as $r) {
				$groupids[] = $r->groupid;
			}

			if($this->user->loggedin) {
				$userid = $this->user->info->ID;
			} else {
				$userid = 0;
			}

			$member = $this->knowledge_model->get_user_groups($groupids, $userid);
			if($member->num_rows() ==0) $this->template->error(lang("error_143"));
		}

		// Get sub cats
		$subcats = $this->knowledge_model->get_subcats($catid);

		$this->template->loadContent("client/view_knowledge_cat.php", array(
			"category" => $category,
			"subcats" => $subcats
			)
		);
	}

	public function knowledge_search() 
	{
		if(!$this->settings->info->enable_knowledge) {
			$this->template->error(lang("error_128"));
		}
		$search = $this->common->nohtml($this->input->post("search"));

		if(empty($search)) {
			$this->template->error(lang("error_88"));
		}

		$articles = $this->knowledge_model->get_articles_search($search);
		$this->template->loadContent("client/knowledge_search.php", array(
			"search" => $search,
			"articles" => $articles
			)
		);
	}

	public function knowledge_cat_page($catid) 
	{
		if(!$this->settings->info->enable_knowledge) {
			$this->template->error(lang("error_128"));
		}
		$catid = intval($catid);
		$category = $this->knowledge_model->get_category($catid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}
		$category = $category->row();

		$this->load->library("datatables");

		$this->datatables->set_default_order("knowledge_articles.title", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"knowledge_articles.title" => 0
				 )
			)
		);
		
		$this->datatables->set_total_rows(
			$this->knowledge_model
				->get_articles_cat_total($catid)
		);
		$articles = $this->knowledge_model->get_articles_cat($catid, $this->datatables);

		foreach($articles->result() as $r) {

			$summary = explode("***", wordwrap(strip_tags($r->body), 100, "***"));
			$this->datatables->data[] = array(
				'<a href="'.site_url('client/view_knowledge/' . $r->ID).'">'.$r->title.'</a>',
				$summary[0],
				'<a href="'.site_url('client/view_knowledge/' . $r->ID).'" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_459").'">'.lang("ctn_459").'</a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function tickets() 
	{
		if($this->user->loggedin) {
			// Get user tickets
			$this->template->loadContent("client/tickets.php", array(
				"page" => "index"
				)
			);
		} else {
			if(!$this->settings->info->enable_ticket_guests) {
				$this->template->error(lang("error_89"));
			}
			// Guest login
			// Get user tickets
			$this->template->loadContent("client/guest_ticket.php", array(
				)
			);
		}
	}

	public function public_tickets() 
	{
			// Get user tickets
			$this->template->loadContent("client/tickets.php", array(
				"page" => "public"
				)
			);
	}

	public function ticket_page($page = "index") 
	{
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
				 5 => array(
				 	"tickets.last_reply_timestamp" => 0
				 ),
			)
		);

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}
		
		if($page == "index") {
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_client_tickets_total($userid)
			);
			$tickets = $this->tickets_model->get_client_tickets($userid, $this->datatables);
		} elseif($page == "public") {
			$this->datatables->set_total_rows(
				$this->tickets_model
					->get_public_tickets_total()
			);
			$tickets = $this->tickets_model->get_public_tickets($this->datatables);
		}
		
		
		$prioritys = array(0 => "<span class='label label-info'>".lang("ctn_429")."</span>", 1 => "<span class='label label-primary'>".lang("ctn_430")."</span>", 2=> "<span class='label label-warning'>".lang("ctn_431")."</span>", 3 => "<span class='label label-danger'>".lang("ctn_432")."</span>");

		foreach($tickets->result() as $r) {
			
			if(isset($r->status_name)) {
				$status = $r->status_name;
			} else {
				$status = lang("ctn_46");
			}

			$options = '<a href="'.site_url('client/view_ticket/' . $r->ID).'" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_459").'">'.lang("ctn_459").'</a>';
			if($this->settings->info->enable_ticket_edit) {
				$options .= ' <a href="'.site_url("client/edit_ticket/" . $r->ID).'" class="btn btn-warning" data-toggle="tooltip" data-placement="bottom" title="'.lang("ctn_55").'"><span class="glyphicon glyphicon-cog"></span></a>';
			}

			
			$this->datatables->data[] = array(
				$r->ID,
				'<a href="'.site_url("client/view_ticket/" . $r->ID).'">'.$r->title.'</a>',
				$prioritys[$r->priority],
				$status,
				$r->cat_name,
				date($this->settings->info->date_format,$r->last_reply_timestamp),
				$options
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function guest_login_pro() 
	{
		if(!$this->settings->info->enable_ticket_guests) {
			$this->template->error(lang("error_89"));
		}

		$email = $this->common->nohtml($this->input->post("email"));
		$pass = $this->common->nohtml($this->input->post("pass"));

		if(empty($email) || empty($pass)) {
			$this->template->error(lang("error_90"));
		}

		// Get ticket
		$ticket = $this->tickets_model->get_guest_ticket($email, $pass);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_91"));
		}

		$ticket = $ticket->row();

		$_SESSION['ticketid'] = $ticket->ID;
		$_SESSION['ticketpass'] = $ticket->message_id_hash;

		$this->tickets_model->add_history(array(
			"userid" => 0,
			"message" => lang("ctn_657"),
			"timestamp" => time(),
			"ticketid" => $ticket->ID
			)
		);

		redirect(site_url("client/view_ticket/" . $ticket->ID));
	}


	public function test() {
	
		$this->sendSampleFcmNotification("gg" ,"gg");
	}

	public function sendSampleFcmNotification($title, $body) {
		$token = "fgLExWDJVbU:APA91bHSbRKMmMhCPYwm92J-OPWb5PVKdNcSETQylycTxmnPBpWGVnFvLy5UEmcQna71pPPAIrwg4j9EYDbIPa9y0GCauc1z5SgJX1YELaTQznr6cnADq5Eg9I7fB5MNtwxteYJY0fMp";
		$image="http://www.abystyle.com/9556-thickbox_default/rick-and-morty-messenger-bag-portal-vinyl-small-size.jpg";
		$icon="https://cdn4.iconfinder.com/data/icons/iconset-addictive-flavour/png/audio_notification.png";
		
		$userlistForUserGroup = $this->fcm_model->get_all_users_for_userGroup();
		$userlistForTicketCategory = $this->fcm_model->get_all_users_for_ticketCategory();

		$result = array_merge($userlistForUserGroup,$userlistForTicketCategory);

		$final  = array();
		if (is_array($result) || is_object($result)){
			foreach ($result as $current) {
				if ( ! in_array($current, $final)) {
					$final[] = $current;
				}
			}

			foreach($final as $value){
				print_r($value['token']);
				$user = $value['user'];
				$fcmToken = $value['token'];
				$this->sendNotification($fcmToken, "Hello ".$user.".".$title, $body, $image);
			}
		}
		
	}

	public function sendNotification($token,$title, $body, $image)
    {
		$url = "https://fcm.googleapis.com/fcm/send";
		$serverKey = $this->config->item('fcm_server_key');
		//$serverKey = "AAAAcKZ-E94:APA91bGvkZ2fXuc_PwXyesCFwYBeXMG79W-2NvpZe1CnRnWo0ujv1vCOLViAg_zcq1BHbCTB7_yGOpFhTEQ2z1jiWZKfCUGt3QwuCUp3BEFz5P0r0RkKTHIhb4m6difZtfLs2eCydM40";
		
    
		$notification = array
		(
		'title'		=> $title,
		'body'		=> $body,
		'vibrate'	=> 1,
		'sound'		=> 1
		//,'image' 	=> $image
		);
		
		$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
		$json = json_encode($arrayToSend);
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: key='. $serverKey;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
		//Send the request
		$response = curl_exec($ch);
		//Close request
		if ($response === FALSE) {
		die('FCM Send Error: ' . curl_error($ch));
		}
		curl_close($ch);
	}

	public function add_pro() 
	{
		
		$title = $this->common->nohtml($this->input->post("title"));
		$guest_email = $this->common->nohtml($this->input->post("guest_email"));
		$priority = intval($this->input->post("priority"));
		$catid = intval($this->input->post("catid"));
		$sub_catid = intval($this->input->post("sub_catid"));

		$file_count = intval($this->input->post("file_count"));

		$body = $this->lib_filter->go($this->input->post("body"));

		
		 if($this->settings->info->price_per_ticket > 0) {
        	if($this->user->info->points < $this->settings->info->price_per_ticket) {
        		$this->template->error(lang("error_145"));
        	}
        }


		if($this->settings->info->captcha_ticket) {
			if (!$this->settings->info->google_recaptcha) {
				$captcha = $this->common->nohtml($this->input->post("captcha"));
				if ($captcha != $_SESSION['sc']) {
					$this->template->error(lang("error_55"));
				}
			} else {
				require(APPPATH . 'third_party/autoload.php');
				$recaptcha = new \ReCaptcha\ReCaptcha(
					$this->settings->info->google_recaptcha_secret);
				$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
				if ($resp->isSuccess()) {
				    // verified!
				} else {
				    $errors = $resp->getErrorCodes();
				    $this->template->error(lang("error_55"));
				}
			}
		}

		if(empty($title)) {
			$this->template->error(lang("error_81"));
		}

		if(empty($body)) {
			$this->template->error(lang("error_92"));
		}

		if($priority < 0 || $priority > 3) {
			$this->template->error(lang("error_93"));
		}

		// Get client
		$clientid = 0;
		if($this->user->loggedin) {
			$clientid = $this->user->info->ID;
			$client_username = $this->user->info->username;
			$client_email = $this->user->info->email;
			$client_first_name = $this->user->info->first_name;
			$client_last_name = $this->user->info->last_name;
		}

		if($clientid == 0) {
			if($this->settings->info->enable_ticket_guests) {
				if(empty($guest_email)) {
					$this->template->error(lang("error_94"));
				}
			} else {
				$this->template->error(lang("error_95"));
			}
		}

		$auto_assigned = 0;

		// Check categories
		$category = $this->tickets_model->get_category($catid);
		if($category->num_rows() == 0) {
			$this->template->error(lang("error_87"));
		}

		$category = $category->row();
		$auto_assigned = $category->auto_assign;

		// Check subcat
		if($sub_catid > 0) {
			$subcat = $this->tickets_model->get_category($sub_catid);
			if($subcat->num_rows() == 0) {
				$this->template->error(lang("error_96"));
			}
			$subcat = $subcat->row();
			$categoryid = $sub_catid;
			$auto_assigned = $subcat->auto_assign;
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
		$answers = $answers['answers'];

		// check subcat or primary cat
		if($sub_catid > 0) {
			$fields = $this->tickets_model->get_custom_fields_for_cat($sub_catid);
		} else {
			$fields = $this->tickets_model->get_custom_fields_for_cat($catid);
		}
		$answers = $this->custom_field_check($fields, $answers);
		$answers = $answers['answers'];

		// Upload check
		$this->load->library("upload");

		$file_data = array();
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
			"timestamp" => time(),
			"categoryid" => $categoryid,
			"status" => $this->settings->info->default_status,
			"priority" => $priority,
			"last_reply_timestamp" => time(),
			"last_reply_string" => date($this->settings->info->date_format, time()),
			"message_id_hash" => $message_id_hash,
			"guest_email" => $guest_email,
			"guest_password" => $guest_password,
			"ticket_date" => date("d-n-Y"),
			"assignedid" => $auto_assigned
			)
		);

		// Alert users of new ticket for this category
		if($clientid == 0) {
			$msg = lang("ctn_607");
		} else {
			$msg = lang("ctn_608");
		}

		if(!empty($this->settings->info->alert_users)) {
			$users = explode(",", $this->settings->info->alert_users);
			foreach($users as $u) 
			{
				$user = $this->user_model->get_user_by_username($u);
				if($user->num_rows() == 0) {
					
				} else {
					$user = $user->row();
					$this->user_model->increment_field($user->ID, "noti_count", 1);
					$this->user_model->add_notification(array(
						"userid" => $user->ID,
						"url" => "tickets/view/" . $ticketid,
						"timestamp" => time(),
						"message" => $msg,
						"status" => 0,
						"fromid" => $clientid,
						"username" => $user->username,
						"email" => $user->email,
						"email_notification" => $user->email_notification
						)
					);
				}
			}

		}

		$extra = "";
		if($clientid == 0) {
			// Send email with guest details

			// Attach guest details to alert
			$extra = lang("ctn_606") . "<br />
			" . lang("ctn_24") . ": <b>$guest_email</b><br />
			".lang("ctn_450").": <b>$guest_password</b>";
			// Verify guest automagically
			$_SESSION['ticketid'] = $ticketid;
			$_SESSION['ticketpass'] = $message_id_hash;
		}

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
				"userid" => $clientid
				)
			);
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

		$this->tickets_model->add_history(array(
			"userid" => $clientid,
			"message" => lang("ctn_649"),
			"timestamp" => time(),
			"ticketid" => $ticketid
			)
		);

		if($clientid > 0) {
			// Send email
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
				$first_name = $guest_email;
				$last_name = "";
			} else {
				$username = $client_username;
				$email = $client_email;
				$first_name = $client_first_name;
				$last_name = $client_last_name;
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
				$first_name = $client_first_name;
				$last_name = $client_last_name;
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
		$this->sendSampleFcmNotification("you have new ticket: #".$ticketid, $title);


		$this->session->set_flashdata("globalmsg", lang("success_44") . $extra);
		redirect(site_url("client/view_ticket/" . $ticketid));
	}

	private function custom_field_check($fields, $answers, $data = null, $skip=0) 
	{
		$field_errors = array(); 
		foreach($fields->result() as $r) {
			if($r->hide_clientside) continue;
			$answer = "";
			if($r->type == 0) {
				// Look for simple text entry
				if($data == null) {
					$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));
				} else {
					$answer = $this->common->nohtml($data["cf_" . $r->ID]);
				}

				if($r->required && (empty($answer) && $answer !== '0')) {
					$field_errors['cf_' . $r->ID] = lang("error_99") . $r->name;
				}
				// Add
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);
			} elseif($r->type == 1) {
				// HTML
				if($data == null) {
					$answer = $this->lib_filter->go($this->input->post("cf_" . $r->ID));
				} else {
					$answer = $this->lib_filter->go($data["cf_" . $r->ID]);
				}

				if($r->required && (empty($answer) && $answer !== '0')) {
					$field_errors['cf_' . $r->ID] = lang("error_99") . $r->name;
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
					if($data == null) {
						$ans = $this->common->nohtml($this->input->post("cf_cb_" . $r->ID . "_" . $k));
					} else {
						$ans = $this->common->nohtml($data["cf_cb_" . $r->ID . "_" . $k]);
					}
					if($ans) {
						if(empty($answer)) {
							$answer .= $v;
						} else {
							$answer .= ", " . $v;
						}
					}
				}

				if($r->required && (empty($answer) && $answer !== '0')) {
					$field_errors['cf_' . $r->ID] = lang("error_99") . $r->name;
				}
				$answers[] = array(
					"fieldid" => $r->ID,
					"answer" => $answer
				);

			} elseif($r->type == 3) {
				// radio
				$options = explode(",", $r->options);
				if($data == null) {
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
							$field_errors['cf_' . $r->ID] = lang("error_99") . $r->name;
						}
						if($flag) {
							$answers[] = array(
								"fieldid" => $r->ID,
								"answer" => $answer
							);
						}
					} 
				} else {
					if(isset($data['cf_radio_' . $r->ID])) {
						$answer = intval($this->common->nohtml($data["cf_radio_" . $r->ID]));
						
						$flag = false;
						foreach($options as $k=>$v) {
							if($k == $answer) {
								$flag = true;
								$answer = $v;
							}
						}
						if($r->required && !$flag) {
							$field_errors['cf_' . $r->ID] = lang("error_99") . $r->name;
						}
						if($flag) {
							$answers[] = array(
								"fieldid" => $r->ID,
								"answer" => $answer
							);
						}
					} 
				}

			} elseif($r->type == 4) {
				// Dropdown menu
				$options = explode(",", $r->options);
				if($data == null) {
					$answer = intval($this->common->nohtml($this->input->post("cf_" . $r->ID)));
				} else {
					$answer = intval($this->common->nohtml($data["cf_" . $r->ID]));
				}
				$flag = false;
				foreach($options as $k=>$v) {
					if($k == $answer) {
						$flag = true;
						$answer = $v;
					}
				}
				if($r->required && !$flag) {
					$field_errors['cf_' . $r->ID] = lang("error_99") . $r->name;
				}
				if($flag) {
					$answers[] = array(
						"fieldid" => $r->ID,
						"answer" => $answer
					);
				}
			} elseif($r->type == 5) {
				// Look for simple text entry
				if($data == null) {
					$answer = $this->common->nohtml($this->input->post("cf_" . $r->ID));
				} else {
					$answer = $this->common->nohtml($data["cf_" . $r->ID]);
				}

				if($r->required && (empty($answer) && $answer !== '0')) {
					$field_errors['cf_' . $r->ID] = lang("error_99") . $r->name;
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

		if(!$skip && count($field_errors) > 0) {
			$e_t = "";
			foreach($field_errors as $e) {
				$e_t = $e . "<br />";
			}
			$this->template->error($e_t);
		}
		return array("answers" => $answers, "field_errors" => $field_errors);
	}

	public function view_ticket($ticketid) 
	{
		$ticketid = intval($ticketid);
		$ticket = $this->tickets_model->get_ticket($ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		$owner = 1;
		if(!$ticket->public) {
			// Check user logged in
			if($this->user->loggedin) {
				if($ticket->userid != $this->user->info->ID) {
					// Check admin permission
					if(!$this->common->has_permissions(array(
						"admin", "ticket_manager"), $this->user)) {
						$this->template->error(lang("error_85"));
					}
				}
			} else {
				if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
					// Check valid
					if($ticket->ID != $_SESSION['ticketid']) {
						$this->template->error(lang("error_84"));
					}
					if($ticket->message_id_hash != $_SESSION['ticketpass']) {
						$this->template->error(lang("error_84"));
					}
				} else {
					$this->template->error(lang("error_84"));
				}
			}
		} else {
			// Check they still the owner so they can reply
			// Check user logged in
			if($this->user->loggedin) {
				if($ticket->userid != $this->user->info->ID) {
					// Check admin permission
					if(!$this->common->has_permissions(array(
						"admin", "ticket_manager"), $this->user)) {
						$owner = 0;
					}
				}
			} else {
				if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
					// Check valid
					if($ticket->ID != $_SESSION['ticketid']) {
						$owner = 0;
					}
					if($ticket->message_id_hash != $_SESSION['ticketpass']) {
						$owner = 0;
					}
				} else {
					$owner = 0;
				}
			}
		}

		$this->template->loadData("activeLink", 
			array("ticket" => array("general" => 1)));

		$files = $this->tickets_model->get_ticket_files($ticketid);

		$replies = $this->tickets_model->get_ticket_replies($ticketid);


		$user_fields = null;
		if($ticket->userid > 0) {
			$user_fields = $this->user_model->get_custom_fields_answers(array(
				), $ticket->userid);
		}

		$ticket_fields = $this->tickets_model->get_custom_fields_for_ticket($ticketid);
		
		$this->template->loadContent("client/view_ticket.php", array(
			"ticket" => $ticket,
			"files" => $files,
			"replies" => $replies,
			"user_fields" => $user_fields,
			"ticket_fields" => $ticket_fields,
			"owner" => $owner
			)
		);
	}

	public function ticket_reply($id) 
	{
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user logged in
		if($this->user->loggedin) {
			if($ticket->userid != $this->user->info->ID) {
				// Check admin permission
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		} else {
			if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
				// Check valid
				if($ticket->ID != $_SESSION['ticketid']) {
					$this->template->error(lang("error_84"));
				}
				if($ticket->message_id_hash != $_SESSION['ticketpass']) {
					$this->template->error(lang("error_84"));
				}
			} else {
				$this->template->error(lang("error_84"));
			}
		}

		$body = $this->lib_filter->go($this->input->post("body"));
		if(empty($body)) {
			$this->template->error(lang("error_100"));
		}
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

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}

		// Add
		$replyid = $this->tickets_model->add_ticket_reply(array(
			"ticketid" => $id,
			"userid" => $userid,
			"body" => $body,
			"timestamp" => time(),
			"files" => $files_flag,
			"hash" => $new_message_id_hash
			)
		);

		$this->tickets_model->add_history(array(
			"userid" => $userid,
			"message" => lang("ctn_650"),
			"timestamp" => time(),
			"ticketid" => $id
			)
		);

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"replyid" => $replyid,
				"ticketid" => $ticket->ID,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $userid
				)
			);
		}

		
		// Update last reply
		$this->tickets_model->update_ticket($ticket->ID, array(
			"last_reply_userid" => $userid,
			"last_reply_timestamp" => time(),
			"last_reply_string" => date($this->settings->info->date_format, time()),
			)
		);

		// Notification
		if($ticket->userid == $userid) {
			// Client reply to check for default client reply
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
					"fromid" => $userid,
					"username" => $ticket->username,
					"email" => $ticket->email,
					"email_notification" => $ticket->email_notification
					)
				);
			}
		} elseif($userid == $ticket->assignedid) {
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
					"fromid" => $this->user->info->ID,
					"username" => $ticket->client_username,
					"email" => $ticket->client_email,
					"email_notification" => $ticket->client_email_notification
					)
				);
			}
		} else {
			if($this->settings->info->client_status > 0) {
				// Update last reply
				$this->tickets_model->update_ticket($ticket->ID, array(
					"status" => $this->settings->info->client_status
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
					"fromid" => $userid,
					"username" => $ticket->client_username,
					"email" => $ticket->client_email,
					"email_notification" => $ticket->client_email_notification
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
					"fromid" => $userid,
					"username" => $ticket->username,
					"email" => $ticket->email,
					"email_notification" => $ticket->email_notification
					)
				);
			}
		}

		if($ticket->userid != $userid) {
			// Send email
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
		redirect(site_url("client/view_ticket/" . $id));
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

		$ticket = $this->tickets_model->get_ticket($reply->ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user logged in
		if($this->user->loggedin) {
			if($ticket->userid != $this->user->info->ID) {
				// Check admin permission
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		} else {
			if($reply->userid != 0) $this->template->error(lang("error_102"));
			if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
				// Check valid
				if($ticket->ID != $_SESSION['ticketid']) {
					$this->template->error(lang("error_84"));
				}
				if($ticket->message_id_hash != $_SESSION['ticketpass']) {
					$this->template->error(lang("error_84"));
				}
			} else {
				$this->template->error(lang("error_84"));
			}
		}

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}

		if($userid > 0) {
			if($reply->userid != $userid) {
				// Check user has admin rights
				// Check 
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		}
		$this->template->loadContent("client/edit_ticket_reply.php", array(
			"reply" => $reply,
			"ticket" => $ticket
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

		$ticket = $this->tickets_model->get_ticket($reply->ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user logged in
		if($this->user->loggedin) {
			if($ticket->userid != $this->user->info->ID) {
				// Check admin permission
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		} else {
			if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
				// Check valid
				if($ticket->ID != $_SESSION['ticketid']) {
					$this->template->error(lang("error_84"));
				}
				if($ticket->message_id_hash != $_SESSION['ticketpass']) {
					$this->template->error(lang("error_84"));
				}
			} else {
				$this->template->error(lang("error_84"));
			}
		}

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}

		$body = $this->lib_filter->go($this->input->post("body"));
		if(empty($body)) {
			$this->template->error(lang("error_103"));
		}

		$this->load->library("upload");

		$file_count = intval($this->input->post("file_count"));
		$file_data = array();
		$files_flag = $reply->files;
		if(!$this->settings->info->enable_ticket_uploads) {
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

		$this->tickets_model->add_history(array(
			"userid" => $userid,
			"message" => lang("ctn_651") ."<br />" . 
			$reply->body . "<br />".lang("ctn_652").":<br />" . $body,
			"timestamp" => time(),
			"ticketid" => $reply->ticketid
			)
		);

		// Add Attached files
		foreach($file_data as $file) {
			$this->tickets_model->add_attached_files(array(
				"replyid" => $id,
				"upload_file_name" => $file['upload_file_name'],
				"file_type" => $file['file_type'],
				"extension" => $file['extension'],
				"file_size" => $file['file_size'],
				"timestamp" => $file['timestamp'],
				"userid" => $userid
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_46"));
		redirect(site_url("client/view_ticket/" . $reply->ticketid));
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

		$ticket = $this->tickets_model->get_ticket($reply->ticketid);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user logged in
		if($this->user->loggedin) {
			if($ticket->userid != $this->user->info->ID) {
				// Check admin permission
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		} else {
			if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
				// Check valid
				if($ticket->ID != $_SESSION['ticketid']) {
					$this->template->error(lang("error_84"));
				}
				if($ticket->message_id_hash != $_SESSION['ticketpass']) {
					$this->template->error(lang("error_84"));
				}
			} else {
				$this->template->error(lang("error_84"));
			}
		}

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}

		$this->tickets_model->add_history(array(
			"userid" => $userid,
			"message" => lang("ctn_653") .":<br />" . 
			$reply->body,
			"timestamp" => time(),
			"ticketid" => $ticket->ID
			)
		);

		$this->tickets_model->delete_ticket_reply($id);
		$this->session->set_flashdata("globalmsg", lang("success_47"));
		redirect(site_url("client/view_ticket/" . $ticket->ID));
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

	public function get_category_description($catid) 
	{
		$category = $this->tickets_model->get_category($catid);
		if($category->num_rows() == 0) {
			return 0;
		}
		$category = $category->row();

		echo $category->description;
		exit();
	}

	public function edit_ticket($id) 
	{
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		if(!$this->settings->info->enable_ticket_edit) {
			$this->template->error(lang("error_106"));
		}

		// Check user logged in
		if($this->user->loggedin) {
			if($ticket->userid != $this->user->info->ID) {
				// Check admin permission
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		} else {
			if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
				// Check valid
				if($ticket->ID != $_SESSION['ticketid']) {
					$this->template->error(lang("error_84"));
				}
				if($ticket->message_id_hash != $_SESSION['ticketpass']) {
					$this->template->error(lang("error_84"));
				}
			} else {
				$this->template->error(lang("error_84"));
			}
		}

		$this->template->loadData("activeLink", 
			array("ticket" => array("general" => 1)));

		// Get sub-categories
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
		
		$this->template->loadContent("client/edit_ticket.php", array(
			"ticket" => $ticket,
			"categories" => $categories,
			"fields" => $fields,
			"sub_cats" => $sub_cats,
			"sub_cat_fields" => $sub_cat_fields,
			"ticket_files" => $files
			)
		);

	}

	public function edit_ticket_pro($id) 
	{
		if(!$this->settings->info->enable_ticket_edit) {
			$this->template->error(lang("error_106"));
		}
		$id = intval($id);
		$ticket = $this->tickets_model->get_ticket($id);
		if($ticket->num_rows() == 0) {
			$this->template->error(lang("error_84"));
		}
		$ticket = $ticket->row();

		// Check user logged in
		if($this->user->loggedin) {
			if($ticket->userid != $this->user->info->ID) {
				// Check admin permission
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		} else {
			if(isset($_SESSION['ticketid']) && isset($_SESSION['ticketpass'])) {
				// Check valid
				if($ticket->ID != $_SESSION['ticketid']) {
					$this->template->error(lang("error_84"));
				}
				if($ticket->message_id_hash != $_SESSION['ticketpass']) {
					$this->template->error(lang("error_84"));
				}
			} else {
				$this->template->error(lang("error_84"));
			}
		}

		$title = $this->common->nohtml($this->input->post("title"));
		$priority = intval($this->input->post("priority"));
		$catid = intval($this->input->post("catid"));
		$sub_catid = intval($this->input->post("sub_catid"));

		$file_count = intval($this->input->post("file_count"));

		$body = $this->lib_filter->go($this->input->post("body"));

		if(empty($title)) {
			$this->template->error(lang("error_81"));
		}

		if(empty($body)) {
			$this->template->error(lang("error_92"));
		}

		if($priority < 0 || $priority > 3) {
			$this->template->error(lang("error_93"));
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

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}

		// Custom fields
		$fields = $this->tickets_model->get_custom_fields_all_cats();
		// Process fields
		$answers = array();
		$answers = $this->custom_field_check($fields, $answers);
		$answers = $answers['answers'];

		// check subcat or primary cat
		if($sub_catid > 0) {
			$fields = $this->tickets_model->get_custom_fields_for_cat($sub_catid);
		} else {
			$fields = $this->tickets_model->get_custom_fields_for_cat($catid);
		}
		$answers = $this->custom_field_check($fields, $answers);
		$answers = $answers['answers'];

		// Upload check
		$this->load->library("upload");

		$file_data = array();
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
			"categoryid" => $categoryid,
			"priority" => $priority,
			)
		);

		$this->tickets_model->add_history(array(
			"userid" => $userid,
			"message" => lang("ctn_654"),
			"timestamp" => time(),
			"ticketid" => $id
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
				"userid" => $userid
				)
			);
		}

		$this->session->set_flashdata("globalmsg", lang("success_48"));
		redirect(site_url("client/view_ticket/" . $id));
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

		$userid = 0;
		if($this->user->loggedin) {
			$userid = $this->user->info->ID;
		}

		if($userid > 0) {
			if($file->userid != $userid) {
				// Check user has admin rights
				// Check 
				if(!$this->common->has_permissions(array(
					"admin", "ticket_manager"), $this->user)) {
					$this->template->error(lang("error_85"));
				}
			}
		}

		$this->tickets_model->delete_ticket_file($id);
		$this->session->set_flashdata("globalmsg", lang("success_49"));
		redirect(site_url("client/view_ticket/" . $file->ticketid));
	}

	public function view_announcement($id) 
	{
		$id = intval($id);
		$announcement = $this->user_model->get_announcement($id);
		if($announcement->num_rows() == 0) {
			$this->template->error(lang("error_82"));
		}

		$announcement = $announcement->row();

		// Set user cookie
		$config = $this->config->item("cookieprefix");
		$cookie = $this->input->cookie($config . "announcement_" . $id, TRUE);

		if(!$cookie) {
			$ttl = 3600 * 72 * 30;
			setcookie($config . "announcement_" . $id, "1", time()+$ttl, "/");
		}

		$this->template->loadContent("client/view_announcement.php", array(
			"announcement" => $announcement
			)
		);
	}

	public function notifications() 
	{
		$this->template->loadContent("client/notifications.php", array(
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
		redirect(site_url("client/notifications"));
	}

	public function payment_log() 
	{
		$this->template->loadContent("client/payment_log.php", array(
			)
		);
	}

	public function payment_logs_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("users.joined", "desc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 2 => array(
				 	"payment_logs.amount" => 0
				 ),
				 3 => array(
				 	"payment_logs.timestamp" => 0
				 ),
				 4 => array(
				 	"payment_logs.processor" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->user_model
				->get_total_payment_logs_count($this->user->info->ID)
		);
		$logs = $this->user_model->get_payment_logs($this->user->info->ID, $this->datatables);

		foreach($logs->result() as $r) {
			$this->datatables->data[] = array(
				$this->common->get_user_display(array("username" => $r->username, "avatar" => $r->avatar, "online_timestamp" => $r->online_timestamp)),
				$r->email,
				number_format($r->amount, 2),
				date($this->settings->info->date_format, $r->timestamp),
				$r->processor
			);
		}
		echo json_encode($this->datatables->process());
	}

	public function documentation() 
	{
		$projects = $this->documentation_model->get_all_projects();

		$this->template->loadContent("client/documentation.php", array(
			"projects" => $projects
			)
		);
	}

	public function view_docs($projectid) 
	{
		$projectid = intval($projectid);
		$project = $this->documentation_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_135"));
		}
		$project = $project->row();

		$documents = $this->documentation_model->get_documents_no_limit_links($projectid);


		$sidebar = $this->load->view("client/doc_sidebar.php",array("project" => $project,
			"documents" => $documents),true);

		$this->template->loadContent("client/view_docs.php", array(
			"project" => $project,
			"sidebar" => $sidebar
			)
		);
	}

	public function document($id) 
	{
		$id = intval($id);
		$document = $this->documentation_model->get_document($id);
		if($document->num_rows() == 0) {
			$this->template->error(lang("error_136"));
		}
		$document = $document->row();

		$project = $this->documentation_model->get_project($document->projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_135"));
		}
		$project = $project->row();


		if($document->link_documentid > 0) {
			$files = $this->documentation_model->get_files($document->link_documentid);
		} else {
			$files = $this->documentation_model->get_files($id);
		}


		$documents = $this->documentation_model->get_documents_no_limit_links($document->projectid);

		$sidebar = $this->load->view("client/doc_sidebar.php",array("project" => $project,
			"documents" => $documents),true);

		$this->template->loadContent("client/document.php", array(
			"project" => $project,
			"document" => $document,
			"files" => $files,
			"sidebar" => $sidebar
			)
		);
	}

	public function download_view($id) 
	{
		$projectid = intval($id);
		$project = $this->documentation_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_135"));
		}
		$project = $project->row();

		$this->template->loadData("hideme", 1);

		$documents = $this->documentation_model->get_documents_no_limit_links($projectid);

		$sidebar = $this->load->view("client/doc_sidebar_download.php",array("project" => $project,
			"documents" => $documents),true);

		$this->template->loadContent("client/download_view.php", array(
			"project" => $project,
			"documents" => $documents,
			"sidebar" => $sidebar
			)
		);
	}

}

?>