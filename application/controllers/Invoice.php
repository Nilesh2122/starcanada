<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->model('Invoice_model');		
		
		$this->GST = 5;
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


	//Old flow automatic
	// public function receivable()
	// {
	// 	$this->isLoggedIn();		

	// 	$title = '';
	// 	$sdate = '';
	// 	$edate = '';

	// 	if(isset($_POST['title'])){
	// 		$title = $_POST['title'];
	// 	}
	// 	if(isset($_POST['sdate'])){
	// 		$sdate = $_POST['sdate'];
	// 	}
	// 	if(isset($_POST['edate'])){
	// 		$edate = $_POST['edate'];
	// 	}					
		
	// 	$data['search_title'] = $title;		 	   			
	// 	$data['sdate'] = $sdate;
	// 	$data['edate'] = $edate;
							    
	// 	$data['datas'] = $this->Invoice_model->completed_operation_list(15, 0, $title, $sdate, $edate);
	// 	$data['op_status'] = $this->op_status();		
		
	// 	$this->load->view('header');
	// 	$this->load->view('a_receivable',$data);
	// 	$this->load->view('footer');				
	// }

	// public function receivable_more()
	// {
	// 	$op_id = $_POST['op_id'];
	// 	$title = $_POST['title'];
	// 	$edate = $_POST['sdate'];
	// 	$sdate = $_POST['edate'];
		
	// 	$data['op_status'] = $this->op_status();
	// 	$data['datas'] = $this->Invoice_model->completed_more(15, $op_id ,$title, $sdate, $edate);
	// 	echo json_encode($data);
	// }
	
	// public function generate_invoice()
	// {
	// 	$this->isLoggedIn();

	// 	if($this->uri->segment(3))
	// 	{
	// 		$op_id = $this->uri->segment(3);

	// 		$data['operation'] = $this->Invoice_model->fetch_operation_gen_inv($op_id);

	// 		if($data['operation'])
	// 		{
	// 			$data['GST'] = $this->GST;

	// 			$this->load->view('header');
	// 			$this->load->view('gen_invoice',$data);
	// 			$this->load->view('footer');
	// 		}
	// 		else
	// 		{
	// 			redirect('invoice/receivable');
	// 		}
	// 	}
	// 	else
	// 	{
	// 		redirect('invoice/receivable');
	// 	}
	// }

	// public function gen_invoice()
	// {
	// 	if(isset($_POST['op_id']))
	// 	{
	// 		$invoice = $this->Invoice_model->gen_invoice($_POST, $_POST['op_id'], $this->GST);

	// 		if($invoice == '0')
	// 		{
	// 			$this->session->set_flashdata('error', 'Invoice saving failed! Try again.');

	// 			//redirect('invoice/receivable');
	// 			header('location:'.$_SERVER['HTTP_REFERER']);
	// 		}
	// 		else
	// 		{
	// 			if(isset($_GET['send']) && $_GET['send'] == '1'){
	// 				$op = $this->Invoice_model->fetch_operation_gen_inv($_POST['op_id']);

	// 				if($op){

	// 					$subject = 'Invoice for Operation '.$op[0]['op_ref_id'].' - Star Canada Inc.';

	// 					$html = '<h3>Hi, '.$op[0]['cust_cname'].'</h3>
	// 							<p>Here we have attached invoice for your transport operation with Star Canada Inc.</p>
	// 							<p>Below are details of the operation.</p>
	// 							<h3>Product to deliver</h3>
	// 							<p>'.$op[0]['op_product_description'].'</p>
	// 							<h3>Origin</h3>
	// 							<p>'.$op[0]['op_loading_location'].'</p>
	// 							<h3>Deliver to</h3>
	// 							<p>'.$op[0]['op_delivery_location'].'</p>
	// 							<p>*Please find attached below invoice for this operation</p><br><br>
	// 							<p>Star Canada Inc.<br>157 Kulawy Drive NW<br>Edmonton, AB T6L6Y9<br>Tel: (780)485-1232   Fax: (780)450-0932</p>';

	// 					$to = $op[0]['cust_email'];

	// 					$file = 'user_data/invoices_data/'.$op[0]['op_invoice'];

	// 					$send = $this->send_email_attach($to, $subject, $file, $html);

	// 					if($send == '1'){
	// 						$this->session->set_flashdata('success', 'Invoice saved and send successfully.');
	// 						header('location:'.$_SERVER['HTTP_REFERER']);
	// 					}else{
	// 						$this->session->set_flashdata('error', "Invoice saved but can't send email right now! Please try again.");
	// 						header('location:'.$_SERVER['HTTP_REFERER']);	
	// 					}

	// 				}else{
	// 					$this->session->set_flashdata('error', "Invoice saved but can't send email right now! Please try again.");
	// 					header('location:'.$_SERVER['HTTP_REFERER']);
	// 				}
	// 			}
	// 			else{

	// 				$this->session->set_flashdata('success', 'Invoice saved successfully.');

	// 				//header('location:'.base_url().$invoice);
	// 				header('location:'.$_SERVER['HTTP_REFERER']);

	// 			}
	// 		}
	// 	}
	// 	else
	// 	{			
	// 		$this->session->set_flashdata('error', 'Form not submited properly, try again.');

	// 		header('location:'.$_SERVER['HTTP_REFERER']);
	// 	}		
	// }	

	// public function mark_paid()
	// {
	// 	$this->isLoggedIn();

	// 	if(isset($_GET['op_id'])){
	// 		$op_id = $_GET['op_id'];

	// 		$update['op_paid'] = '1';
	// 		$update['op_paid_date'] = date('Y-m-d');

	// 		$this->db->where('op_id', $op_id);
	// 		$this->db->update('tbl_operations', $update);

	// 		if($this->db->affected_rows())
	// 		{
	// 			$this->session->set_flashdata('success', 'Invoice marked as paid!');

	// 			header('location:'.$_SERVER['HTTP_REFERER']);
	// 		}
	// 		else
	// 		{
	// 			$this->session->set_flashdata('error', 'Unable to update status, try again.');

	// 			header('location:'.$_SERVER['HTTP_REFERER']);
	// 		}			
	// 	}	
	// 	else
	// 	{
	// 		redirect('invoice/receivable');
	// 	}
	// }

	//New flow manual
	public function receivable()
	{
		$this->isLoggedIn();		

		$title = '';
		$sdate = '';
		$edate = '';

		if(isset($_POST['title'])){
			$title = $_POST['title'];
		}
		if(isset($_POST['sdate'])){
			$sdate = $_POST['sdate'];
		}
		if(isset($_POST['edate'])){
			$edate = $_POST['edate'];
		}					
		
		$data['search_title'] = $title;		 	   			
		$data['sdate'] = $sdate;
		$data['edate'] = $edate;
							    
		$data['datas'] = $this->Invoice_model->invoices_list(15, 0, $title, $sdate, $edate);
		$data['op_status'] = $this->op_status();		
		$data['customers'] = $this->Invoice_model->get_customers();		
		
		$this->load->view('header');
		$this->load->view('invoices',$data);
		$this->load->view('footer');				
	}

	public function receivable_more()
	{
		$inv_id = $_POST['inv_id'];
		$title = $_POST['title'];
		$edate = $_POST['sdate'];
		$sdate = $_POST['edate'];
		
		$data['op_status'] = $this->op_status();
		$data['datas'] = $this->Invoice_model->invoices_list_more(15, $inv_id ,$title, $sdate, $edate);
		echo json_encode($data);
	}

	public function create()
	{
		$this->isLoggedIn();

		$mu_id = $this->session->userdata['mu_id'];

		$data = $_POST;

		$data['mu_id'] = $mu_id;

		//fetch all pending invoicing operation of customer	
		$this->db->where('cust_id', $data['cust_id']);
		$this->db->where('op_status', '7');
		$this->db->where('op_manual_invoicing', '0');
		$allOp = $this->db->get('tbl_operations')->result_array();

		$data['inv_operations'] = '';
		foreach($allOp as $o){
			$data['inv_operations'] .= $data['inv_operations'] ? ','.$o['op_id'] : $o['op_id'];

			//update op_manual_invoicing
			$this->db->where('op_id', $o['op_id']);
			$this->db->set('op_manual_invoicing', '1');
			$this->db->update('tbl_operations');
		}

		if($data['inv_operations']) {
			$data['created_at'] = date('Y-m-d H:i:s');

			$this->db->insert('tbl_invoice', $data);

			if($this->db->affected_rows() > 0){
				$ID = $this->db->insert_id();
				
				//update inv number
				$this->db->where('inv_id', $ID);
				$this->db->set('inv_number', date('Y').'-'.$ID);
				$this->db->update('tbl_invoice');

				$this->session->set_flashdata('success', 'Invoice created successfully.');
			
				redirect('invoice/receivable');
			}
			else {
				$this->session->set_flashdata('error', 'Failed to create invoice, Try again.');
			
				header('location:'.$_SERVER['HTTP_REFERER']);
			}
		}
		else{
			$this->session->set_flashdata('success', 'No pending operations for invoicing of this customer.');
			
			header('location:'.$_SERVER['HTTP_REFERER']);
		}
	}
	
	public function generate_invoice()
	{
		$this->isLoggedIn();

		if($this->uri->segment(3))
		{
			$op_id = $this->uri->segment(3);

			$data['invoice'] = $this->Invoice_model->fetch_invoice_operations($op_id);
			/*echo "<pre>";
			print_r($data);
			echo "</pre>";
			exit();*/
			if($data['invoice'])
			{
				$data['GST'] = $this->GST;

				$this->load->view('header');
				$this->load->view('gen_invoice_m',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect('invoice/receivable');
			}
		}
		else
		{
			redirect('invoice/receivable');
		}
	}

	public function gen_invoice()
	{
		if(isset($_POST['inv_id']))
		{
			$invoice = $this->Invoice_model->gen_invoice($_POST);

			if($invoice == '0')
			{
				$this->session->set_flashdata('error', 'Invoice saving failed! Try again.');

				//redirect('invoice/receivable');
				header('location:'.$_SERVER['HTTP_REFERER']);
			}
			else
			{
				if(isset($_GET['send']) && isset($_GET['sendEmail'])){
					$op = $this->Invoice_model->fetch_invoice_operations($_POST['inv_id']);

					if($op){

						$subject = 'Invoice for Operations till date - Star Canada Inc.';

						$html = '<h3>Hi, '.$op['operations'][0]['cust_cname'].'</h3>
								<p>Here we have attached invoice for your transport operation with Star Canada Inc.</p>
								<p>Attached invoice includes the operations till the date of today.</p>								
								<p>*Please find attached below invoice for the previous operations</p><br><br>
								<p>Star Canada Inc.<br>157 Kulawy Drive NW<br>Edmonton, AB T6L6Y9<br>Tel: (780)485-1232   Fax: (780)450-0932</p>';

						//$to = $op['operations'][0]['cust_email'];
						$to = trim($_GET['sendEmail']);

						$file = 'user_data/invoices_data/'.$op['inv_invoice'];

						$send = $this->send_email_attach($to, $subject, $file, $html);

						if($send == '1'){
							$this->session->set_flashdata('success', 'Invoice saved and send successfully.');
							header('location:'.$_SERVER['HTTP_REFERER']);
						}else{
							$this->session->set_flashdata('error', "Invoice saved but can't send email right now! Please try again.");
							header('location:'.$_SERVER['HTTP_REFERER']);	
						}

					}else{
						$this->session->set_flashdata('error', "Invoice saved but can't send email right now! Please try again.");
						header('location:'.$_SERVER['HTTP_REFERER']);
					}
				}
				else{

					$this->session->set_flashdata('success', 'Invoice saved successfully.');

					//header('location:'.base_url().$invoice);
					header('location:'.$_SERVER['HTTP_REFERER']);

				}
			}
		}
		else
		{			
			$this->session->set_flashdata('error', 'Form not submited properly, try again.');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}		
	}	

	public function mark_paid()
	{
		$this->isLoggedIn();

		if(isset($_GET['inv_id'])){
			$inv_id = $_GET['inv_id'];

			$update['inv_paid'] = '1';
			$update['inv_paid_date'] = date('Y-m-d');

			$this->db->where('inv_id', $inv_id);
			$this->db->update('tbl_invoice', $update);

			if($this->db->affected_rows())
			{
				$this->session->set_flashdata('success', 'Invoice marked as paid!');

				header('location:'.$_SERVER['HTTP_REFERER']);
			}
			else
			{
				$this->session->set_flashdata('error', 'Unable to update status, try again.');

				header('location:'.$_SERVER['HTTP_REFERER']);
			}			
		}	
		else
		{
			redirect('invoice/receivable');
		}
	}

	public function receivable_delete()
	{
		$this->isLoggedIn();					

		$res = '0';

		if(isset($_GET['id'])){
			$id = base64_decode($_GET['id']);

			//fetch invoice to update all operation
			$this->db->where('inv_id', $id);
			$inv = $this->db->get('tbl_invoice')->result_array();

			if($inv){
				foreach(explode(',', $inv[0]['inv_operations']) as $i){

					$this->db->where('op_id', $i);					
					$this->db->set('op_manual_invoicing', '0');
					$this->db->update('tbl_operations');

				}				
				
				$this->db->where('inv_id', $id);
				$this->db->delete('tbl_invoice');

				if($this->db->affected_rows() > 0){
					$res = '1';
				}
			}									
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

	public function payable()
	{
		$this->isLoggedIn();		

		$title = '';
		$sdate = '';
		$edate = '';

		if(isset($_POST['title'])){
			$title = $_POST['title'];
		}
		if(isset($_POST['sdate'])){
			$sdate = $_POST['sdate'];
		}
		if(isset($_POST['edate'])){
			$edate = $_POST['edate'];
		}					
		
		$data['search_title'] = $title;		 	   			
		$data['sdate'] = $sdate;
		$data['edate'] = $edate;
							    
		$data['datas'] = $this->Invoice_model->payables_list(15, 0, $title, $sdate, $edate);
		$data['op_status'] = $this->op_status();		
		
		$this->load->view('header');
		$this->load->view('a_payable',$data);
		$this->load->view('footer');				
	}

	public function payable_more()
	{
		$st_id = $_POST['st_id'];
		$title = $_POST['title'];
		$edate = $_POST['sdate'];
		$sdate = $_POST['edate'];
		
		$data['op_status'] = $this->op_status();
		$data['datas'] = $this->Invoice_model->payables_more(15, $st_id ,$title, $sdate, $edate);
		echo json_encode($data);
	}

	public function generate_statement()
	{
		$this->isLoggedIn();

		if($this->uri->segment(3))
		{
			$st_id = $this->uri->segment(3);

			$data['statement'] = $this->Invoice_model->fetch_statement_data($st_id);

			if($data['statement'])
			{
				$data['GST'] = $this->GST;

				$this->load->view('header');
				$this->load->view('gen_statement',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect('invoice/payable');
			}
		}
		else
		{
			redirect('invoice/payable');
		}
	}

	public function gen_statement()
	{
		if(isset($_POST['st_id']))
		{			
			$statement = $this->Invoice_model->gen_statement($_POST, $_POST['st_id'], $this->GST);

			if($statement == '0')
			{
				$this->session->set_flashdata('error', 'Statement saving failed! Try again.');

				//redirect('invoice/payable');
				header('location:'.$_SERVER['HTTP_REFERER']);
			}
			else
			{
				if(isset($_GET['send']) && isset($_GET['sendEmail'])){
					$st = $this->Invoice_model->fetch_statement_data($_POST['st_id']);

					if($st){

						$subject = 'Statement of '.$st['st_month_year'].' for '.$st['con_name'].' - Star Canada Inc.';

						$html = '<h3>Hi, '.$st['con_contact_name'].'</h3>
								<p>Here we have attached statement for operations of '.$st['st_month_year'].', You had with Star Canada Inc.</p>
								<p>*Please find attached below statement copy for operations of '.$st['st_month_year'].'</p><br><br>
								<p>Star Canada Inc.<br>157 Kulawy Drive NW<br>Edmonton, AB T6L6Y9<br>Tel: (780)485-1232   Fax: (780)450-0932</p>';

						//$to = $st['con_email'];
						$to = trim($_GET['sendEmail']);

						$file = 'user_data/statements_data/'.$st['st_statement'];

						$send = $this->send_email_attach($to, $subject, $file, $html);

						if($send == '1'){
							$this->session->set_flashdata('success', 'Statement saved and send successfully.');
							header('location:'.$_SERVER['HTTP_REFERER']);
						}else{
							$this->session->set_flashdata('error', "Statement saved but can't send email right now! Please try again.");
							header('location:'.$_SERVER['HTTP_REFERER']);	
						}

					}else{
						$this->session->set_flashdata('error', "Statement saved but can't send email right now! Please try again.");
						header('location:'.$_SERVER['HTTP_REFERER']);
					}
				}
				else{

					$this->session->set_flashdata('success', 'Statement saved successfully');

					//header('location:'.base_url().$invoice);
					header('location:'.$_SERVER['HTTP_REFERER']);

				}				
			}
		}
		else
		{			
			$this->session->set_flashdata('error', 'Form not submited properly, try again.');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}		
	}

	public function statement_paid()
	{
		$this->isLoggedIn();

		if(isset($_GET['st_id'])){
			$st_id = $_GET['st_id'];

			$update['st_paid'] = '1';
			$update['st_paid_on'] = date('Y-m-d');

			$this->db->where('st_id', $st_id);
			$this->db->update('tbl_statement', $update);

			if($this->db->affected_rows())
			{
				$this->session->set_flashdata('success', 'Statement marked as paid!');

				header('location:'.$_SERVER['HTTP_REFERER']);
			}
			else
			{
				$this->session->set_flashdata('error', 'Unable to update status, try again.');

				header('location:'.$_SERVER['HTTP_REFERER']);
			}			
		}	
		else
		{
			redirect('invoice/payable');
		}
	}

	//Vendors statement
	public function vendor_invoice()
	{
		$this->isLoggedIn();		

		$title = '';
		$sdate = '';
		$edate = '';

		if(isset($_POST['title'])){
			$title = $_POST['title'];
		}
		if(isset($_POST['sdate'])){
			$sdate = $_POST['sdate'];
		}
		if(isset($_POST['edate'])){
			$edate = $_POST['edate'];
		}					
		
		$data['search_title'] = $title;		 	   			
		$data['sdate'] = $sdate;
		$data['edate'] = $edate;
							    
		$data['datas'] = $this->Invoice_model->vendor_invoice_list(15, 0, $title, $sdate, $edate);				
		$data['vendors'] = $this->Invoice_model->get_vendors();
		
		$this->load->view('header');
		$this->load->view('vendor_invoices',$data);
		$this->load->view('footer');				
	}

	public function vendor_invoice_more()
	{
		$inv_id = $_POST['vs_id'];
		$title = $_POST['title'];
		$edate = $_POST['sdate'];
		$sdate = $_POST['edate'];
				
		$data['datas'] = $this->Invoice_model->vendor_invoice_list_more(15, $inv_id ,$title, $sdate, $edate);
		echo json_encode($data);
	}

	public function vendor_invoice_create()
	{
		$this->isLoggedIn();

		$mu_id = $this->session->userdata['mu_id'];

		$data = $_POST;

		$data['mu_id'] = $mu_id;
				
		$data['created_at'] = date('Y-m-d H:i:s');

		$this->db->insert('tbl_vendor_statements', $data);

		if($this->db->affected_rows() > 0){
			$ID = $this->db->insert_id();
			
			//update inv number
			$this->db->where('vs_id', $ID);
			$this->db->set('vs_number', date('Y').'-'.$ID);
			$this->db->update('tbl_vendor_statements');

			$this->session->set_flashdata('success', 'Vendor statement created successfully.');
		
			redirect('invoice/vendor_invoice');
		}
		else {
			$this->session->set_flashdata('error', 'Failed to create statement, Try again.');
		
			header('location:'.$_SERVER['HTTP_REFERER']);
		}		
	}
	
	public function generate_vendor_invoice()
	{
		$this->isLoggedIn();

		if($this->uri->segment(3))
		{
			$op_id = $this->uri->segment(3);

			$data['invoice'] = $this->Invoice_model->fetch_vendor_invoice($op_id);

			if($data['invoice'])
			{
				$data['invoice'] = $data['invoice'][0];
				$data['GST'] = $this->GST;

				$this->load->view('header');
				$this->load->view('gen_vendor_invoice',$data);
				$this->load->view('footer');
			}
			else
			{
				redirect('invoice/vendor_invoice');
			}
		}
		else
		{
			redirect('invoice/vendor_invoice');
		}
	}

	public function gen_vendor_invoice()
	{
		if(isset($_POST['vs_id']))
		{
			$invoice = $this->Invoice_model->gen_vendor_invoice($_POST);

			if($invoice == '0')
			{
				$this->session->set_flashdata('error', 'Statement saving failed! Try again.');

				//redirect('invoice/receivable');
				header('location:'.$_SERVER['HTTP_REFERER']);
			}
			else
			{
				if(isset($_GET['send']) && isset($_GET['sendEmail'])){
					$op = $this->Invoice_model->fetch_vendor_invoice($_POST['vs_id']);

					if($op){

						$subject = 'Statement - Star Canada Inc.';

						$html = '<h3>Hi, '.$op[0]['v_name'].'</h3>
								<p>Here we have attached statement of previous operations.</p>								
								<p>*Please find attached below statement for the previous operations</p><br><br>
								<p>Star Canada Inc.<br>157 Kulawy Drive NW<br>Edmonton, AB T6L6Y9<br>Tel: (780)485-1232   Fax: (780)450-0932</p>';

						//$to = $op[0]['v_email'];
						$to = trim($_GET['sendEmail']);

						$file = 'user_data/statements_v_data/'.$op[0]['vs_invoice'];

						$send = $this->send_email_attach($to, $subject, $file, $html);

						if($send == '1'){
							$this->session->set_flashdata('success', 'Statement saved and send successfully.');
							header('location:'.$_SERVER['HTTP_REFERER']);
						}else{
							$this->session->set_flashdata('error', "Statement saved but can't send email right now! Please try again.");
							header('location:'.$_SERVER['HTTP_REFERER']);	
						}

					}else{
						$this->session->set_flashdata('error', "Statement saved but can't send email right now! Please try again.");
						header('location:'.$_SERVER['HTTP_REFERER']);
					}
				}
				else{

					$this->session->set_flashdata('success', 'Statement saved successfully.');

					//header('location:'.base_url().$invoice);
					header('location:'.$_SERVER['HTTP_REFERER']);

				}
			}
		}
		else
		{			
			$this->session->set_flashdata('error', 'Form not submited properly, try again.');

			header('location:'.$_SERVER['HTTP_REFERER']);
		}		
	}	

	public function vendor_invoice_mark_paid()
	{
		$this->isLoggedIn();

		if(isset($_GET['inv_id'])){
			$inv_id = $_GET['inv_id'];

			$update['vs_paid'] = '1';
			$update['vs_paid_date'] = date('Y-m-d');

			$this->db->where('vs_id', $inv_id);
			$this->db->update('tbl_vendor_statements', $update);

			if($this->db->affected_rows())
			{
				$this->session->set_flashdata('success', 'Vendor statement marked as paid!');

				header('location:'.$_SERVER['HTTP_REFERER']);
			}
			else
			{
				$this->session->set_flashdata('error', 'Unable to update status, try again.');

				header('location:'.$_SERVER['HTTP_REFERER']);
			}			
		}	
		else
		{
			redirect('invoice/vendor_invoice');
		}
	}

	public function vendor_invoice_delete()
	{
		$this->isLoggedIn();					

		$res = '0';

		if(isset($_GET['id'])){
			$id = base64_decode($_GET['id']);			
			
			$this->db->where('vs_id', $id);
			$this->db->delete('tbl_vendor_statements');

			if($this->db->affected_rows() > 0){
				$res = '1';
			}
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
