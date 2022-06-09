<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	function isLoggedIn() 
	{
		if (!$this->session->userdata('id')) {
			redirect('/');
		}		
	}
		
	public function index()
	{
		$this->isLoggedIn();
		
		$mu_id = $this->session->userdata['mu_id'];
		
		$this->db->select('*');
		$this->db->from('tbl_accounting_user');
		$this->db->where('is_active', '1');
		$this->db->where('mu_id', $mu_id);
		$rs = $this->db->get();
		$data['accountants'] = $rs->num_rows();
		
		$this->db->select('*');
		$this->db->from('tbl_customers');
		$this->db->where('is_active', '1');
		$this->db->where('mu_id', $mu_id);		
		$rs = $this->db->get();
		$data['customers'] = $rs->num_rows();
		
		$this->db->select('*');
		$this->db->from('tbl_drivers');
		$this->db->where('is_active', '1');
		$this->db->where('mu_id', $mu_id);	
		$rs = $this->db->get();
		$data['drivers'] = $rs->num_rows();
		
		$this->db->select('*');
		$this->db->from('tbl_dispatch_user');
		$this->db->where('is_active', '1');
		$this->db->where('mu_id', $mu_id);	
		$rs = $this->db->get();
		$data['dispatchers'] = $rs->num_rows();
		
		$this->db->select('*');
		$this->db->from('tbl_contractors');
		$this->db->where('is_active', '1');
		$this->db->where('mu_id', $mu_id);	
		$rs = $this->db->get();
		$data['contractors'] = $rs->num_rows();
		
		$this->db->select('*');
		$this->db->from('tbl_operations');
		$this->db->where('mu_id', $mu_id);	
		$rs = $this->db->get();
		$data['operations'] = $rs->num_rows();				
		
		$this->db->select('*');
		$this->db->from('tbl_vendors');
		$this->db->where('mu_id', $mu_id);	
		$rs = $this->db->get();
		$data['vendors'] = $rs->num_rows();		
		
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_drivers.driver_name');		
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');				
		$this->db->where('tbl_operations.mu_id', $mu_id);		
		$this->db->where('tbl_operations.op_status <', '5');
		$this->db->order_by('tbl_operations.op_id','desc');
		$this->db->limit(5);
		$rs = $this->db->get();
		$data['ops'] = $rs->result_array();

		$data['statuses'] = $this->op_status();

		$this->load->view('header');
		$this->load->view('home', $data);
		$this->load->view('footer');
	}

	private function op_status()
	{
		//Declare
		$op_status = array();	
		
		//1 = 'Intiated' , 2 = 'Ready to assign' , 3 = 'Assigned to driver' , 4 = 'Accepted' , 5 = 'Loaded' , 6 = 'On going' , 7 = 'Delivered'
		//Define
		$op_status[1] = 'Intiated';
		$op_status[2] = 'Ready to assign';
		$op_status[3] = 'Load assigned';
		$op_status[4] = 'Accepted';
		$op_status[5] = 'Loaded';
		$op_status[6] = 'On going';
		$op_status[7] = 'Delivered';		
		
		return $op_status;	
	}

	public function notifications()
	{
		$this->load->view('header');
		$this->load->view('notifications');
		$this->load->view('footer');		
	}
	
	public function unauthorized()
	{
		$this->load->view('header');
		$this->load->view('unauthorized');
		$this->load->view('footer');
	}

	public function unread_notifications()
	{
		if($this->session->userdata('mu_id'))
		{
			$mu_id = $this->session->userdata['mu_id'];

			$this->db->where('mu_id', $mu_id);
			$this->db->set('clicked', '1');
			$this->db->update('tbl_desktop_notifications');

			echo json_encode('1');
		}
		else
		{
			echo json_encode('0');
		}
	}
}
