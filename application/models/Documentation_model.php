<?php

class Documentation_Model extends CI_Model 
{

	public function get_projects_total() 
	{
		return $this->db->from("documentation_projects")->count_all_results();
	}

	public function get_projects($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"documentation_projects.name"
			)
		);

		return $this->db
			->limit($datatable->length, $datatable->start)
			->get("documentation_projects");
	}

	public function get_all_projects() 
	{
		return $this->db->get("documentation_projects");
	}

	public function add_project($data) 
	{
		$this->db->insert("documentation_projects", $data);
	}

	public function get_project($id) 
	{
		return $this->db->where("ID", $id)->get("documentation_projects");
	}

	public function delete_project($id) 
	{
		$this->db->where("ID", $id)->delete("documentation_projects");
	}

	public function update_project($id, $data) 
	{
		$this->db->where("ID", $id)->update("documentation_projects", $data);
	}

	public function get_documents_no_limit($projectid) 
	{
		return $this->db->where("projectid", $projectid)->get("documents");
	}

	public function add_document($data) 
	{
		$this->db->insert("documents", $data);
		return $this->db->insert_ID();
	}

	public function get_document($id) 
	{
		return $this->db
		->where("documents.ID", $id)
		->select("documents.ID, documents.title, documents.document,
				documents.userid, 
				documents.timestamp, documents.last_updated, documents.projectid,
				documents.link_documentid,
				documentation_projects.name as project_name,
				users.username,
				link.title as link_title, link.document as link_document")
		->join("documentation_projects", "documentation_projects.ID = documents.projectid")
			->join("users", "users.ID = documents.userid")
			->join("documents as link", "link.ID = documents.link_documentid",
			 "left outer")
		->get("documents");
	}

	public function update_document($id, $data) 
	{
		$this->db->where("ID", $id)->update("documents", $data);
	}

	public function delete_document($id) 
	{
		$this->db->where("ID", $id)->delete("documents");
	}

	public function add_file($data) 
	{
		$this->db->insert("document_files", $data);
	}

	public function get_documents_total($projectid) 
	{
		if($projectid > 0) {
			$this->db->where("projectid", $projectid);
		}
		return $this->db->from("documents")->count_all_results();
	}

	public function get_documents($projectid, $datatable) 
	{
		if($projectid > 0) {
			$this->db->where("documents.projectid", $projectid);
		}
		$datatable->db_order();

		$datatable->db_search(array(
			"documents.title"
			)
		);

		return $this->db
			->select("documents.ID, documents.title, documents.userid, 
				documents.timestamp, documents.last_updated, documents.projectid,
				documents.link_documentid,
				documentation_projects.name as project_name,
				users.username, users.avatar, users.online_timestamp,
				link.title as link_title, link.document as link_document")
			->join("documentation_projects", "documentation_projects.ID = documents.projectid")
			->join("users", "users.ID = documents.userid")
			->join("documents as link", "link.ID = documents.link_documentid",
			 "left outer")
			->limit($datatable->length, $datatable->start)
			->order_by("documents.ID", "DESC")
			->get("documents");
	}

	public function get_linked_documents($id) 
	{
		return $this->db->where("link_documentid", $id)->get("documents");
	}

	public function get_files($documentid) 
	{
		return $this->db->where("documentid", $documentid)->get("document_files");
	}

	public function get_file($id) 
	{
		return $this->db->where("ID", $id)->get("document_files");
	}

	public function delete_file($id) 
	{
		$this->db->where("ID", $id)->delete("document_files");
	}

	public function get_documents_order($projectid) 
	{
		if($projectid > 0) {
			$this->db->where("documents.projectid", $projectid);
		}
		return $this->db
			->select("documents.ID, documents.title, documents.userid, 
				documents.timestamp, documents.last_updated, documents.projectid,
				documentation_projects.name as project_name,
				documents.link_documentid,
				users.username")
			->join("documentation_projects", "documentation_projects.ID = documents.projectid")
			->join("users", "users.ID = documents.userid")
			->order_by("documents.sort_no")
			->order_by("documents.ID", "DESC")
			->get("documents");
	}

	public function get_documents_no_limit_links($projectid) 
	{
		if($projectid > 0) {
			$this->db->where("documents.projectid", $projectid);
		}
		return $this->db
			->select("documents.ID, documents.title, documents.userid, 
				documents.timestamp, documents.document, 
				documents.last_updated, documents.projectid,
				documents.link_documentid,
				documentation_projects.name as project_name,
				users.username,
				link.title as link_title, link.document as link_document")
			->join("documentation_projects", "documentation_projects.ID = documents.projectid")
			->join("users", "users.ID = documents.userid")
			->join("documents as link", "link.ID = documents.link_documentid",
			 "left outer")
			->order_by("documents.sort_no")
			->order_by("documents.ID", "DESC")
			->get("documents");
	}

}

?>