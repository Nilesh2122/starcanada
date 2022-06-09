<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//Dom PDF library
require_once('application/libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf;

class Invoice_model extends CI_Model
{   	 			
	//Old flow automatic
	// public function completed_operation_list($limit,$offset,$title,$sdate,$edate){
	// 	$mu_id = $this->session->userdata['mu_id'];					

	// 	$this->db->select('tbl_operations.*, tbl_customers.cust_number, tbl_customers.cust_name');		
	// 	$this->db->from('tbl_operations');
	// 	$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');				
	// 	$this->db->where('tbl_operations.mu_id', $mu_id);
	// 	$this->db->where('tbl_operations.op_status', '7');
	// 	if(!empty($sdate) && !empty($edate)){
	// 		$this->db->where('tbl_operations.op_invoice_date >=', $sdate);
	// 		$this->db->where('tbl_operations.op_invoice_date <=', $edate);
	// 	}
	// 	if(!empty($title)){
	// 		$this->db->like('tbl_customers.cust_name',$title);			
	// 		$this->db->or_like('tbl_operations.op_ref_id',$title);
	// 	}		
	// 	$this->db->order_by('tbl_operations.op_id','desc');
	// 	$this->db->limit($limit);
	// 	$rs = $this->db->get();
	// 	$data = $rs->result_array();
		
	// 	return $data;		
	// }

	// public function completed_more($limit, $op_id ,$title,$sdate,$edate){
	// 	$mu_id = $this->session->userdata['mu_id'];		

	// 	$this->db->select('tbl_operations.*, tbl_customers.cust_number, tbl_customers.cust_name');
	// 	$this->db->from('tbl_operations');
	// 	$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');					
	// 	$this->db->where('tbl_operations.mu_id', $mu_id);	
	// 	$this->db->where('tbl_operations.op_status', '7');	
	// 	$this->db->where('tbl_operations.op_id <', $op_id);	
	// 	if(!empty($sdate) && !empty($edate)){
	// 		$this->db->where('tbl_operations.op_invoice_date >=', $sdate);
	// 		$this->db->where('tbl_operations.op_invoice_date <=', $edate);
	// 	}
	// 	if(!empty($title)){
	// 		$this->db->like('tbl_customers.cust_name',$title);			
	// 		$this->db->or_like('tbl_operations.op_ref_id',$title);
	// 	}			
	// 	$this->db->order_by('tbl_operations.op_id','desc');
	// 	$this->db->limit($limit);
	// 	$rs = $this->db->get();
	// 	$data = $rs->result_array();

	// 	//return $this->db->last_query();		
	// 	return array_values($data);
	// }		

	// public function fetch_operation_gen_inv($op_id)
	// {
	// 	$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_customers.cust_number, tbl_customers.cust_address, tbl_customers.cust_cname1 as cust_cname, tbl_customers.cust_contact1 as cust_contact, tbl_customers.cust_cemail1 as cust_email');
	// 	$this->db->from('tbl_operations');
	// 	$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');				
	// 	$this->db->where('tbl_operations.op_id', $op_id);		
	// 	$rs = $this->db->get();
	// 	$operation = $rs->result_array();

	// 	return $operation;
	// }

	// public function gen_invoice($data, $op_id, $GST)
	// {	
	// 	$GST = $data['op_GST'] ? $data['op_GST'] : $GST;
		
	// 	$html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	// 		<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
	// 		<style>			
	// 		body {
	// 			font-family: "Source Sans Pro", sans-serif!important;
	// 			line-height:16px;
	// 		}
	// 		</style>
	// 		<body>
	// 		<table border="0" width="100%">
	// 		<tr valign="top">
	// 			<td style="width: 50%;">
	// 				<h3>Star Canada Inc.</h3>
	// 				<p>157 Kulawy Drive NW<br> Edmonton, AB T6L6Y9<br>Tel: (780)485-1232 &nbsp; Fax: (780)450-0932</p>
	// 			</td>
	// 			<td style="width: 50%;" align="right">
	// 				<h3>Invoice</h3>
	// 				<p>'.$data['op_ref_id'].'<br>'.date('m-d-Y').'</p>			
	// 			</td>
	// 		</tr>

	// 		<tr>
	// 			<td style="padding-top: 30px;" colspan="2">
	// 				<p>Bill to:</p>
	// 				<div style="padding: 10px; border:1px solid #c7c7c7; width: 50%;">
	// 					<p style="margin: 0;">                        
	// 						'.$data['cust_name'].'<br>            
	// 						'.$data['cust_address'].'<br>
	// 						'.$data['cust_email'].'<br>
	// 						'.$data['cust_contact'].'
	// 					</p>						
	// 				</div>
	// 			</td>
	// 		</tr>

	// 		<tr>
	// 			<td style="padding-top: 20px;" colspan="2">
	// 				<table border="1" width="100%" cellpadding="2" style="border:1px solid #000;" id="bill-table">
	// 					<thead>
	// 						<tr bgcolor="#bdd6ee">
	// 							<td style="text-align: center;background-color: #f2f2f2;">Date</td>
	// 							<td style="text-align: center;background-color: #f2f2f2;">Description</td>
	// 							<td style="text-align: center;background-color: #f2f2f2;">Ref#</td>
	// 							<td style="text-align: center;background-color: #f2f2f2;">Rate</td>
	// 							<td style="text-align: center;background-color: #f2f2f2;">Amount</td>				
	// 						</tr>
	// 					</thead>                                
	// 					<tbody>
	// 						<tr valign="top">
	// 							<td style="text-align: center">
	// 								'.$data['inv_date'].'
	// 							</td>
	// 							<td style="padding-bottom:40px; text-align: center; width: 45%;">
	// 								'.$data['op_loading_location'].' / '.$data['op_delivery_location'].'									
	// 							</td>
	// 							<td style="text-align: center">
	// 								'.$data['op_c_load_reference'].'									
	// 							</td>
	// 							<td style="text-align: center">
	// 								'.$data['op_mesuare_unit'].'									
	// 							</td>
	// 							<td style="text-align: center">
	// 								$'.$data['op_total_cost'].'									
	// 							</td>
	// 						</tr>';
						
	// 					if(isset($data['exd']) && isset($data['exc'])){
	// 						for($i=0; $i<sizeof($data['exc']); $i++){
	// 							$html .= '<tr valign="top">
	// 							<td style="text-align: center">'.$data['exdate'][$i].'</td>
	// 							<td style="text-align: center; padding-bottom:10px;">'.$data['exd'][$i].'</td>
	// 							<td style="text-align: center">'.$data['exref'][$i].'</td>
	// 							<td style="text-align: center">'.$data['exrate'][$i].'</td>
	// 							<td style="text-align: center">$'.$data['exc'][$i].'</td>
	// 							</tr>';
	// 						}
	// 					}
							
	// 					$html .= '</tbody>';

	// 					$sub_total = $data['op_total_cost'];
	// 					$extracharges = array();

	// 					if(isset($data['exd']) && isset($data['exc'])){
	// 						for($i=0; $i<sizeof($data['exc']); $i++){
	// 							$sub_total += (float)$data['exc'][$i];
	// 							$extracharges[$i]['desc'] = $data['exd'][$i];
	// 							$extracharges[$i]['ammount'] = $data['exc'][$i];
	// 							$extracharges[$i]['date'] = $data['exdate'][$i];
	// 							$extracharges[$i]['rate'] = $data['exrate'][$i];
	// 							$extracharges[$i]['ref'] = $data['exref'][$i];
	// 						}
	// 					}

	// 					$gst = (($sub_total*$GST)/100);
	// 					$total = (($sub_total*$GST)/100) + $sub_total;						

	// 					$html .= '<tfoot>
	// 						<tr>
	// 							<td rowspan="4" colspan="3" valign="center" style="padding-left: 20px;">
	// 								GST No. 138523998 R
	// 							</td> 
	// 							<td align="center">
	// 								<p>SUB TOTAL</p>
	// 							</td>
	// 							<td align="center">
	// 								<p id="sbtval">$'.$sub_total.'</p>
	// 							</td>
	// 						</tr>
	// 						<tr>
	// 							<td align="center">
	// 								<p>GST</p>
	// 							</td>
	// 							<td align="center">
	// 								<p id="gstval">$'.$gst.'</p>
	// 							</td>
	// 						</tr>
	// 						<tr>
	// 							<td>&nbsp;</td>
	// 							<td>&nbsp;</td>
	// 						</tr>
	// 						<tr>                                            
	// 							<td align="center">
	// 								<p>TOTAL</p>
	// 							</td>
	// 							<td align="center" id="optotal">
	// 								<p>$'.$total.'</p>
	// 							</td>
	// 						</tr>
	// 					</tfoot>
	// 				</table>
	// 			</td>
	// 		</tr>
	// 	</table>

	// 	<p style="padding-top:30px">Kindly pay upon receipt of invoice.<br> Thank You.</p>

	// 	</body>';
    //     // echo $html;
    //     // exit();

    //     error_reporting(E_ERROR | E_PARSE);

    //     //PDF Generate
    //     $dompdf = new Dompdf();        
        
    //     $dompdf->loadHtml($html);
        
    //     $dompdf->setPaper('A4');                

    //     $dompdf->render();
                
    //     $output = $dompdf->output();

    //     $filename = 'SC-Invoice-'.$op_id.'.pdf';

    //     if(file_put_contents('user_data/invoices_data/'.$filename, $output) > 0)
    //     {
	// 		if($extracharges){
	// 			$extracharges = json_encode($extracharges);
	// 		}
	// 		else{
	// 			$extracharges = '';
	// 		}

	// 		$this->db->where('op_id', $op_id);            
	// 		$this->db->set('op_invoice', $filename);
	// 		$this->db->set('op_invoice_date', date('Y-m-d'));						
	// 		$this->db->set('op_extra_charge', $extracharges);	
	// 		$this->db->set('op_GST', $GST);
	// 		$this->db->update('tbl_operations');
			
	// 		return 'user_data/invoices_data/'.$filename;
	// 	}
	// 	else
	// 	{
	// 		return 0;
	// 	}
	// }


	//New flow manual
	public function invoices_list($limit,$offset,$title,$sdate,$edate){
		$mu_id = $this->session->userdata['mu_id'];

		$this->db->select('tbl_invoice.*, tbl_customers.cust_number, tbl_customers.cust_name');		
		$this->db->from('tbl_invoice');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_invoice.cust_id');				
		$this->db->where('tbl_invoice.mu_id', $mu_id);		
		if(!empty($sdate) && !empty($edate)){
			$this->db->where('tbl_invoice.created_at >=', $sdate);
			$this->db->where('tbl_invoice.created_at <=', $edate);
		}
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);			
		}		
		$this->db->order_by('tbl_invoice.inv_id','desc');
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;		
	}

	public function invoices_list_more($limit, $inv_id ,$title,$sdate,$edate){
		$mu_id = $this->session->userdata['mu_id'];		

		$this->db->select('tbl_invoice.*, tbl_customers.cust_number, tbl_customers.cust_name');		
		$this->db->from('tbl_invoice');
		$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_invoice.cust_id');				
		$this->db->where('tbl_invoice.mu_id', $mu_id);		
		$this->db->where('tbl_invoice.inv_id <', $inv_id);
		if(!empty($sdate) && !empty($edate)){
			$this->db->where('tbl_invoice.created_at >=', $sdate);
			$this->db->where('tbl_invoice.created_at <=', $edate);
		}
		if(!empty($title)){
			$this->db->like('tbl_customers.cust_name',$title);			
		}		
		$this->db->order_by('tbl_invoice.inv_id','desc');
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;	
	}	
	
	public function get_customers()
	{
		$mu_id = $this->session->userdata['mu_id'];
		
		$this->db->where('mu_id', $mu_id);
		$this->db->where('is_active', '1');
		return $this->db->get('tbl_customers')->result_array();
	}

	public function fetch_invoice_operations($inv_id)
	{
		$this->db->where('inv_id', $inv_id);
		$invoice = $this->db->get('tbl_invoice')->result_array();

		if($invoice){
			$invoice = $invoice[0];
			$op = explode(',', $invoice['inv_operations']);
			$invoice['operations'] = array();

			foreach($op as $o){
				$this->db->select('tbl_operations.*, tbl_customers.cust_name, tbl_customers.cust_number, tbl_customers.cust_address, tbl_customers.cust_cname1 as cust_cname, tbl_customers.cust_contact1 as cust_contact, tbl_customers.cust_cemail1 as cust_email');
				$this->db->from('tbl_operations');
				$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');				
				$this->db->where('tbl_operations.op_id', $o);		
				$rs = $this->db->get()->result_array();
				if($rs){
					$invoice['operations'][] = $rs[0];
				}
			}
		}		

		return $invoice;
	}

	public function gen_invoice($data)
	{			
		$invoice = $this->fetch_invoice_operations($data['inv_id']);
		
		$html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
			<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
			<style>			
			body {
				font-family: "Source Sans Pro", sans-serif!important;
				line-height:16px;
			}
			</style>
			<body>
			<table border="0" width="100%">
			<tr valign="top">
				<td style="width: 50%;">
					<h3>Star Canada Inc.</h3>
					<p>157 Kulawy Drive NW<br> Edmonton, AB T6L6Y9<br>Tel: (780)485-1232 &nbsp; Fax: (780)450-0932</p>
				</td>
				<td style="width: 50%;" align="right">
					<h3>Invoice</h3>
					<p>'.$invoice['inv_number'].'<br>'.explode(' ', $invoice['created_at'])[0].'</p>
				</td>
			</tr>

			<tr>
				<td style="padding-top: 30px;" colspan="2">
					<p>Bill to:</p>
					<div style="padding: 10px; border:1px solid #c7c7c7; width: 50%;">
						<p style="margin: 0;">                        
							'.$invoice['operations'][0]['cust_name'].'<br>            
							'.$invoice['operations'][0]['cust_address'].'<br>
							'.$invoice['operations'][0]['cust_email'].'<br>
							'.$invoice['operations'][0]['cust_contact'].'
						</p>						
					</div>
				</td>
			</tr>

			<tr>
				<td style="padding-top: 20px;" colspan="2">
					<table border="1" width="100%" cellpadding="2" style="border:1px solid #000;" id="bill-table">
						<thead>
							<tr bgcolor="#bdd6ee">
								<td style="text-align: center;background-color: #f2f2f2;">Date</td>
								<td style="text-align: center;background-color: #f2f2f2;">Description</td>
								<td style="text-align: center;background-color: #f2f2f2;">Ref#</td>
								<td style="text-align: center;background-color: #f2f2f2;">Rate</td>
								<td style="text-align: center;background-color: #f2f2f2;">Amount</td>				
							</tr>
						</thead>                                
						<tbody>';

						$sub_total = 0;
						foreach($invoice['operations'] as $op){

						$html .= '<tr valign="top">
								<td style="text-align: center">
									'.date("M d, 'y", strtotime(explode(' ', $op['created_at'])[0])).'
								</td>
								<td style="padding-bottom:40px; text-align: center; width: 45%;">
									'.$op['op_loading_city'].' / '.$op['op_delivery_city'].'
								</td>
								<td style="text-align: center">
									'.$op['op_c_load_reference'].'									
								</td>
								<td style="text-align: center">
									'.$op['op_mesuare_unit'].'									
								</td>
								<td style="text-align: center">
									$'.$op['op_total_cost'].'
								</td>
							</tr>';

							$sub_total += $op['op_total_cost'];
						}
						
						if(isset($data['exd']) && isset($data['exc'])){
							for($i=0; $i<sizeof($data['exc']); $i++){
								$html .= '<tr valign="top">
								<td style="text-align: center">'.$data['exdate'][$i].'</td>
								<td style="text-align: center; padding-bottom:10px;">'.$data['exd'][$i].'</td>
								<td style="text-align: center">'.$data['exref'][$i].'</td>
								<td style="text-align: center">'.$data['exrate'][$i].'</td>
								<td style="text-align: center">$'.$data['exc'][$i].'</td>
								</tr>';
							}
						}
							
						$html .= '</tbody>';
						
						$extracharges = array();

						if(isset($data['exd']) && isset($data['exc'])){
							for($i=0; $i<sizeof($data['exc']); $i++){
								$sub_total += (float)$data['exc'][$i];
								$extracharges[$i]['desc'] = $data['exd'][$i];
								$extracharges[$i]['ammount'] = $data['exc'][$i];
								$extracharges[$i]['date'] = $data['exdate'][$i];
								$extracharges[$i]['rate'] = $data['exrate'][$i];
								$extracharges[$i]['ref'] = $data['exref'][$i];
							}
						}

						$gst = (($sub_total*$data['inv_GST'])/100);
						$total = (($sub_total*$data['inv_GST'])/100) + $sub_total;						

						$html .= '<tfoot>
							<tr>
								<td rowspan="4" colspan="3" valign="center" style="padding-left: 20px;">
									GST No. 138523998 R
								</td> 
								<td align="center">
									<p>SUB TOTAL</p>
								</td>
								<td align="center">
									<p id="sbtval">$'.$sub_total.'</p>
								</td>
							</tr>
							<tr>
								<td align="center">
									<p>GST</p>
								</td>
								<td align="center">
									<p id="gstval">$'.$gst.'</p>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>                                            
								<td align="center">
									<p>TOTAL</p>
								</td>
								<td align="center" id="optotal">
									<p>$'.$total.'</p>
								</td>
							</tr>
						</tfoot>
					</table>
				</td>
			</tr>
		</table>

		<p style="padding-top:30px">Kindly pay upon receipt of invoice.<br> Thank You.</p>

		</body>';
        // echo $html;
        // exit();

        error_reporting(E_ERROR | E_PARSE);

        //PDF Generate
        $dompdf = new Dompdf();        
        
        $dompdf->loadHtml($html);
        
        $dompdf->setPaper('A4');                

        $dompdf->render();
                
        $output = $dompdf->output();

        $filename = 'SC-Invoice-'.$data['inv_id'].'.pdf';

        if(file_put_contents('user_data/invoices_data/'.$filename, $output) > 0)
        {
			if($extracharges){
				$extracharges = json_encode($extracharges);
			}
			else{
				$extracharges = '';
			}

			$this->db->where('inv_id', $data['inv_id']);            
			$this->db->set('inv_invoice', $filename);			
			$this->db->set('inv_extra_charge', $extracharges);	
			$this->db->set('inv_GST', $data['inv_GST']);
			$this->db->update('tbl_invoice');
			
			return 'user_data/invoices_data/'.$filename;
		}
		else
		{
			return 0;
		}
	}

	public function payables_list($limit,$offset,$title,$sdate,$edate){
		$mu_id = $this->session->userdata['mu_id'];					

		$this->db->select('tbl_statement.*, tbl_contractors.con_name, tbl_contractors.con_number');		
		$this->db->from('tbl_statement');		
		$this->db->join('tbl_contractors', 'tbl_contractors.con_id = tbl_statement.con_id');				
		$this->db->where('tbl_statement.mu_id', $mu_id);
		if(!empty($sdate) && !empty($edate)){
			$this->db->where('tbl_statement.created_at >=', date('Y-m-d H:i:s', strtotime($sdate)));
			$this->db->where('tbl_statement.created_at <=', date('Y-m-d H:i:s', strtotime($edate)));
		}
		if(!empty($title)){
			$this->db->like('tbl_contractors.con_name',$title);			
			$this->db->or_like('tbl_contractors.con_number',$title);
		}		
		$this->db->order_by('tbl_statement.st_id','desc');
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;		
	}

	public function payables_more($limit, $st_id ,$title,$sdate,$edate){
		$mu_id = $this->session->userdata['mu_id'];		

		$this->db->select('tbl_statement.*, tbl_contractors.con_name, tbl_contractors.con_number');
		$this->db->from('tbl_statement');		
		$this->db->join('tbl_contractors', 'tbl_contractors.con_id = tbl_statement.con_id');				
		$this->db->where('tbl_statement.mu_id', $mu_id);
		$this->db->where('tbl_statement.st_id <', $st_id);
		if(!empty($sdate) && !empty($edate)){
			$this->db->where('tbl_statement.created_at >=', date('Y-m-d H:i:s', strtotime($sdate)));
			$this->db->where('tbl_statement.created_at <=', date('Y-m-d H:i:s', strtotime($edate)));
		}
		if(!empty($title)){
			$this->db->like('tbl_contractors.cust_name',$title);			
			$this->db->or_like('tbl_contractors.cust_number',$title);
		}		
		$this->db->order_by('tbl_statement.st_id','desc');
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
			
		return array_values($data);
	}

	public function fetch_statement_data($st_id)
	{
		$this->db->select('tbl_statement.*, tbl_contractors.con_number, tbl_contractors.con_name, tbl_contractors.con_commission, tbl_contractors.con_contact_name, tbl_contractors.con_email');		
		$this->db->from('tbl_statement');		
		$this->db->join('tbl_contractors', 'tbl_contractors.con_id = tbl_statement.con_id');
		$this->db->where('tbl_statement.st_id', $st_id);
		$st = $this->db->get()->result_array();

		if($st){
			$ops = explode(',', $st[0]['st_orders']);
			$ops = array_values(array_unique($ops));
			$st[0]['operations'] = array();

			foreach($ops as $op){				
				$this->db->select('tbl_operations.*, tbl_customers.cust_name');						
				$this->db->join('tbl_customers', 'tbl_customers.cust_id = tbl_operations.cust_id');
				$this->db->where('tbl_operations.op_id', $op);
				$operation = $this->db->get('tbl_operations')->result_array();

				if($operation){
					$st[0]['operations'][] = $operation[0];
				}
			}			

			return $st[0];
		}
		else
		{
			return $st;
		}
	}

	public function gen_statement($data, $st_id, $GST)
	{
		$deduct = array();

		if(isset($data['desc']) && isset($data['amount'])){
			for($i=0; $i<sizeof($data['desc']); $i++){
				$deduct[$i]['desc'] = $data['desc'][$i];
				$deduct[$i]['amount'] = $data['amount'][$i];				
			}

			if($deduct){
				$this->db->where('st_id', $st_id);
				$this->db->set('st_deduction', json_encode($deduct));
				$this->db->update('tbl_statement');
			}
		}
		
		$statement = $this->fetch_statement_data($st_id);		

		$html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
			<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
			<style>			
			body {
				font-family: "Source Sans Pro", sans-serif!important;
				line-height:16px;
			}
			</style>
			<body>

			<table border="0" width="100%">
			<tr>
				<td colspan="2" width="100%">
					<h3 style="text-align:center; margin-bottom:0px;">Star Canada Inc.</h3>
					<p style="text-align:center;">'.$statement['st_month_year'].' Statement for '.$statement['con_name'].'</p>
				</td>
			</tr>
			<tr>
				<td width="50%">                                
					<p>GST No.: 138523998 RT 0001</p>                                
				</td>
				<td width="50%" align="right">                                
					<p>Statement No.: '.$statement['st_no'].'</p>
				</td>
			</tr>                        

			<tr>
				<td style="padding-top: 20px;" colspan="2">
					<table border="1" width="100%" cellpadding="2" style="border:1px solid #000;" id="bill-table">
						<thead>
							<tr bgcolor="#bdd6ee">
								<th style="text-align: center;background-color: #f2f2f2;">Date</th>
								<th style="text-align: center;background-color: #f2f2f2;">Customer</th>
								<th style="text-align: center;background-color: #f2f2f2;">Origin</th>
								<th style="text-align: center;background-color: #f2f2f2;">Destination</th>
								<th style="text-align: center;background-color: #f2f2f2;">Amount</th>
							</tr>
						</thead>                                
						<tbody>';
							$total = 0; foreach($statement['operations'] as $op){ $total += (float)$op['op_total_cost'];
						$html .= '<tr valign="top">
								<td style="text-align: center;padding:10px;">
									'.date('m/d/Y', strtotime($op['created_at'])).'
								</td>
								<td style="text-align: center;padding:10px;">
									'.$op['cust_name'].'
								</td>
								<td style="text-align: center;padding:10px;">  
									'.$op['op_loading_city'].'
								</td>
								<td style="text-align: center;padding:10px;">                                                
									'.$op['op_delivery_city'].'
								</td>
								<td style="text-align: right;padding:10px;">
									'.$op['op_total_cost'].'
								</td>
							</tr>';
							}

						$html .= '<tr>
								<td style="padding:10px"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding:10px"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding:10px"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding:10px"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td style="padding:10px"></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>                                                                               
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4" valign="center" align="right" style="padding:10px;">
									Gross Earnings:
								</td>
								<td valign="center" align="right" style="padding:10px;">
									'.$total.'
								</td>
							</tr>                                        
						</tfoot>
					</table>
				</td>
			</tr>
			
			<tr>
				<td style="padding-top: 25px;" colspan="2">
					<p>Deductions:</p>
				</td>
			</tr>

			<tr>
				<td style="padding-top: 8px;" colspan="2">
					<table id="DeductionTable" border="1" width="100%" cellpadding="2" style="border:1px solid #000;" id="bill-table">
						<thead>
							<tr bgcolor="#bdd6ee">
								<th style="text-align: center;background-color: #f2f2f2;">Description</th>
								<th style="text-align: center;background-color: #f2f2f2;">Amount</th>
								<th style="text-align: center;background-color: #f2f2f2;">GST</th>
								<th style="text-align: center;background-color: #f2f2f2;">Total</th>                                            
							</tr>
						</thead>                                
						<tbody>';

						$dedusctions = 0; $dedusctionsGST = 0; $dedusctionsTOTAL = 0; $GST = $statement['st_GST'] ? $statement['st_GST'] : $GST;
						$dispatch = (((float)$statement['con_commission']*(float)$total)/100);
						$dispatchGST = (((float)$GST*(float)$dispatch)/100); 

						$html .= '<tr valign="top">
								<td style="padding:10px;">
									Dispatch
								</td>
								<td style="text-align: right;padding:10px;">
									$'.$dispatch.'
								</td>
								<td style="text-align: right;padding:10px;">  
									$'.$dispatchGST.'
								</td>
								<td style="text-align: right;padding:10px;">                                                
									$'.((float)$dispatch+(float)$dispatchGST).'
								</td>                                            
							</tr>';
							
							$dedusctions += ($dispatch); $dedusctionsGST += ($dispatchGST); $dedusctionsTOTAL = ($dedusctions+$dedusctionsGST);

							if($statement['st_deduction']){ $ded = json_decode($statement['st_deduction'], true); 
							foreach($ded as $d){	
								
								//$dedusctions += (((float)$GST*(float)$d['amount'])/100);
								$dedusctions += $d['amount']; 
								$dedusctionsGST += (((float)$GST*(float)$d['amount'])/100);
								$dedusctionsTOTAL += ((($GST*$d['amount'])/100)+$d['amount']);

								$DISPLAYGST = (($GST*$d['amount'])/100);
								$DISPLAYTD = ((($GST*$d['amount'])/100)+$d['amount']);
							
						$html .= '<tr valign="top">
								<td style="padding:10px;">
									'.$d['desc'].'									
								</td>
								<td style="text-align: right;padding:10px;">
									$'.$d['amount'].'									
								</td>
								<td style="text-align: right;padding:10px;">
									$'.$DISPLAYGST.'
								</td>
								<td style="text-align: right;padding:10px;">
									$'.$DISPLAYTD.'
								</td>                                            
							</tr>';							

							} }else{

						$html .= '<tr valign="top">
								<td style="padding:10px;">
									Fuel Charge 									
								</td>
								<td style="text-align: right;padding:10px;">
									$0									
								</td>
								<td style="text-align: right;padding:10px;">
									$0
								</td>
								<td style="text-align: right;padding:10px;">
									$0
								</td>                                            
							</tr>

							<tr valign="top">
								<td style="padding:10px;">
									Administration Fee									
								</td>
								<td style="text-align: right;padding:10px;">
									$0									
								</td>
								<td style="text-align: right;padding:10px;">  
									$0
								</td>
								<td style="text-align: right;padding:10px;">                                                
									$0
								</td>                                            
							</tr>

							<tr valign="top">
								<td style="padding:10px;">
									Insurance									
								</td>
								<td style="text-align: right;padding:10px;">
									$0									
								</td>
								<td style="text-align: right;padding:10px;">  
									$0
								</td>
								<td style="text-align: right;padding:10px;">                                                
									$0
								</td>                                            
							</tr>'; 
						}

					$DISPLAYNP = ($total-$dedusctionsTOTAL);
						
					$html .= '</tbody>
						<tfoot>
							<tr valign="top">
								<td style="text-align: center;padding:10px;">
									Total Deductions
								</td>
								<td style="text-align: right;padding:10px;">
									$'.$dedusctions.'
								</td>
								<td style="text-align: right;padding:10px;">  
									$'.$dedusctionsGST.'
								</td>
								<td style="text-align: right;padding:10px;">                                                
									$'.$dedusctionsTOTAL.'
								</td>                                            
							</tr>
							
							<tr>
								<td colspan="3" style="padding: 10px;" align="right">Net Payable</td>
								<td style="padding: 10px;" align="right">$'.$DISPLAYNP.'</td>
							</tr>
						</tfoot>                                    
					</table>
				</td>
			</tr>                        
		</table>
			

		<p style="padding-top:30px; text-align:center;">Please check your statement for any discrepancy. After 30 days there will be no claim.<br>
		Please make copy of all the load you do to support claim. Any missed load must be accompanied by a copy of Proof of Delivery.</p>

		</body>';
		
		// echo $html;
        // exit();

        error_reporting(E_ERROR | E_PARSE);

        //PDF Generate
        $dompdf = new Dompdf();        
        
        $dompdf->loadHtml($html);
        
        $dompdf->setPaper('A4');                

        $dompdf->render();
                
        $output = $dompdf->output();

        $filename = 'SC-Statement-'.$st_id.'.pdf';

        if(file_put_contents('user_data/statements_data/'.$filename, $output) > 0)
        {
			$this->db->where('st_id', $st_id);
			$this->db->set('st_statement', $filename);
			$this->db->set('st_GST', $data['st_GST']);
			$this->db->update('tbl_statement');
			
			return 'user_data/statements_data/'.$filename;
		}
		else
		{
			return 0;
		}
	}

	//New flow manual
	public function vendor_invoice_list($limit,$offset,$title,$sdate,$edate){
		$mu_id = $this->session->userdata['mu_id'];

		$this->db->select('tbl_vendor_statements.*, tbl_vendors.v_number, tbl_vendors.v_name');		
		$this->db->from('tbl_vendor_statements');
		$this->db->join('tbl_vendors', 'tbl_vendors.v_id = tbl_vendor_statements.v_id');				
		$this->db->where('tbl_vendor_statements.mu_id', $mu_id);
		if(!empty($sdate) && !empty($edate)){
			$this->db->where('tbl_vendor_statements.created_at >=', $sdate);
			$this->db->where('tbl_vendor_statements.created_at <=', $edate);
		}
		if(!empty($title)){
			$this->db->like('tbl_vendors.v_number',$title);			
			$this->db->or_like('tbl_vendors.v_name',$title);			
		}		
		$this->db->order_by('tbl_vendor_statements.vs_id','desc');
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;		
	}

	public function vendor_invoice_list_more($limit, $inv_id ,$title,$sdate,$edate){
		$mu_id = $this->session->userdata['mu_id'];

		$this->db->select('tbl_vendor_statements.*, tbl_vendors.v_number, tbl_vendors.v_name');		
		$this->db->from('tbl_vendor_statements');
		$this->db->join('tbl_vendors', 'tbl_vendors.v_id = tbl_vendor_statements.v_id');				
		$this->db->where('tbl_vendor_statements.mu_id', $mu_id);
		$this->db->where('tbl_vendor_statements.vs_id <', $inv_id);
		if(!empty($sdate) && !empty($edate)){
			$this->db->where('tbl_vendor_statements.created_at >=', $sdate);
			$this->db->where('tbl_vendor_statements.created_at <=', $edate);
		}
		if(!empty($title)){
			$this->db->like('tbl_vendors.v_number',$title);			
			$this->db->or_like('tbl_vendors.v_name',$title);			
		}		
		$this->db->order_by('tbl_vendor_statements.vs_id','desc');
		$this->db->limit($limit);
		$rs = $this->db->get();
		$data = $rs->result_array();
		
		return $data;	
	}	
	
	public function get_vendors()
	{
		$mu_id = $this->session->userdata['mu_id'];
		
		$this->db->where('mu_id', $mu_id);
		$this->db->where('is_active', '1');
		return $this->db->get('tbl_vendors')->result_array();
	}

	public function fetch_vendor_invoice($inv_id)
	{
		$this->db->select('tbl_vendor_statements.*, tbl_vendors.v_number, tbl_vendors.v_name, tbl_vendors.v_address, tbl_vendors.v_cemail1 as v_email, tbl_vendors.v_contact1 as v_contact');
		$this->db->join('tbl_vendors', 'tbl_vendors.v_id = tbl_vendor_statements.v_id');
		$this->db->where('vs_id', $inv_id);
		$invoice = $this->db->get('tbl_vendor_statements')->result_array();

		return $invoice;
	}

	public function gen_vendor_invoice($data)
	{			
		$invoice = $this->fetch_vendor_invoice($data['vs_id']);
		if($invoice){

		$invoice = $invoice[0];
		
		$html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
			<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
			<style>			
			body {
				font-family: "Source Sans Pro", sans-serif!important;
				line-height:16px;
			}
			</style>
			<body>
			<table border="0" width="100%">
			<tr valign="top">
				<td style="width: 50%;">
					<h3>Star Canada Inc.</h3>
					<p>157 Kulawy Drive NW<br> Edmonton, AB T6L6Y9<br>Tel: (780)485-1232 &nbsp; Fax: (780)450-0932</p>
				</td>
				<td style="width: 50%;" align="right">
					<h3>Statement</h3>
					<p>'.$invoice['vs_number'].'<br>'.explode(' ', $invoice['created_at'])[0].'</p>
				</td>
			</tr>

			<tr>
				<td style="padding-top: 30px;" colspan="2">
					<p>Statement for:</p>
					<div style="padding: 10px; border:1px solid #c7c7c7; width: 50%;">
						<p style="margin: 0;">                        
							'.$invoice['v_name'].'<br>            
							'.$invoice['v_address'].'<br>
							'.$invoice['v_email'].'<br>
							'.$invoice['v_contact'].'
						</p>						
					</div>
				</td>
			</tr>

			<tr>
				<td style="padding-top: 20px;" colspan="2">
					<table border="1" width="100%" cellpadding="2" style="border:1px solid #000;" id="bill-table">
						<thead>
							<tr bgcolor="#bdd6ee">
								<td style="text-align: center;background-color: #f2f2f2;">Date</td>
								<td style="text-align: center;background-color: #f2f2f2;">Description</td>
								<td style="text-align: center;background-color: #f2f2f2;">Ref#</td>
								<td style="text-align: center;background-color: #f2f2f2;">Rate</td>
								<td style="text-align: center;background-color: #f2f2f2;">Amount</td>				
							</tr>
						</thead>                                
						<tbody>';

						$sub_total = 0;
						
						if(isset($data['exd']) && isset($data['exc'])){
							for($i=0; $i<sizeof($data['exc']); $i++){
								$html .= '<tr valign="top">
								<td style="text-align: center">'.$data['exdate'][$i].'</td>
								<td style="text-align: center; padding-bottom:10px;">'.$data['exd'][$i].'</td>
								<td style="text-align: center">'.$data['exref'][$i].'</td>
								<td style="text-align: center">'.$data['exrate'][$i].'</td>
								<td style="text-align: center">$'.$data['exc'][$i].'</td>
								</tr>';
							}
						}
							
						$html .= '</tbody>';
						
						$extracharges = array();

						if(isset($data['exd']) && isset($data['exc'])){
							for($i=0; $i<sizeof($data['exc']); $i++){
								$sub_total += (float)$data['exc'][$i];
								$extracharges[$i]['desc'] = $data['exd'][$i];
								$extracharges[$i]['ammount'] = $data['exc'][$i];
								$extracharges[$i]['date'] = $data['exdate'][$i];
								$extracharges[$i]['rate'] = $data['exrate'][$i];
								$extracharges[$i]['ref'] = $data['exref'][$i];
							}
						}

						$gst = (($sub_total*$data['vs_GST'])/100);
						$total = (($sub_total*$data['vs_GST'])/100) + $sub_total;						

						$html .= '<tfoot>
							<tr>
								<td rowspan="4" colspan="3" valign="center" style="padding-left: 20px;">
									GST No. 138523998 R
								</td> 
								<td align="center">
									<p>SUB TOTAL</p>
								</td>
								<td align="center">
									<p id="sbtval">$'.$sub_total.'</p>
								</td>
							</tr>
							<tr>
								<td align="center">
									<p>GST</p>
								</td>
								<td align="center">
									<p id="gstval">$'.$gst.'</p>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>                                            
								<td align="center">
									<p>TOTAL</p>
								</td>
								<td align="center" id="optotal">
									<p>$'.$total.'</p>
								</td>
							</tr>
						</tfoot>
					</table>
				</td>
			</tr>
		</table>		

		</body>';
        // echo $html;
        // exit();

        error_reporting(E_ERROR | E_PARSE);

        //PDF Generate
        $dompdf = new Dompdf();        
        
        $dompdf->loadHtml($html);
        
        $dompdf->setPaper('A4');                

        $dompdf->render();
                
        $output = $dompdf->output();

        $filename = 'SC-Vendor-Statement-'.$data['vs_id'].'.pdf';

        if(file_put_contents('user_data/statements_v_data/'.$filename, $output) > 0)
        {
			if($extracharges){
				$extracharges = json_encode($extracharges);
			}
			else{
				$extracharges = '';
			}

			$this->db->where('vs_id', $data['vs_id']);            
			$this->db->set('vs_invoice', $filename);			
			$this->db->set('vs_extra_charge', $extracharges);	
			$this->db->set('vs_GST', $data['vs_GST']);
			$this->db->update('tbl_vendor_statements');
			
			return 'user_data/statements_v_data/'.$filename;
		}
		else
		{
			return 0;
		}

		}
		else{
			return 0;
		}
	}
}

  