<div id="page-wrapper">

    <a class="SITE-BACK" href="javascript:history.back();"><i class="fa fa-angle-double-left"></i> Back</a>

    <div class="main-page">
    <div class="forms">
    
    	<div class=" form-grids row form-grids-right" style="margin-top:0">
            <div class="widget-shadow " data-example-id="basic-forms"> 
                <div class="form-title">
                    <h4>Generate Statement</h4>
                </div>
                <div class="form-body">
                <form id="GEN-FORM" action="<?php echo base_url(); ?>index.php/invoice/gen_statement" method="post" class="form-horizontal">  

                    <div class="row">                     
                    <div class="col-md-10 col-md-offset-1">
                    <div style="border: 1px solid #323232; padding: 15px;">
                    <table border="0" width="100%">
                        <tr>
                            <td colspan="2" width="100%">
                                <h3 style="text-align:center;">Star Canada Inc.</h3>
                                <p style="text-align:center;"><?php echo $statement['st_month_year']; ?> Statement for <?php echo $statement['con_name']; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%">                                
                                <p>GST No.: 138523998 RT 0001</p>                                
                            </td>
                            <td width="50%" align="right">                                
                                <p>Statement No.: <?php echo $statement['st_no']; ?></p>
                            </td>
                        </tr>                        

                        <tr>
                            <td style="padding-top: 20px;" colspan="2">
                                <table border="1" width="100%" cellpadding="2" style="border:1px solid #000;" id="bill-table">
                                    <thead>
                                        <tr bgcolor="#bdd6ee">
                                            <th style="text-align: center;background-color: #f2f2f2;">Date</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Customer</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Order No</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Origin</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Destination</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Amount</th>
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php $total = 0; foreach($statement['operations'] as $op){ $total += $op['op_total_cost']; ?>
                                        <tr valign="top">
                                            <td style="text-align: center;padding:10px;">
                                                <?php echo date('m/d/Y', strtotime($op['created_at'])); ?> 
                                            </td>
                                            <td style="text-align: center;padding:10px;">
                                                <?php echo $op['cust_name']; ?>
                                            </td>
                                            <td style="text-align: center;padding:10px;">
                                                <?php echo $op['op_c_load_reference']; ?>
                                            </td>
                                            <td style="text-align: center;padding:10px;">  
                                                <?php echo $op['op_loading_city']; ?>
                                            </td>
                                            <td style="text-align: center;padding:10px;">                                                
                                                <?php echo $op['op_delivery_city']; ?>
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $<?php echo $op['op_total_cost']; ?>
                                            </td>
                                        </tr>  
                                        <?php } ?>  
                                        <tr>
                                            <td style="padding:10px"></td>
                                            <td></td>
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
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:10px"></td>
                                            <td></td>
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
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:10px"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>                                                                               
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" valign="center" align="right" style="padding:10px;">
                                                Gross Earnings:
                                            </td>
                                            <td valign="center" align="right" style="padding:10px;">
                                                $<?php echo $total; ?>
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
                                    <tbody>
                                        <?php   $updateFuel = 0; $updateAdmin = 0; $updateInsurance = 0;
                                                $dedusctions = 0; $dedusctionsGST = 0; $dedusctionsTOTAL = 0; $GST = $statement['st_GST'] ? $statement['st_GST'] : $GST; ?>
                                        <tr valign="top">
                                            <td style="padding:10px;">
                                                Dispatch
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $<?php $dispatch = (($statement['con_commission']*$total)/100); echo $dispatch; ?>                                                                                                
                                            </td>
                                            <td style="text-align: right;padding:10px;">  
                                                $<?php $dispatchGST = (($GST*$dispatch)/100); echo $dispatchGST; ?>
                                            </td>
                                            <td style="text-align: right;padding:10px;">                                                
                                                $<?php echo $dispatch+$dispatchGST; ?>
                                            </td>                                            
                                        </tr>
                                        
                                        <?php $TEC = 0; $dedusctions += $dispatch; $dedusctionsGST += $dispatchGST; $dedusctionsTOTAL = ($dedusctions+$dedusctionsGST); ?>

                                        <?php   if($statement['st_deduction']){ $ded = json_decode($statement['st_deduction'], true); 
                                                foreach($ded as $d){ $TEC++;

                                                $RID = '';

                                                if($d['desc'] == 'Fuel Charge'){
                                                    $updateFuel = $d['amount'];
                                                    $RID = 'FC';
                                                }
                                                else if($d['desc'] == 'Administration Fee'){
                                                    $updateAdmin = $d['amount'];
                                                    $RID = 'AC';
                                                }
                                                else if($d['desc'] == 'Insurance'){
                                                    $updateInsurance = $d['amount'];
                                                    $RID = 'IC';
                                                }
                                                
                                        ?>
                                        
                                        <tr valign="top" id="<?php echo $RID; ?>">
                                            <td style="padding:10px;">
                                                <?php echo $d['desc']; ?>
                                                <?php if($TEC > 3){ ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" onclick="$(this).closest('tr').remove(); calculateTD();">Remove</a>
                                                <?php } ?>
                                                <input type="hidden" name="desc[]" value="<?php echo $d['desc'] ?>">
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $<?php echo $d['amount'] ?>
                                                <input type="hidden" name="amount[]" value="<?php echo $d['amount'] ?>">
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $<?php echo (($GST*$d['amount'])/100); //$dedusctions += (($GST*$d['amount'])/100);  ?>
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $<?php echo ((($GST*$d['amount'])/100)+$d['amount']); $dedusctions += $d['amount']; $dedusctionsGST += (($GST*$d['amount'])/100); ?>
                                            </td>                                            
                                        </tr>

                                        <?php $dedusctionsTOTAL += ((($GST*$d['amount'])/100)+$d['amount']); ?>

                                        <?php } }else{ ?>

                                        <tr valign="top" id="FC">
                                            <td style="padding:10px;">
                                                Fuel Charge 
                                                <input type="hidden" name="desc[]" value="Fuel Charge">
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $0
                                                <input type="hidden" name="amount[]" value="0">
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $0
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $0
                                            </td>                                            
                                        </tr>

                                        <tr valign="top" id="AC">
                                            <td style="padding:10px;">
                                                Administration Fee
                                                <input type="hidden" name="desc[]" value="Administration Fee">
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $0
                                                <input type="hidden" name="amount[]" value="0">
                                            </td>
                                            <td style="text-align: right;padding:10px;">  
                                                $0
                                            </td>
                                            <td style="text-align: right;padding:10px;">                                                
                                                $0
                                            </td>                                            
                                        </tr>

                                        <tr valign="top" id="IC">
                                            <td style="padding:10px;">
                                                Insurance
                                                <input type="hidden" name="desc[]" value="Insurance">
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $0
                                                <input type="hidden" name="amount[]" value="0">
                                            </td>
                                            <td style="text-align: right;padding:10px;">  
                                                $0
                                            </td>
                                            <td style="text-align: right;padding:10px;">                                                
                                                $0
                                            </td>                                            
                                        </tr>  
                                        <?php } ?>                                                                              
                                    </tbody>
                                    <tfoot>
                                        <tr valign="top">
                                            <td style="text-align: center;padding:10px;">
                                                Total Deductions
                                            </td>
                                            <td style="text-align: right;padding:10px;">
                                                $<?php echo $dedusctions ?>
                                            </td>
                                            <td style="text-align: right;padding:10px;">  
                                                $<?php echo $dedusctionsGST ?>
                                            </td>
                                            <td style="text-align: right;padding:10px;">                                                
                                                $<?php echo $dedusctionsTOTAL ?>
                                            </td>                                            
                                        </tr>

                                        <tr>
                                            <td colspan="3" style="padding: 10px;" align="right">Net Payable</td>
                                            <td style="padding: 10px;" align="right">$<?php echo ($total-$dedusctionsTOTAL); ?></td>
                                        </tr>
                                    </tfoot>                                    
                                </table>
                            </td>
                        </tr>                        
                    </table>
                    </div>
                    </div>
                    </div>
                    
                    <input type="hidden" name="st_GST" value="<?php echo $GST; ?>">
                    <input type="hidden" name="st_id" value="<?php echo $statement['st_id']; ?>">
                    
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1" style="text-align: right;"> 
                        <button type="submit" class="btn btn-default">Save</button> 
                        <button type="button" class="btn btn-default save-and-send" style="margin-left:5px;">Save & Send</button> 
                        </div>          
                    </div>                               
                    
                </form>  


                <form action="#" method="post" id="updateCharges">   
                    <div class="row">
                    <div class="col-md-10 col-md-offset-1" style="padding: 0;">
                    <div style="background-color: #f8f8f8; border: 1px solid #c7c7c7; padding: 20px 0; margin: 0 15px;">
                                                
                        <p style="padding: 10px 15px;font-size: 20px;background-color: #ddd;margin-top: -20px;margin-bottom: 15px;">Default Deductions</p>

                        <div class="row" style="margin-top: 0;">                      
                         
                        <div class="col-md-4">
                        <div class="form-group"> 
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Fuel Charge</label> 
                            <input type="number" step="any" class="form-control" name="fcharge" placeholder="Fuel Charge" required="required" value="<?php echo $updateFuel; ?>">
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="form-group"> 
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Administration Fee</label> 
                            <input type="number" step="any" class="form-control" name="acharge" placeholder="Administrator Fee" required="required" value="<?php echo $updateAdmin; ?>">
                        </div>
                        </div>

                        <div class="col-md-4">
                        <div class="form-group"> 
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Insurance</label>
                            <input type="number" step="any" class="form-control" name="icharge" placeholder="Insurance" required="required" value="<?php echo $updateInsurance; ?>">
                        </div>
                        </div>

                        </div>
                        
                        <button type="submit" class="btn btn-default" style="float: right; margin-right: 15px;">Update</button>                     

                        <div class="clearfix"></div>

                    </div>

                    </div>
                    </div>
                </form>


                <form action="#" method="post" id="AddDeductions" autocomplete="off">   
                    <div class="row">
                    <div class="col-md-10 col-md-offset-1" style="padding: 0;">
                    <div style="background-color: #f8f8f8; border: 1px solid #c7c7c7; padding: 20px 0; margin: 0 15px;">
                                                
                        <p style="padding: 10px 15px;font-size: 20px;background-color: #ddd;margin-top: -20px;margin-bottom: 15px;">Additional Deduction</p>

                        <div class="row" style="margin-top: 0;">                      
                         
                        <div class="col-md-6">
                        <div class="form-group"> 
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Description</label> 
                            <input type="text" class="form-control" name="desc" placeholder="Description" required="required">
                        </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group"> 
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Amount</label> 
                            <input type="number" step="any" class="form-control" name="amt" placeholder="Amount" required="required">
                        </div>
                        </div>                                                   

                        </div>
                        
                        <button type="submit" class="btn btn-default" style="float: right; margin-right: 15px;">Add</button>                     

                        <div class="clearfix"></div>

                    </div>

                    </div>
                    </div>
                </form>
                                
                </div>
                
            </div>
        </div>
    
    </div>
    </div>

    <!-- Modal for send email confirmation -->
    <!-- Modal -->
    <div id="sendInvoice" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 350px;">

        <!-- Modal content-->
        <div class="modal-content">        
        <div class="modal-body" style="border: 0;">
            <h3 style="margin-bottom: 15px;">Are you sure?
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </h3>

            <p>You want to send this Statement to</p>

            <input type="text" class="form-control" id="sendEmail" value="<?php echo $statement['con_email']; ?>" style="margin: 10px 0;">

            <p>Or you can modify above email address!</p>
        </div>
        <div class="modal-footer" style="border: 0;">
            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            <button type="button" class="btn btn-success send-Invoice">Yes</button>
        </div>
        </div>

    </div>
    </div>
    
</div>

<script>
$('#updateCharges').submit(function(){
    var form = $(this).serializeArray();

    //get form data in array
    var formdata = [];
    for (var i = 0; i < form.length; i++) {
        formdata[form[i]['name']] = form[i]['value'];
    }

    $('#FC td:nth-child(2)').empty();
    $('#FC td:nth-child(2)').html('$'+formdata['fcharge']+' <input type="hidden" name="amount[]" value="'+formdata['fcharge']+'"> ');

    $('#FC td:nth-child(3)').empty();
    $('#FC td:nth-child(3)').html('$'+((formdata['fcharge']*<?php echo $GST; ?>)/100));

    $('#FC td:nth-child(4)').empty();
    $('#FC td:nth-child(4)').html('$'+(parseFloat(formdata['fcharge'])+parseFloat(((formdata['fcharge']*<?php echo $GST; ?>)/100))));


    $('#AC td:nth-child(2)').empty();
    $('#AC td:nth-child(2)').html('$'+formdata['acharge']+' <input type="hidden" name="amount[]" value="'+formdata['acharge']+'"> ');

    $('#AC td:nth-child(3)').empty();
    $('#AC td:nth-child(3)').html('$'+((formdata['acharge']*<?php echo $GST; ?>)/100));

    $('#AC td:nth-child(4)').empty();
    $('#AC td:nth-child(4)').html('$'+(parseFloat(formdata['acharge'])+parseFloat(((formdata['acharge']*<?php echo $GST; ?>)/100))));


    $('#IC td:nth-child(2)').empty();
    $('#IC td:nth-child(2)').html('$'+formdata['icharge']+' <input type="hidden" name="amount[]" value="'+formdata['icharge']+'"> ');

    $('#IC td:nth-child(3)').empty();
    $('#IC td:nth-child(3)').html('$'+((formdata['icharge']*<?php echo $GST; ?>)/100));

    $('#IC td:nth-child(4)').empty();
    $('#IC td:nth-child(4)').html('$'+(parseFloat(formdata['icharge'])+parseFloat(((formdata['icharge']*<?php echo $GST; ?>)/100))));

    //total change    
    calculateTD();    

    return false;
});

function calculateTD() {
    var total = 0, deduction = 0, gst = 0;
    $('#DeductionTable tbody tr').each(function() {
        var value = $('td:nth-child(4)', this).text();
        
        if (value) {            
            total += parseFloat(value.split('$')[1]);
        }

        var value = $('td:nth-child(3)', this).text();
        
        if (value) {            
            gst += parseFloat(value.split('$')[1]);
        }

        var value = $('td:nth-child(2)', this).text();
        
        if (value) {            
            deduction += parseFloat(value.split('$')[1]);
        }
    });      

    $('#DeductionTable tfoot tr:first td:nth-child(4)').text('$' + total.toFixed(2));
    $('#DeductionTable tfoot tr:first td:nth-child(3)').text('$' + gst.toFixed(2));
    $('#DeductionTable tfoot tr:first td:nth-child(2)').text('$' + deduction.toFixed(2));

    $('#DeductionTable tfoot tr:last td:nth-child(2)').text('$' + (parseFloat(<?php echo $total ?>)-total).toFixed(2));
}

// Additional
$('#AddDeductions').submit(function(){
    var form = $(this).serializeArray();

    //get form data in array
    var formdata = [];
    for (var i = 0; i < form.length; i++) {
        formdata[form[i]['name']] = form[i]['value'];
    }

    var tr = "'tr'";
    $('#DeductionTable tbody tr:last').after('<tr valign="top"><td style="padding:10px;">'+formdata['desc']+'&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" onclick="$(this).closest('+tr+').remove(); calculateTD();">Remove</a><input type="hidden" name="desc[]" value="'+formdata['desc']+'"></td><td style="text-align: right;padding:10px;">$'+formdata['amt']+'<input type="hidden" name="amount[]" value="'+formdata['amt']+'"></td><td style="text-align: right;padding:10px;">$'+((formdata['amt']*<?php echo $GST; ?>)/100)+'</td><td style="text-align: right;padding:10px;">$'+(parseFloat(((formdata['amt']*<?php echo $GST; ?>)/100))+(parseFloat(formdata['amt'])))+'</td></tr>');

    //total change  
    calculateTD();   

    $('input[name=desc]').val('');
    $('input[name=amt]').val('');

    return false;
});

$('.save-and-send').click(function(){
    $('#sendInvoice').modal('show');    
});

$('.send-Invoice').click(function(){
    $('#GEN-FORM').attr('action', $('#GEN-FORM').attr('action')+'?send=1&sendEmail='+$('#sendEmail').val());
    $('#GEN-FORM').submit();
});
</script>