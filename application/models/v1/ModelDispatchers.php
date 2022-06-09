<?php
class ModelDispatchers extends CI_Model {
	
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->helper('url');
    }				

    public function badge_count($data)
    {
    	$user_id = 'U_'.$data['du_id'].'_P';
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
		$to = count($result_c);
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
		$this->db->where('du_email', $data['du_email']);	
		$this->db->where('du_password', $data['du_password']);	
	// $this->db->where('du_password', md5($data['du_password']));	
		$this->db->where('is_active', '1');	
		$this->db->where('is_archieved', '0');	
		$this->db->where('is_removed', '0');	
		$rs = $this->db->get('tbl_dispatch_user');

		if($rs->num_rows() == 1)
		{
			$d = $rs->result_array()[0];
			
			$udata = array(												
				//'remember_token'=>$this->get_random_string(25),
				'device_token'=>$data['device_token'],
				'device_type'=>$data['device_type']
			);

			$this->db->where ('du_id', $d['du_id']);
			$this->db->update ('tbl_dispatch_user', $udata);
			
			$this->db->where ('du_id', $d['du_id']);
			$return = $this->db->get ('tbl_dispatch_user')->result_array();			

			$result = array('success'=>true, 'status_code'=>1, 'message'=>'Login Successful!', 'data'=>$return[0]);
		}	
		else
		{
			$result = array('success'=>false, 'status_code'=>0, 'message'=>'You are not authorized for login to this Application! Please contact Administrator.', 'data'=>NULL);
		}

		return $result;
	}		
}
?>
