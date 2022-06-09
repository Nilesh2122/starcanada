<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Contractors_model extends CI_Model
{   	 		
	public function get_contractor_count($title){
		$mu_id = $this->session->userdata['mu_id'];		
		
		if(!empty($title)){
			$this->db->like('tbl_contractors.con_name',$title);
			$this->db->or_like('tbl_contractors.con_number',$title);
			$this->db->or_like('tbl_contractors.con_hire_date',$title);
		}
		$this->db->select('tbl_contractors.*');
		$this->db->from('tbl_contractors');
		$this->db->where('mu_id', $mu_id);	
		$this->db->where('is_removed', '0');
		if(isset($_GET['archieved'])){
			$this->db->where('is_archieved', '1');
		}else{
			$this->db->where('is_archieved', '0');
		}			
		$rs = $this->db->get();
		return $rs->num_rows();
	}
	
	public function get_contractor_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		$this->db->select('tbl_contractors.*');
		$this->db->from('tbl_contractors');		
		$this->db->order_by('tbl_contractors.con_id','desc');
		$this->db->where('mu_id', $mu_id);	
		$this->db->where('is_removed', '0');	
		if(isset($_GET['archieved'])){
			$this->db->where('is_archieved', '1');
		}else{
			$this->db->where('is_archieved', '0');
		}
		if(!empty($title)){
			$this->db->like('tbl_contractors.con_name',$title);
			$this->db->or_like('tbl_contractors.con_number',$title);
			$this->db->or_like('tbl_contractors.con_hire_date',$title);
		}
		$this->db->limit($limit,$offset);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;		
	}

	public function add_contractor($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');				
		$this->db->insert('tbl_contractors', $data);	
			
		return '1';			
	}

	public function get_contractor_by_id($con_id)
	{
		$this->db->where('con_id', $con_id);
		return $this->db->get('tbl_contractors')->result_array();
	}

	public function edit_contractor($data)
	{
		$id = $data['con_id'];
		unset($data['con_id']);
				
		$this->db->where('con_id', $id);
		$this->db->update('tbl_contractors', $data);
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}
	
	public function get_drivers_for_contractor($con_id)
	{
		$this->db->where('con_id', $con_id);
		$this->db->where('is_active', '1');	
		return $this->db->get('tbl_drivers')->result_array();
	}

	public function add_equipment($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');				
		$this->db->insert('tbl_equipments', $data);
			
		return '1';			
	}

	public function get_equipments_for_contractor($con_id)
	{
		$this->db->where('con_id', $con_id);	
		/*$this->db->where('is_removed', '0');	
		$this->db->where('is_archieved', '0');*/
		$eq = $this->db->get('tbl_equipments')->result_array();

		$equipments = array();

		foreach($eq as $e){
			$this->db->where('is_removed', '0');	
			$this->db->where('is_archieved', '0');
			$this->db->where('eq_id', $e['eq_id']);
			$driver = $this->db->get('tbl_drivers')->result_array();

			if($driver){
				$e['assigned'] = 'This Equipment is assigned to driver('.$driver[0]['driver_name'].'), Please unassign this equipment first to delete.';
			}else{
				$e['assigned'] = '0';
			}

			$equipments[] = $e;
		}

		return $equipments;
	}

	public function edit_equipment($data)
	{
		$id = $data['eq_id'];
		unset($data['eq_id']);
				
		$this->db->where('eq_id', $id);
		$this->db->update('tbl_equipments', $data);
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}
}

  