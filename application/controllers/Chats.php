<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chats extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->model('Chats_model'); 
		$this->load->model('v1/ModelNotification');						
    }
	
	function isLoggedIn() 
	{
		if (!$this->session->userdata('id')) {
			redirect('/');
		}
		else
		{
			$role = $this->session->userdata('user_role');
			if($role == 'Accountant' || $role == 'Contractor')
			{
				redirect('/home/unauthorized');
			}
			else
			{
				$user_id = $this->session->userdata['id'];
				$role = $this->session->userdata['user_role'];

				if($role == 'Driver')
				{
					$user_id = 'U_'.$user_id.'_D';
				}
				else if($role == 'Dispatcher')
				{
					$user_id = 'U_'.$user_id.'_P';
				}
				else if($role == 'Accountant')
				{
					$user_id = 'U_'.$user_id.'_A';
				}
				else if($role == 'Administrator')
				{
					$user_id = 'U0';
				}
				else if($role == 'Contractor')
				{
					$user_id = 'U_'.$user_id.'_C';
				}

				return $user_id;
			}
		}		
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
		
	public function index($CHATID = 0)
	{	
		$user_id = $this->isLoggedIn();
		$mu_id = $this->session->userdata['mu_id'];

		$data['chat_user'] = $user_id;
		$data['drivers'] = $this->Chats_model->get_drivers($mu_id);
		$data['chats'] = $this->Chats_model->get_chats($mu_id, $user_id);

		$data['chat'] = '';
		if(isset($_POST['chatId']))
		{
			$data['chat'] = $this->Chats_model->fetch_chat($_POST['chatId']);	
			$data['chatId'] = $_POST['chatId'];
		}
		else if($CHATID != 0)
		{
			$data['chat'] = $this->Chats_model->fetch_chat($CHATID);
			$data['chatId'] = $CHATID;
		}
		else
		{
			$CHATID = $data['chats'][0]['chat_id'];

			$data['chat'] = $this->Chats_model->fetch_chat($CHATID);
			$data['chatId'] = $CHATID;
		}

		/*echo '<pre>';
		print_r($data);
		echo '</pre>';
		exit();*/
		
		$this->load->view('header');				
		$this->load->view('chats', $data);
		$this->load->view('footer');				
	}	

	public function create()
	{	
		if($_POST && sizeof($_POST) >= 2)
		{
			$user_id = $this->isLoggedIn();
			$mu_id = $this->session->userdata['mu_id'];
				
			$res = $this->Chats_model->create_chat($mu_id, $user_id, $_POST);

			$this->index($res);
		}
		else
		{
			redirect('/chats');
		}										
	}

	public function send_message()
	{
		if(!$this->session->userdata('id')) 
		{
			echo json_encode('0');
		}
		else
		{
			$user_id = $this->isLoggedIn();				
			$username = $this->session->userdata['name'];

			$data = $_POST;

			$result = $this->Chats_model->push_message($data, $user_id);			

			//send notification
			if($result != '2')		
			{
				$this->db->where('chat_id', $result['chat_id']);
				$chat = $this->db->get('tbl_chats')->result_array();

				if($chat)
				{
					$chat_users = explode(',', $chat[0]['chat_users']);

					$notification_data = $result;
					$notification_data['message'] = isset($result['msg_type']) && $result['msg_type'] == '2' ? 'Location' : $result['msg'];
					$notification_data['title'] = $username;

					foreach($chat_users as $c)
					{
						if($c != 'U0'){
							if(explode('_', $c)[2] == 'D' && $c != $result['chat_user'])
							{																								
								//Send notification
								$notification_data['driver_id']	= explode('_', $c)[1];
								$nres = $this->ModelNotification->send_user_notification($notification_data);							
							}
						}
					}
				}					
			}

			echo json_encode($result);
		}		
	}

	public function send_image()
	{
		if(!$this->session->userdata('id')) 
		{
			echo json_encode('0');
		}
		else
		{
			$user_id = $this->isLoggedIn();	
			$username = $this->session->userdata['name'];		

			$data = $_POST;

			//Image upload
			if(!empty($_FILES['file_img']['tmp_name']))
			{
				$today = date('Y-m-d');	
				$site_url = base_url();	
				$path_parts = pathinfo($_FILES['file_img']['name']);
				$extension = $path_parts['extension'];
				$new_file_name = 'CHAT_'.$user_id.'_'.$this->get_random_string().'_'.time().'.'.$extension; 
				
				$targetFolder = 'user_data/chat_data/';
									
				$target_file = $targetFolder.$new_file_name;
				
				if (move_uploaded_file($_FILES['file_img']['tmp_name'],$target_file))  // upload original file
				{	
					$data['msg'] = $new_file_name;
					$data['msg_type'] = '1';

					$result = $this->Chats_model->push_message($data, $user_id);

					//send notification
					if($result != '2')		
					{
						$this->db->where('chat_id', $result['chat_id']);
						$chat = $this->db->get('tbl_chats')->result_array();

						if($chat)
						{
							$chat_users = explode(',', $chat[0]['chat_users']);

							$notification_data = $result;
							$notification_data['message'] = isset($result['msg_type']) ? 'Image' : $result['msg'];
							$notification_data['title'] = $username;

							foreach($chat_users as $c)
							{
								if(explode('_', $c)[2] == 'D' && $c != $result['chat_user'])
								{																								
									//Send notification
									$notification_data['driver_id']	= explode('_', $c)[1];
									$nres = $this->ModelNotification->send_user_notification($notification_data);							
								}
							}
						}					
					}

					echo json_encode($result);
				}				
				else
				{
					echo json_encode('12');
				}
			}
			else
			{
				echo json_encode('11');
			}									
		}	
	}

	public function fetch_live_replies()
	{
		if(!$this->session->userdata('id')) 
		{
			echo json_encode('0');
		}
		else
		{
			$user_id = $this->isLoggedIn();			

			$data = $_POST;

			$res = $this->Chats_model->fetch_live_replies($data, $user_id);

			echo json_encode($res);
		}		
	}

	public function fetchlivereplies_ajax()
	{
		if(!$this->session->userdata('id')) 
		{
			echo json_encode('0');
		}
		else
		{
			$user_id = $this->isLoggedIn();			
			$data = $_POST;
			$mu_id = 1;
			$this->db->where('mu_id', $mu_id);
			$this->db->like('chat_users', $user_id, 'both');
			$r = $this->db->get('tbl_chats')->result_array();
			
			$result_c = array();
			for($i=0;$i<count($r);$i++)
			{
				$this->db->from( 'tbl_chat_messages' );
				$this->db->where( 'chat_id',$r[$i]['chat_id']) ;
				$this->db->where('sent', '0');
				$this->db->where( 'chat_user !=',$user_id) ;
				$this->db->order_by('msg_id', 'desc');
				$this->db->limit(1);
				$query = $this->db->get();
				if($query->num_rows() > 0)
				{
					$result = $query->row_array();
					$r[$i]['msg'] =	$result['msg_id'];
				}
				else
				{
					$r[$i]['msg'] = array();
					$r[$i]['msg'] =	'0';
				}
			}

			$msg_id = max(array_column($r, 'msg'));

			$this->db->from( 'tbl_chat_messages' );
			$this->db->where( 'msg_id',$msg_id) ;
			$query1 = $this->db->get();
			if($query1->num_rows() > 0)
			{
				$result1 = $query1->row_array();

				$exp = explode('_', $result1['chat_user']);	
				if($exp[2] == 'D')
				{					
					$this->db->where('tbl_drivers.driver_id', $exp[1]);
					$d = $this->db->get('tbl_drivers')->row_array();
					$result1['chat_user_name'] = $d['driver_name'];
				}
				else if($exp[2] == 'P')
				{
					$this->db->where('du_id', $exp[1]);
					$d = $this->db->get('tbl_dispatch_user')->row_array();
					$result1['chat_user_name'] = $d['driver_name'];
				}
				else
				{
					$result1['chat_user_name'] = $d['chat_user'];
				}
				$this->db->where('msg_id', $msg_id);
				$this->db->set('sent','1',false);
				$this->db->update('tbl_chat_messages');
			}
			else
			{
				$result1 = null;
			}
			echo json_encode($result1);
			/*echo '<pre>';
			print_r($result1);
			echo '</pre>';
			exit();
			$to = count($result_c);
			echo '<pre>';
			print_r($j);
			echo '</pre>';
			exit();*/
		}		
	}

	public function countunseen()
	{
		$user_id = 'U_90_D';
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
		echo '<pre>';
		print_r($j);
		echo '</pre>';
		exit();
		/*$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',94);
		$this->db->where( 'seen',0);
		$this->db->where( 'chat_user !=',$user_id) ;
		$query = $this->db->get();
		$result_c = $query->result_array();
		for($k=0;$k<count($result_c);$k++)
		{
			$unseen['seen'] = '1';
			$this->db->where('msg_id', $result_c[$k]['msg_id']);
			$this->db->update('tbl_chat_messages', $unseen);
		}*/
	}
}
