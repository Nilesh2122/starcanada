<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->model('Users_model'); 						
    }
	
	function isLoggedIn() 
	{
		if (!$this->session->userdata('id')) {
			redirect('/');
		}
		else
		{
			$role = $this->session->userdata('user_role');
			if($role != 'Administrator')
			{
				redirect('/home/unauthorized');
			}
		}		
	}			
	
	public function index()
	{
		if (!$this->session->userdata('id')) {			
			$this->load->view('login');
		}
		else
		{
			redirect('/home');
		}
	}	
	
	public function login_process()
	{
		$result = $this->CheckCaptcha($_POST['g-recaptcha-response']);
		
		if ($result['success']) {
			//If the user has checked the Captcha box		

			$email = $_POST['email'];
			$password = $_POST['password'];
				
			$res = $this->Users_model->login_process($email, $password);
			
			if($res == '0')
			{
				$this->session->set_flashdata('error', 'Invalid email or password!');
				redirect('/');
			}
			else
			{
				$sessionArray = array(
					'id'=>$res[0]['id'],        
					'mu_id'=>$res[0]['mu_id'],            
					'user_email'=>$res[0]['user_email'],
					'user_role'=>$res[0]['role'],
					'name'=>$res[0]['uname']
				);
								
				$this->session->set_userdata($sessionArray);
				
				redirect('/home');
			}
		
		} else {
			// If the CAPTCHA box wasn't checked
			$this->session->set_flashdata('error', 'Captcha can not verify you as a human!');
			redirect('/');
		}		
	}

	private function CheckCaptcha($userResponse) {

        $fields_string = '';
        $fields = array(
            'secret' => '6Lc7DeYUAAAAAK7qetPMCg7HCKi7mp6_-vcunu6_',
            'response' => $userResponse
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, true);
	}
		
	public function logout()
	{
		$this->session->sess_destroy();
		header('location:'.base_url());
	}

	public function forgot_password()
	{
		if (!$this->session->userdata('id')) {			
			$this->load->view('forgot_password');
		}
		else
		{
			redirect('/home');
		}
	}

	public function reset_password()
	{
		if (!$this->session->userdata('id')) {			
			$email = $_POST['email'];			
				
			$res = $this->Users_model->reset_password($email);
			
			if($res == '0')
			{
				$this->session->set_flashdata('error', 'No account found with this email address!');
				redirect('/users/forgot_password');
			}
			else
			{					
				if($res == 'Administrator'){
					$this->session->set_flashdata('error', 'Admin account password can not change!');
					redirect('/users/forgot_password');	
				}
				else{
					//Accountant == 1 Dispatcher == 2
					$userType = $res == 'Accountant' ? 1 : 2;

					$subject = 'Reset Password - Star Canada Inc.';

					$html = '<h3>Hi,</h3>
							<p>We have get a request of reseting password for accout "'.$email.'"</p>
							<p>Below is a link for reset password of your account</p>
							<a href="'.base_url().'index.php/users/reset_password_now?reset='.base64_encode($email.';'.$userType).'">Reset password</a><br><br>
							
							<p>Thanks</p>
							<p>Star Canada Inc.<br>157 Kulawy Drive NW<br>Edmonton, AB T6L6Y9<br>Tel: (780)485-1232   Fax: (780)450-0932</p>';

					$to = $email;

					$file = '';

					$send = $this->send_email_attach($to, $subject, $file, $html);

					if($send == '1'){
						$this->session->set_flashdata('success', 'Reset password link sent to this email address!');
						redirect('/users/forgot_password');
					}
					else{
						$this->session->set_flashdata('error', 'Can not send reset password email right now, Please try again!');
						redirect('/users/forgot_password');
					}
				}				
			}
		}
		else
		{
			redirect('/home');
		}
	}

	public function reset_password_now()
	{
		if (!$this->session->userdata('id')) {			
			if(isset($_GET['reset'])){
				$getData = $_GET['reset'];

				$email = isset(explode(';', base64_decode($getData))[0]) ? explode(';', base64_decode($getData))[0] : ' ';
				$userType = isset(explode(';', base64_decode($getData))[1]) ? explode(';', base64_decode($getData))[1] : ' ';

				if($userType == '1'){ //update accountant					
					$this->db->where('acu_email', trim(strtolower($email)));
					$this->db->where('reset_password', '0');
					$user = $this->db->get('tbl_accounting_user')->result_array();

					if($user){
						$this->load->view('reset_password');
					}
					else{
						?>
						<script>
							alert('This link is already used once!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
				}
				else if($userType == '2'){ //update dispatcher
					$this->db->where('du_email', trim(strtolower($email)));
					$this->db->where('reset_password', '0');
					$user = $this->db->get('tbl_dispatch_user')->result_array();

					if($user){
						$this->load->view('reset_password');
					}
					else{
						?>
						<script>
							alert('This link is already used once!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
				}
				else if($userType == '3'){ //update driver
					$this->db->where('driver_email', trim(strtolower($email)));
					$this->db->where('reset_password', '0');
					$user = $this->db->get('tbl_drivers')->result_array();

					if($user){
						$this->load->view('reset_password');
					}
					else{
						?>
						<script>
							alert('This link is already used once!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
				}
				else {
					?>
					<script>
						alert('Oops! This link is broken.');
						window.location="<?php echo base_url(); ?>";
					</script>
					<?php
				}
			}
			else{
				redirect('/home');	
			}
		}
		else
		{
			redirect('/home');
		}
	}

	public function reset_password_process()
	{		
		if (!$this->session->userdata('id')) {			
			if(isset($_GET['reset']) && isset($_POST['password'])){
				$getData = $_GET['reset'];
				$password = $_POST['password'];

				$email = isset(explode(';', base64_decode($getData))[0]) ? explode(';', base64_decode($getData))[0] : ' ';
				$userType = isset(explode(';', base64_decode($getData))[1]) ? explode(';', base64_decode($getData))[1] : ' ';

				if($userType == '1'){ //update accountant
					//update pass
					$this->db->where('acu_email', trim(strtolower($email)));
					$this->db->set('reset_password', '1');
					$this->db->set('acu_password', md5($password));
					$this->db->update('tbl_accounting_user');

					if($this->db->affected_rows() > 0){
						?>
						<script>
							alert('Password updated successfully!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Password update failed, Try again from link!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
				}
				else if($userType == '2'){ //update dispatcher
					//update pass
					$this->db->where('du_email', trim(strtolower($email)));
					$this->db->set('reset_password', '1');
					$this->db->set('du_password', md5($password));
					$this->db->update('tbl_dispatch_user');

					if($this->db->affected_rows() > 0){
						?>
						<script>
							alert('Password updated successfully!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Password update failed, Try again from link!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
				}
				else if($userType == '3'){ //update driver
					//update pass
					$this->db->where('driver_email', trim(strtolower($email)));
					$this->db->set('reset_password', '1');
					$this->db->set('driver_password', md5($password));
					$this->db->update('tbl_drivers');

					if($this->db->affected_rows() > 0){
						?>
						<script>
							alert('Password updated successfully!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
					else{
						?>
						<script>
							alert('Password update failed, Try again from link!');
							window.location="<?php echo base_url(); ?>";
						</script>
						<?php
					}
				}
				else { //not updated
					?>
					<script>
						alert('Password update failed, Try again from link!');
						window.location="<?php echo base_url(); ?>";
					</script>
					<?php
				}
			}
			else{
				redirect('/home');	
			}
		}
		else
		{
			redirect('/home');
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
	
	public function accountants()
	{
		$this->isLoggedIn();
		
		$this->load->view('header');
		if($this->input->post('title') !="")
		{
	        $title = trim($this->input->post('title'));
		}
		else
		{
			$title = str_replace("%20",' ',($this->uri->segment(3))?$this->uri->segment(3):0);
		} 
		
        $data['search_title']=$title;		
		 
	    $allrecord = $this->Users_model->get_user_count($title);
		$baseurl =  base_url().'index.php/'.$this->router->class.'/'.$this->router->method."/".$title;
		
	    $paging=array();
		$paging['base_url'] =$baseurl;
		$paging['total_rows'] = $allrecord;
		$paging['per_page'] = 10;
		$paging['uri_segment']= 4;
		$paging['num_links'] = 5;
		$paging['first_link'] = 'First';
		$paging['first_tag_open'] = '<li>>';
		$paging['first_tag_close'] = '</li>';
		$paging['num_tag_open'] = '<li>';
		$paging['num_tag_close'] = '</li>';
		$paging['prev_link'] = 'Prev';
		$paging['prev_tag_open'] = '<li>';
		$paging['prev_tag_close'] = '</li>';
		$paging['next_link'] = 'Next';
		$paging['next_tag_open'] = '<li>';
		$paging['next_tag_close'] = '</li>';
		$paging['last_link'] = 'Last';
		$paging['last_tag_open'] = '<li>';
		$paging['last_tag_close'] = '</li>';
		$paging['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
		$paging['cur_tag_close'] = '</a></li>';
		
		$this->pagination->initialize($paging);	
		
		$data['limit'] = $paging['per_page'];
		$data['number_page'] = $paging['per_page']; 
        $data['offset'] = ($this->uri->segment(4)) ? $this->uri->segment(4):'0';	
        $data['nav'] = $this->pagination->create_links();
		$data['datas'] = $this->Users_model->get_user_list($data['limit'],$data['offset'],$title);
		
		$this->load->view('users',$data);
		$this->load->view('footer');				
	}

	public function add_user()
	{
		$this->isLoggedIn();
		$mu_id = $this->session->userdata['mu_id'];
		
		$data = $_POST; //POST data to insert to DB
		$data['acu_email'] = trim(strtolower($data['acu_email']));
		$data['acu_password'] = md5($data['acu_password']);
		$data['mu_id'] = $mu_id;	

		$res = $this->Users_model->add_user($data);
		
		if($res == '0')
		{
			$this->session->set_flashdata('error', 'Email addres allready registered, Choose another!');

			redirect('users/accountants');
		}
		else if($res == '3')
		{
			$this->session->set_flashdata('error', 'Failed to add, Try again!');
			
			redirect('users/accountants');
		}
		else
		{
			$this->session->set_flashdata('success', 'Accountant added successfuly!');
			
			redirect('users/accountants');
		}							
	}

	public function get_user_by_id()
	{
		$this->isLoggedIn();
		
		$user_id = $_POST['user_id'];

		$res = $this->Users_model->get_user_by_id($user_id);

		echo json_encode($res[0]);
	}

	public function edit_user()
	{
		$this->isLoggedIn();
		
		$data = $_POST; //POST data to insert to DB		

		unset($data['acu_email']);

		$res = $this->Users_model->edit_user($data);

		if($res == '0')
		{
			$this->session->set_flashdata('success', 'Nothing changes has made!');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}
		else
		{
			$this->session->set_flashdata('success', 'Updated successfuly!');

			header('location:'.$_SERVER['HTTP_REFERER']);			
		}
	}

	public function delete()
	{
		$this->isLoggedIn();					

		$res = '0';

		if(isset($_GET['id'])){
			$id = base64_decode($_GET['id']);

			$this->db->where('acu_id', $id);
			$this->db->set('is_removed', '1');
			$this->db->update('tbl_accounting_user');
			
			if($this->db->affected_rows() > 0){
				$res = '1';
			}
		}		

		if($res == '0')
		{
			$this->session->set_flashdata('success', 'Nothing is deleted!');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}
		else
		{
			$this->session->set_flashdata('success', 'Record deleted successfully!');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}
	}

	public function archieve()
	{
		$this->isLoggedIn();					

		$res = '0';

		if(isset($_GET['id'])){
			$id = base64_decode($_GET['id']);
			$archive = 'archieved';

			$this->db->where('acu_id', $id);
			if(isset($_GET['unarchive'])){$this->db->set('is_archieved', '0'); $archive = 'Un'.$archive;}
			else{$this->db->set('is_archieved', '1');}
			$this->db->update('tbl_accounting_user');
			
			if($this->db->affected_rows() > 0){
				$res = '1';
			}
		}		

		if($res == '0')
		{
			$this->session->set_flashdata('success', 'Nothing is '.$archive.'!');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}
		else
		{
			$this->session->set_flashdata('success', 'Record '.$archive.' successfully!');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}
	}

	public function change_status()
	{
		$this->isLoggedIn();
		
		$update['is_active'] = $_POST['flag'];
		$user_id = $_POST['user_id'];

		$this->db->where('acu_id', $user_id);
		$this->db->update('tbl_accounting_user', $update);

		echo $this->db->affected_rows() == 1 ? json_encode('1') : json_encode('0');	
	}
	
	


	// Temp
	public function login_dev()
	{
		if (!$this->session->userdata('id')) {			
			$this->load->view('login_');
		}
		else
		{
			redirect('/home');
		}
	}

	public function login_process_()
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
			
		$res = $this->Users_model->login_process($email, $password);
		
		if($res == '0')
		{
			$this->session->set_flashdata('error', 'Invalid email or password!');
			redirect('/');
		}
		else
		{
			$sessionArray = array(
				'id'=>$res[0]['id'],        
				'mu_id'=>$res[0]['mu_id'],            
				'user_email'=>$res[0]['user_email'],
				'user_role'=>$res[0]['role'],
				'name'=>$res[0]['uname']
			);
							
			$this->session->set_userdata($sessionArray);
			
			redirect('/home');
		}			
	}
	public function myprofile()
	{
		$this->isLoggedIn();
		$id = $this->session->userdata['id'];
		$role = $this->session->userdata['user_role'];
		$data['customers'] = $this->Users_model->myprofile($id,$role);		
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";
		exit();*/
		$this->load->view('header');	
		$this->load->view('myprofile',$data);
		$this->load->view('footer');				
	}
	public function edit_profile()
	{
		$this->isLoggedIn();
		$data = $_POST; //POST data to insert to DB		
		$res = $this->Users_model->edit_profile($data);

		if($res == '0')
		{
			$this->session->set_flashdata('success', 'Nothing changes has made!');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}
		else
		{
			$this->session->set_flashdata('success', 'Updated successfuly!');

			header('location:'.$_SERVER['HTTP_REFERER']);			
		}
	}
}
