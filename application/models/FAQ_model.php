<?php

class FAQ_Model extends CI_Model 
{

	public function get_categories() 
	{
		return $this->db->get("faq_categories");
	}

	public function get_category($id) 
	{
		return $this->db->where("ID", $id)->get("faq_categories");
	}

	public function add_category($data) 
	{
		$this->db->insert("faq_categories", $data);
		return $this->db->insert_id();
	}

	public function delete_category($id) 
	{
		$this->db->where("ID", $id)->delete("faq_categories");
	}

	public function update_category($id, $data) 
	{
		$this->db->where("ID", $id)->update("faq_categories", $data);
	}

	public function get_categories_total() 
	{
		$s = $this->db->select("COUNT(*) as num")->get("faq_categories");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_categories_dt($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"faq_categories.name"
			)
		);

		return $this->db
			->limit($datatable->length, $datatable->start)
			->get("faq_categories");

	}

	public function get_faq_total() 
	{
		$s = $this->db->select("COUNT(*) as num")->get("faq");
		$r = $s->row();
		if(isset($r->num)) return $r->num;
		return 0;
	}

	public function get_faq_dt($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"faq.question"
			)
		);

		return $this->db
			->select("faq.question, faq.ID,
				faq_categories.name as cat_name")
			->join("faq_categories", "faq_categories.ID = faq.catid")
			->limit($datatable->length, $datatable->start)
			->get("faq");

	}

	public function get_faq_all($catid) 
	{
		return $this->db
			->where("faq.catid", $catid)
			->select("faq.question, faq.ID,
				faq_categories.name as cat_name, faq.answer")
			->join("faq_categories", "faq_categories.ID = faq.catid")
			->get("faq");

	}

	public function add_faq($data) 
	{
		$this->db->insert("faq", $data);
	}

	public function get_faq($id) 
	{
		return $this->db->where("ID", $id)->get("faq");
	}

	public function delete_faq($id) 
	{
		$this->db->where("ID", $id)->delete("faq");
	}

	public function update_faq($id, $data) 
	{
		$this->db->where("ID", $id)->update("faq", $data);
	}

	public function get_faq_count($catid) 
	{
		return $this->db->where("catid", $catid)->from("faq")->count_all_results();
	}

	public function get_recent_faq($limit)
	{
		return $this->db->limit($limit)->get("faq");
	}

}

?>