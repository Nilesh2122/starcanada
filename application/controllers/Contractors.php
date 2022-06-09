<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contractors extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->model('Contractors_model'); 						
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
		 
	    $allrecord = $this->Contractors_model->get_contractor_count($title);
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
		$data['datas'] = $this->Contractors_model->get_contractor_list($data['limit'],$data['offset'],$title);
		
		$this->load->view('contractors',$data);
		$this->load->view('footer');				
	}

	public function add_contractor()
	{
		$this->isLoggedIn();
		$mu_id = $this->session->userdata['mu_id'];
		
		$data = $_POST; //POST data to insert to DB
		$data['mu_id'] = $mu_id;	

		$res = $this->Contractors_model->add_contractor($data);
		
		$this->session->set_flashdata('success', 'Contractor added successfully!');

		redirect('contractors');		
	}

	public function get_contractor_by_id()
	{
		$this->isLoggedIn();
		
		$con_id = $_POST['con_id'];

		$res = $this->Contractors_model->get_contractor_by_id($con_id);

		echo json_encode($res[0]);
	}

	public function edit_contractor()
	{
		$this->isLoggedIn();
		
		$data = $_POST; //POST data to insert to DB

		$res = $this->Contractors_model->edit_contractor($data);

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

			$this->db->where('con_id', $id);
			$this->db->set('is_active', '0');
			$this->db->set('is_removed', '1');
			$this->db->update('tbl_contractors');
			
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

	public function deleteEq()
	{
		$this->isLoggedIn();					

		$res = '0';

		if(isset($_GET['id'])){
			$id = base64_decode($_GET['id']);

			$this->db->where('eq_id', $id);
			$this->db->set('is_removed', '1');
			$this->db->update('tbl_equipments');
			
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

			$this->db->where('con_id', $id);
			if(isset($_GET['unarchive'])){$this->db->set('is_archieved', '0'); $archive = 'Un'.$archive;}
			else{$this->db->set('is_archieved', '1');}
			$this->db->update('tbl_contractors');
			
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
	
	public function get_drivers_for_contractor()
	{
		$this->isLoggedIn();
		
		$con_id = $_POST['con_id'];

		$res = $this->Contractors_model->get_drivers_for_contractor($con_id);

		echo json_encode($res);
	}

	public function add_equipment()
	{
		$this->isLoggedIn();
		$mu_id = $this->session->userdata['mu_id'];
		
		$data = $_POST; //POST data to insert to DB
		$data['mu_id'] = $mu_id;	

		$res = $this->Contractors_model->add_equipment($data);
		
		$this->session->set_flashdata('success', 'Equipment added successfully!');

		redirect('contractors');		
	}

	public function get_equipments_for_contractor()
	{
		$this->isLoggedIn();
		
		$con_id = $_POST['con_id'];

		$res = $this->Contractors_model->get_equipments_for_contractor($con_id);

		echo json_encode($res);
	}

	public function edit_equipment()
	{
		$this->isLoggedIn();
		
		$data = $_POST; //POST data to insert to DB

		$res = $this->Contractors_model->edit_equipment($data);

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
}
