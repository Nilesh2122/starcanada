<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rates extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->model('Rates_model'); 
		$this->load->model('Operations_model');
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
		 
	    $allrecord = $this->Rates_model->get_rate_count($title);
		$baseurl =  base_url().'index.php/'.$this->router->class.'/'.$this->router->method."/".$title;
		
	    $paging=array();
		$paging['base_url'] =$baseurl;
		$paging['total_rows'] = $allrecord;
		$paging['per_page'] = 20;
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
		$data['datas'] = $this->Rates_model->get_rate_list($data['limit'],$data['offset'],$title);
		$data['customers'] = $this->Operations_model->get_customers();
		
		$this->load->view('rates',$data);
		$this->load->view('footer');				
	}

	public function get_rate_by_cust()
	{
		$this->isLoggedIn();
		
		$cust_id = $_POST['cust_id'];

		$res = $this->Rates_model->get_rate_by_cust($cust_id);

		echo json_encode($res);
	}

	public function add_rate()
	{
		$this->isLoggedIn();
		$mu_id = $this->session->userdata['mu_id'];
		
		$data = $_POST; //POST data to insert to DB
		$data['mu_id'] = $mu_id;
		
		$data['from_city_latlong'] = $data['from_city_latlong_lat'].','.$data['from_city_latlong_lon'];
		$data['to_city_latlong'] = $data['to_city_latlong_lat'].','.$data['to_city_latlong_lon'];
		
		//unset fields (seperated fields)
		unset($data['from_city_latlong_lat']);
		unset($data['from_city_latlong_lon']);
		unset($data['to_city_latlong_lat']);
		unset($data['to_city_latlong_lon']);

		$res = $this->Rates_model->add_rate($data);
		
		$this->session->set_flashdata('success', 'Rate added successfuly!');

		redirect('rates');							
	}

	public function get_rate_by_id()
	{
		$this->isLoggedIn();
		
		$rate_id = $_POST['rate_id'];

		$res = $this->Rates_model->get_rate_by_id($rate_id);

		echo json_encode($res[0]);
	}

	public function edit_rate()
	{
		$this->isLoggedIn();
		
		$data = $_POST; //POST data to insert to DB

		$res = $this->Rates_model->edit_rate($data);

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
	
	public function change_status()
	{
		$this->isLoggedIn();
		
		$update['is_active'] = $_POST['flag'];
		$rate_id = $_POST['rate_id'];

		$this->db->where('rate_id', $rate_id);
		$this->db->update('tbl_rates', $update);

		echo $this->db->affected_rows() == 1 ? json_encode('1') : json_encode('0');	
	}	
}
