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

	public function get_recent_articles($limit) 
	{
		return $this->db->select("knowledge_articles.ID, knowledge_articles.title,
			knowledge_articles.last_updated_timestamp, knowledge_articles.body,
			knowledge_articles.catid,
			users.ID as userid, users.username, users.avatar, users.online_timestamp,
			knowledge_categories.name as catname, knowledge_categories.image")
			->join("knowledge_categories", "knowledge_categories.ID = knowledge_articles.catid")
			->join("users", "users.ID = knowledge_articles.userid")
			->order_by("knowledge_articles.ID", "DESC")
			->limit($limit)
			->get("knowledge_articles");

	}
}

?>	