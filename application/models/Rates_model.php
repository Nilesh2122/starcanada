<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Rates_model extends CI_Model
{   	 		
	public function get_rate_count($title){
		$mu_id = $this->session->userdata['mu_id'];		
		
		if(!empty($title)){
			// $this->db->like('tbl_rates.from_city',$title);
			// $this->db->or_like('tbl_rates.to_city',$title);
			$this->db->or_like('tbl_customers.cust_name',$title);
		}
		$this->db->select('tbl_rates.*, tbl_customers.cust_name, COUNT(tbl_rates.cust_id) as rates_total');
		$this->db->from('tbl_rates');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_rates.cust_id');		
		$this->db->where('tbl_rates.mu_id', $mu_id);
		$this->db->group_by('tbl_rates.cust_id');
		$rs = $this->db->get();
		return $rs->num_rows();
	}
	
	public function get_rate_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			// $this->db->like('tbl_rates.from_city',$title);
			// $this->db->or_like('tbl_rates.to_city',$title);
			$this->db->or_like('tbl_customers.cust_name',$title);
		}
		$this->db->select('tbl_rates.*, tbl_customers.cust_name, COUNT(tbl_rates.cust_id) as rates_total');
		$this->db->from('tbl_rates');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_rates.cust_id');			
		$this->db->order_by('tbl_rates.rate_id','desc');
		$this->db->where('tbl_rates.mu_id', $mu_id);		
		$this->db->limit($limit,$offset);
		$this->db->group_by('tbl_rates.cust_id');
		$rs = $this->db->get();
		$data = $rs->result_array();

		// echo $this->db->last_query();

		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
		// exit();
		
		return $data;	
		
		return $data;		
	}

	public function get_rate_by_cust($cust_id)
	{
		$this->db->where('cust_id', $cust_id);
		return $this->db->get('tbl_rates')->result_array();
	}

	public function add_rate($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');				
		$this->db->insert('tbl_rates', $data);	
			
		return '1';			
	}

	public function get_rate_by_id($rate_id)
	{
		$this->db->where('rate_id', $rate_id);
		return $this->db->get('tbl_rates')->result_array();
	}

	public function edit_rate($data)
	{
		$id = $data['rate_id'];
		unset($data['rate_id']);
		
		$data['from_city_latlong'] = $data['from_city_latlong_lat'].','.$data['from_city_latlong_lon'];
		$data['to_city_latlong'] = $data['to_city_latlong_lat'].','.$data['to_city_latlong_lon'];
		
		//unset fields (seperated fields)
		unset($data['from_city_latlong_lat']);
		unset($data['from_city_latlong_lon']);
		unset($data['to_city_latlong_lat']);
		unset($data['to_city_latlong_lon']);
				
		$this->db->where('rate_id', $id);
		$this->db->update('tbl_rates', $data);
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}	
}

  