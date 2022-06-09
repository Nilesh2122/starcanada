<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Notifications_model extends CI_Model
{   	 	
	public function get_all()
	{
		if($this->session->userdata('mu_id'))
		{
			$mu_id = $this->session->userdata['mu_id'];

			$this->db->where('mu_id', $mu_id);
			$this->db->order_by('id', 'desc');
			$this->db->limit(50);
			return $this->db->get('tbl_desktop_notifications')->result_array();
		}
		else
		{
			header('location:'.base_url());
		}
	}

	public function get_unread()
	{
		if($this->session->userdata('mu_id'))
		{
			$mu_id = $this->session->userdata['mu_id'];

			$this->db->where('mu_id', $mu_id);
			$this->db->where('clicked', '0');
			$this->db->order_by('id', 'desc');			
			return $this->db->get('tbl_desktop_notifications')->result_array();
		}
		else
		{
			header('location:'.base_url());
		}
	}
}

  