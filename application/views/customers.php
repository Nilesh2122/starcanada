<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Customers <?php if(isset($_GET['archieved'])){ echo '[Archived]'; } ?></h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Search" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">

                        <?php if(!isset($_GET['archieved'])){ ?> 
                        <a onclick="document.getElementById('add_user').click();" class="btn btn-primary"
                            style="float:right;border-radius:0"><i class="fa fa-plus"></i> &nbsp;Add Customer</a>
                        
                        <a href="<?php echo base_url(); ?>index.php/customers?archieved=1" target="_blank" class="btn btn-primary" style="float:right;border-radius:0; margin-right: 15px;"><i class="fa fa-archive"></i> &nbsp;Archived</a>
                        <?php } ?>
                    </form>
                </div>
                <table class="table table-bordered sortable">

                    <thead>
                        <tr>
                            <th>Sr.</th>                            
                            <th>Number</th>
                            <th>Name</th>                            
                            <th>Address</th>  
                            <th>Primary Contact Name</th>
                            <th>Primary Contact Email</th>                          
                            <th>Primary Contact Phone</th>
                            <th>Secondary Contact Name</th>
                            <th>Secondary Contact Email</th>                          
                            <th>Secondary Contact Phone</th>
                            <th>FAX</th>
                            <th>Website</th>
                            <th style="text-align:center;">Active</th>
                            <th style="text-align:center;">Action</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $key['cust_number']; ?></td>                            
                            <td><?php echo $key['cust_name']; ?></td>
                            <td><?php echo $key['cust_address']; ?></td>                            
                            <td><?php echo $key['cust_cname1']; ?></td>                            
                            <td><?php echo $key['cust_cemail1']; ?></td>                            
                            <td><?php echo $key['cust_contact1']; ?></td>                            
                            <td><?php echo $key['cust_cname2']; ?></td>                            
                            <td><?php echo $key['cust_cemail2']; ?></td>                            
                            <td><?php echo $key['cust_contact2']; ?></td>                            
                            <td><?php echo $key['cust_fax']; ?></td>                            
                            <td><?php echo $key['cust_website']; ?></td>                            
                            <td align="center">
                                <label class="switch">
                                    <input type="checkbox" <?php echo $key['is_active'] == '1'?'checked':'' ?>
                                        class="chk-active" data-user="<?php echo $key['cust_id']; ?>">
                                    <span class="slider round"></span>
                                </label>
                            </td> 
                            <td class="table-actions">
                                <button type="button" class="edit-user"
                                    data-user-id="<?php echo $key['cust_id']; ?>"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>                               

                                <button type="button" class="btn-danger" onclick="deleteRecord('<?php echo base_url(); ?>index.php/customers/delete?id=<?php echo base64_encode($key['cust_id']); ?>')"><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button>

                                <?php if(!isset($_GET['archieved'])){ ?>
                                <button type="button" class="btn-warning" onclick="archieveRecord('<?php echo base_url(); ?>index.php/customers/archieve?id=<?php echo base64_encode($key['cust_id']); ?>')"><i class="fa fa-archive" data-toggle="tooltip" title="Archive"></i></button>
                                <?php }else{ ?>
                                <button type="button" class="btn-warning" onclick="unarchieveRecord('<?php echo base_url(); ?>index.php/customers/archieve?id=<?php echo base64_encode($key['cust_id']); ?>&unarchive=1')"><i class="fa fa-undo" data-toggle="tooltip" title="Unarchive"></i></button>
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
    <div class="modal-dialog" style="width: 70%">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Customer</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/customers/add_customer" method="post"
                                    enctype="multipart/form-data">   
                                    
                                    <div class="form-group" style="margin-top: 0px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Basic Details</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 20px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_name"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Number</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="cust_number"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_address"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Primary Contact Details</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_cname1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="cust_cemail1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="cust_contact1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Secondary Contact Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_cname2">
                                        </div>
                                    </div>                                                                                 

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="cust_cemail2">
                                        </div>
                                    </div>                                    

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="cust_contact2">
                                        </div>
                                    </div>       
                                    
                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Additional Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Fax</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="cust_fax">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Website</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_website">
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
    <div class="modal-dialog" style="width: 70%">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Customer</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img_()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/customers/edit_customer" method="post"
                                    enctype="multipart/form-data">   
                                    
                                    <div class="form-group" style="margin-top: 0px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Basic Details</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 20px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_name" id="cust_name"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Number</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="cust_number" id="cust_number"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_address" id="cust_address"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Primary Contact Details</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_cname1" id="cust_cname1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="cust_cemail1" id="cust_cemail1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="cust_contact1" id="cust_contact1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Secondary Contact Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_cname2" id="cust_cname2">
                                        </div>
                                    </div>                                    

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="cust_cemail2" id="cust_cemail2">
                                        </div>
                                    </div>                                    

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="cust_contact2" id="cust_contact2">
                                        </div>
                                    </div>   
                                    
                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Additional Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Fax</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="cust_fax" id="cust_fax">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Website</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_website" id="cust_website">
                                        </div>
                                    </div>
                                    
                                    <input name="cust_id" type="hidden" id="cust_id" />
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
        url: '<?php echo base_url(); ?>index.php/customers/get_customer_by_id',
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
            $('#cust_id').val(data['cust_id']);
            $('#cust_number').val(data['cust_number']);
            $('#cust_name').val(data['cust_name']);
            $('#cust_address').val(data['cust_address']);
            $('#cust_cname1').val(data['cust_cname1']);
            $('#cust_cname2').val(data['cust_cname2']);            
            $('#cust_cemail1').val(data['cust_cemail1']);
            $('#cust_cemail2').val(data['cust_cemail2']);            
			$('#cust_contact1').val(data['cust_contact1']);
            $('#cust_contact2').val(data['cust_contact2']);
            $('#cust_fax').val(data['cust_fax']);
			$('#cust_website').val(data['cust_website']);                        

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
        url: '<?php echo base_url(); ?>index.php/customers/change_status',
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