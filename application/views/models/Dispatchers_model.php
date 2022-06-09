<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dispatchers_model extends CI_Model
{   	 		
	public function get_dispatcher_count($title){
		$mu_id = $this->session->userdata['mu_id'];		
		
		if(!empty($title)){
			$this->db->like('tbl_dispatch_user.du_name',$title);
		}
		$this->db->select('tbl_dispatch_user.*');
		$this->db->from('tbl_dispatch_user');
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
	
	public function get_dispatcher_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_dispatch_user.du_name',$title);
		}
		$this->db->select('tbl_dispatch_user.*');
		$this->db->from('tbl_dispatch_user');		
		$this->db->order_by('tbl_dispatch_user.du_id','desc');
		$this->db->where('mu_id', $mu_id);	
		$this->db->where('is_removed', '0');	
		if(isset($_GET['archieved'])){
			$this->db->where('is_archieved', '1');
		}else{
			$this->db->where('is_archieved', '0');
		}
		$this->db->limit($limit,$offset);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;		
	}

	public function add_dispatcher($data)
	{
		$this->db->where('acu_email', $data['du_email']);
		$r1 = $this->db->get('tbl_accounting_user')->result_array();
		
		$this->db->where('au_email', $data['du_email']);
		$r2 = $this->db->get('tbl_admin_user')->result_array();
		
		$this->db->where('du_email', $data['du_email']);
		$r3 = $this->db->get('tbl_dispatch_user')->result_array();
		
		$this->db->where('driver_email', $data['du_email']);
		$r4 = $this->db->get('tbl_drivers')->result_array();
		
		if(sizeof($r1)==1 || sizeof($r2)==1 || sizeof($r3)==1 || sizeof($r4)==1)
		{
			return '0';
		}
		else
		{
			$data['created_at'] = date('Y-m-d H:i:s');				
			$this->db->insert('tbl_dispatch_user', $data);	
				
			return $this->db->affected_rows() == 1 ? '1' : '3';	
		}
	}

	public function get_dispatcher_by_id($du_id)
	{
		$this->db->where('du_id', $du_id);
		return $this->db->get('tbl_dispatch_user')->result_array();
	}

	public function edit_dispatcher($data)
	{
		$id = $data['du_id'];
		unset($data['du_id']);
				
		$this->db->where('du_id', $id);
		$this->db->update('tbl_dispatch_user', $data);
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}	
}

  