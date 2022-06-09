<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Drivers extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();    
		
		$this->load->helper('url');
		$this->load->database();
		$this->load->helper('file');
		$this->load->model('v1/ModelDriver');    
	}	
	
	private function check_header()
	{
		$headerData = apache_request_headers();

		if(!isset($headerData['App-Name']) || !isset($headerData['Authorization-Token'])){
			$this->_sendResponse(50);
		}		
		else{
			if($headerData['App-Name'] != 'star_canada'){
				$this->_sendResponse(50);
			}
			else{
				return $headerData['Authorization-Token'];
			}
		}		
	}
	
	public function login_post()
    {	
		$data = json_decode(file_get_contents('php://input'), true);

		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_email'] || !$data['driver_password'] || !$data['device_type'] || !$data['device_token'])
		{
			$this->_sendResponse(4);
		}
		else
		{		
			$result = $this->ModelDriver->login_process($data);
			
			if($result)
			{			
				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}	
	
	public function operations_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['op_filter'] || !$data['page'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelDriver->operations($data);
			
			if($result)
			{			
				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}	
	
	public function operation_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['op_id'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelDriver->get_operation($data['op_id']);						
			
			if($result)
			{			
				$result = array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>$result[0]);
				
				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}	
	
	public function update_op_status_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['op_id'] || !$data['op_status'])
		{
			$this->_sendResponse(4);
		}
		else
		{													
			if($data['op_status'] == '5' && !$data['op_loading_notes'] && !$data['op_loading_note_images'])
			{
				$this->_sendResponse(4);
			}
			
			if($data['op_status'] == '7' && !$data['op_delivery_notes'] && !$data['op_delivery_note_images'])
			{
				$this->_sendResponse(4);
			}
		
			$driver = $this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelDriver->update_op_status($data, $driver);
			
			if($result)
			{			
				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}
	
	public function operation_live_location_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['op_id'] || !$data['op_tracking_latlong'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelDriver->operation_live_location($data);
			
			if($result)
			{			
				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}	
	
	public function upload_op_file_post()
	{		
		$headerData = $this->check_header();
		
		if(!$this->post('driver_id') || !$this->post('remember_token'))
		{
			$this->_sendResponse(4);
		}
		else
		{
			$data = $_POST;
			
			$data['remember_token'] = $headerData;

			$this->is_authorised($data['driver_id'], $data['remember_token']);
			
			// store image
			if(!empty($_FILES['file']))
			{
				$today = date('Y_m_d');				
				$path_parts = pathinfo($_FILES['file']['name']);
				$extension = $path_parts['extension'];
				$new_file_name = 'op_notes_file_'.$data['driver_id'].'_'.$today.'_'.time().'.'.$extension;  // op_notes_file_1_2017-05-09_114560.jpg
				
				$targetFolder = 'user_data/operation_data/';
				
			    $target_file = $targetFolder.$new_file_name;
				
				if (move_uploaded_file($_FILES['file']['tmp_name'],$target_file))  // upload original img
				{					
					$idata['file_name'] = $new_file_name;
				}
				else
				{
					$this->_sendResponse(5);
				}
			}
			else
			{
				$this->_sendResponse(4);
			}	
			
			$result = $this->ModelDriver->store_op_file($idata);
			
			if($result)
			{			
				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}
	
	public function delete_op_file_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['file_id'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised($data['driver_id'], $data['remember_token']);						
			
			$result = $this->ModelDriver->delete_op_file($data);
			
			if($result)
			{			
				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}	

	public function badge_count_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised($data['driver_id'], $data['remember_token']);
			$result = $this->ModelDriver->badge_count($data);
			
			if($result)
			{			
				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}

	// public function testheader_post()
	// {		
	// 	print_r(apache_request_headers());
	// 	print_r(getallheaders());		
	// }	
	
	private function is_authorised($driver_id, $remember_token)
	{
		$this->db->select('*');
		$this->db->from('tbl_drivers');
		$where = ' driver_id = "'.$driver_id.'" AND remember_token = "'.$remember_token.'" ';
		$this->db->where($where);
		$result = $this->db->get();
        if (!($result->result_array())) 
        {
            $this->_sendResponse(2);
        } 
        $result=$result->result_array();
        return $result[0];
	}
	
	private function _sendResponse($status_code = 200) {        
        $this->msg = $this->_getStatusCodeMessage($status_code);
        $this->result['message'] = $this->msg;
        $this->result['status_code'] = $status_code;
        
        header('Content-Type: application/json');
        echo json_encode($this->result);
        die();
    }
	
	private function _getStatusCodeMessage($status) 
    {
        $codes = Array(
            1 => 'OK',
            4 => 'Bad Request',
            2 => 'You must be authorized to view this page.',
            3 => 'The requested URL  was not found.',
            0 => 'internal server error',
            5 => 'file not upload',
            6 => 'At least one image required',
            8 => 'Email Already Exists',
            9 => 'Email invalid',
            10 => 'password invalid',
            11 => 'user is blocked',
            12 => 'user is not verified',
            16 => 'Temporary Password Invalid',
            18 => 'Email not exist',
			19 => "Email or password invalid",
			50 => "Invalid Headers"
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}
