<?php
date_default_timezone_set('Asia/Kolkata');
class ModelNotification extends CI_Model
{
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->and_server_key = 'AAAA6SMAjYc:APA91bGUqIxWHeo8NUloVATGZ0rNHPhldV4AtlK0RL652Z1smGYkbSQFvPCWCsyF_Ya2EovZ0zwquOjDxzxJsl_1alXH2xiyw1WcfhkxNV_R6f3Pf0-iezMwUSenn4zNROdCL5AzA_Dm';
		$this->ios_server_key = 'AAAA6SMAjYc:APA91bGUqIxWHeo8NUloVATGZ0rNHPhldV4AtlK0RL652Z1smGYkbSQFvPCWCsyF_Ya2EovZ0zwquOjDxzxJsl_1alXH2xiyw1WcfhkxNV_R6f3Pf0-iezMwUSenn4zNROdCL5AzA_Dm';
    }
	
	function send_user_notification($notification_data)
    {		
		$a_users = array();
		$i_users = array();	
		$result = '';			
		
		$this->db->select('device_token, device_type');
		$this->db->from('tbl_drivers');
		
		$this->db->where('driver_id',$notification_data['driver_id']);
		
		$this->db->where('is_active !=',0);
		$this->db->where('device_token !=','');
		$this->db->where('device_type !=',3);
		$this->db->where('device_type !=',4);		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{		
			$row = $query->result_array();
						
			if($row[0]['device_type'] == '1') //android
			{
				$a_users[] = $row[0]['device_token'];
			}
			else if($row[0]['device_type'] == '2') //ios
			{
				$i_users[] = $row[0]['device_token'];
			}
			
			if(sizeof($a_users) > 0)
			{
				$result = $this->send_android_notification($a_users, $notification_data);
			}
			if(sizeof($i_users) > 0)
			{
				$result = $this->send_ios_notification($i_users, $notification_data);
			}
		}
		//$result = array('success'=>true,'status_code'=>'1');
		return $result;
	}

	function send_user_notification_($notification_data)
    {		
		$a_users = array();
		$i_users = array();	
		$result = '';			
		
		$this->db->select('device_token, device_type');
		$this->db->from('tbl_dispatch_user');
		
		$this->db->where('du_id',$notification_data['du_id']);
		
		$this->db->where('is_active !=',0);
		$this->db->where('device_token !=','');
		$this->db->where('device_type !=',3);
		$this->db->where('device_type !=',4);		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{		
			$row = $query->result_array();
						
			if($row[0]['device_type'] == '1') //android
			{
				$a_users[] = $row[0]['device_token'];
			}
			else if($row[0]['device_type'] == '2') //ios
			{
				$i_users[] = $row[0]['device_token'];
			}
			
			if(sizeof($a_users) > 0)
			{
				$result = $this->send_android_notification($a_users, $notification_data);
			}
			if(sizeof($i_users) > 0)
			{
				$result = $this->send_ios_notification($i_users, $notification_data);
			}
		}
		//$result = array('success'=>true,'status_code'=>'1');
		return $result;
	}
	
	function send_android_notification($a_users, $notification_data)
	{
		//FCM api URL
		$url = 'https://fcm.googleapis.com/fcm/send';
				
		/*$fields = array();	
			 
		$msg = array
		(
		  "body" => $notification_data,
		  "title" => $notification_data['message'],
		);

		$fields = array
		(
		  'registration_ids'  => $a_users,
		  'notification'      => $msg
		);*/
		
		$target = $a_users;				
		
		$fields = array();
		
		$fields['data'] = $notification_data;
		if(is_array($target)){
			$fields['registration_ids'] = $target;
		}else{
			$fields['to'] = $target;
		}
				
		//header with content_type api key
		$headers = array(
			'Content-Type:application/json',
		  'Authorization:key='.$this->and_server_key
		);
					
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$response_object = curl_exec($ch);
				
		curl_close($ch);				
		
		return $response_object;
	}


	function send_ios_notification($registrationIds, $notification_data)
    {
		// API access key from Google API's Console	
		define('API_ACCESS_KEY', $this->ios_server_key);
		
		//$registrationIds = array($user[0]['user_device_token']);
		
		// prep the bundle
		$msg = array(
				'body'  => $notification_data['message'],
				'title'     => 'STAR CANADA',
				'vibrate'   => 1,
				'sound'     => 1,
			);
		
		$custome_data['operation_data'] = $notification_data;
		
		$fields = array(
					'registration_ids'  => $registrationIds,
					'notification'      => $msg,
					'data' => $custome_data,
					'priority'=>'high'
				);
		
		$headers = array(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return $result;
	}
}
?>