<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Password extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();    
		
		$this->load->helper('url');
		$this->load->database();
		$this->load->helper('file');
		$this->load->model('v1/ModelPassword');		
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
		
	public function reset_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);

		$headerData = $this->check_header();

		if(!$data['email'])
		{
			$this->_sendResponse(4);
		}
		else
		{										
			$result = $this->ModelPassword->reset_password($data['email']);
			
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
