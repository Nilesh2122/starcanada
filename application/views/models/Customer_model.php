<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Customer_model extends CI_Model
{   	 		
	public function get_customer_count($title){
		$mu_id = $this->session->userdata['mu_id'];		
		
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);
			$this->db->or_like('tbl_customers.cust_number',$title);
			$this->db->or_like('tbl_customers.created_at',$title);
		}
		$this->db->select('tbl_customers.*');
		$this->db->from('tbl_customers');
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
	
	public function get_customer_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);
			$this->db->or_like('tbl_customers.cust_number',$title);
			$this->db->or_like('tbl_customers.created_at',$title);
		}
		$this->db->select('tbl_customers.*');
		$this->db->from('tbl_customers');		
		$this->db->order_by('tbl_customers.cust_id','desc');
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

	public function add_customer($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');				
		$this->db->insert('tbl_customers', $data);			
			
		return $this->db->affected_rows() == 1 ? '1' : '0';		
	}

	public function get_customer_by_id($user_id)
	{
		$this->db->where('cust_id', $user_id);
		return $this->db->get('tbl_customers')->result_array();
	}

	public function edit_customer($data)
	{
		$id = $data['cust_id'];
		unset($data['cust_id']);
				
		$this->db->where('cust_id', $id);
		$this->db->update('tbl_customers', $data);		
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}
}

  