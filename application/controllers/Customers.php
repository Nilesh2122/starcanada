<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('pagination');
        $this->load->model('Customer_model');        
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
		 
	    $allrecord = $this->Customer_model->get_customer_count($title);
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
		$data['datas'] = $this->Customer_model->get_customer_list($data['limit'],$data['offset'],$title);
		
		$this->load->view('customers',$data);
		$this->load->view('footer');				
	}

	public function add_customer()
	{
		$this->isLoggedIn();
		$mu_id = $this->session->userdata['mu_id'];
		
		$data = $_POST; //POST data to insert to DB
		$data['mu_id'] = $mu_id;	

		$res = $this->Customer_model->add_customer($data);
		
		if($res == '0')
		{
			$this->session->set_flashdata('error', 'Failed to add, Try again later!');

			redirect('customers');			
		}
		else
		{
			$this->session->set_flashdata('success', 'Customer added successfuly!');

			redirect('customers');			
		}						
	}

	public function get_customer_by_id()
	{
		$this->isLoggedIn();
		
		$user_id = $_POST['user_id'];

		$res = $this->Customer_model->get_customer_by_id($user_id);

		echo json_encode($res[0]);
	}

	public function edit_customer()
	{
		$this->isLoggedIn();
		
		$data = $_POST; //POST data to insert to DB		

		unset($data['acu_email']);

		$res = $this->Customer_model->edit_customer($data);
		
		if($res = '0')
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

			$this->db->where('cust_id', $id);
			$this->db->set('is_removed', '1');
			$this->db->update('tbl_customers');
			
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

			$this->db->where('cust_id', $id);
			if(isset($_GET['unarchive'])){$this->db->set('is_archieved', '0'); $archive = 'Un'.$archive;}
			else{$this->db->set('is_archieved', '1');}
			$this->db->update('tbl_customers');
			
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

		$this->db->where('cust_id', $user_id);
		$this->db->update('tbl_customers', $update);

		echo $this->db->affected_rows() == 1 ? json_encode('1') : json_encode('0');	
	}	
}
