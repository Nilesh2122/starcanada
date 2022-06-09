<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Vendors_model extends CI_Model
{   	 		
	public function get_vendor_count($title){
		$mu_id = $this->session->userdata['mu_id'];		
		
		if(!empty($title)){
			$this->db->like('tbl_vendors.v_name',$title);
			$this->db->or_like('tbl_vendors.v_number',$title);
			$this->db->or_like('tbl_vendors.created_at',$title);
		}
		$this->db->select('tbl_vendors.*');
		$this->db->from('tbl_vendors');
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
	
	public function get_vendor_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_vendors.v_name',$title);
			$this->db->or_like('tbl_vendors.v_number',$title);
			$this->db->or_like('tbl_vendors.created_at',$title);
		}
		$this->db->select('tbl_vendors.*');
		$this->db->from('tbl_vendors');
		$this->db->where('is_removed', '0');		
		if(isset($_GET['archieved'])){
			$this->db->where('is_archieved', '1');
		}else{
			$this->db->where('is_archieved', '0');
		}
		$this->db->where('mu_id', $mu_id);		
		$this->db->order_by('tbl_vendors.v_id','desc');
		$this->db->limit($limit,$offset);
		$rs = $this->db->get();
		$data = $rs->result_array(); 
		
		return $data;		
	}

	public function add_vendor($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');				
		$this->db->insert('tbl_vendors', $data);			
			
		return $this->db->affected_rows() == 1 ? '1' : '0';		
	}

	public function get_vendor_by_id($v_id)
	{
		$this->db->where('v_id', $v_id);
		return $this->db->get('tbl_vendors')->result_array();
	}

	public function edit_vendor($data)
	{
		$id = $data['v_id'];
		unset($data['v_id']);
				
		$this->db->where('v_id', $id);
		$this->db->update('tbl_vendors', $data);		
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}
}

  