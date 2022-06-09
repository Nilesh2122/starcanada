<?php
class ModelChat extends CI_Model {
	
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->helper('url');
    }				

	public function get_random_string($length = 10) {
		
			$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
			$token = "";
			$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < $length; $i++) {
				$n = rand(0, $alphaLength);
				$token.= $alphabet[$n];
			}
			return $token;
	}
	
	public function fetch_dispatchers($data, $mu_id)
	{
		$this->db->where('mu_id', $mu_id);	
		$this->db->where('is_removed', 0);										
		$d = $this->db->get('tbl_dispatch_user')->result_array();

		$result = array('success'=>true, 'status_code'=>1, 'message'=>$d ? 'Dispatchers found!' : 'Dispatchers not found!', 'data'=>$d);				

		return $result;
	}
	
	public function fetch_drivers($data, $mu_id, $driver = 1)
	{
		$this->db->where('mu_id', $mu_id);
		$this->db->where('is_removed', 0);		
		if($driver == 1){
			$this->db->where('driver_id !=', $data['driver_id']);
		}
		$d = $this->db->get('tbl_drivers')->result_array();

		$result = array('success'=>true, 'status_code'=>1, 'message'=>$d ? 'Drivers found!' : 'Drivers not found!', 'data'=>$d);				

		return $result;
	}	

	public function fetch_chats($data, $mu_id)
	{
		$user_id = 'U_'.$data['driver_id'].'_D';

		$this->db->where('mu_id', $mu_id);
		$this->db->like('chat_users', $user_id, 'both');
		$r = $this->db->get('tbl_chats')->result_array();

		$return = array();
		$chat_role = '';
		$seen_status = '';

		foreach($r as $key)
		{
			$this->db->select( '*' );
			$this->db->from( 'tbl_chat_messages' );
			$this->db->where( 'chat_id',$key['chat_id']) ;
			$this->db->order_by('msg_id', 'desc');
			$this->db->limit(1);
			$query = $this->db->get();
			$result_c = $query->row_array();
			$key['seen_unseen'] = json_decode($result_c['seen_unseen']);
			$key['created_at'] = $result_c['created_at'];
			
			if($key['seen_unseen'] != NULL)
			{
				for($j=0;$j<count($key['seen_unseen']);$j++)
				{
					if($key['seen_unseen'][$j]->user == $user_id && $key['seen_unseen'][$j]->status == '1')
					{
						$seen_status = '1';
					}
				}
			}
			else
			{
				$seen_status = '0';
			}
			$key['seen_status'] = $seen_status;
			//$key['seen_status'] = $result_c['seen'];
			$expl = explode(',', $key['chat_users']);			
			
			$i = 0;
			$users = array();
			foreach($expl as $val)
			{				
				$i++;								
				if($val != $user_id)
				{
					$exp = explode('_', $val);
					if(sizeof($exp) > 2){
						if($exp[2] == 'D')
						{
							$this->db->select('tbl_drivers.*, tbl_equipments.eq_unit');						
							$this->db->join('tbl_equipments', 'tbl_equipments.eq_id = tbl_drivers.eq_id');
							$this->db->where('driver_id', $exp[1]);
							$r = $this->db->get('tbl_drivers')->result_array();

							$users[] = sizeof($r) == 1 ? $r[0]['eq_unit'].' - '.$r[0]['driver_name'] : 'Driver';	
							$chat_role = 'Driver';
						}
						else if($exp[2] == 'P')
						{
							$this->db->where('du_id', $exp[1]);
							$r = $this->db->get('tbl_dispatch_user')->result_array();

							$users[] = sizeof($r) == 1 ? $r[0]['du_name'] : 'Dispatcher';
							$chat_role = 'Dispatcher';					
						}
						else if($exp[2] == 'A')
						{
							$this->db->where('acu_id', $exp[1]);
							$r = $this->db->get('tbl_accounting_user')->result_array();

							$users[] = sizeof($r) == 1 ? $r[0]['acu_name'] : 'Accountant';
							$chat_role = 'Accountant';
						}
						else if($exp[2] == 'C')
						{
							$this->db->where('con_id', $exp[1]);
							$r = $this->db->get('tbl_contractors')->result_array();

							$users[] = sizeof($r) == 1 ? $r[0]['con_name'] : 'Contractor';
							$chat_role = 'Contractor';
						}
					}						
				}
			}			

			$key['chat_title'] = implode(',', $users);

			if($i > 2)
			{
				
				$key['chat_role'] = 'Group';
			}
			else
			{				
				$key['chat_role'] = $chat_role;
			}
			if($key['created_at'] == NULL)
			{
				$key['seen_status'] = '1';
			}
			$return[] = $key;
		}
		foreach ($return as $key => $row) {
		    $distance[$key] = $row['created_at'];
		}

		array_multisort($distance, SORT_ASC, $return);
		
		$result = array('success'=>true, 'status_code'=>1, 'message'=>$return ? 'Chats found!' : 'No chats found!', 'data'=>$return);

		return $result;
	}

	public function create_chat($data, $mu_id)
	{
		$user_id = 'U_'.$data['driver_id'].'_D';

		//chat users coma seprated to array()
		$data['chat_users'] = explode(',', $data['chat_users']);
		//making all element to chat user id formated
		$formated = array();
		foreach($data['chat_users'] as $key)
		{
			$formated[] = 'U_'.$key.'_P';
		}
		$data['chat_users'] = $formated;

		$msg = 'Something went wrong!';
		$returndata = '';

		if(sizeof($data['chat_users']) == 1)
		{
			//Conditions
			$c1 = trim($user_id).','.trim($data['chat_users'][0]);
			$c2 = trim($data['chat_users'][0]).','.trim($user_id);	

			$this->db->where('mu_id', $mu_id);
			$this->db->where('chat_users', $c1, 'both');
			$this->db->or_where('chat_users', $c2, 'both');
			$q = $this->db->get('tbl_chats');

			if($q->num_rows() == 1)
			{
				$r = $q->result_array();
				$returndata = $this->fetch_chat($r[0]['chat_id'], $user_id); //allready exist (2 person)
				$msg = 'Chat allready exist!';
			}
			else
			{
				date_default_timezone_set('America/Edmonton');
				$idata['mu_id'] = $mu_id;
				$idata['chat_users'] = trim($user_id).','.trim($data['chat_users'][0]);
				$idata['chat_name'] = $data['chat_name'];
				$idata['created_at'] = date('Y-m-d H:i:s');

				//insert data to create chat				
				$this->db->insert('tbl_chats', $idata);
				$iid = $this->db->insert_id();
				
				$returndata = $this->fetch_chat($iid, $user_id); //created for 2 person
				$msg = 'Chat created successfully!';
			}
		}
		else
		{
			//Multiusers (Group)
			$idata['mu_id'] = $mu_id;
			$idata['chat_users'] = trim($user_id).','.trim(implode(',', $data['chat_users']));
			$idata['chat_name'] = $data['chat_name'];
			$idata['created_at'] = date('Y-m-d H:i:s');

			//insert data to create chat				
			$this->db->insert('tbl_chats', $idata);
			$iid = $this->db->insert_id();

			$returndata = $this->fetch_chat($iid, $user_id); //created for group
			$msg = 'Group created successfully!';
		}	

		return array('success'=>true, 'status_code'=>1, 'message'=>$msg, 'data'=>$returndata);
	}

	public function fetch_chat($chat_id, $chat_user)
	{
		$this->db->select( '*' );
		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$chat_id);
		$this->db->order_by('msg_id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result_c = $query->row_array();
		$msg_id = $result_c['msg_id'];
		$seen_unseen = json_decode($result_c['seen_unseen']);

		for($i=0;$i<count($seen_unseen);$i++)
		{
			if($seen_unseen[$i]->user == $chat_user)
			{
				$seen_unseen[$i]->status = '1';
			}
		}

		$data_seen_unseen['seen'] = '1';
		$data_seen_unseen['seen_unseen'] = json_encode(($seen_unseen));
		$this->db->where('msg_id', $msg_id);
		$this->db->update('tbl_chat_messages', $data_seen_unseen);

		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$chat_id) ;
		$this->db->where( 'chat_user !=',$chat_user) ;
		$query = $this->db->get();
		$result_c = $query->result_array();
		for($k=0;$k<count($result_c);$k++)
		{
			if(isset($result_c[$k]['msg_id']))
			{
				$id = $result_c[$k]['msg_id'];
				$unseen['seen'] = '1';
				$this->db->where('msg_id', $id);
				$this->db->update('tbl_chat_messages', $unseen);
			}
		}

		$this->db->where('chat_id', $chat_id);
		$return['chat'] = $this->db->get('tbl_chats')->result_array();		

		if($return['chat'])
		{
			$return['chat'] = $return['chat'][0];

			$chat_users = explode(',', $return['chat']['chat_users']);

			$usersinfo = array();
			$usersinfo['U0']['name'] = 'Admin';
			$usersinfo['U0']['role'] = 'Administrator';
			foreach($chat_users as $key)
			{
				$exp = explode('_', $key);
				if(sizeof($exp) > 2){
					if($exp[2] == 'D')
					{
						$this->db->select('tbl_drivers.*, tbl_equipments.eq_unit');						
						$this->db->join('tbl_equipments', 'tbl_equipments.eq_id = tbl_drivers.eq_id');
						$this->db->where('driver_id', $exp[1]);
						$r = $this->db->get('tbl_drivers')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['eq_unit'].' - '.$r[0]['driver_name'] : 'Driver';
						$usersinfo[$key]['role'] = 'Driver';					
					}
					else if($exp[2] == 'P')
					{
						$this->db->where('du_id', $exp[1]);
						$r = $this->db->get('tbl_dispatch_user')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['du_name'] : 'Dispatcher';
						$usersinfo[$key]['role'] = 'Dispatcher';
					}
					else if($exp[2] == 'A')
					{
						$this->db->where('acu_id', $exp[1]);
						$r = $this->db->get('tbl_accounting_user')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['acu_name'] : 'Accountant';
						$usersinfo[$key]['role'] = 'Accountant';					
					}
					else if($exp[2] == 'C')
					{
						$this->db->where('con_id', $exp[1]);
						$r = $this->db->get('tbl_contractors')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['con_name'] : 'Contractor';
						$usersinfo[$key]['role'] = 'Contractor';
					}	
				}			
			}

			$return['usersinfo'] = $usersinfo;

			$this->db->where('chat_id', $return['chat']['chat_id']);
			$this->db->order_by('msg_id', 'desc');
			$this->db->limit(10);
			$return['messages'] = array_reverse($this->db->get('tbl_chat_messages')->result_array());
		}

		//set chat role and title
		$i = 0;
		$users = array();
		$chat_role = '';
		$chat_title = '';
		foreach($return['usersinfo'] as $key=>$val){
			$i++;
			if($chat_user != $key && $key != 'U0')
			{
				$users[] = $val['name'];
				$chat_role = $val['role'];
			}           
		}
		$chat_title = implode(',', $users);
		if($i > 3)
		{  
			$chat_role = 'Group';
		}
		$return['chat']['chat_title'] = $chat_title;
		$return['chat']['chat_role'] = $chat_role;

		return $return;
	}

	public function push_message($idata)
	{				
		$this->db->select( '*' );
		$this->db->from( 'tbl_chats' );
		$this->db->where( 'chat_id',$idata['chat_id']);
		$query = $this->db->get();
		$result_c = $query->row_array();
		$str = $result_c['chat_users'];
	
		$dat = explode(",",$str);
		$data = array();
		for($i=0;$i<count($dat);$i++)
		{
			$data[$i]['user'] = $dat[$i];
			if($dat[$i] == $idata['chat_user'])
			{
				$data[$i]['status'] = '1';
			}
			else
			{
				$data[$i]['status'] = '0';
			}
			
		}
		$idata['seen_unseen'] = json_encode($data);

		date_default_timezone_set('America/Edmonton');
		$idata['created_at'] = date('Y-m-d H:i:s');
		$idata['seen'] = '0';

		$this->db->insert('tbl_chat_messages', $idata);

		if($this->db->affected_rows() == 1)
		{				
			return array('success'=>true, 'status_code'=>1, 'message'=>'Message submited!', 'data'=>$idata);
		}
		else
		{
			return array('success'=>false, 'status_code'=>0, 'message'=>'Failed to submit message!', 'data'=>null);
		}			
	}

	public function fetch_new_messages($data)
	{
		$user_id = 'U_'.$data['driver_id'].'_D';

		$this->db->select( '*' );
		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$data['chat_id']);
		$this->db->order_by('msg_id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result_c = $query->row_array();
		$msg_id = $result_c['msg_id'];
		$seen_unseen = json_decode($result_c['seen_unseen']);

		for($i=0;$i<count($seen_unseen);$i++)
		{
			if($seen_unseen[$i]->user == $user_id)
			{
				$seen_unseen[$i]->status = '1';
			}
		}
		//$data_seen_unseen['seen'] = '1';
		$data_seen_unseen['seen_unseen'] = json_encode(($seen_unseen));
		$this->db->where('msg_id', $msg_id);
		$this->db->update('tbl_chat_messages', $data_seen_unseen);

		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$data['chat_id']) ;
		$this->db->where( 'chat_user !=',$user_id);
		$query = $this->db->get();
		$result_c = $query->result_array();
		for($k=0;$k<count($result_c);$k++)
		{
			if(isset($result_c[$k]['msg_id']))
			{
				$id = $result_c[$k]['msg_id'];
				$unseen['seen'] = '1';
				$this->db->where('msg_id', $id);
				$this->db->update('tbl_chat_messages', $unseen);
			}
		}

		$this->db->where('chat_id', $data['chat_id']);			
		$this->db->where('msg_id >', $data['msg_id']);
		//$this->db->where('chat_user !=', $user_id);
		$messages = $this->db->get('tbl_chat_messages')->result_array();		
		
		return array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>$messages);
	}

	public function load_chat($data)
	{
		$user_id = 'U_'.$data['driver_id'].'_D';

		$this->db->select( '*' );
		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$data['chat_id']);
		$this->db->order_by('msg_id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result_c = $query->row_array();
		$msg_id = $result_c['msg_id'];
		$seen_unseen = json_decode($result_c['seen_unseen']);

		for($i=0;$i<count($seen_unseen);$i++)
		{
			if($seen_unseen[$i]->user == $user_id)
			{
				$seen_unseen[$i]->status = '1';
			}
		}
		$data_seen_unseen['seen'] = '1';
		$data_seen_unseen['seen_unseen'] = json_encode(($seen_unseen));
		$this->db->where('msg_id', $msg_id);
		$this->db->update('tbl_chat_messages', $data_seen_unseen);

		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$data['chat_id']);
		$this->db->where( 'seen',0);
		$this->db->where( 'chat_user !=',$user_id) ;
		$query = $this->db->get();
		$result_c = $query->result_array();
		for($k=0;$k<count($result_c);$k++)
		{
			$unseen['seen'] = '1';
			$this->db->where('msg_id', $result_c[$k]['msg_id']);
			$this->db->update('tbl_chat_messages', $unseen);
		}


		$this->db->where('chat_id', $data['chat_id']);			
		$this->db->where('msg_id <', $data['msg_id']);
		$this->db->limit(10);
		$messages = $this->db->get('tbl_chat_messages')->result_array();		
		
		return array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>$messages);
	}


	// Dispatchers APIs ---------------------------------------------------------

	public function fetch_chats_du($data, $mu_id)
	{
		$user_id = 'U_'.$data['du_id'].'_P';

		$this->db->where('mu_id', $mu_id);
		$this->db->like('chat_users', $user_id, 'both');
		$r = $this->db->get('tbl_chats')->result_array();

		$return = array();
		$chat_role = '';
		$seen_status = '';
		foreach($r as $key)
		{
			$this->db->select( '*' );
			$this->db->from( 'tbl_chat_messages' );
			$this->db->where( 'chat_id',$key['chat_id']) ;
			$this->db->order_by('msg_id', 'desc');
			$this->db->limit(1);
			$query = $this->db->get();
			$result_c = $query->row_array();
			$key['created_at'] = $result_c['created_at'];
			$key['seen_unseen'] = json_decode($result_c['seen_unseen']);
			if($key['seen_unseen'] != NULL)
			{
				for($j=0;$j<count($key['seen_unseen']);$j++)
				{
					if($key['seen_unseen'][$j]->user == $user_id && $key['seen_unseen'][$j]->status == '1')
					{
						$seen_status = '1';
					}
				}
			}
			else
			{
				$seen_status = '0';
			}
			$key['seen_status'] = $seen_status;
			//$key['seen_status'] = $result_c['seen'];
			$expl = explode(',', $key['chat_users']);			
			
			$i = 0;
			$users = array();
			foreach($expl as $val)
			{				
				$i++;								
				if($val != $user_id)
				{
					$exp = explode('_', $val);
					if(sizeof($exp) > 2){
						if($exp[2] == 'D')
						{
							$this->db->select('tbl_drivers.*, tbl_equipments.eq_unit');						
							$this->db->join('tbl_equipments', 'tbl_equipments.eq_id = tbl_drivers.eq_id');
							$this->db->where('driver_id', $exp[1]);
							$r = $this->db->get('tbl_drivers')->result_array();

							$users[] = sizeof($r) == 1 ? $r[0]['eq_unit'].' - '.$r[0]['driver_name'] : 'Driver';	
							$chat_role = 'Driver';
						}
						else if($exp[2] == 'P')
						{
							$this->db->where('du_id', $exp[1]);
							$r = $this->db->get('tbl_dispatch_user')->result_array();

							$users[] = sizeof($r) == 1 ? $r[0]['du_name'] : 'Dispatcher';
							$chat_role = 'Dispatcher';					
						}
						else if($exp[2] == 'A')
						{
							$this->db->where('acu_id', $exp[1]);
							$r = $this->db->get('tbl_accounting_user')->result_array();

							$users[] = sizeof($r) == 1 ? $r[0]['acu_name'] : 'Accountant';
							$chat_role = 'Accountant';
						}
						else if($exp[2] == 'C')
						{
							$this->db->where('con_id', $exp[1]);
							$r = $this->db->get('tbl_contractors')->result_array();

							$users[] = sizeof($r) == 1 ? $r[0]['con_name'] : 'Contractor';
							$chat_role = 'Contractor';
						}
					}						
				}
			}			

			$key['chat_title'] = implode(',', $users);

			if($i > 2)
			{				
				$key['chat_role'] = 'Group';
			}
			else
			{				
				$key['chat_role'] = $chat_role;
			}
			if($key['created_at'] == NULL)
			{
				$key['seen_status'] = '1';
			}
			$return[] = $key;
		}
		
		foreach ($return as $key => $row) {
		    $distance[$key] = $row['created_at'];
		}

		array_multisort($distance, SORT_ASC, $return);

		$result = array('success'=>true, 'status_code'=>1, 'message'=>$return ? 'Chats found!' : 'No chats found!', 'data'=>$return);

		return $result;
	}

	public function fetch_chat_du($chat_id, $chat_user)
	{
		$this->db->select( '*' );
		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$chat_id);
		$this->db->order_by('msg_id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result_c = $query->row_array();
		$msg_id = $result_c['msg_id'];
		$seen_unseen = json_decode($result_c['seen_unseen']);
		for($i=0;$i<count($seen_unseen);$i++)
		{
			if($seen_unseen[$i]->user == $chat_user)
			{
				$seen_unseen[$i]->status = '1';
			}
		}
		$data_seen_unseen['seen'] = '1';
		$data_seen_unseen['seen_unseen'] = json_encode(($seen_unseen));

		$this->db->where('msg_id', $msg_id);
		$this->db->update('tbl_chat_messages', $data_seen_unseen);

		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$chat_id) ;
		$this->db->where( 'chat_user !=',$chat_user) ;
		$query = $this->db->get();
		$result_c = $query->result_array();
		for($k=0;$k<count($result_c);$k++)
		{
			if(isset($result_c[$k]['msg_id']))
			{
				$id = $result_c[$k]['msg_id'];
				$unseen['seen'] = '1';
				$this->db->where('msg_id', $id);
				$this->db->update('tbl_chat_messages', $unseen);
			}
		}

		$this->db->where('chat_id', $chat_id);
		$return['chat'] = $this->db->get('tbl_chats')->result_array();		

		if($return['chat'])
		{
			$return['chat'] = $return['chat'][0];

			$chat_users = explode(',', $return['chat']['chat_users']);

			$usersinfo = array();
			$usersinfo['U0']['name'] = 'Admin';
			$usersinfo['U0']['role'] = 'Administrator';
			foreach($chat_users as $key)
			{
				$exp = explode('_', $key);
				if(sizeof($exp) > 2){
					if($exp[2] == 'D')
					{
						$this->db->select('tbl_drivers.*, tbl_equipments.eq_unit');						
						$this->db->join('tbl_equipments', 'tbl_equipments.eq_id = tbl_drivers.eq_id');
						$this->db->where('driver_id', $exp[1]);
						$r = $this->db->get('tbl_drivers')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['eq_unit'].' - '.$r[0]['driver_name'] : 'Driver';
						$usersinfo[$key]['role'] = 'Driver';					
					}
					else if($exp[2] == 'P')
					{
						$this->db->where('du_id', $exp[1]);
						$r = $this->db->get('tbl_dispatch_user')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['du_name'] : 'Dispatcher';
						$usersinfo[$key]['role'] = 'Dispatcher';
					}
					else if($exp[2] == 'A')
					{
						$this->db->where('acu_id', $exp[1]);
						$r = $this->db->get('tbl_accounting_user')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['acu_name'] : 'Accountant';
						$usersinfo[$key]['role'] = 'Accountant';					
					}
					else if($exp[2] == 'C')
					{
						$this->db->where('con_id', $exp[1]);
						$r = $this->db->get('tbl_contractors')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['con_name'] : 'Contractor';
						$usersinfo[$key]['role'] = 'Contractor';
					}	
				}			
			}

			$return['usersinfo'] = $usersinfo;

			$this->db->where('chat_id', $return['chat']['chat_id']);
			$this->db->order_by('msg_id', 'desc');
			$this->db->limit(10);
			$return['messages'] = array_reverse($this->db->get('tbl_chat_messages')->result_array());
		}

		//set chat role and title
		$i = 0;
		$users = array();
		$chat_role = '';
		$chat_title = '';
		foreach($return['usersinfo'] as $key=>$val){
			$i++;
			if($chat_user != $key && $key != 'U0')
			{
				$users[] = $val['name'];
				$chat_role = $val['role'];
			}           
		}
		$chat_title = implode(',', $users);
		if($i > 3)
		{  
			$chat_role = 'Group';
		}
		$return['chat']['chat_title'] = $chat_title;
		$return['chat']['chat_role'] = $chat_role;

		return $return;
	}

	public function create_chat_du($data, $mu_id)
	{
		$user_id = 'U_'.$data['du_id'].'_P';

		//chat users coma seprated to array()
		$data['chat_users'] = explode(',', $data['chat_users']);
		//making all element to chat user id formated
		$formated = array();
		foreach($data['chat_users'] as $key)
		{
			$formated[] = 'U_'.$key.'_D';
		}
		$data['chat_users'] = $formated;

		$msg = 'Something went wrong!';
		$returndata = '';

		if(sizeof($data['chat_users']) == 1)
		{
			//Conditions
			$c1 = trim($user_id).','.trim($data['chat_users'][0]);
			$c2 = trim($data['chat_users'][0]).','.trim($user_id);	

			$this->db->where('mu_id', $mu_id);
			$this->db->where('chat_users', $c1, 'both');
			$this->db->or_where('chat_users', $c2, 'both');
			$q = $this->db->get('tbl_chats');

			if($q->num_rows() == 1)
			{
				$r = $q->result_array();
				$returndata = $this->fetch_chat_du($r[0]['chat_id'], $user_id); //allready exist (2 person)
				$msg = 'Chat allready exist!';
			}
			else
			{
				$idata['mu_id'] = $mu_id;
				$idata['chat_users'] = trim($user_id).','.trim($data['chat_users'][0]);
				$idata['chat_name'] = $data['chat_name'];
				$idata['created_at'] = date('Y-m-d H:i:s');

				//insert data to create chat				
				$this->db->insert('tbl_chats', $idata);
				$iid = $this->db->insert_id();
				
				$returndata = $this->fetch_chat_du($iid, $user_id); //created for 2 person
				$msg = 'Chat created successfully!';
			}
		}
		else
		{
			//Multiusers (Group)
			$idata['mu_id'] = $mu_id;
			$idata['chat_users'] = trim($user_id).','.trim(implode(',', $data['chat_users']));
			$idata['chat_name'] = $data['chat_name'];
			$idata['created_at'] = date('Y-m-d H:i:s');

			//insert data to create chat				
			$this->db->insert('tbl_chats', $idata);
			$iid = $this->db->insert_id();

			$returndata = $this->fetch_chat_du($iid, $user_id); //created for group
			$msg = 'Group created successfully!';
		}	

		return array('success'=>true, 'status_code'=>1, 'message'=>$msg, 'data'=>$returndata);
	}

	public function push_message_du($idata)
	{				
		$this->db->select( '*' );
		$this->db->from( 'tbl_chats' );
		$this->db->where( 'chat_id',$idata['chat_id']);
		$query = $this->db->get();
		$result_c = $query->row_array();
		$str = $result_c['chat_users'];
	
		$dat = explode(",",$str);
		$data = array();
		for($i=0;$i<count($dat);$i++)
		{
			$data[$i]['user'] = $dat[$i];
			if($dat[$i] == $idata['chat_user'])
			{
				$data[$i]['status'] = '1';
			}
			else
			{
				$data[$i]['status'] = '0';
			}
			
		}
		/*echo "<pre>";
		print_r($dat);
		echo "</pre>";
		exit();*/
		$idata['seen_unseen'] = json_encode($data);
		/*date_default_timezone_set('America/Edmonton');*/
		$idata['created_at'] = date('Y-m-d H:i:s');
		$idata['seen'] = '0';

		$this->db->insert('tbl_chat_messages', $idata);

		if($this->db->affected_rows() == 1)
		{				
			return array('success'=>true, 'status_code'=>1, 'message'=>'Message submited!', 'data'=>$idata);
		}
		else
		{
			return array('success'=>false, 'status_code'=>0, 'message'=>'Failed to submit message!', 'data'=>null);
		}			
	}

	public function fetch_new_messages_du($data)
	{
		$user_id = 'U_'.$data['du_id'].'_P';
		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$data['chat_id']);
		$this->db->order_by('msg_id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result_c = $query->row_array();
		$msg_id = $result_c['msg_id'];
		$seen_unseen = json_decode($result_c['seen_unseen']);
		for($i=0;$i<count($seen_unseen);$i++)
		{
			if($seen_unseen[$i]->user == $user_id)
			{
				$seen_unseen[$i]->status = '1';
			}
		}
		//$data_seen_unseen['seen'] = '1';
		$data_seen_unseen['seen_unseen'] = json_encode(($seen_unseen));

		$this->db->where('msg_id', $msg_id);
		$this->db->update('tbl_chat_messages', $data_seen_unseen);


		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$data['chat_id']) ;
		$this->db->where( 'chat_user !=',$user_id);
		$query = $this->db->get();
		$result_c = $query->result_array();
		for($k=0;$k<count($result_c);$k++)
		{
			if(isset($result_c[$k]['msg_id']))
			{
				$id = $result_c[$k]['msg_id'];
				$unseen['seen'] = '1';
				$this->db->where('msg_id', $id);
				$this->db->update('tbl_chat_messages', $unseen);
			}
		}

		$user_id = 'U_'.$data['du_id'].'_P';
		$this->db->where('chat_id', $data['chat_id']);			
		$this->db->where('msg_id >', $data['msg_id']);
		//$this->db->where('chat_user !=', $user_id);
		$messages = $this->db->get('tbl_chat_messages')->result_array();		
		
		return array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>$messages);
	}

	public function load_chat_du($data)
	{
		$user_id = 'U_'.$data['du_id'].'_P';
		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$data['chat_id']);
		$this->db->order_by('msg_id', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result_c = $query->row_array();
		$msg_id = $result_c['msg_id'];
		$seen_unseen = json_decode($result_c['seen_unseen']);
		for($i=0;$i<count($seen_unseen);$i++)
		{
			if($seen_unseen[$i]->user == $user_id)
			{
				$seen_unseen[$i]->status = '1';
			}
		}
		$data_seen_unseen['seen'] = '1';
		$data_seen_unseen['seen_unseen'] = json_encode(($seen_unseen));

		$this->db->where('msg_id', $msg_id);
		$this->db->update('tbl_chat_messages', $data_seen_unseen);

		$this->db->from( 'tbl_chat_messages' );
		$this->db->where( 'chat_id',$data['chat_id']);
		$this->db->where( 'seen',0);
		$this->db->where( 'chat_user !=',$user_id) ;
		$query = $this->db->get();
		$result_c = $query->result_array();
		for($k=0;$k<count($result_c);$k++)
		{
			$unseen['seen'] = '1';
			$this->db->where('msg_id', $result_c[$k]['msg_id']);
			$this->db->update('tbl_chat_messages', $unseen);
		}
		
		$this->db->where('chat_id', $data['chat_id']);			
		$this->db->where('msg_id <', $data['msg_id']);
		$this->db->limit(10);
		$messages = $this->db->get('tbl_chat_messages')->result_array();		
		
		return array('success'=>true, 'status_code'=>1, 'message'=>'Success!', 'data'=>$messages);
	}
}
?>
