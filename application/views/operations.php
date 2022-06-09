<?php 
$STATUSCOLORS[1] = '#81b3ff';
$STATUSCOLORS[2] = '#81b3ff';
$STATUSCOLORS[3] = '#ffc107';
$STATUSCOLORS[4] = '#03a9f4';
$STATUSCOLORS[5] = '#ff9800';
$STATUSCOLORS[6] = '#009688';
$STATUSCOLORS[7] = '#4caf50';
?>

<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Operations [Current]</h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Customer name" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">
                        <?php if($this->session->userdata('user_role') != 'Dispatcher'){ ?>
                        <a href="<?php echo base_url(); ?>index.php/operations/create_operation" class="btn btn-primary"
                            style="float:right;border-radius:0"><i class="fa fa-truck"></i> &nbsp;Create Operation</a>
                        <?php } ?>
                    </form>
                </div>
                <table class="table table-bordered sortable">

                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <!--<th>Reference ID</th>-->
                            <th> Origin </th> 
                            <th> Destination </th>
                            <!--<th>Total Cost</th>
                            <th>Commission</th>-->
                            <th>Unit No.</th>
                            <th style="text-align: center;width:20%">Status</th>
                            <th>Customer</th>
                            <th>Driver</th>
                            <!--<th>Loading date</th>
                            <th>Delivery date</th>-->
                            <th>Created on</th>                            
                            <!-- <th style="text-align: center;">Assign Driver</th> -->
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <!--<td><?php //echo $key['op_ref_id']; ?></td>-->
                            <td><?php echo $key['op_loading_city']; ?></td>
                            <td><?php echo $key['op_delivery_city']; ?></td>
                            <!--<td><?php //echo '$'.$key['op_total_cost']; ?></td>
                            <td><?php //if($key['op_status'] >= 3){ echo $key['con_id']=='0' ? 'To Driver ':'To Contractor '; echo $key['op_dc_commission']; ?>% <br>( <?php //echo '$'.($key['op_total_cost']*$key['op_dc_commission'])/100; ?> ) <?php //}else{ echo '-'; } ?></td>-->
                            <td><?=((isset($key['unit_no'])) && (!empty($key['unit_no'])))?trim($key['unit_no']):'----'; ?></td>
                            <td align="center"><span style="color:#fff; padding:2px 15px 4px; border-radius:15px; background-color: <?php echo $STATUSCOLORS[$key['op_status']]; ?>"><?php echo $op_status[$key['op_status']]; ?></span></td>
                            <td><?php echo $key['cust_name']; ?></td>
                            <td><?php echo $key['driver_name']; ?></td>                         
                            <!--<td><?php //echo $key['op_loading_date']; ?></td>
                            <td><?php //echo $key['op_delivery_date']; ?></td>-->
                            <td><?php echo date('Y-m-d', strtotime($key['created_at'])); ?></td>                            
                            
                            <td class="table-actions">
                            <!-- <td align="center"> -->

                                <form action="<?php echo base_url(); ?>index.php/operations/track" method="post" target="_blank">
                                <input type="hidden" name="op_id" value="<?php echo $key['op_id']; ?>" />
                                <button><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button>
                                </form>
                                                                
                                <form action="<?php echo base_url(); ?>index.php/operations/modify_operation" method="post">
                                <input type="hidden" name="op_id" value="<?php echo $key['op_id']; ?>" />    
                                <button <?php if($key['op_status'] == '7'){?> disabled="disabled" <?php } ?>><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>
                                </form>                                

                                <button type="button" class="assign-driver"
                                    data-op-id="<?php echo $key['op_id']; ?>" <?php if($key['op_status'] < '2' || $key['op_status'] > '3'){?> disabled="disabled" <?php } ?>><i class="fa fa-check" data-toggle="tooltip" title="Assign Driver"></i></button>

                                <button type="button" class="btn-danger" onclick="deleteRecord('<?php echo base_url(); ?>index.php/operations/operations_delete?id=<?php echo base64_encode($key['op_id']); ?>')"><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button>
                                <!--<form action="<?php //echo base_url(); ?>index.php/operations/modify_operation" method="post">
                                <input type="hidden" name="op_id" value="<?php //echo $key['op_id']; ?>" />    
                                <button <?php //if($key['op_status'] == '7'){?> disabled="disabled" <?php //} ?>><i class="fa fa-pencil-square-o"></i></button>
                                </form>                                
                                
                                <button class="assign-driver" data-op-id="<?php //echo $key['op_id']; ?>" <?php //if($key['op_status'] != '2'){?> disabled="disabled" <?php //} ?>><i class="fa fa-check-square-o"></i></button>                                                                                                
                                <form action="<?php //echo base_url(); ?>index.php/operations/track" method="post" target="_blank">
                                <input type="hidden" name="op_id" value="<?php //echo $key['op_id']; ?>" />
                                <button><i class="fa fa-truck"></i></button>
                                </form>-->
                            </td>                          
                        </tr>
                        <?php } ?>
                    </tbody>                    
                </table>                
                <div id="data-loader" style="display: none; text-align: center;">
                    <img src="<?php echo base_url(); ?>assets/images/data-loader.gif" style="max-width: 100px;" alt="Data loader">
                </div>                
            </div>
        </div>
    </div>
</div>

<!-- Assign driver modal -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal1" style="display:none;"
    id="assign_driver">Open Modal</button>

<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign driver to operation</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class="form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img_()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/operations/assign_driver" method="post"
                                    enctype="multipart/form-data">                                                                        
                                    <!-- <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Loading location</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" rows="3" disabled="disabled" id="LL"></textarea> 
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Delivery location</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" rows="3" disabled="disabled" id="DL"></textarea> 
                                        </div>
                                    </div> -->
                                    
                                    <!-- <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Select contractor</label>
                                        <div class="col-sm-9">
                                            <select class="form-control cont_sel" name="con_id" required="required">
                                                <option selected="selected" value="-1" disabled="disabled">Select contractor</option>
                                                <option value="0">Company driver</option>
                                                <?php //foreach($contractors as $c){ ?>
                                                    <option value="<?php //echo $c['con_id']; ?>" data-com="<?php //echo $c['con_commission']; ?>">
                                                    <?php //echo $c['con_name']; ?>
                                                    </option>
                                                <?php //} ?>
                                            </select> 
                                        </div>
                                    </div> -->
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Select Unit</label>
                                        <div class="col-sm-9 driver_sel_div">
                                            <select class="form-control" name="driver_id" required="required">
                                                <option selected="selected" value="0" disabled="disabled">Select Unit</option>                                                
                                            </select> 
                                        </div>
                                    </div>
                                                                      
                                    <div class="form-group" <?php if($this->session->userdata('user_role') != 'Administrator'){ ?>style="display:none;"<?php } ?>>
                                        <label for="inputEmail3" class="col-sm-3 control-label">Commission(%)</label>
                                        <div class="col-sm-9">
                                    		<input class="form-control" name="op_dc_commission" id="op_dc_commission" type="number" required="required"/>         
                                        </div>
                                    </div>
                                    
                                    <input name="con_id" type="hidden" id="con_id" />
                                    <input name="op_id" type="hidden" id="op_id" />
                                    <input type="submit" style="display:none;" id="assign_driver_btn" />

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('assign_driver_btn').click();">Assign</button>
            </div>
        </div>

    </div>
</div>

<script>
$('table').on('click', '.assign-driver', function() {    
	<?php if($this->session->userdata('user_role') == 'Dispatcher' || $this->session->userdata('user_role') == 'Administrator'){ ?>
	$('#op_id').val($(this).data('op-id'));
	
	// $('#LL').val($(this).parent().parent().children().eq(2).html());
	// $('#DL').val($(this).parent().parent().children().eq(3).html());

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/operations/get_refreshed_drivers',
        type: 'POST',
        data: {            
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {
            //console.log(data);
			if(data == '0')
			{
				window.location='<?php echo base_url(); ?>';
			}	
			else if(data == '1')
			{
				alert('No drivers found for currently!');			
				
				var apend = '';
				apend = '<select class="form-control" name="driver_id" required="required"><option selected="selected" value="0" disabled="disabled">Select Unit</option></select>';
				
				$('.driver_sel_div').empty();
				$('.driver_sel_div').append(apend);								
			}	
			else
			{							
				var apend = '';
				apend = '<select class="form-control" name="driver_id" required="required" onchange="append_com(this);"><option selected="selected" value="0" disabled="disabled">Select Unit</option>';
				
				for(var i = 0; i < data.length; i++)
				{
					apend+= '<option data-con="'+data[i]['con_id']+'" value="'+data[i]['driver_id']+'" data-com="'+data[i]['driver_commission']+'">'+data[i]['unit_no']+'</option>';
				}
				
				apend += '</select>';
				
				$('.driver_sel_div').empty();
				$('.driver_sel_div').append(apend);												
			}	                       
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });	
	
	$('#assign_driver').click();
	<?php }else{ ?>
	
	alert("You don't have access to assign driver!");
	
	<?php } ?>	
});

$('.cont_sel').change(function(){	
	var con_id = $(this).val();
	var com = $(this).find(':selected').data('com') ? $(this).find(':selected').data('com') : 0;

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/operations/get_drivers_by_contractors',
        type: 'POST',
        data: {
            'con_id': con_id
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {
            //console.log(data);
			if(data == '0')
			{
				window.location='<?php echo base_url(); ?>';
			}	
			else if(data == '1')
			{
				alert('No drivers found for this contractor, Choose another!');
				$('.cont_sel').focus();
				
				var apend = '';
				apend = '<select class="form-control" name="driver_id" required="required"><option selected="selected" value="0" disabled="disabled">Select driver</option></select>';
				
				$('.driver_sel_div').empty();
				$('.driver_sel_div').append(apend);
				
				$('#op_dc_commission').val('0');
				
				$('.cont_sel').prop('selectedIndex',0);
			}	
			else
			{							
				var apend = '';
				apend = '<select class="form-control" name="driver_id" required="required" onchange="append_com(this, '+con_id+');"><option selected="selected" value="0" disabled="disabled">Select driver</option>';
				
				for(var i = 0; i < data.length; i++)
				{
					apend+= '<option value="'+data[i]['driver_id']+'" data-com="'+data[i]['driver_commission']+'">'+data[i]['driver_name']+'</option>';
				}
				
				apend += '</select>';
				
				$('.driver_sel_div').empty();
				$('.driver_sel_div').append(apend);
				
				$('#op_dc_commission').val(com);				
			}	                       
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });	
});

function append_com(sel)
{
	$('#op_dc_commission').val($(sel).find(':selected').data('com'));
    $('#con_id').val($(sel).find(':selected').data('con'));	
}

var last_op = '<?php if($datas){ echo $key['op_id']; } ?>';
var row = '<?php if($datas){ echo $i; } ?>';
var statuscolors = '<?php echo implode(',', $STATUSCOLORS); ?>';
statuscolors = statuscolors.split(',');
<?php if($datas){ ?>
$(window).scroll(function() {    
    if($(window).scrollTop() + $(window).height() == $(document).height()) {        
        if(last_op){
        //ajax to call other data        
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/operations/get_more',
            type: 'POST',
            data: {
                'op_id': last_op,
                'title': '<?php echo $search_title; ?>'
            },
            dataType: 'json',
            beforeSend: function() {
                $('#data-loader').fadeIn();
            }, 
            success: function(data) {
                //console.log(data);
                //exit();
                
                var append = '';
                for(var j=0; j<data['datas'].length; j++)
                {
                    row++;                
                    append += '<tr><td>'+ row +'</td><td>' + data['datas'][j]['op_loading_city'] + '</td><td>' + data['datas'][j]['op_delivery_city'] + '</td><td>' + data['datas'][j]['unit_no'] + '</td><td align="center"><span style="color:#fff; padding:2px 15px 4px; border-radius:15px; background-color: '+ statuscolors[(parseInt(data['datas'][j]['op_status'])-1)] +'">'+ data['op_status'][data['datas'][j]['op_status']] +'</span></td><td>'+ data['datas'][j]['cust_name'] +'</td><td>'+ data['datas'][j]['driver_name'] +'</td><td>'+ data['datas'][j]['created_at'].split(' ')[0] +'</td><td class="table-actions"><form action="<?php echo base_url(); ?>index.php/operations/track" method="post" target="_blank"><input type="hidden" name="op_id" value="'+ data['datas'][j]['op_id'] +'" /><button><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button></form>';

                    var disabled = '';
                    var disabled1 = '';
                    if(data['datas'][j]['op_status'] < '2' || data['datas'][j]['op_status'] > '3'){
                        disabled = 'disabled="disabled"';
                    }
                    if(data['datas'][j]['op_status'] == '7'){
                        disabled1 = 'disabled="disabled"';
                    }

                    append += '<form action="<?php echo base_url(); ?>index.php/operations/modify_operation" method="post"><input type="hidden" name="op_id" value="'+ data['datas'][j]['op_id'] +'" /><button '+ disabled1 +'><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button></form> <button type="button" class="assign-driver" data-op-id="'+ data['datas'][j]['op_id'] +'" '+ disabled +'><i class="fa fa-check" data-toggle="tooltip" title="Assign Driver"></i></button> </td></tr>';

                    last_op = data['datas'][j]['op_id'];                    
                }                

                $('tbody').append(append);
            },
            error: function(request, error) {
                alert("Some thing went wrong!");
                location.reload();
            },
            complete: function() {
                $('#data-loader').fadeOut();			
            }
        });	
        }        
    }
});
<?php } ?>
</script>