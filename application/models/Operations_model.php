<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Operations_model extends CI_Model
{   	 		
	public function get_operation_count($title){
		$mu_id = $this->session->userdata['mu_id'];		
		
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);
			$this->db->or_like('tbl_drivers.driver_name',$title);
		}
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_drivers.driver_name');		
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');
		$this->db->where('tbl_operations.mu_id', $mu_id);	
		$this->db->where('tbl_operations.is_removed', '0');
		$rs = $this->db->get();
		return $rs->num_rows();
	}
	
	public function get_operation_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);
			//$this->db->or_like('tbl_drivers.driver_name',$title);
		}
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_drivers.driver_name, tbl_drivers.unit_no');		
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id','left');
		$this->db->order_by('tbl_operations.assigned_by','desc');
		$this->db->where('tbl_operations.mu_id', $mu_id);	
		$this->db->where('tbl_operations.is_removed', '0');
		$this->db->where('tbl_operations.op_status !=', '7');
		//$this->db->limit($limit,$offset);
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;		
	}
	public function get_operation_list_sheet($limit,$offset,$title,$date){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);
			//$this->db->or_like('tbl_drivers.driver_name',$title);
		}
		$this->db->select('tbl_drivers.driver_id, tbl_drivers.driver_name, tbl_drivers.unit_no');		
		$this->db->from('tbl_drivers');
		$this->db->order_by('tbl_drivers.driver_id','desc');
		$rs = $this->db->get();
		$data['driver'] = $rs->result_array();
		
		for($i=0;$i<count($data['driver']);$i++)
		{
			$this->db->select('tbl_operations.*,tbl_customers.cust_name');	
			$this->db->from('tbl_operations');
			$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id','left');
			$this->db->where('tbl_operations.driver_id', $data['driver'][$i]['driver_id']);	
			$this->db->where('tbl_operations.mu_id', $mu_id);	
			/*$this->db->where('tbl_operations.is_removed', '0');
			$this->db->where('tbl_operations.op_status !=', '7');*/
			if($date != ''){
			$this->db->where('tbl_operations.op_loading_date', $date);
			}else{
				$current_date = date('Y/m/d');
				$this->db->where('tbl_operations.op_loading_date', $current_date);
			}
			$rs = $this->db->get();
			$data['driver'][$i]['op'] = $rs->result_array();
		}
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";
		exit();*/
		return $data;

		/*$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_drivers.driver_name, tbl_drivers.unit_no');		
		$this->db->from('tbl_drivers');
		$this->db->join('tbl_operations', 'tbl_drivers.driver_id = tbl_operations.driver_id','left');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id','left');
		$this->db->order_by('tbl_drivers.driver_id','desc');
		$this->db->where('tbl_operations.mu_id', $mu_id);	
		$this->db->where('tbl_operations.is_removed', '0');
		if($date != ''){
			$this->db->where('tbl_operations.op_loading_date', $date);
		}else{
			$current_date = date('Y/m/d');
			$this->db->where('tbl_operations.op_loading_date', $current_date);
		}
		//$this->db->where('tbl_operations.op_status !=', '7');
		//$this->db->limit($limit,$offset);
		//$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";
		exit();
		return $data;*/

		/*$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_drivers.driver_name, tbl_drivers.unit_no');		
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');
		$this->db->order_by('tbl_operations.op_id','desc');
		$this->db->where('tbl_operations.mu_id', $mu_id);	
		$this->db->where('tbl_operations.is_removed', '0');
		//$this->db->where('tbl_operations.op_status !=', '7');
		//$this->db->limit($limit,$offset);
		//$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;	*/	
	}

	public function get_more($limit, $op_id ,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);
			//$this->db->or_like('tbl_drivers.driver_name',$title);
		}
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_drivers.driver_name, tbl_drivers.unit_no');		
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');				
		$this->db->where('tbl_operations.mu_id', $mu_id);	
		$this->db->where('tbl_operations.is_removed', '0');
		$this->db->where('tbl_operations.op_status !=', '7');	
		$this->db->where('tbl_operations.op_id <', $op_id);
		$this->db->order_by('tbl_operations.op_id','desc');
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();

		//return $this->db->last_query();		
		return array_values($data);
	}

	public function completed_operation_list($limit,$offset,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);
			//$this->db->or_like('tbl_drivers.driver_name',$title);
		}
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_drivers.driver_name');		
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');
		$this->db->order_by('tbl_operations.op_id','desc');
		$this->db->where('tbl_operations.mu_id', $mu_id);
		$this->db->where('tbl_operations.is_removed', '0');
		$this->db->where('tbl_operations.op_status', '7');		
		//$this->db->limit($limit,$offset);
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;		
	}

	public function completed_more($limit, $op_id ,$title){
		$mu_id = $this->session->userdata['mu_id'];
		
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);
			//$this->db->or_like('tbl_drivers.driver_name',$title);
		}
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_drivers.driver_name');		
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');				
		$this->db->where('tbl_operations.mu_id', $mu_id);	
		$this->db->where('tbl_operations.is_removed', '0');
		$this->db->where('tbl_operations.op_status', '7');	
		$this->db->where('tbl_operations.op_id <', $op_id);		
		$this->db->order_by('tbl_operations.op_id','desc');
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();

		//return $this->db->last_query();		
		return array_values($data);
	}
	
	public function get_contractors()
	{
		$mu_id = $this->session->userdata['mu_id'];
		
		$this->db->where('mu_id', $mu_id);
		$this->db->where('is_active', '1');	
		return $this->db->get('tbl_contractors')->result_array();
	}
	
	public function get_customers()
	{
		$mu_id = $this->session->userdata['mu_id'];
		
		$this->db->where('mu_id', $mu_id);
		$this->db->where('is_active', '1');
		return $this->db->get('tbl_customers')->result_array();
	}

	public function add_operation($data)
	{
		date_default_timezone_set('America/Edmonton');
		//explode unit and its rate
		// $data['op_mesuare_rate'] = explode(';', $data['op_mesuare_unit'])[1];
		// $data['op_mesuare_unit'] = explode(';', $data['op_mesuare_unit'])[0];

		//calculate total fare of operation
		$data['op_total_cost'] = round((((float)$data['op_sub_rate'] * (float)$data['op_mesuare_rate'] * ((float)$data['op_fuel_surcharge'] + 1)) + (float)$data['op_loading_charge'] + (float)$data['op_delivery_charge'] + (float)$data['op_trap_amount']), 2);

		$data['created_at'] = date('Y-m-d H:i:s');
		
		$this->db->insert('tbl_operations', $data);	
		
		if($this->db->affected_rows() == 1)
		{
			$insertID = $this->db->insert_id();			
			
			$udata['op_ref_id'] = '#REF'.$insertID.'OP'.time();
			
			$this->db->where('op_id', $insertID);	
			$this->db->update('tbl_operations', $udata);
			
			return '1';
		}
		else
		{
			return '0';
		}		
	}

	public function get_operation_by_id($op_id)
	{
		$this->db->where('op_id', $op_id);
		return $this->db->get('tbl_operations')->result_array();
	}

	public function edit_operation($data)
	{
		$id = $data['op_id'];
		unset($data['op_id']);

		//explode unit and its rate
		// $data['op_mesuare_rate'] = explode(';', $data['op_mesuare_unit'])[1];
		// $data['op_mesuare_unit'] = explode(';', $data['op_mesuare_unit'])[0];		

		//calculate total fare of operation
		$data['op_total_cost'] = round((((float)$data['op_sub_rate'] * (float)$data['op_mesuare_rate'] * ((float)$data['op_fuel_surcharge'] + 1)) + (float)$data['op_loading_charge'] + (float)$data['op_delivery_charge'] + (float)$data['op_trap_amount']), 2);		

		$this->db->where('op_id', $id);
		$this->db->update('tbl_operations', $data);
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}	
	
	public function get_drivers_by_contractors($mu_id, $con_id)
	{
		$this->db->where('mu_id', $mu_id);
		$this->db->where('con_id', $con_id);
		$this->db->where('is_active', '1');		
		$res = $this->db->get('tbl_drivers')->result_array();
		
		return sizeof($res) > 0 ? $res : '1';
	}	

	public function get_refreshed_drivers($mu_id)
	{
		$this->db->select('driver_id');
		$this->db->where('mu_id', $mu_id);		
		$this->db->where('op_status', '3');
		$this->db->or_where('op_status', '4');
		$this->db->or_where('op_status', '5');
		$this->db->or_where('op_status', '6');		
		$OD = $this->db->get('tbl_operations')->result_array();
		
		$check[0] = 0;
		if($OD){
			foreach($OD as $d)
			{
				$check[] = $d['driver_id'];
			}
		}		

		$this->db->where('mu_id', $mu_id);		
		$this->db->where('is_active', '1');
		$this->db->where('is_archieved', '0');
		$this->db->where('is_removed', '0');
		// $this->db->where_not_in('driver_id', $check);
		$res = $this->db->get('tbl_drivers')->result_array();
		
		return sizeof($res) > 0 ? $res : '1';
	}
	
	public function assign_driver($data)
	{
		$id = $data['op_id'];
		unset($data['op_id']);
		
		$data['op_status'] = '3';

		$this->db->where('op_id', $id);
		$this->db->update('tbl_operations', $data);		
		
		return $this->db->affected_rows() == 1 ? '1' : '0';
	}
	
	public function get_operation_track($op_id)
	{
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_customers.cust_contact1 as cust_contact, tbl_drivers.driver_name, tbl_drivers.driver_contact, tbl_contractors.con_name, tbl_contractors.con_contact');
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id','left');
		$this->db->join('tbl_contractors', 'tbl_contractors.con_id = tbl_operations.con_id');
		$this->db->where('tbl_operations.op_id', $op_id);		
		$rs = $this->db->get();
		$operation = $rs->result_array();				
		
		//loading images
		if($operation && $operation[0]['op_loading_note_images'])
		{
			$files = explode(',', $operation[0]['op_loading_note_images']);
			$new_files = array();
			
			for($i=0; $i<sizeof($files); $i++)
			{
				$this->db->where('file_id', $files[$i]);
				$file = $this->db->get('tbl_operation_files')->result_array();
				
				if($file)
				{
					$new_files[] = $file[0]['file_name'];
				}
			}
			
			$operation[0]['op_loading_note_images'] = implode(',', $new_files);
		}
		
		//delivery images
		if($operation && $operation[0]['op_delivery_note_images'])
		{
			$files = explode(',', $operation[0]['op_delivery_note_images']);
			$new_files = array();
			
			for($i=0; $i<sizeof($files); $i++)
			{
				$this->db->where('file_id', $files[$i]);
				$file = $this->db->get('tbl_operation_files')->result_array();
				
				if($file)
				{
					$new_files[] = $file[0]['file_name'];
				}
			}
			
			$operation[0]['op_delivery_note_images'] = implode(',', $new_files);
		}				
		
		return $operation[0];				
	}	
}

