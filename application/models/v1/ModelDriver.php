<?php
class ModelDriver extends CI_Model {
	
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->helper('url');
    }				
    public function badge_count($data)
    {
    	$user_id = 'U_'.$data['driver_id'].'_D';
		$mu_id = 1;
		$this->db->where('mu_id', $mu_id);
		$this->db->like('chat_users', $user_id, 'both');
		$r = $this->db->get('tbl_chats')->result_array();
		
		$result_c = array();
		$i = 0;
		$j = 0;
		foreach($r as $key)
		{
			$this->db->from( 'tbl_chat_messages' );
			$this->db->where( 'chat_id',$key['chat_id']) ;
			$this->db->where( 'seen',0);
			$this->db->where( 'chat_user !=',$user_id) ;
			$query = $this->db->get();
			$result_c[] = $query->result_array();

			if(!empty($result_c[$i]))
			{
				for($k=0;$k<count($result_c[$i]);$k++)
				{
					$j = $j + 1;
				}
			}
			$i++;
		}

		$to = count($j);
		$result = array('success'=>true, 'status_code'=>1, 'message'=>'Total badge count', 'data'=>$j);
		return $result;

    }
	private function get_random_string($length = 10) {
		
			$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
			$token = "";
			$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < $length; $i++) {
				$n = rand(0, $alphaLength);
				$token.= $alphabet[$n];
			}
			return $token;
	}	

	public function login_process($data) {
		$this->db->where('driver_email', $data['driver_email']);	
		$this->db->where('driver_password', $data['driver_password']);	
		// $this->db->where('driver_password', md5($data['driver_password']));	
		$this->db->where('is_active', '1');	
		$rs = $this->db->get('tbl_drivers');
		if($rs->num_rows() == 1)
		{
			$d = $rs->result_array()[0];
			
			$udata = array(												
				'remember_token'=>$this->get_random_string(25),
				'device_token'=>$data['device_token'],
				'device_type'=>$data['device_type']
			);

			$this->db->where ('driver_id', $d['driver_id']);
			$this->db->update ('tbl_drivers', $udata);
			
			$return = $this->get_driver_profile($d['driver_id']);

			$result = array('success'=>true, 'status_code'=>1, 'message'=>'Login Successful!', 'data'=>$return[0]);
		}	
		else
		{
			$result = array('success'=>false, 'status_code'=>0, 'message'=>'You are not authorized for login to this Application! Please contact Administrator.', 'data'=>NULL);
		}

		return $result;
	}
	
	public function get_driver_profile($driver_id)
	{		
		$this->db->select('driver_id, mu_id, con_id, driver_name, driver_email, driver_address, driver_contact, remember_token, created_at');
		$this->db->from('tbl_drivers');		
		$this->db->where('driver_id', $driver_id);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		$rdata = array();
		
		foreach($data as $key)
		{
			//fetch Contractor
			$key['con_name'] = 'Company Driver';
			
			$this->db->select('con_name');
			$this->db->from('tbl_contractors');		
			$this->db->where('con_id', $key['con_id']);				
			$rs = $this->db->get();
			
			if($rs->num_rows()>0)
			{
				$con = $rs->result_array();
				
				$key['con_name'] = $con[0]['con_name'];
			}			
			
			$rdata[] = $key;
		}
		
		return $rdata;
	}
	
	public function operations($data)
	{
		//Count
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_customers.cust_contact1 as cust_contact, tbl_drivers.driver_name, tbl_drivers.driver_contact, tbl_contractors.con_name, tbl_contractors.con_contact');
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');
		$this->db->join('tbl_contractors', 'tbl_contractors.con_id = tbl_drivers.con_id');
		$this->db->where('tbl_operations.driver_id', $data['driver_id']);
		
		//Filter delivered and ongoing
		if($data['op_filter'] == '1')//on going
		{
			$this->db->where('tbl_operations.op_status !=', '7');	
		}
		else if($data['op_filter'] == '2')//delivered
		{
			$this->db->where('tbl_operations.op_status', '7');
		}
				
		$count = $this->db->get()->num_rows();
		
		//Data
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_customers.cust_contact1 as cust_contact, tbl_drivers.driver_name, tbl_drivers.driver_contact, tbl_contractors.con_name, tbl_contractors.con_contact');
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');
		$this->db->join('tbl_contractors', 'tbl_contractors.con_id = tbl_drivers.con_id');
		$this->db->where('tbl_operations.driver_id', $data['driver_id']);
		
		//Filter delivered and ongoing
		if($data['op_filter'] == '1')//on going
		{
			$this->db->where('tbl_operations.op_status !=', '7');	
		}
		else if($data['op_filter'] == '2')//delivered
		{
			$this->db->where('tbl_operations.op_status', '7');
		}
		
		$this->db->limit(10,(($data['page']*10)-10));				
		$rs = $this->db->get();
		
		if($rs->num_rows() > 0)
		{
			$return['total'] = $count;
			$return['operations'] = $rs->result_array();
			
			return array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>$return);
		}
		else
		{
			return array('success'=>false, 'status_code'=>0, 'message'=>'No operations found!', 'data'=>NULL);
		}					
	}
	
	public function get_operation($op_id)
	{
		$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_customers.cust_contact1 as cust_contact, tbl_drivers.driver_name, tbl_drivers.driver_contact');
		$this->db->from('tbl_operations');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
		$this->db->join('tbl_drivers', 'tbl_drivers.driver_id = tbl_operations.driver_id');		
		$this->db->where('tbl_operations.op_id', $op_id);
		
		$operation = $this->db->get()->result_array();
		
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
		
		return $operation;
	}	
	
	public function update_op_status($data, $driver)
	{
		//For notification
		$operation = $this->get_operation($data['op_id']);
		$ndata['mu_id'] = $driver['mu_id'];
		
		//Accepted
		$ndata['title'] = 'Operation Accepted!';
		$ndata['body'] = 'Operation : '.$operation[0]['op_ref_id'].' accepted by driver, Click to view details.';
		$ndata['redirect'] = base_url().'index.php/operations/track/'.$operation[0]['op_id'];		
		
		//set status default flag
		$update['op_status'] = $data['op_status'];
		
		if($data['op_status'] == '11')
		{
			$update['op_status'] = '2';	
			$update['driver_id'] = '0';
			
			$ndata['title'] = 'Operation Rejected!';
			$ndata['body'] = 'Driver has rejected Operation : '.$operation[0]['op_ref_id'];
			$ndata['redirect'] = base_url().'index.php/operations';
		}
		
		if($data['op_status'] == '5')
		{
			$update['op_status'] = '6';	
			$update['op_loading_a_time'] = date('H:i:s');
			$update['op_loading_notes'] = $data['op_loading_notes'];
			$update['op_loading_note_images'] = $data['op_loading_note_images'];
			
			$ndata['title'] = 'Loading Completed!';
			$ndata['body'] = 'Loading has been successful for Operation : '.$operation[0]['op_ref_id'].' and its on the way to delivery, Click to track.';
			$ndata['redirect'] = base_url().'index.php/operations/track/'.$operation[0]['op_id'];
		}
		
		if($data['op_status'] == '7')
		{
			$update['op_delivery_a_time'] = date('H:i:s');			
			$update['op_delivery_notes'] = $data['op_delivery_notes'];
			$update['op_delivery_note_images'] = $data['op_delivery_note_images'];
			
			$ndata['title'] = 'Delivered Successfuly!';
			$ndata['body'] = 'Operation : '.$operation[0]['op_ref_id'].' has been delivered successfuly, Click to track.';
			$ndata['redirect'] = base_url().'index.php/operations/track/'.$operation[0]['op_id'];
		}				
		
		$this->db->where('op_id', $data['op_id']);
		$this->db->update ('tbl_operations', $update);		
		
		if($this->db->affected_rows() == 1)
		{
			$this->store_notification($ndata);
			
			$return = $this->get_operation($data['op_id']);

			if($return) {
				//generate statement
				$this->store_statement($return[0]);
			}

			return array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>$return ? $return[0] : null);
		}
		else
		{
			return array('success'=>false, 'status_code'=>0, 'message'=>'Nothing is updated!', 'data'=>NULL);
		}
	}

	public function store_statement($operation)
	{
		$MY = date('M-Y', strtotime($operation['created_at']));

		//check allreadt exist or not
		$this->db->where('con_id', $operation['con_id']);
		$this->db->where('st_month_year', $MY);
		$st = $this->db->get('tbl_statement')->result_array();

		if($st){
			$this->db->where('st_id', $st[0]['st_id']);
			$this->db->set('st_orders', ($st[0]['st_orders'].','.$operation['op_id']));
			$this->db->update('tbl_statement');			
		}
		else{
			$in['mu_id'] = $operation['mu_id'];
			$in['con_id'] = $operation['con_id'];
			$in['st_month_year'] = $MY;
			$in['st_orders'] = $operation['op_id'];
			$in['created_at'] = date('Y-m-d H:i:s');			

			$this->db->insert('tbl_statement', $in);

			if($this->db->affected_rows()){
				$ID = $this->db->insert_id();
				
				$this->db->where('st_id', $ID);
				$this->db->set('st_no', ($ID.'-'.date('Y')));
				$this->db->update('tbl_statement');
			}
		}
	}
	
	public function operation_live_location($data)
	{
		$update['op_tracking_latlong'] = $data['op_tracking_latlong'];
		
		$this->db->where('op_id', $data['op_id']);
		$this->db->update ('tbl_operations', $update);				
		
		if($this->db->affected_rows() == 1)
		{
			//$return = $this->get_operation($data['op_id']);
			return array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>NULL);
		}
		else
		{
			return array('success'=>false, 'status_code'=>0, 'message'=>'Nothing is updated!', 'data'=>NULL);
		}
	}	
	
	public function store_op_file($idata)
	{
		$idata['created_at'] = date('Y-m-d H:i:s');
		
		//Insert uploaded file to DB
		$this->db->insert('tbl_operation_files', $idata);
		$file_id = $this->db->insert_id();
		
		//Fetch uploaded file record
		$this->db->where('file_id', $file_id);
		$data = $this->db->get('tbl_operation_files')->result_array()[0];
		
		return array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>$data);
	}
	
	public function delete_op_file($data)
	{
		//fetch file data by id
		$this->db->where('file_id', $data['file_id']);
		$fdata = $this->db->get('tbl_operation_files')->result_array();
		
		//return if no data found
		$false = array('success'=>false, 'status_code'=>0, 'message'=>'File not exist!', 'data'=>NULL);
		
		if($fdata)
		{		
			//delete file and record
			if($fdata[0]['file_name'])
			{
				if(file_exists('user_data/operation_data/'.$fdata[0]['file_name']))
				{
					//delete file
					unlink('user_data/operation_data/'.$fdata[0]['file_name']);
					
					//delete record
					$this->db->where('file_id', $data['file_id']);
					$this->db->delete('tbl_operation_files');
					
					return array('success'=>true, 'status_code'=>1, 'message'=>'File deleted!', 'data'=>NULL);
				}
				else
				{
					return $false;
				}
			}
			else
			{
				return $false;
			}
		}
		else
		{
			return $false;
		}
	}
	
	public function store_notification($ndata)
	{
		$this->db->insert('tbl_desktop_notifications', $ndata);	
	}
	
}
?>
