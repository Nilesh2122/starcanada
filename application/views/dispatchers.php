<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Dispatchers <?php if(isset($_GET['archieved'])){ echo '[Archived]'; } ?></h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Search" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">

                        <?php if(!isset($_GET['archieved'])){ ?> 
                        <a onclick="document.getElementById('add_user').click();" class="btn btn-primary"
                            style="float:right;border-radius:0"><i class="fa fa-plus"></i> &nbsp;Add Dispatcher</a>
                        
                        <a href="<?php echo base_url(); ?>index.php/dispatchers?archieved=1" target="_blank" class="btn btn-primary" style="float:right;border-radius:0; margin-right: 15px;"><i class="fa fa-archive"></i> &nbsp;Archived</a>
                        <?php } ?>
                    </form>
                </div>
                <table class="table table-bordered sortable">

                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Created at</th>
                            <th style="text-align:center;">Active</th>
                            <th style="text-align:center;">Action</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $key['du_name']; ?></td>
                            <td><?php echo $key['du_contact']; ?></td>                         
                            <td><?php echo $key['du_email']; ?></td>                         
                            <td><?php echo date('Y-m-d', strtotime($key['created_at'])); ?></td>
                            <td align="center">
                                <label class="switch">
                                    <input type="checkbox" <?php echo $key['is_active'] == '1'?'checked':'' ?>
                                        class="chk-active" data-user="<?php echo $key['du_id']; ?>">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td class="table-actions">
                                <button type="button" class="edit-user"
                                    data-user-id="<?php echo $key['du_id']; ?>"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>                               
 
                                <button type="button" class="btn-danger" onclick="deleteRecord('<?php echo base_url(); ?>index.php/dispatchers/delete?id=<?php echo base64_encode($key['du_id']); ?>')"><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button>

                                <?php if(!isset($_GET['archieved'])){ ?>
                                <button type="button" class="btn-warning" onclick="archieveRecord('<?php echo base_url(); ?>index.php/dispatchers/archieve?id=<?php echo base64_encode($key['du_id']); ?>')"><i class="fa fa-archive" data-toggle="tooltip" title="Archive"></i></button>
                                <?php }else{ ?>
                                <button type="button" class="btn-warning" onclick="unarchieveRecord('<?php echo base_url(); ?>index.php/dispatchers/archieve?id=<?php echo base64_encode($key['du_id']); ?>&unarchive=1')"><i class="fa fa-undo" data-toggle="tooltip" title="Unarchive"></i></button>
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
                <h4 class="modal-title">Add Dispatcher</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/dispatchers/add_dispatcher" method="post"
                                    enctype="multipart/form-data">                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="du_name"
                                                required="required">
                                        </div>
                                    </div>                                   

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="du_contact"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="du_email"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="du_password"
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
                <h4 class="modal-title">Edit Dispatcher</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img_()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/dispatchers/edit_dispatcher" method="post"
                                    enctype="multipart/form-data">                                                                        
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="du_name" id="du_name"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="du_contact" id="du_contact"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="du_email" id="du_email"
                                                required="required" disabled="disabled">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" name="du_password" id="du_password" required="required">

                                            <input type="hidden" class="form-control" name="du_password_hide" id="du_password_hide" required="required">
                                        </div>
                                    </div>    
                                    
                                    <input name="du_id" type="hidden" id="du_id" />
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
$('.edit-user').click(function() {
    var user_id = $(this).data('user-id');

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/dispatchers/get_dispatcher_by_id',
        type: 'POST',
        data: {
            'du_id': user_id
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {
            //console.log(data);
            $('#du_id').val(data['du_id']);
            $('#du_name').val(data['du_name']);
			$('#du_contact').val(data['du_contact']);
            $('#du_email').val(data['du_email']); 
            $('#du_password').val(data['du_password']); 
            $('#du_password_hide').val(data['du_password']);            

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
        url: '<?php echo base_url(); ?>index.php/dispatchers/change_status',
        type: 'POST',
        data: {
            'du_id': user_id,
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