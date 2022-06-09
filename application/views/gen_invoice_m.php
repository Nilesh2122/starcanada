<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php $GST = $invoice['inv_GST'] ? $invoice['inv_GST'] : $GST; ?>
<div id="page-wrapper">

    <a class="SITE-BACK" href="javascript:history.back();"><i class="fa fa-angle-double-left"></i> Back</a>

    <div class="main-page">
    <div class="forms">
    
    	<div class=" form-grids row form-grids-right" style="margin-top:0">
            <div class="widget-shadow " data-example-id="basic-forms"> 
                <div class="form-title">
                    <h4>Generate Invoice</h4>
                </div>
                <div class="form-body">
                <form id="GEN-FORM" action="<?php echo base_url(); ?>index.php/invoice/gen_invoice" method="post" class="form-horizontal">  

                    <div class="row">                     
                    <div class="col-md-10 col-md-offset-1">
                    <div style="border: 1px solid #323232; padding: 15px;">
                    <table border="0" width="100%">
                        <tr valign="top">
                            <td style="width: 50%;">
                                <h3>Star Canada Inc.</h3>
                                <p>157 Kulawy Drive NW<br> Edmonton, AB T6L6Y9</p>
                                <p>Tel: (780)485-1232 &nbsp; Fax: (780)450-0932</p>
                            </td>
                            <td style="width: 50%;" align="right">
                                <h3>Invoice</h3>
                                <p><?php echo $invoice['inv_number']; ?></p>
                                <p><?php echo explode(' ', $invoice['created_at'])[0]; ?></p>                                
                            </td>
                        </tr>

                        <tr>
                            <td style="padding-top: 30px;" colspan="2">
                                <p>Bill to:</p>
                                <div style="padding: 10px; border:1px solid #c7c7c7; width: 50%;">
                                    <p style="margin: 0;">                        
                                        <?php echo $invoice['operations'][0]['cust_name']; ?><br>            
                                        <?php echo $invoice['operations'][0]['cust_address']; ?><br>
                                        <?php echo $invoice['operations'][0]['cust_email']; ?><br>
                                        <?php echo $invoice['operations'][0]['cust_contact']; ?>
                                    </p>                                    
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding-top: 20px;" colspan="2">
                                <table border="1" width="100%" cellpadding="2" style="border:1px solid #000;" id="bill-table">
                                    <thead>
                                        <tr bgcolor="#bdd6ee">
                                            <th style="text-align: center;background-color: #f2f2f2;">Date</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Description</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Ref#</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Units</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Rate</th>
                                            <th style="text-align: center;background-color: #f2f2f2;">Amount</th>				
                                        </tr>
                                    </thead>                                
                                    <tbody>
                                        <?php $subtotal=0; foreach($invoice['operations'] as $op){ ?>

                                        <tr valign="top">
                                            <td style="text-align: center">
                                                <?php echo date("M d, 'y", strtotime(explode(' ', $op['created_at'])[0])); ?>                                            
                                            </td>
                                            <td style="padding-bottom:40px; text-align: center; width: 45%;">
                                                <?php echo $op['op_loading_city'].' / '.$op['op_delivery_city']; ?>                                                
                                            </td>
                                            <td style="text-align: center">
                                                <?php echo $op['op_c_load_reference']; ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php echo $op['op_sub_rate']; ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php echo $op['op_mesuare_rate']; ?>
                                            </td>
                                            <td style="text-align: center">
                                                <?php
                                                setlocale(LC_MONETARY,"en_US");
                                                echo money_format("%(#10n", $op['op_total_cost']);
                                                ?>
                                            </td>
                                        </tr>  
                                        
                                        
                                        <?php $subtotal += $op['op_total_cost']; } ?>
                                        
                                        <?php                                             
                                            if($invoice['inv_extra_charge']){ 
                                            $charges = json_decode($invoice['inv_extra_charge'], true);
                                            foreach($charges as $charge){ $subtotal += $charge['ammount']; ?>

                                            <tr valign="top">
                                                <td style="text-align: center"><?php echo $charge['date']; ?>
                                                <input type="hidden" name="exdate[]" value="<?php echo $charge['date']; ?>"></td>
                                                <td style="text-align: center; padding-bottom:10px;"><?php echo $charge['desc']; ?><a style="margin-left: 20px; font-size: 14px; cursor: pointer;" onclick="$(this).closest('tr').remove(); calculateColumn(4);">Remove</a>
                                                <input type="hidden" name="exd[]" value="<?php echo $charge['desc']; ?>"></td>
                                                <td style="text-align: center"><?php echo $charge['ref']; ?>
                                                <input type="hidden" name="exref[]" value="<?php echo $charge['ref']; ?>"></td>
                                                <td style="text-align: center"><?php echo $charge['rate']; ?>
                                                <input type="hidden" name="exrate[]" value="<?php echo $charge['rate']; ?>"></td>
                                                <td style="text-align: center">$<?php echo $charge['ammount']; ?>
                                                <input type="hidden" name="exc[]" value="<?php echo $charge['ammount']; ?>"></td>
                                            </tr>
                                            
                                        <?php } } ?>
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td rowspan="4" colspan="4" valign="center" style="padding-left: 20px;">
                                                GST No. 138523998 R
                                            </td> 
                                            <td align="center">
                                                <b>SUB TOTAL</b>
                                            </td>
                                            <td align="center">
                                                <b id="sbtval">
                                                    <?php
                                                    setlocale(LC_MONETARY,"en_US");
                                                    echo money_format("%(#10n", $subtotal);
                                                    ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <b>GST</b>
                                            </td>
                                            <td align="center">
                                                <b id="gstval">
                                                    <?php
                                                    $gst = (($subtotal*$GST)/100);
                                                    setlocale(LC_MONETARY,"en_US");
                                                    echo money_format("%(#10n", $gst);
                                                    ?></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>                                            
                                            <td align="center">
                                                <b>TOTAL</b>
                                            </td>
                                            <td align="center" id="optotal">
                                                <b>
                                                    <?php
                                                    $total = ((($subtotal*$GST)/100)+$subtotal);
                                                    setlocale(LC_MONETARY,"en_US");
                                                    echo money_format("%(#10n", $total);
                                                    ?></b>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </table>
                    </div>
                    </div>
                    </div>                                                            

                    <input type="hidden" name="inv_id" value="<?php echo $invoice['inv_id']; ?>" />  
                    <input type="hidden" name="inv_GST" value="<?php echo $GST; ?>">
                    
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1" style="text-align: right;"> 
                        <button type="submit" class="btn btn-default">Save</button> 
                        <button type="button" class="btn btn-default save-and-send" style="margin-left:5px;">Save & Send</button> 
                        </div>          
                    </div>                               
                    
                </form>  
                
                <form action="#" method="post" id="excform" autocomplete="off">   
                    <div class="row">
                    <div class="col-md-10 col-md-offset-1" style="padding: 0;">
                    <div style="background-color: #f8f8f8; border: 1px solid #c7c7c7; padding: 20px 0; margin: 0 15px;">
                                                
                        <p style="padding: 10px 15px;font-size: 20px;background-color: #ddd;margin-top: -20px;margin-bottom: 15px;">Extra charges</p>

                        <div class="row" style="margin-top: 0;">

                        <div class="col-md-2">
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Date</label> 
                            <input type="text" class="form-control dp" name="exdate" placeholder="Date">
                        </div>
                         
                        <div class="col-md-4">
                        <div class="form-group"> 
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Description</label> 
                            <input type="text" class="form-control" name="exd" placeholder="Description" required="required">
                        </div>
                        </div>

                        <div class="col-md-2">
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">#Ref</label> 
                            <input type="text" class="form-control" name="exref" placeholder="Reference">
                        </div>

                        <div class="col-md-2">
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Unite</label> 
                            <select class="form-control" name="exrate" required>
                                <option value="Base fare">Base fare</option>
                                <option value="Flate rate">Flat rate</option>
                                <option value="Per tonne">Per tonne</option>
                                <option value="Per CWT">Per CWT</option>
                                <option value="FBM">FBM</option>
                                <option value="Per pallet">Per pallet</option>
                            </select>
                            <!-- <input type="text" class="form-control" name="exrate" placeholder="Rate" required="required"> -->
                        </div>

                        <div class="col-md-2">
                        <div class="form-group"> 
                            <label for="inputEmail3" class="control-label" style="margin-bottom: 5px;">Rate</label> 
                            <input type="number" step="any" class="form-control" name="exc" placeholder="Rate" required="required">
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

            <p>You want to send this Invoice to</p>
            
            <input type="text" class="form-control" id="sendEmail" value="<?php echo $invoice['operations'][0]['cust_email']; ?>" style="margin: 10px 0;">

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

$('#excform').submit(function(){
    var form = $(this).serializeArray();

    //get form data in array
    var formdata = [];
    for (var i = 0; i < form.length; i++) {
        formdata[form[i]['name']] = form[i]['value'];
    }

    var tr = "'tr'";
    $('#bill-table tbody tr:last').after('<tr valign="top"><td style="text-align:center;">'+formdata['exdate']+'<input type="hidden" name="exdate[]" value="'+formdata['exdate']+'"></td><td style="text-align: center; padding-bottom:10px;">'+formdata['exd']+'<a style="margin-left: 20px; font-size: 14px; cursor: pointer;" onclick="$(this).closest('+tr+').remove(); calculateColumn(4);">Remove</a><input type="hidden" name="exd[]" value="'+formdata['exd']+'"></td><td style="text-align: center">'+formdata['exref']+'<input type="hidden" name="exref[]" value="'+formdata['exref']+'"></td><td style="text-align: center">'+formdata['exrate']+'<input type="hidden" name="exrate[]" value="'+formdata['exrate']+'"></td><td></td><td style="text-align: center">$'+formdata['exc']+'<input type="hidden" name="exc[]" value="'+formdata['exc']+'"></td></tr>');

    $(this).find("input[type=text]").val("");
    $(this).find("input[type=number]").val("");

    //total change    
    calculateColumn(4);    

    return false;
});

function calculateColumn(index) {

    var total = 0;
    $('#bill-table tbody tr').each(function() {
        var value = $('td:last', this).text();
    
        if (value) {         
            pri = value.split('$')[1];
            var thenum = pri.replace(',', '');
            thenum = Math.trunc(thenum)
            total += parseInt(thenum);
        }
    }); 
   
    $('#sbtval').html('$'+total.toFixed(2));
    $('#gstval').html('$'+(((total*<?php echo $GST; ?>)/100)).toFixed(2));

    $('#bill-table tfoot td:last').text('$' + (total + parseInt($('#gstval').text().split('$')[1])).toFixed(2));
}

$(function() {
    $(".dp").datepicker({dateFormat: "M d, y"});
});

$('.save-and-send').click(function(){
    $('#sendInvoice').modal('show');    
});

$('.send-Invoice').click(function(){
    $('#GEN-FORM').attr('action', $('#GEN-FORM').attr('action')+'?send=1&sendEmail='+$('#sendEmail').val());
    $('#GEN-FORM').submit();
});
</script>