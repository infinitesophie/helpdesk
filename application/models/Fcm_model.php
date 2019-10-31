<?php
include (APPPATH . "/config/ChromePhp.php");
class FCM_Model extends CI_Model 
{
	
	public function add_user_to_fcm($user, $token) 
	{
		$this->db->insert("fcm_user", array(
			"user" => $user, 
			"token" => $token
			)
		);
	}
	public function get_user_from_fcm($user) 
	{
		$s = $this->db
			->where("user", $user)
			->select("COUNT(*) as num")->get("fcm_user");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}
	public function update_user_to_fcm($user, $token) 
	{
		$this->db->where("user", $user)
			
			->update("fcm_user", array("token" => $token));
			
		#  $this->db->where("user", $user)
        #	->set("token", $token)->update("fcm_user");
	}

	public function get_all_users_for_userGroup() 
	{
				$groupName = "Default Group";
				$result = $this->db->query("SELECT DISTINCT(user), token FROM `fcm_user` where user in (SELECT DISTINCT username FROM `users` WHERE id in(SELECT DISTINCT userid from `user_group_users` where groupid in(SELECT DISTINCT id FROM `user_groups` where name='".$groupName."')))");
				$result = $result->result_array();
				return $result;
	}

	public function get_all_users_for_ticketCategory() 
	{
				//SELECT DISTINCT(user), token FROM `fcm_user` where user in (SELECT DISTINCT username FROM `users` WHERE id in(SELECT DISTINCT userid from `user_group_users` where groupid in(SELECT DISTINCT id FROM `user_groups` where name="Default Group")))
				//SELECT DISTINCT(user), token FROM `fcm_user` where user in (SELECT DISTINCT username FROM `users` WHERE id in(SELECT DISTINCT userid from `user_group_users` where groupid in(SELECT DISTINCT id FROM `user_groups` where id IN(SELECT groupid FROM `ticket_category_groups`where catid IN (SELECT ID FROM `ticket_categories` where id=7)))))

				$ticketId = 7;
				$result = $this->db->query("SELECT DISTINCT(user), token FROM `fcm_user` where user in (SELECT DISTINCT username FROM `users` WHERE id in(SELECT DISTINCT userid from `user_group_users` where groupid in(SELECT DISTINCT id FROM `user_groups` where id IN(SELECT groupid FROM `ticket_category_groups`where catid IN (SELECT ID FROM `ticket_categories` where id='".$ticketId."')))))");
				$result = $result->result_array();
				return $result;
	}
}

?>	