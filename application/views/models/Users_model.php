<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model
{   	 	
	public function login_process($email, $password)
	{
		//Checking for admin
		$this->db->select('*');
		$this->db->from('tbl_admin_user');
		$this->db->where('au_email', trim(strtolower($email)));
		$this->db->where('au_password', md5($password));
		$q = $this->db->get();
		
		//Checking for Accountant
		$this->db->select('*');
		$this->db->from('tbl_accounting_user');
		$this->db->where('acu_email', trim(strtolower($email)));
		$this->db->where('acu_password', md5($password));
		$this->db->where('is_active', '1');
		$this->db->where('is_removed', '0');
		$q2 = $this->db->get();
		
		//Checking for Dispatcher
		$this->db->select('*');
		$this->db->from('tbl_dispatch_user');
		$this->db->where('du_email', trim(strtolower($email)));
		$this->db->where('du_password', md5($password));
		$this->db->where('is_active', '1');
		$this->db->where('is_removed', '0');
		$q3 = $this->db->get();
		
		if($q->num_rows() == 1)
		{
			$res = $q->result_array();
			$res[0]['role'] = 'Administrator';
			$res[0]['id'] = $res[0]['au_id'];			
			$res[0]['uname'] = $res[0]['au_name'];			
			$res[0]['user_email'] = $res[0]['au_email'];						
			return $res;
		}
		else if($q2->num_rows() == 1)
		{
			$res = $q2->result_array();
			$res[0]['role'] = 'Accountant';
			$res[0]['id'] = $res[0]['acu_id'];			
			$res[0]['uname'] = $res[0]['acu_name'];			
			$res[0]['user_email'] = $res[0]['acu_email'];						
			return $res;
		}
		else if($q3->num_rows() == 1)
		{
			$res = $q3->result_array();
			$res[0]['role'] = 'Dispatcher';
			$res[0]['id'] = $res[0]['du_id'];			
			$res[0]['uname'] = $res[0]['du_name'];			
			$res[0]['user_email'] = $res[0]['du_email'];						
			return $res;
		}
		else
		{
			return '0';			
		}
	}

	public function reset_password($email)
	{
		//Checking for admin
		$this->db->select('*');
		$this->db->from('tbl_admin_user');
		$this->db->where('au_email', trim(strtolower($email)));		
		$q = $this->db->get();
		
		//Checking for Accountant
		$this->db->select('*');
		$this->db->from('tbl_accounting_user');
		$this->db->where('acu_email', trim(strtolower($email)));
		$this->db->where('is_active', '1');
		$this->db->where('is_removed', '0');
		$this->db->where('is_archieved', '0');
		$q2 = $this->db->get();
		
		//Checking for Dispatcher
		$this->db->select('*');
		$this->db->from('tbl_dispatch_user');
		$this->db->where('du_email', trim(strtolower($email)));
		$this->db->where('is_active', '1');
		$this->db->where('is_removed', '0'); 
		$this->db->where('is_archieved', '0');
		$q3 = $this->db->get();
		
		if($q->num_rows() == 1)
		{
			$res = $q->result_array();
			return 'Administrator';						
		}
		else if($q2->num_rows() == 1)
		{
			$res = $q2->result_array();

			//update reset pass flag
			$this->db->where('acu_email', trim(strtolower($email)));
			$this->db->set('reset_password', '0');
			$this->db->update('tbl_accounting_user');

			return 'Accountant';
		}
		else if($q3->num_rows() == 1)
		{
			$res = $q3->result_array();

			//update reset pass flag			
			$this->db->where('du_email', trim(strtolower($email)));
			$this->db->set('reset_password', '0');
			$this->db->update('tbl_dispatch_user');

			return 'Dispatcher';
		}
		else
		{
			return '0';			
		}
	}
	
	public function get_user_count($title){
		$mu_id = $this->session->userdata['mu_id'];		
		
		if(!empty($title)){
			$this->db->like('tbl_accounting_user.acu_name',$title);
			$this->db->or_like('tbl_accounting_user.acu_number',$title);
			$this->db->or_like('tbl_accounting_user.acu_hire_date',$title);
		}
		$this->db->select('tbl_accounting_user.*');
		$this->db->from('tbl_accounting_user');
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
	
	public function get_user_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_accounting_user.acu_name',$title);
			$this->db->or_like('tbl_accounting_user.acu_number',$title);
			$this->db->or_like('tbl_accounting_user.acu_hire_date',$title);
		}
		$this->db->select('tbl_accounting_user.*');
		$this->db->from('tbl_accounting_user');		
		$this->db->order_by('tbl_accounting_user.acu_id','desc');
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

	public function add_user($data)
	{
		$this->db->where('acu_email', $data['acu_email']);
		$r1 = $this->db->get('tbl_accounting_user')->result_array();
		
		$this->db->where('au_email', $data['acu_email']);
		$r2 = $this->db->get('tbl_admin_user')->result_array();
		
		$this->db->where('du_email', $data['acu_email']);
		$r3 = $this->db->get('tbl_dispatch_user')->result_array();
		
		$this->db->where('driver_email', $data['acu_email']);
		$r4 = $this->db->get('tbl_drivers')->result_array();
		
		if(sizeof($r1)==1 || sizeof($r2)==1 || sizeof($r3)==1 || sizeof($r4)==1)
		{
			return '0';
		}
		else
		{
			$data['created_at'] = date('Y-m-d H:i:s');				
			$this->db->insert('tbl_accounting_user', $data);	
			
			return $this->db->affected_rows() == 1 ? '1' : '3';
		}			
	}

	public function get_user_by_id($user_id)
	{
		$this->db->where('acu_id', $user_id);
		return $this->db->get('tbl_accounting_user')->result_array();
	}

	public function edit_user($data)
	{
		$id = $data['acu_id'];
		unset($data['acu_id']);
				
		$this->db->where('acu_id', $id);
		$this->db->update('tbl_accounting_user', $data);
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}
}

  