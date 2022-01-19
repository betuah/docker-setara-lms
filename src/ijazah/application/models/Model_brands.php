<?php

class Model_brands extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*get the active brands information*/
	public function getActiveBrands()
	{
		$sql = "SELECT * FROM brands WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/*get thefiltered brands information*/
	public function getFilteredBrands($program, $tahun)
	{
		$user_id = $this->session->userdata('id');

		if($user_id == 1){
			$sql = "SELECT * FROM brands WHERE active=$program AND tahun=$tahun ";
		}else{
			$sql = "SELECT * FROM brands WHERE active=$program AND tahun=$tahun AND user_id=$user_id";
		}

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/* get the brand data */
	public function getBrandData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM brands WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$user_id = $this->session->userdata('id');

		if($user_id==1){
			$sql = "SELECT * FROM brands b Join users u ON b.user_id=u.id  ORDER BY b.id DESC";
		}else{
			$sql = "SELECT * FROM brands WHERE user_id=$user_id ORDER BY id DESC";
		}

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('brands', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('brands', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('brands');
			return ($delete == true) ? true : false;
		}
	}

}
