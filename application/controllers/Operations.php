<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operations extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->model('Operations_model'); 
		$this->load->model('Users_model'); 
		$this->load->model('Dispatchers_model');
		$this->load->model('v1/ModelNotification');			
	}				
	
	function isLoggedIn() 
	{
		if (!$this->session->userdata('id')) {
			redirect('/');
		}		
	}
	
	private function op_status()
	{
		//Declare
		$op_status = array();	
		
		//1 = 'Intiated' , 2 = 'Ready to assign' , 3 = 'Assigned to driver' , 4 = 'Accepted' , 5 = 'Loaded' , 6 = 'On going' , 7 = 'Delivered'
		//Define
		$op_status[1] = 'Intiated';
		$op_status[2] = 'Ready to assign';
		$op_status[3] = 'Load assigned';
		$op_status[4] = 'Accepted';
		$op_status[5] = 'Loaded';
		$op_status[6] = 'On going';
		$op_status[7] = 'Delivered';		
		
		return $op_status;	
	}

	public function index()
	{
		$this->isLoggedIn();		

		$title = '';

		if(isset($_POST['title'])){
			$title = $_POST['title'];
		}					
		
		$data['search_title'] = $title;		 	   			

		$data['datas'] = $this->Operations_model->get_operation_list(15, 0, $title);
		$data['op_status'] = $this->op_status();		
		$data['contractors'] = $this->Operations_model->get_contractors();
		
		$this->load->view('header');
		$this->load->view('operations',$data);
		$this->load->view('footer');				
	}
	public function operations_delete()
	{
		$this->isLoggedIn();					

		$res = '0';

		if(isset($_GET['id'])){
			$id = base64_decode($_GET['id']);

			$this->db->where('op_id', $id);
			$this->db->delete('tbl_operations');
												
		}		

		if($res == '0')
		{
			$this->session->set_flashdata('error', 'Failed to delete! Try again.');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}
		else
		{
			$this->session->set_flashdata('success', 'Record deleted successfully!');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}
	}
	public function dispatch_sheet()
	{
		$this->isLoggedIn();		

		$title = '';

		if(isset($_POST['title'])){
			$title = $_POST['title'];
		}					
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}
		else{
                    $cdate = new DateTime("now", new DateTimeZone('Canada/Mountain') );
		    $date = $cdate->format('Y/m/d');
		    //$date = '';
		}
                
		$data['search_title'] = $title;		 	   			

		$data['datas'] = $this->Operations_model->get_operation_list_sheet(15, 0, $title,$date);
		$data['op_status'] = $this->op_status();		
		$data['contractors'] = $this->Operations_model->get_contractors();		
		/*echo "<pre>";
		print_r($data['datas']);
		echo "</pre>";
		exit();*/
		$this->load->view('header');
		$this->load->view('operations_new',$data);
		$this->load->view('footer');				
	}
	public function get_more()
	{
		$op_id = $_POST['op_id'];
		$title = $_POST['title'];
		
		$data['op_status'] = $this->op_status();
		$data['datas'] = $this->Operations_model->get_more(15, $op_id ,$title);
		echo json_encode($data);
	}

	public function completed()
	{
		$this->isLoggedIn();		

		$title = '';

		if(isset($_POST['title'])){
			$title = $_POST['title'];
		}					
		
		$data['search_title'] = $title;		 	   			

		$data['datas'] = $this->Operations_model->completed_operation_list(15, 0, $title);
		$data['op_status'] = $this->op_status();		
		$data['contractors'] = $this->Operations_model->get_contractors();		
		
		$this->load->view('header');
		$this->load->view('completed_operations',$data);
		$this->load->view('footer');				
	}

	public function completed_more()
	{
		$op_id = $_POST['op_id'];
		$title = $_POST['title'];
		
		$data['op_status'] = $this->op_status();
		$data['datas'] = $this->Operations_model->completed_more(15, $op_id ,$title);
		echo json_encode($data);
	}

	public function index_old()
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

		$allrecord = $this->Operations_model->get_operation_count($title);
		$baseurl =  base_url().'index.php/'.$this->router->class.'/'.$this->router->method."/".$title;
		
		$paging=array();
		$paging['base_url'] =$baseurl;
		$paging['total_rows'] = $allrecord;
		$paging['per_page'] = 10;
		$paging['uri_segment']= 4;
		$paging['num_links'] = 5;
		$paging['first_link'] = 'First';
		$paging['first_tag_open'] = '<li>';
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
		$data['datas'] = $this->Operations_model->get_operation_list($data['limit'],$data['offset'],$title);
		$data['op_status'] = $this->op_status();		
		$data['contractors'] = $this->Operations_model->get_contractors();		
		
		$this->load->view('operations',$data);
		$this->load->view('footer');				
	}
	
	public function create_operation()
	{
		$this->isLoggedIn();
		
		$data['customers'] = $this->Operations_model->get_customers();		
		
		$this->load->view('header');	
		$this->load->view('add_operation',$data);
		$this->load->view('footer');	
	}

	public function add_operation()
	{
		$this->isLoggedIn();
		$mu_id = $this->session->userdata['mu_id'];

		$role = $this->session->userdata('user_role');
		
		$id = 0;
		
		if($role == 'Administrator')
		{
			$id = 0;	
		}
		else if($role == 'Accountant')
		{
			$id = $this->session->userdata('id');
		}
		else
		{
			$this->session->set_flashdata('error', 'You are unauthorized to create operation!');

			redirect('operations');			
		}
		
		$data = $_POST; //POST data to insert to DB
		$data['mu_id'] = $mu_id;	
		$data['created_by'] = $id;

		$res = $this->Operations_model->add_operation($data);
		
		$this->session->set_flashdata('success', 'Operation created successfuly!');

		redirect('operations');					
	}

	public function get_operation_by_id()
	{
		$this->isLoggedIn();
		
		$op_id = $_POST['op_id'];

		$res = $this->Operations_model->get_operation_by_id($op_id);

		echo json_encode($res[0]);
	}
	
	public function modify_operation()
	{
		$this->isLoggedIn();
		
		$op_id = $_POST['op_id'];
		
		$data['customers'] = $this->Operations_model->get_customers();		
		$data['operation'] = $this->Operations_model->get_operation_by_id($op_id);
		
		$this->load->view('header');
		$this->load->view('edit_operation',$data);
		$this->load->view('footer');
	}

	public function edit_operation()
	{
		$this->isLoggedIn();
		if($_POST)
		{
			$data = $_POST; //POST data to insert to DB

			$res = $this->Operations_model->edit_operation($data);

			if($res == '0')
			{
				$this->session->set_flashdata('success', 'Nothing changes has made!');

				redirect('operations');				
			}
			else
			{
				$this->session->set_flashdata('success', 'Updated successfuly!');

				redirect('operations');
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'Form not submmited properly, Try again.');

			redirect('operations');			
		}
	}
	
	public function get_drivers_by_contractors()
	{
		if (!$this->session->userdata('id')) {
			echo json_encode('0');
		}
		else{
			$mu_id = $this->session->userdata['mu_id'];
			
			$con_id = $_POST['con_id'];
			
			$res = $this->Operations_model->get_drivers_by_contractors($mu_id, $con_id);
			
			echo json_encode($res);
		}		
	}	

	public function get_refreshed_drivers()
	{
		if (!$this->session->userdata('id')) {
			echo json_encode('0');
		}
		else{
			$mu_id = $this->session->userdata['mu_id'];
			
			$res = $this->Operations_model->get_refreshed_drivers($mu_id);
			
			echo json_encode($res);
		}
	}
	
	public function assign_driver()
	{
		$this->isLoggedIn();
		
		$role = $this->session->userdata('user_role');
		
		$id = 0;
		
		if($role == 'Administrator')
		{
			$id = 0;	
		}
		else if($role == 'Dispatcher')
		{
			$id = $this->session->userdata('id');
		}
		else
		{
			$this->session->set_flashdata('error', 'You are unauthorized for assign operation to driver!');

			redirect('operations');
		}
		
		if($_POST)
		{
			$data = $_POST; //POST data to insert to DB		
			$data['assigned_by'] = $id;				

			$res = $this->Operations_model->assign_driver($data);

			if($res = '0')
			{
				$this->session->set_flashdata('error', 'Operation assining FAILED!');

				redirect('operations');				
			}
			else
			{
				//fetch operation data
				$notification_data = $this->Operations_model->get_operation_by_id($data['op_id'])[0];
				
				$notification_data['message'] = 'Delivery Operation assigned to you, Click to Accept/Reject.';
				$notification_data['title'] = 'Operation assigned';
				
				//Send notification
				
				$nres = $this->ModelNotification->send_user_notification($notification_data);
				
				$this->session->set_flashdata('success', 'Operation assigned to driver successfully!');

				redirect('operations');					
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'Form not submited!');

			redirect('operations');			
		}
	}
	
	public function track()
	{
		$this->isLoggedIn();		
		
		if(isset($_POST['op_id'])){
			$op_id = $_POST['op_id'];
		}
		else if($this->uri->segment(3)){
			$op_id = $this->uri->segment(3);
		}
		else{
			$this->session->set_flashdata('error', 'Form not submited!');

			redirect('operations');			
		}			
		
		$data['operation'] = $this->Operations_model->get_operation_track($op_id);
		$data['op_status'] = $this->op_status();
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";
		exit();*/
		$this->load->view('header');
		$this->load->view('track_operation',$data);
		$this->load->view('footer');
	}	
	
	public function get_cost_by_city()
	{
		$this->isLoggedIn();
		
		$data = $_POST;		
		
		$this->db->where('from_city', $data['from']);
		$this->db->where('to_city', $data['to']);
		$this->db->where('cust_id', $data['cust']);
		$this->db->where('is_active', '1');
		$return = $this->db->get('tbl_rates')->result_array();
		echo sizeof($return) > 0 ? json_encode($return[0]) : json_encode('0');
	}

	public function fetch_cust_rates()
	{
		$this->isLoggedIn();
		
		$data = $_POST;

		$this->db->where('cust_id', $data['cust']);
		$this->db->where('is_active', '1');
		$return = $this->db->get('tbl_rates')->result_array();
		echo sizeof($return) > 0 ? json_encode($return) : json_encode('0');
	}
	
	public function get_op_notification()
	{
		if (!$this->session->userdata('id')) {
			echo json_encode('0');
		}
		else{
			$mu_id = $this->session->userdata['mu_id'];						
			
			$this->db->where('sent', '0');
			$this->db->where('mu_id', $mu_id);
			$n = $this->db->get('tbl_desktop_notifications')->result_array();
			
			foreach($n as $key){
				$this->db->where('id', $key['id']);
				$this->db->set('sent','1',false);
				$this->db->update('tbl_desktop_notifications');
			}
			
			echo json_encode($n);
		}
	}

	public function op_notification_open()
	{
		if (!$this->session->userdata('id')) {
			header('location:'.base_url());
		}
		else{
			if($this->uri->segment(3)){				
				$this->db->where('id', $this->uri->segment(3));
				$this->db->set('clicked','1',false);
				$this->db->update('tbl_desktop_notifications');

				$this->db->where('id', $this->uri->segment(3));
				$n = $this->db->get('tbl_desktop_notifications')->result_array();	
				
				if($n){
					header('location:'.$n[0]['redirect']);
				}
				else
				{
					header('location:'.base_url());
				}
				
			}
			else{
				header('location:'.base_url());
			}
		}
	}	
}
