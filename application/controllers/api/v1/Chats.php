<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Chats extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();    
		
		$this->load->helper('url');
		$this->load->database();
		$this->load->helper('file');
		$this->load->model('v1/ModelChat');
		$this->load->model('v1/ModelNotification'); 
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
		
	public function get_drivers_post()
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
			$res = $this->is_authorised($data['driver_id'], $data['remember_token']);
						
			$result = $this->ModelChat->fetch_drivers($data, $res['mu_id']);
			
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
	
	public function get_dispatchers_post()
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
			$res = $this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelChat->fetch_dispatchers($data, $res['mu_id']);			
			
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
    
    public function get_chats_post()
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
			$res = $this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelChat->fetch_chats($data, $res['mu_id']);
			
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
	
	public function fetch_messages_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['chat_id'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised($data['driver_id'], $data['remember_token']);
			$user_id = 'U_'.$data['driver_id'].'_D';
			
			$result = $this->ModelChat->fetch_chat($data['chat_id'], $user_id);

			$result = array('success'=>true, 'status_code'=>1, 'message'=>'Success', 'data'=>$result);
			
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

    public function create_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['chat_users'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$res = $this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelChat->create_chat($data, $res['mu_id']);
			
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

    public function push_message_post()
    {
		$headerData = $this->check_header();
		
        if(!$this->post('driver_id') || !$this->post('remember_token') || !$this->post('chat_id'))
		{
			$this->_sendResponse(4);
		}
		else
		{
			$data = $_POST;        
			
			$data['remember_token'] = $headerData;
			/*echo "<pre>";
             print_r($data);
             echo "</pre>";
             exit();*/
            $res = $this->is_authorised($data['driver_id'], $data['remember_token']);
                                    
            $idata['chat_id'] = $data['chat_id'];
            $idata['chat_user'] = 'U_'.$data['driver_id'].'_D';
            $idata['msg'] = $data['msg'];
            $idata['msg_type'] = $data['msg_type'];
             /*echo "<pre>";
             print_r($idata);
             echo "</pre>";
             exit();*/
            if($data['msg_type'] == '1')
            {
                //Image upload if msg_type == 1
                if(!empty($_FILES['file']['tmp_name']))
                {											
                    $path_parts = pathinfo($_FILES['file']['name']);
                    $extension = $path_parts['extension'];
                    $new_file_name = 'CHAT_'.$idata['chat_user'].'_'.$this->ModelChat->get_random_string().'_'.time().'.'.$extension; 
                    
                    $targetFolder = 'user_data/chat_data/';
                                        
                    $target_file = $targetFolder.$new_file_name;
                    
                    if (move_uploaded_file($_FILES['file']['tmp_name'],$target_file))  // upload original file
                    {	                        			
                        $idata['msg'] = $new_file_name;                                                
                    }				
                    else
                    {
                        $this->_sendResponse(5);
                    }
                }
                else
                {
                    $this->_sendResponse(5);
                }
            }
			
			$result = $this->ModelChat->push_message($idata);
			
			if($result)
			{	
				//send notification
				if($result['success'] == true)		
				{
					$this->db->where('chat_id', $idata['chat_id']);
					$chat = $this->db->get('tbl_chats')->result_array();

					if($chat)
					{
						$chat_users = explode(',', $chat[0]['chat_users']);

						$notification_data = $result['data'];
						$notification_data['message'] = $result['data']['msg_type'] == '1' ? 'Image' : $result['data']['msg'];
						$notification_data['title'] = $res['driver_name'];

						foreach($chat_users as $c)
						{
							$exp = explode('_', $c);
							if(sizeof($exp) > 2){
								if($exp[2] == 'D' && $c != $idata['chat_user'])
								{																								
									//Send notification
									$notification_data['driver_id']	= explode('_', $c)[1];
									$nres = $this->ModelNotification->send_user_notification($notification_data);								
								}

								if($exp[2] == 'P' && $c != $idata['chat_user'])
								{																								
									//Send notification
									$notification_data['du_id']	= explode('_', $c)[1];
									$nres = $this->ModelNotification->send_user_notification_($notification_data);								
								}
							}
						}
					}					
				}

				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}
	}
	
	public function fetch_new_msg_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['chat_id'] || !$data['msg_id'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelChat->fetch_new_messages($data);
			
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
	
	public function load_chat_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['driver_id'] || !$data['remember_token'] || !$data['chat_id'] || !$data['msg_id'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised($data['driver_id'], $data['remember_token']);
			
			$result = $this->ModelChat->load_chat($data);
			
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


	
	// Dispatcher APIs -----------------------------------------------------------------

	public function get_drivers_du_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['du_id'] || !$data['remember_token'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$res = $this->is_authorised_($data['du_id'], $data['remember_token']);
			
			$result = $this->ModelChat->fetch_drivers($data, $res['mu_id'], 0);
			
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
    
    public function get_chats_du_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['du_id'] || !$data['remember_token'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$res = $this->is_authorised_($data['du_id'], $data['remember_token']);
			
			$result = $this->ModelChat->fetch_chats_du($data, $res['mu_id']);
			
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
	
	public function fetch_messages_du_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['du_id'] || !$data['remember_token'] || !$data['chat_id'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised_($data['du_id'], $data['remember_token']);
			$user_id = 'U_'.$data['du_id'].'_P';
			
			$result = $this->ModelChat->fetch_chat_du($data['chat_id'], $user_id);

			$result = array('success'=>true, 'status_code'=>1, 'message'=>'Success', 'data'=>$result);
			
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

    public function create_du_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['du_id'] || !$data['remember_token'] || !$data['chat_users'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$res = $this->is_authorised_($data['du_id'], $data['remember_token']);
			
			$result = $this->ModelChat->create_chat_du($data, $res['mu_id']);
			
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

    public function push_message_du_post()
    {
		$headerData = $this->check_header();
		
        if(!$this->post('du_id') || !$this->post('remember_token') || !$this->post('chat_id'))
		{
			$this->_sendResponse(4);
		}
		else
		{
			$data = $_POST;        
			
			$data['remember_token'] = $headerData;

            $res = $this->is_authorised_($data['du_id'], $data['remember_token']);
                                    
            $idata['chat_id'] = $data['chat_id'];
            $idata['chat_user'] = 'U_'.$data['du_id'].'_P';
            $idata['msg'] = $data['msg'];
            $idata['msg_type'] = $data['msg_type'];
            
            if($data['msg_type'] == '1')
            {
                //Image upload if msg_type == 1
                if(!empty($_FILES['file']['tmp_name']))
                {											
                    $path_parts = pathinfo($_FILES['file']['name']);
                    $extension = $path_parts['extension'];
                    $new_file_name = 'CHAT_'.$idata['chat_user'].'_'.$this->ModelChat->get_random_string().'_'.time().'.'.$extension; 
                    
                    $targetFolder = 'user_data/chat_data/';
                                        
                    $target_file = $targetFolder.$new_file_name;
                    
                    if (move_uploaded_file($_FILES['file']['tmp_name'],$target_file))  // upload original file
                    {	                        			
                        $idata['msg'] = $new_file_name;                                                
                    }				
                    else
                    {
                        $this->_sendResponse(5);
                    }
                }
                else
                {
                    $this->_sendResponse(5);
                }
            }
			
			$result = $this->ModelChat->push_message_du($idata);
			
			if($result)
			{	
				//send notification
				if($result['success'] == true)		
				{
					$this->db->where('chat_id', $idata['chat_id']);
					$chat = $this->db->get('tbl_chats')->result_array();

					if($chat)
					{
						$chat_users = explode(',', $chat[0]['chat_users']);

						$notification_data = $result['data'];
						$notification_data['message'] = $result['data']['msg_type'] == '1' ? 'Image' : $result['data']['msg'];
						$notification_data['title'] = $res['du_name'];

						foreach($chat_users as $c)
						{
							$exp = explode('_', $c);
							if(sizeof($exp) > 2){
								if($exp[2] == 'D' && $c != $idata['chat_user'])
								{																								
									//Send notification
									$notification_data['driver_id']	= explode('_', $c)[1];
									$nres = $this->ModelNotification->send_user_notification($notification_data);								
								}

								if($exp[2] == 'P' && $c != $idata['chat_user'])
								{																								
									//Send notification
									$notification_data['du_id']	= explode('_', $c)[1];
									$nres = $this->ModelNotification->send_user_notification_($notification_data);								
								}
							}
						}
					}					
				}

				$this->response($result, 200); // 200 being the HTTP response code
			}
			else
			{
				$result = array('status_code'=>false,'status_code'=>0,'message'=>'no response model'); 
				$this->response($result, 404);
			}
		}	
	}
	
	public function fetch_new_msg_du_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['du_id'] || !$data['remember_token'] || !$data['chat_id'] || !$data['msg_id'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised_($data['du_id'], $data['remember_token']);
			
			$result = $this->ModelChat->fetch_new_messages_du($data);
			
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
	
	public function load_chat_du_post()
    {
		$data = json_decode(file_get_contents('php://input'), true);
		
		$headerData = $this->check_header();

		$data['remember_token'] = $headerData;

		if(!$data['du_id'] || !$data['remember_token'] || !$data['chat_id'] || !$data['msg_id'])
		{
			$this->_sendResponse(4);
		}
		else
		{	
			$this->is_authorised_($data['du_id'], $data['remember_token']);
			
			$result = $this->ModelChat->load_chat_du($data);
			
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
        /*echo "<pre>";
             print_r($result);
             echo "</pre>";
             exit();*/
        return $result[0];
	}

	private function is_authorised_($du_id, $remember_token)
	{
		$this->db->select('*');
		$this->db->from('tbl_dispatch_user');
		$where = ' du_id = "'.$du_id.'" AND remember_token = "'.$remember_token.'" ';
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
