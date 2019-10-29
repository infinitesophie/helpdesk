<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Settings 
{

	var $info=array();

	var $version = "2.7";

	public function __construct() 
	{
		$CI =& get_instance();
		$site = $CI->db->select("site_name,site_desc,site_email,
			upload_path_relative, upload_path, site_logo, register,
			 disable_captcha, date_format, avatar_upload, file_types,
			 twitter_consumer_key, twitter_consumer_secret, disable_social_login
			 , facebook_app_id, facebook_app_secret, google_client_id,
			 google_client_secret, file_size, paypal_email, paypal_currency,
			 payment_enabled, payment_symbol, global_premium, install,
			 login_protect, activate_account, default_user_role,
			 secure_login, stripe_secret_key, stripe_publish_key,
			 enable_ticket_guests, enable_ticket_uploads, 
			 enable_ticket_edit, require_login, protocol, price_per_ticket,
			 protocol_path, protocol_email, protocol_password, protocol_ssl,
			 ticket_title, ticket_rating, notes, google_recaptcha, 
			 google_recaptcha_secret, google_recaptcha_key, logo_option,
			 avatar_height, avatar_width, default_category, checkout2_accountno,
			 checkout2_secret, layout, imap_ticket_string, imap_reply_string,
			 captcha_ticket, envato_personal_token, cache_time, ticket_note_close,
			 close_ticket_reply, disable_cert, staff_status, client_status,
			 public_tickets, enable_knowledge, enable_faq, logo_height, logo_width,
			 default_status, enable_documentation, resize_avatar, alert_users")
		->where("ID", 1)
		->get("site_settings");
		
		if($site->num_rows() == 0) {
			$CI->template->error(
				"You are missing the site settings database row."
			);
		} else {
			$this->info = $site->row();
		}
	}

}

?>