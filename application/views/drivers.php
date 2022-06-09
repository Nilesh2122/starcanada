<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Drivers <?php if(isset($_GET['archieved'])){ echo '[Archived]'; } ?></h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Search" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">


                        <?php if(!isset($_GET['archieved'])){ ?> 
                        <a onclick="document.getElementById('add_user').click();" class="btn btn-primary"
                            style="float:right;border-radius:0"><i class="fa fa-plus"></i> &nbsp;Add Driver</a>
                        
                        <a href="<?php echo base_url(); ?>index.php/drivers?archieved=1" target="_blank" class="btn btn-primary" style="float:right;border-radius:0; margin-right: 15px;"><i class="fa fa-archive"></i> &nbsp;Archived</a>
                        <?php } ?>
                    </form>
                </div>
                <table class="table table-bordered sortable">

                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Name</th>
                            <th>Email</th>                                                      
                            <th>Phone</th>                            
                            <th>Unit No</th>
                            <th>Hire Date</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>License No.</th>
                            <th style="text-align:center;">Active</th>
                            <th style="text-align:center;">Action</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $key['driver_name']; ?></td>
                            <td><?php echo $key['driver_email']; ?></td>                                                    
                            <td><?php echo $key['driver_contact']; ?></td>                            
                            <td><?php echo $key['unit_no']; ?></td>
                            <td><?php echo $key['driver_hire_date']; ?></td>
                            <td><?php echo $key['driver_address']; ?></td>
                            <td><?php echo $key['driver_contact']; ?></td>
                            <td><?php echo $key['license_number']; ?></td>                            
                            <td align="center">
                                <label class="switch">
                                    <input type="checkbox" <?php echo $key['is_active'] == '1'?'checked':'' ?>
                                        class="chk-active" data-user="<?php echo $key['driver_id']; ?>">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="table-actions">
                                <button type="button" class="edit-user"
                                    data-user-id="<?php echo $key['driver_id']; ?>"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>                               

                                <button type="button" class="btn-danger" onclick="deleteRecord('<?php echo base_url(); ?>index.php/drivers/delete?id=<?php echo base64_encode($key['driver_id']); ?>')"><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button>

                                <?php if(!isset($_GET['archieved'])){ ?>
                                <button type="button" class="btn-warning" onclick="archieveRecord('<?php echo base_url(); ?>index.php/drivers/archieve?id=<?php echo base64_encode($key['driver_id']); ?>')"><i class="fa fa-archive" data-toggle="tooltip" title="Archive"></i></button>
                                <?php }else{ ?>
                                <button type="button" class="btn-warning" onclick="unarchieveRecord('<?php echo base_url(); ?>index.php/drivers/archieve?id=<?php echo base64_encode($key['driver_id']); ?>&unarchive=1')"><i class="fa fa-undo" data-toggle="tooltip" title="Unarchive"></i></button>
                                <?php } ?>
                            </td>                             
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?php echo $nav; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add accountant modal -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;"
    id="add_user">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Driver</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/drivers/add_driver" method="post"
                                    enctype="multipart/form-data">                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contractor</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="con_id"
                                                required="required"> 
                                            	<option selected="selected" value="0">Company Driver</option>
                                                <?php foreach($contractors as $con){ ?>
               	                               	<option value="<?php echo $con['con_id']; ?>"><?php echo $con['con_name']; ?></option>
												<?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Equipment</label>
                                        <div class="col-sm-9 eq_sel_div">
                                            <select class="form-control" name="eq_id" required="required">
                                                <option selected="selected" value="0" disabled="disabled">Select equipment</option>                                                
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Unit No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="unit_no"
                                                required="required" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="driver_name"
                                                required="required">
                                        </div>
                                    </div>                                   

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="driver_email"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="driver_password"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="driver_address"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="driver_contact"
                                                required="required">
                                        </div>
                                    </div>                                    
                                    
                                    <!-- <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Commission(%)</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="driver_commission"
                                                required="required">
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">License Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="license_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Hire Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control dp" name="driver_hire_date"
                                                required="required">
                                        </div>
                                    </div>                                    
                                                                        
                                    <!-- <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">License</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" name="driver_license"
                                                required="required" accept="image/*">
                                            <img style="max-width:200px;" />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">ID Proof</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" name="driver_id_proof"
                                                required="required" accept="image/*">
                                            <img style="max-width:200px;" />
                                        </div>
                                    </div> -->                                    
                                                                                 
                                    <input type="submit" style="display:none;" id="add_user_submit" />

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('add_user_submit').click();">Add</button>
            </div>
        </div>

    </div>
</div>


<!-- Edit Modal -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal1" style="display:none;"
    id="edit_user">Open Modal</button>

<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Driver</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img_()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/drivers/edit_driver" method="post"
                                    enctype="multipart/form-data">
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contractor</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="con_id" id="con_id"
                                                required="required"> 
                                            	<option selected="selected" value="0">Company Driver</option>
                                                <?php foreach($contractors as $con){ ?>
               	                               	<option value="<?php echo $con['con_id']; ?>"><?php echo $con['con_name']; ?></option>
												<?php } ?>
                                            </select>
                                        </div>
                                    </div>    
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Equipment</label>
                                        <div class="col-sm-9 eq_sel_div eq_sel_div_e">
                                            <select class="form-control" name="eq_id" required="required">
                                                <option selected="selected" value="0" disabled="disabled">Select equipment</option>                                                
                                            </select> 
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Unit No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="unit_no" id="unit_no"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="driver_name" id="driver_name"
                                                required="required">
                                        </div>
                                    </div>                                   

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="driver_email" id="driver_email"
                                                required="required" disabled="disabled">
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="driver_address" id="driver_address"
                                                required="required">
                                        </div>
                                    </div>
                                                                        
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="driver_contact" id="driver_contact"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Commission(%)</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="driver_commission" id="driver_commission"
                                                required="required">
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">License Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="license_number" id="license_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Hire Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control dp" name="driver_hire_date" id="driver_hire_date"
                                                required="required">
                                        </div>
                                    </div>   

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="driver_password" id="driver_password" required="required">

                                            <input type="hidden" class="form-control" name="driver_password_hide" id="driver_password_hide" required="required">
                                        </div>
                                    </div>                                    
                                    
                                    <!-- <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">License</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" name="driver_license"
                                                accept="image/*">
                                            <img style="max-width:200px;" id="driver_license" />
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">ID Proof</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" name="driver_id_proof"
                                                accept="image/*">
                                            <img style="max-width:200px;" id="driver_id_proof" />
                                        </div>
                                    </div> -->
                                    
                                    <input name="driver_id" type="hidden" id="driver_id" />
                                    <input type="submit" style="display:none;" id="add_user_submit_" />

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('add_user_submit_').click();">Save</button>
            </div>
        </div>

    </div>
</div>

<script>
$(function() {
    $(".dp").datepicker({dateFormat: 'yy-mm-dd'});
});

$('.edit-user').click(function() {
    var user_id = $(this).data('user-id');

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/drivers/get_driver_by_id',
        type: 'POST',
        data: {
            'user_id': user_id
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {
            //console.log(data);
			$('#con_id').val(data['con_id']);
            $('#driver_id').val(data['driver_id']);
            $('#driver_name').val(data['driver_name']);
			$('#driver_email').val(data['driver_email']);
            $('#driver_address').val(data['driver_address']);
			// $('#driver_commission').val(data['driver_commission']);
			$('#driver_contact').val(data['driver_contact']);
            $('#license_number').val(data['license_number']);
            $('#driver_hire_date').val(data['driver_hire_date']);
            $('#unit_no').val(data['unit_no']);
            $('#driver_password').val(data['driver_password']);
            $('#driver_password_hide').val(data['driver_password']);
            //$('#driver_license').attr('src', '<?php //echo base_url(); ?>user_data/driver_data/'+data['driver_license']);
            //$('#driver_id_proof').attr('src', '<?php //echo base_url(); ?>user_data/driver_data/'+data['driver_id_proof']);

            var apend = ''; var selected = '';
            apend = '<select class="form-control" name="eq_id" required="required"><option selected="selected" value="0" disabled="disabled">Select Equipment</option>';
            
            for(var i = 0; i < data['eq'].length; i++)
            {
                selected = data['eq'][i]['eq_id'] == data['eq_id'] ? 'selected' : '';
                apend+= '<option value="'+data['eq'][i]['eq_id']+'" data-unit="'+data['eq'][i]['eq_unit']+'" '+selected+'>'+data['eq'][i]['eq_type']+'</option>';
            }
            
            apend += '</select>';
            
            $('.eq_sel_div_e').empty();
            $('.eq_sel_div_e').append(apend);	

            $('#edit_user').click();
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });
});

$('.chk-active').click(function() {
    var user_id = $(this).data('user');
    var flag = '0';

    if ($(this).is(":checked")) {
        flag = '1';
    } else if ($(this).is(":not(:checked)")) {
        flag = '0';
    }

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/drivers/change_status',
        type: 'POST',
        data: {
            'driver_id': user_id,
            'flag': flag
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {            
            //alert('Success!');
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(input).next().attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$('input[type=file]').change(function(){
    readURL(this);
})

$('select[name="con_id"]').change(function() {
    var con_id = $(this).val();	

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/drivers/get_equipments',
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
				alert('No equipments found for this contractor, Add equipments first!');				
				
                $('select[name="con_id"]').prop('selectedIndex',0);

                var apend = '';
				apend = '<select class="form-control" name="eq_id" required="required"><option selected="selected" value="0" disabled="disabled">Select Equipment</option></select>';
				
				$('.eq_sel_div').empty();
				$('.eq_sel_div').append(apend);	

                $('input[name="unit_no"]').val('');
			}	
			else
			{							
				var apend = '';
				apend = '<select class="form-control" name="eq_id" required="required"><option selected="selected" value="0" disabled="disabled">Select Equipment</option>';
				
				for(var i = 0; i < data.length; i++)
				{
					apend+= '<option value="'+data[i]['eq_id']+'" data-unit="'+data[i]['eq_unit']+'">'+data[i]['eq_unit']+'</option>';
				}
				
				apend += '</select>';
				
				$('.eq_sel_div').empty();
				$('.eq_sel_div').append(apend);								
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

$(document).on('change', 'select[name="eq_id"]', function() {    
    var unit = $(this).find(':selected').data('unit');

    $('input[name="unit_no"]').val(unit);
});
</script>