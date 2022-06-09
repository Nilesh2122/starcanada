<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Accountants <?php if(isset($_GET['archieved'])){ echo '[Archived]'; } ?></h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Search" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">

                        <?php if(!isset($_GET['archieved'])){ ?> 
                        <a onclick="document.getElementById('add_user').click();" class="btn btn-primary"
                            style="float:right;border-radius:0"><i class="fa fa-plus"></i> &nbsp;Add Accountant</a>
                            
                        <a href="<?php echo base_url(); ?>index.php/users/accountants?archieved=1" target="_blank" class="btn btn-primary" style="float:right;border-radius:0; margin-right: 15px;"><i class="fa fa-archive"></i> &nbsp;Archived</a>
                        <?php } ?>
                    </form>
                </div>
                <table class="table table-bordered sortable">

                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Email address</th>                            
                            <th>DOB</th>                          
                            <th>Address</th>
                            <th>Driving License</th>
                            <th>SI Number</th>
                            <th>Bank A/C</th>                            
                            <th>Hire Date</th>
                            <th style="text-align:center;">Active</th>
                            <th style="text-align:center;">Action</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $key['acu_number']; ?></td>
                            <td><?php echo $key['acu_name']; ?></td>
                            <td><?php echo $key['acu_email']; ?></td>                                
                            <td><?php echo $key['acu_dob']; ?></td>
                            <td><?php echo $key['acu_address']; ?></td>
                            <td><?php echo $key['acu_dl_number']; ?></td>
                            <td><?php echo $key['acu_si_number']; ?></td>
                            <td><?php echo $key['acu_bank_ac']; ?></td>
                            <td><?php echo $key['acu_hire_date']; ?></td>                            
                            <td align="center">
                                <label class="switch">
                                    <input type="checkbox" <?php echo $key['is_active'] == '1'?'checked':'' ?>
                                        class="chk-active" data-user="<?php echo $key['acu_id']; ?>">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="table-actions">
                                <button type="button" class="edit-user" data-user-id="<?php echo $key['acu_id']; ?>"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>                               

                                <button type="button" class="btn-danger" onclick="deleteRecord('<?php echo base_url(); ?>index.php/users/delete?id=<?php echo base64_encode($key['acu_id']); ?>')"><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button>

                                <?php if(!isset($_GET['archieved'])){ ?>
                                <button type="button" class="btn-warning" onclick="archieveRecord('<?php echo base_url(); ?>index.php/users/archieve?id=<?php echo base64_encode($key['acu_id']); ?>')"><i class="fa fa-archive" data-toggle="tooltip" title="Archive"></i></button>
                                <?php }else{ ?>
                                <button type="button" class="btn-warning" onclick="unarchieveRecord('<?php echo base_url(); ?>index.php/users/archieve?id=<?php echo base64_encode($key['acu_id']); ?>&unarchive=1')"><i class="fa fa-undo" data-toggle="tooltip" title="Unarchive"></i></button>
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
                <h4 class="modal-title">Add Accountant</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/users/add_user" method="post"
                                    enctype="multipart/form-data">                                    
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Full Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_name"
                                                required="required">
                                        </div>
                                    </div>                                   

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Email Address</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="acu_email"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="acu_password"
                                                required="required">
                                        </div>
                                    </div>    
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Emp No.</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_address"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Birth date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="dp form-control" name="acu_dob"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">License number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_dl_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Insurance number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_si_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Bank A/C</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_bank_ac"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Hire date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="dp form-control" name="acu_hire_date"
                                                required="required">
                                        </div>
                                    </div>                                    
                                    
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
                <h4 class="modal-title">Edit Accountant</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img_()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/users/edit_user" method="post"
                                    enctype="multipart/form-data">                                                                        
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Full Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_name" id="user_name"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Email Address</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="acu_email" id="user_email"
                                                required="required" disabled="disabled">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Emp No.</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_number" id="acu_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_address" id="acu_address"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Birth date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="dp form-control" name="acu_dob" id="acu_dob"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">License number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_dl_number" id="acu_dl_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Insurance number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_si_number" id="acu_si_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Bank A/C</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="acu_bank_ac" id="acu_bank_ac"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Hire date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="dp form-control" name="acu_hire_date" id="acu_hire_date"
                                                required="required">
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="acu_password" id="acu_password"
                                                required="required">
                                            <input type="hidden" class="form-control" name="acu_password_hide" id="acu_password_hide"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <input name="acu_id" type="hidden" id="user_id" />
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
/*$(function() {
    $(".dp").datepicker({dateFormat: 'yy-mm-dd'});
});*/
$( function() {
    $( ".dp" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd'
    });
  } );

$('.edit-user').click(function() {
    var user_id = $(this).data('user-id');

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/users/get_user_by_id',
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
            $('#user_id').val(data['acu_id']);
            $('#user_name').val(data['acu_name']);
            $('#user_email').val(data['acu_email']);
            $('#acu_number').val(data['acu_number']);
            $('#acu_address').val(data['acu_address']);
            $('#acu_dob').val(data['acu_dob']);
            $('#acu_dl_number').val(data['acu_dl_number']);
            $('#acu_si_number').val(data['acu_si_number']);
            $('#acu_bank_ac').val(data['acu_bank_ac']);
            $('#acu_hire_date').val(data['acu_hire_date']);            
            $('#acu_password').val(data['acu_password']);
            $('#acu_password_hide').val(data['acu_password']);
            
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
        url: '<?php echo base_url(); ?>index.php/users/change_status',
        type: 'POST',
        data: {
            'user_id': user_id,
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
</script>