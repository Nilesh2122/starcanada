<?php
class ModelPassword extends CI_Model {
	
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->helper('url');
    }				
	
	public function reset_password($email)
	{	
		//Checking for Dispatcher
		$this->db->select('*');
		$this->db->from('tbl_drivers');
		$this->db->where('driver_email', trim(strtolower($email)));
		$this->db->where('is_active', '1');
		$this->db->where('is_removed', '0'); 
		$this->db->where('is_archieved', '0');
		$q1 = $this->db->get();
		
		//Checking for Dispatcher
		$this->db->select('*');
		$this->db->from('tbl_dispatch_user');
		$this->db->where('du_email', trim(strtolower($email)));
		$this->db->where('is_active', '1');
		$this->db->where('is_removed', '0'); 
		$this->db->where('is_archieved', '0');
		$q2 = $this->db->get();

		if($q1->num_rows() == 1)
		{
			$res = $q1->result_array();

			//update reset pass flag
			$this->db->where('driver_email', trim(strtolower($email)));
			$this->db->set('reset_password', '0');
			$this->db->update('tbl_drivers');
			
			$userType = 3;

			$subject = 'Reset Password - Star Canada Inc.';

			$html = '<h3>Hi,</h3>
					<p>We have get a request of reseting password for account "'.$email.'"</p>
					<p>Below is a link for reset password of your account</p>
					<a href="'.base_url().'index.php/users/reset_password_now?reset='.base64_encode($email.';'.$userType).'">Reset password</a><br><br>
					
					<p>Thanks</p>
					<p>Star Canada Inc.<br>157 Kulawy Drive NW<br>Edmonton, AB T6L6Y9<br>Tel: (780)485-1232   Fax: (780)450-0932</p>';

			$to = $email;

			$file = '';

			$send = $this->send_email_attach($to, $subject, $file, $html);

			if($send == '1'){
				return array('success'=>true, 'status_code'=>1, 'message'=>'Reset password link sent to your registered email!', 'data'=>NULL);
			}
			else{
				return array('success'=>false, 'status_code'=>0, 'message'=>'Can not send email for reset password right now, Please try later.', 'data'=>NULL);
			}			
		}
		else if($q2->num_rows() == 1)
		{
			$res = $q2->result_array();

			//update reset pass flag			
			$this->db->where('du_email', trim(strtolower($email)));
			$this->db->set('reset_password', '0');
			$this->db->update('tbl_dispatch_user');			

			$userType = 2;

			$subject = 'Reset Password - Star Canada Inc.';

			$html = '<h3>Hi,</h3>
					<p>We have get a request of reseting password for account "'.$email.'"</p>
					<p>Below is a link for reset password of your account</p>
					<a href="'.base_url().'index.php/users/reset_password_now?reset='.base64_encode($email.';'.$userType).'">Reset password</a><br><br>
					
					<p>Thanks</p>
					<p>Star Canada Inc.<br>157 Kulawy Drive NW<br>Edmonton, AB T6L6Y9<br>Tel: (780)485-1232   Fax: (780)450-0932</p>';

			$to = $email;

			$file = '';

			$send = $this->send_email_attach($to, $subject, $file, $html);

			if($send == '1'){
				return array('success'=>true, 'status_code'=>1, 'message'=>'Reset password link sent to your registered email!', 'data'=>NULL);
			}
			else{
				return array('success'=>false, 'status_code'=>0, 'message'=>'Can not send email for reset password right now, Please try later.', 'data'=>NULL);
			}
		}
		else
		{
			return array('success'=>false, 'status_code'=>0, 'message'=>'Can not found any account with this email address.', 'data'=>NULL);
		}
	}	

	public function send_email_attach($to_, $subject_, $file_, $content_)
	//public function send_email_attach()
	{
		// Recipient 
		//$to = 'satyamgandhi1211@gmail.com'; 
		$to = $to_;
		
		// Sender 
		$from = 'account@starcanadaapp.com';
		$fromName = 'Star Canada Inc.'; 
		
		// Email subject 
		$subject = $subject_;  
		
		// Attachment file 
		$file = $file_; 
		
		// Email body content 
		$htmlContent = $content_; 
		
		// Header for sender info 
		$headers = "From: $fromName"." <".$from.">"; 
		
		// Boundary  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
		
		// Headers for attachment  
		$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
		
		// Multipart boundary  
		$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
		"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
		
		// Preparing attachment 
		if(!empty($file) > 0){ 
			if(is_file($file)){ 
				$message .= "--{$mime_boundary}\n"; 
				$fp =    @fopen($file,"rb"); 
				$data =  @fread($fp,filesize($file)); 
		
				@fclose($fp); 
				$data = chunk_split(base64_encode($data)); 
				$message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
				"Content-Description: ".basename($file)."\n" . 
				"Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
				"Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
			} 
		} 
		$message .= "--{$mime_boundary}--"; 
		$returnpath = "-f" . $from; 
		
		// Send email 
		$mail = @mail($to, $subject, $message, $headers, $returnpath);  
		
		// Email sending status 
		//echo $mail?"<h1>Email Sent Successfully!</h1>":"<h1>Email sending failed.</h1>"; 
		return $mail ? '1' : '0';
	}
}
?>
