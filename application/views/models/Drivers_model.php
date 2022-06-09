<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Drivers_model extends CI_Model
{   	 		
	public function get_driver_count($title){
		$mu_id = $this->session->userdata['mu_id'];		
		
		if(!empty($title)){
			$this->db->like('tbl_drivers.driver_name',$title);
			$this->db->or_like('tbl_drivers.unit_no',$title);
			$this->db->or_like('tbl_drivers.driver_hire_date',$title);
		}
		$this->db->select('tbl_drivers.*');
		$this->db->from('tbl_drivers');
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
	
	public function get_driver_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_drivers.driver_name',$title);
			$this->db->or_like('tbl_drivers.unit_no',$title);
			$this->db->or_like('tbl_drivers.driver_hire_date',$title);
		}
		$this->db->select('tbl_drivers.*');
		$this->db->from('tbl_drivers');		
		$this->db->order_by('tbl_drivers.driver_id','desc');
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
		
		$rdata = array();
		
		foreach($data as $key)
		{
			$this->db->select('tbl_contractors.con_name');
			$this->db->from('tbl_contractors');		
			$this->db->where('con_id', $key['con_id']);				
			$rs = $this->db->get();
			
			if($rs->num_rows()>0)
			{
				$con = $rs->result_array();
				
				$key['con_name'] = $con[0]['con_name'];
			}
			else
			{
				$key['con_name'] = 'Company Driver';
			}
			
			$rdata[] = $key;
		}
		
		return $rdata;				
	}
	
	public function get_contractors()
	{
		$mu_id = $this->session->userdata['mu_id'];
		
		$this->db->select('*');
		$this->db->from('tbl_contractors');		
		$this->db->where('mu_id', $mu_id);			
		$this->db->where('is_active', '1');				
		$this->db->where('is_archieved', '0');				
		$this->db->where('is_removed', '0');				
		$rs = $this->db->get();
		
		return $rs->result_array();
	}

	public function add_driver($data)
	{
		$this->db->where('acu_email', $data['driver_email']);
		$r1 = $this->db->get('tbl_accounting_user')->result_array();
		
		$this->db->where('au_email', $data['driver_email']);
		$r2 = $this->db->get('tbl_admin_user')->result_array();
		
		$this->db->where('du_email', $data['driver_email']);
		$r3 = $this->db->get('tbl_dispatch_user')->result_array();
		
		$this->db->where('driver_email', $data['driver_email']);
		$r4 = $this->db->get('tbl_drivers')->result_array();
		
		if(sizeof($r1)==1 || sizeof($r2)==1 || sizeof($r3)==1 || sizeof($r4)==1)
		{
			// if($data['driver_license'] != '')
			// {
			// 	if(file_exists('user_data/driver_data/'.$data['driver_license']))
			// 	{
			// 		unlink('user_data/driver_data/'.$data['driver_license']);
			// 	}				
			// }
			
			// if($data['driver_id_proof'] != '')
			// {
			// 	if(file_exists('user_data/driver_data/'.$data['driver_id_proof']))
			// 	{
			// 		unlink('user_data/driver_data/'.$data['driver_id_proof']);
			// 	}
			// }
			
			return '0';			
		}
		else
		{
			$data['created_at'] = date('Y-m-d H:i:s');				
			$this->db->insert('tbl_drivers', $data);			
				
			return $this->db->affected_rows() == 1 ? '1' : '3';
		}
	}

	public function get_driver_by_id($user_id)
	{
		$this->db->where('driver_id', $user_id);
		$d = $this->db->get('tbl_drivers')->result_array();

		if($d)
		{
			$this->db->where('con_id', $d[0]['con_id']);
			$d[0]['eq'] = $this->db->get('tbl_equipments')->result_array();
		}

		return $d;
	}

	public function edit_driver($data)
	{
		$id = $data['driver_id'];
		unset($data['driver_id']);
		
		$this->db->where('driver_id', $id);
		$res = $this->db->get('tbl_drivers')->result_array();
		
		if(isset($data['driver_license']))
		{
			if($res[0]['driver_license'])
			{
				if(file_exists('user_data/driver_data/'.$res[0]['driver_license']))
				{
					unlink('user_data/driver_data/'.$res[0]['driver_license']);
				}
			}			
		}
		
		if(isset($data['driver_id_proof']))
		{
			if($res[0]['driver_id_proof'])
			{
				if(file_exists('user_data/driver_data/'.$res[0]['driver_id_proof']))
				{
					unlink('user_data/driver_data/'.$res[0]['driver_id_proof']);
				}
			}
		}		
				
		$this->db->where('driver_id', $id);
		$this->db->update('tbl_drivers', $data);		
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}

	public function get_equipments_by_con($mu_id, $con_id)
	{
		$this->db->where('con_id', $con_id);
		$this->db->where('mu_id', $mu_id);
		return $this->db->get('tbl_equipments')->result_array() ? $this->db->get('tbl_equipments')->result_array() : '1';
	}
}

  