<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drivers extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('pagination');
        $this->load->model('Drivers_model');        
    }
	
	function isLoggedIn() 
	{
		if (!$this->session->userdata('id')) {
			redirect('/');
		}	
		else
		{
			$role = $this->session->userdata('user_role');
			if($role == 'Dispatcher')
			{
				redirect('/home/unauthorized');
			}
		}	
	}
		
	public function index()
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
		 
	    $allrecord = $this->Drivers_model->get_driver_count($title);
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
		$data['datas'] = $this->Drivers_model->get_driver_list($data['limit'],$data['offset'],$title);
		$data['contractors'] = $this->Drivers_model->get_contractors();
		
		$this->load->view('drivers',$data);
		$this->load->view('footer');				
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

	public function add_driver()
	{
		$this->isLoggedIn();
		$mu_id = $this->session->userdata['mu_id'];
		
		$data = $_POST; //POST data to insert to DB
		$data['mu_id'] = $mu_id;	
		
		$data['driver_email'] = trim(strtolower($data['driver_email']));
		$data['driver_password'] = md5($data['driver_password']);	
		
		//License upload
		if(!empty($_FILES['driver_license']['tmp_name']))
		{
			$today = date('Y-m-d');	
			$site_url = base_url();	
			$path_parts = pathinfo($_FILES['driver_license']['name']);
			$extension = $path_parts['extension'];
			$new_file_name = 'DDL_'.$this->get_random_string().'_'.time().'.'.$extension; 
			
			$targetFolder = 'user_data/driver_data/';
								
			$target_file = $targetFolder.$new_file_name;
			
			if (move_uploaded_file($_FILES['driver_license']['tmp_name'],$target_file))  // upload original file
			{	
				$data['driver_license'] = $new_file_name;
			}				
		}
		
		//ID Proof upload
		if(!empty($_FILES['driver_id_proof']['tmp_name']))
		{
			$today = date('Y-m-d');	
			$site_url = base_url();	
			$path_parts = pathinfo($_FILES['driver_id_proof']['name']);
			$extension = $path_parts['extension'];
			$new_file_name = 'DIP_'.$this->get_random_string().'_'.time().'.'.$extension; 
			
			$targetFolder = 'user_data/driver_data/';
								
			$target_file = $targetFolder.$new_file_name;
			
			if (move_uploaded_file($_FILES['driver_id_proof']['tmp_name'],$target_file))  // upload original file
			{	
				$data['driver_id_proof'] = $new_file_name;
			}				
		}

		$res = $this->Drivers_model->add_driver($data);
		
		if($res == '0')
		{
			$this->session->set_flashdata('error', 'Email addres allready registered, Choose another!');

			redirect('drivers');			
		}
		else if($res == '3')
		{
			$this->session->set_flashdata('error', 'Failed to add, Try again!');

			redirect('drivers');			
		}
		else
		{
			$this->session->set_flashdata('success', 'Driver added successfully!');

			redirect('drivers');			
		}						
	}

	public function get_driver_by_id()
	{
		$this->isLoggedIn();
		
		$user_id = $_POST['user_id'];

		$res = $this->Drivers_model->get_driver_by_id($user_id);

		echo json_encode($res[0]);
	}

	public function edit_driver()
	{
		$this->isLoggedIn();
		
		$data = $_POST; //POST data to insert to DB	
		unset($data['driver_email']);
		
		//License upload
		if(!empty($_FILES['driver_license']['tmp_name']))
		{
			$today = date('Y-m-d');	
			$site_url = base_url();	
			$path_parts = pathinfo($_FILES['driver_license']['name']);
			$extension = $path_parts['extension'];
			$new_file_name = 'DDL_'.$this->get_random_string().'_'.time().'.'.$extension; 
			
			$targetFolder = 'user_data/driver_data/';
								
			$target_file = $targetFolder.$new_file_name;
			
			if (move_uploaded_file($_FILES['driver_license']['tmp_name'],$target_file))  // upload original file
			{	
				$data['driver_license'] = $new_file_name;
			}				
		}
		
		//ID Proof upload
		if(!empty($_FILES['driver_id_proof']['tmp_name']))
		{
			$today = date('Y-m-d');	
			$site_url = base_url();	
			$path_parts = pathinfo($_FILES['driver_id_proof']['name']);
			$extension = $path_parts['extension'];
			$new_file_name = 'DIP_'.$this->get_random_string().'_'.time().'.'.$extension; 
			
			$targetFolder = 'user_data/driver_data/';
								
			$target_file = $targetFolder.$new_file_name;
			
			if (move_uploaded_file($_FILES['driver_id_proof']['tmp_name'],$target_file))  // upload original file
			{	
				$data['driver_id_proof'] = $new_file_name;
			}				
		}		

		$res = $this->Drivers_model->edit_driver($data);
		
		if($res == '0')
		{
			$this->session->set_flashdata('success', 'Nothing changes has made!');

			header('location:'.$_SERVER['HTTP_REFERER']);			
		}
		else
		{
			$this->session->set_flashdata('success', 'Updated successfully!');

			header('location:'.$_SERVER['HTTP_REFERER']);			
		}		
	}

	public function delete()
	{
		$this->isLoggedIn();					

		$res = '0';

		if(isset($_GET['id'])){
			$id = base64_decode($_GET['id']);

			$this->db->where('driver_id', $id);
			$this->db->set('is_active', '0');
			$this->db->set('is_removed', '1');
			$this->db->set('remember_token', '');
			$this->db->update('tbl_drivers');
			
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

			$this->db->where('driver_id', $id);
			if(isset($_GET['unarchive'])){
				$this->db->set('is_archieved', '0'); 
				$archive = 'Un'.$archive;
			}
			else{
				$this->db->set('is_archieved', '1');
				$this->db->set('remember_token', '');
			}
			$this->db->update('tbl_drivers');
			
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
		if($_POST['flag'] == '0'){ $update['remember_token'] = ''; }
		$driver_id = $_POST['driver_id'];

		$this->db->where('driver_id', $driver_id);
		$this->db->update('tbl_drivers', $update);

		echo $this->db->affected_rows() == 1 ? json_encode('1') : json_encode('0');	
	}	

	public function get_equipments()
	{
		if (!$this->session->userdata('id')) {
			echo json_encode('0');
		}
		else{
			$mu_id = $this->session->userdata['mu_id'];
			
			$con_id = $_POST['con_id'];
			
			$res = $this->Drivers_model->get_equipments_by_con($mu_id, $con_id);
			
			echo json_encode($res);
		}
	}
}
