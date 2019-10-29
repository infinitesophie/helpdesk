<?php

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
}

?>	