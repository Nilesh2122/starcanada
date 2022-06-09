<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('America/Edmonton');
class Chats_model extends CI_Model
{   	 		
	public function get_drivers($mu_id)
	{
		$this->db->select('tbl_drivers.*, tbl_equipments.eq_unit');
		$this->db->where('tbl_drivers.mu_id', $mu_id);
		$this->db->where('tbl_drivers.is_removed', '0');
		$this->db->where('tbl_drivers.is_archieved', '0');
		$this->db->where('tbl_drivers.is_active', '1');
		$this->db->join('tbl_equipments', 'tbl_equipments.eq_id = tbl_drivers.eq_id');
		return $this->db->get('tbl_drivers')->result_array();
	}

	public function get_chats($mu_id, $user_id)
	{
		$this->db->where('mu_id', $mu_id);
		$this->db->like('chat_users', $user_id, 'both');
		$r = $this->db->get('tbl_chats')->result_array();

		$return = array();
		$chat_role = '';
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
			$key['seen'] = $result_c['seen'];

			$expl = explode(',', $key['chat_users']);			
			
			$i = 0;
			$users = array();
			foreach($expl as $val)
			{				
				$i++;								
				if($val != $user_id)
				{
					/*$this->db->from( 'tbl_chat_messages' );
					$this->db->where( 'chat_id',$key['chat_id']) ;
					$this->db->where( 'chat_user',$val) ;
					$this->db->order_by('msg_id', 'desc');
					$this->db->limit(1);
					$query = $this->db->get();
					$cl = $query->row_array();
					$key['seen'] = $cl['seen'];*/

					$exp = explode('_', $val);					
					if($exp[2] == 'D')
					{
						$this->db->select('tbl_drivers.*, tbl_equipments.eq_unit');						
						$this->db->join('tbl_equipments', 'tbl_equipments.eq_id = tbl_drivers.eq_id');
						$this->db->where('tbl_drivers.driver_id', $exp[1]);
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

			$key['chat_title'] = implode(',', $users);

			if($i > 2)
			{
				
				$key['chat_role'] = 'Group';
			}
			else
			{				
				$key['chat_role'] = $chat_role;
			}

			$return[] = $key;
		}
		foreach ($return as $key => $row) {
		    $distance[$key] = $row['created_at'];
		}
		array_multisort($distance, SORT_DESC , $return);
		// echo '<pre>';
		// print_r($return);
		// echo '</pre>';
		// exit();

		return $return;
	}

	public function create_chat($mu_id, $user_id, $data)
	{
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
				return $r[0]['chat_id']; //allready exist (2 person)
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
				
				return $iid; //created for 2 person
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

			return $iid; //created for group
		}
	}

	public function fetch_chat($chat_id)
	{
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
				if($key != 'U0'){
					$exp = explode('_', $key);
					if($exp[2] == 'D')
					{
						$this->db->where('driver_id', $exp[1]);
						$r = $this->db->get('tbl_drivers')->result_array();

						$usersinfo[$key]['name'] = sizeof($r) == 1 ? $r[0]['driver_name'] : 'Driver';
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
			$return['messages'] = $this->db->get('tbl_chat_messages')->result_array();


			for($i=0;$i<count($return['messages']);$i++)
			{
				//$return['messages'][$i]['counttt'] = count($return['messages'][$i]['seen_unseen']);
				
				$seen_unseen = json_decode($return['messages'][$i]['seen_unseen']);
				$return['messages'][$i]['arr'] = $seen_unseen;
				for($j=0;$j<count($return['messages'][$i]['arr']);$j++)
				{
					$return['messages'][$i]['arr11'] = $seen_unseen[$j]->user;
					if($seen_unseen[0]->user == 'U0')
					{
						$return['messages'][$i]['seen_data'] = $seen_unseen[0]->status;
					}
					else
					{
						$return['messages'][$i]['seen_data'] = '0';
					}
				}
			}

			$this->db->from( 'tbl_chat_messages' );
			$this->db->where( 'chat_id',$return['chat']['chat_id']);
			$this->db->order_by('msg_id', 'desc');
			$this->db->limit(1);
			$query = $this->db->get();
			if($query->num_rows() == 1)
			{
				$cl = $query->row_array();
				$insertID = $cl['msg_id'];			
				$udata['seen'] = '1';
				$this->db->where('msg_id', $insertID);	
				$this->db->update('tbl_chat_messages', $udata);	
			}
			


		}

		return $return;
	}

	public function push_message($data, $user_id)
	{
		date_default_timezone_set('America/Edmonton');

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
			else
			{
				$seen_unseen[$i]->status = '0';
			}
		}
		$data['seen_unseen'] = json_encode(($seen_unseen));
		$data['chat_user'] = $user_id;
		$data['created_at'] = date('Y-m-d H:i:s');

		$this->db->insert('tbl_chat_messages', $data);

		if($this->db->affected_rows() == 1)
		{
			//date formating for append issue
			$data['created_at'] = date('d M y, h:i A', strtotime($data['created_at']));

			return $data;
		}
		else
		{
			return '2';
		}		
	}	

	public function fetch_live_replies($data, $user_id)
	{
		$this->db->where('chat_id', $data['chatID']);			
		$this->db->where('msg_id >', $data['lastID']);
		$this->db->where('chat_user !=', $user_id);
		$messages = $this->db->get('tbl_chat_messages')->result_array();

		$return = array();
		foreach($messages as $m){
			$m['created_at'] = date('d M y, h:i A', strtotime($m['created_at']));

			$return[] = $m;
		}
		
		return $return;
	}		
}

  