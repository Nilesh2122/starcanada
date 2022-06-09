<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Contractors <?php if(isset($_GET['archieved'])){ echo '[Archived]'; } ?></h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Search" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">                        
                        
                        <?php if(!isset($_GET['archieved'])){ ?> 
                        <a onclick="document.getElementById('add_user').click();" class="btn btn-primary"
                            style="float:right;border-radius:0"><i class="fa fa-plus"></i> &nbsp;Add Contractor</a>
                                                   
                        <a href="<?php echo base_url(); ?>index.php/contractors?archieved=1" target="_blank" class="btn btn-primary" style="float:right;border-radius:0; margin-right: 15px;"><i class="fa fa-archive"></i> &nbsp;Archived</a>
                        <?php } ?>
                    </form>
                </div>
                <table class="table table-bordered sortable">

                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Contact Name</th>
                            <th>Phone</th>
                            <th>Email Address</th>
                            <th>Address</th>
                            <th>Commission(%)</th>                                                        
                            <th>Hire Date</th>
                            <th>MVID</th>
                            <th>NSC</th>
                            <th>Bank A/C</th>
                            <th>Insurance Broker</th>
                            <th>Insurance Broker Contact Name</th>
                            <th>Insurance Broker Contact Phone</th>
                            <th>Insurance Broker Contact Email</th>
                            <th>Insurance Company 1</th>
                            <th>Policy No. 1</th>
                            <th>Insurance Company 2</th>
                            <th>Policy No. 2</th>
                            <th>Insurance Company 3</th>
                            <th>Policy No. 3</th>
                            <th style="text-align:center;">Action</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $key['con_number']; ?></td>
                            <td><?php echo $key['con_name']; ?></td>
                            <td><?php echo $key['con_contact_name']; ?></td>
                            <td><?php echo $key['con_contact']; ?></td>                         
                            <td><?php echo $key['con_email']; ?></td>
                            <td><?php echo $key['con_address']; ?></td>
                            <td><?php echo $key['con_commission']; ?></td>
                            <td><?php echo $key['con_hire_date']; ?></td>
                            <td><?php echo $key['con_mvid']; ?></td>
                            <td><?php echo $key['con_nsc']; ?></td>
                            <td><?php echo $key['con_bank_ac']; ?></td>
                            <td><?php echo $key['con_insurance_broker']; ?></td>
                            <td><?php echo $key['con_insurance_broker_contact_name']; ?></td>
                            <td><?php echo $key['con_insurance_broker_contact']; ?></td>
                            <td><?php echo $key['con_insurance_broker_email']; ?></td>
                            <td><?php echo $key['con_insurance_company1']; ?></td>
                            <td><?php echo $key['con_policy_num1']; ?></td>
                            <td><?php echo $key['con_insurance_company2']; ?></td>
                            <td><?php echo $key['con_policy_num2']; ?></td>
                            <td><?php echo $key['con_insurance_company3']; ?></td>
                            <td><?php echo $key['con_policy_num3']; ?></td>                                                    
                            <td class="table-actions">
                                <button type="button" class="view-drivers"
                                    data-user-id="<?php echo $key['con_id']; ?>"><i class="fa fa-users" data-toggle="tooltip" title="View Drivers"></i></button>

                                <button type="button" class="view-equp"
                                    data-user-id="<?php echo $key['con_id']; ?>"><i class="fa fa-truck" data-toggle="tooltip" title="View Equipments"></i></button>                               

                                <button type="button" class="add-eq"
                                    data-user-id="<?php echo $key['con_id']; ?>"><i class="fa fa-plus" data-toggle="tooltip" title="Add Equipments"></i></button>
                            
                                <br><br>

                                <button type="button" class="edit-user"
                                    data-user-id="<?php echo $key['con_id']; ?>"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>                                                                     

                                <button type="button" class="btn-danger" onclick="deleteRecord('<?php echo base_url(); ?>index.php/contractors/delete?id=<?php echo base64_encode($key['con_id']); ?>')"><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button>

                                <?php if(!isset($_GET['archieved'])){ ?>
                                <button type="button" class="btn-warning" onclick="archieveRecord('<?php echo base_url(); ?>index.php/contractors/archieve?id=<?php echo base64_encode($key['con_id']); ?>')"><i class="fa fa-archive" data-toggle="tooltip" title="Archive"></i></button>
                                <?php }else{ ?>
                                <button type="button" class="btn-warning" onclick="unarchieveRecord('<?php echo base_url(); ?>index.php/contractors/archieve?id=<?php echo base64_encode($key['con_id']); ?>&unarchive=1')"><i class="fa fa-undo" data-toggle="tooltip" title="Unarchive"></i></button>
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
                <h4 class="modal-title">Add Contractor</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/contractors/add_contractor" method="post"
                                    enctype="multipart/form-data">   
                                    
                                    <div class="form-group" style="margin-top: 0px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Basic Details</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 20px; border-color: #ddd;">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Cont. Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_name"
                                                required="required">
                                        </div>
                                    </div>                                   

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="con_contact"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_address"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Commission(%)</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="con_commission"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Hire Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control dp" name="con_hire_date"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Cont. Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_contact_name"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="con_email"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">MVID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_mvid"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">NSC</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_nsc"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Bank A/C</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_bank_ac"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Primary Insurance Details</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Company</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_company1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Policy No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_policy_num1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Secondary Insurance Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Company</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_company2">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Policy No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_policy_num2">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Tertiary Insurance Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Company</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_company3">
                                        </div>
                                    </div>                                                                    

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Policy No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_policy_num3">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Insurance Broker Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Insurance Broker</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_broker">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_broker_contact_name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="con_insurance_broker_contact">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="con_insurance_broker_email">
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
                <h4 class="modal-title">Edit Contractor</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img_()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/contractors/edit_contractor" method="post"
                                    enctype="multipart/form-data">   
                                    
                                    <div class="form-group" style="margin-top: 0px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Basic Details</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 20px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Cont. name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_name" id="con_name"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="con_contact" id="con_contact"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_address" id="con_address"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Commission(%)</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="con_commission" id="con_commission"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Hire Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control dp" name="con_hire_date" id="con_hire_date"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Cont. Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_number" id="con_number"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_contact_name" id="con_contact_name"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="con_email" id="con_email"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">MVID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_mvid" id="con_mvid"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">NSC</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_nsc" id="con_nsc"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Bank A/C</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_bank_ac" id="con_bank_ac"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Primary Insurance Details</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Company</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_company1" id="con_insurance_company1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Policy No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_policy_num1" id="con_policy_num1"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Secondary Insurance Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Company</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_company2" id="con_insurance_company2">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Policy No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_policy_num2" id="con_policy_num2">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Tertiary Insurance Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Company</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_company3" id="con_insurance_company3">
                                        </div>
                                    </div>                                                                        

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Policy No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_policy_num3" id="con_policy_num3">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 30px;">
                                        <label style="color: #999;" for="inputEmail3" class="col-sm-3 control-label">Insurance Broker Details (Optional)</label>
                                        <div class="col-sm-9">
                                            <hr style="margin-bottom: 30px; border-color: #ddd;">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Insurance Broker</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_broker" id="con_insurance_broker">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="con_insurance_broker_contact_name" id="con_insurance_broker_contact_name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Phone</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="con_insurance_broker_contact" id="con_insurance_broker_contact">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Contact Email</label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control" name="con_insurance_broker_email" id="con_insurance_broker_email">
                                        </div>
                                    </div>
                                    
                                    <input name="con_id" type="hidden" id="con_id" />
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


<!-- View Drivers -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal2" style="display:none;"
    id="view-drivers">Open Modal</button>

<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width:90%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="append-title"></h4>
            </div>
            <div class="modal-body" id="drivers-div">
                
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
        </div>

    </div>
</div>

<!-- Add equipments modal -->
<!-- Modal -->
<div id="eqModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Equipment</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/contractors/add_equipment" method="post"
                                    enctype="multipart/form-data">                                                                           
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Unit</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="eq_unit"
                                                required="required">
                                        </div>
                                    </div>                                   

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Type</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_type"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Year</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_year"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Make</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_make"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">VIN</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_vin"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">LPN</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_lpn"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">SI Expiry</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control dp" name="eq_si_expiry"
                                                required="required">
                                        </div>
                                    </div>

                                    <input type="hidden" name="con_id" id="eq_con_id">

                                    <input type="submit" style="display:none;" id="eq_add_user_submit" />

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('eq_add_user_submit').click();">Add</button>
            </div>
        </div>

    </div>
</div>


<!-- edit equipments modal -->
<!-- Modal -->
<div id="eqModaledit" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Equipment</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/contractors/edit_equipment" method="post"
                                    enctype="multipart/form-data">                                                                           
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Unit</label>
                                        <div class="col-sm-9">
                                            <input type="number" step="any" class="form-control" name="eq_unit" id="eq_unit"
                                                required="required">
                                        </div>
                                    </div>                                   

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Type</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_type" id="eq_type"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Year</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_year" id="eq_year"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Make</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_make" id="eq_make"
                                                required="required">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">VIN</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_vin" id="eq_vin"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">LPN</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eq_lpn" id="eq_lpn"
                                                required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">SI Expiry</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control dp" name="eq_si_expiry" id="eq_si_expiry"
                                                required="required">
                                        </div>
                                    </div>

                                    <input type="hidden" name="eq_id" id="eq_id">

                                    <input type="submit" style="display:none;" id="eq_edit_user_submit" />

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button type="button" class="btn btn-primary"
                    onclick="document.getElementById('eq_edit_user_submit').click();">Save</button>
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
        url: '<?php echo base_url(); ?>index.php/contractors/get_contractor_by_id',
        type: 'POST',
        data: {
            'con_id': user_id
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {
            //console.log(data);
            $('#con_id').val(data['con_id']);
            $('#con_name').val(data['con_name']);
			$('#con_contact').val(data['con_contact']);
            $('#con_address').val(data['con_address']);
			$('#con_commission').val(data['con_commission']);
            $('#con_hire_date').val(data['con_hire_date']);
            $('#con_number').val(data['con_number']);
            $('#con_contact_name').val(data['con_contact_name']);            
            $('#con_email').val(data['con_email']);
            $('#con_mvid').val(data['con_mvid']);
            $('#con_nsc').val(data['con_nsc']);
            $('#con_bank_ac').val(data['con_bank_ac']);
            $('#con_insurance_broker').val(data['con_insurance_broker']);
            $('#con_insurance_broker_contact_name').val(data['con_insurance_broker_contact_name']);
            $('#con_insurance_broker_contact').val(data['con_insurance_broker_contact']);
            $('#con_insurance_broker_email').val(data['con_insurance_broker_email']);
            $('#con_insurance_company1').val(data['con_insurance_company1']);
            $('#con_insurance_company2').val(data['con_insurance_company2']);
            $('#con_insurance_company3').val(data['con_insurance_company3']);
            $('#con_policy_num1').val(data['con_policy_num1']);
            $('#con_policy_num2').val(data['con_policy_num2']);
            $('#con_policy_num3').val(data['con_policy_num3']);            

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

$('.view-drivers').click(function(){
	var user_id = $(this).data('user-id');

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/contractors/get_drivers_for_contractor',
        type: 'POST',
        data: {
            'con_id': user_id
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {                			
			var apend = '';
			
			if(data.length > 0){
				apend = '<div style="margin:0;" class="table-responsive bs-example widget-shadow"><table class="table table-bordered"><thead><tr><th>Sr.</th><th>Name</th><th>Email</th><th>Address</th><th>Phone</th><th>License</th></tr></thead><tbody>';
				
				for(var i=0; i<data.length; i++)
				{
					apend += '<tr><td>'+ (i+1) +'</td><td>'+ data[i]['driver_name'] +'</td><td>'+ data[i]['driver_email'] +'</td><td>'+ data[i]['driver_address'] +'</td><td>'+ data[i]['driver_contact'] +'</td><td>'+ data[i]['license_number'] +'</td></tr>';
				}
				
				apend += '</tbody></table></div>';
			}
			else{
				apend = '<p style="margin:50px 0; font-size:18px; color:#c7c7c7; text-align:center;">No drivers found!</p>';
			}   
			
            $('#append-title').empty();
            $('#append-title').append('Drivers');
			$('#drivers-div').empty();
			$('#drivers-div').append(apend);     

            $('#view-drivers').click();
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });
});

//add eqp
$('.add-eq').click(function(){
    $('#eq_con_id').val($(this).data('user-id'));
    $('#eqModal').modal('toggle');
});

$('.view-equp').click(function(){
	var user_id = $(this).data('user-id');

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/contractors/get_equipments_for_contractor',
        type: 'POST',
        data: {
            'con_id': user_id
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {                			
			var apend = '';
			
			if(data.length > 0){
				apend = '<div style="margin:0;" class="table-responsive bs-example widget-shadow"><table class="table table-bordered"><thead><tr><th>Sr.</th><th>Unit</th><th>Type</th><th>Year</th><th>Make</th><th>VIN</th><th>LPN</th><th>SI Expiry</th><th style="text-align:center;">Action</th></tr></thead><tbody>';
				
				for(var i=0; i<data.length; i++)
				{
                    if(data[i]['assigned'] == '0'){
                        var dltLink = "'<?php echo base_url(); ?>index.php/contractors/deleteEq?id="+ btoa(data[i]['eq_id']) +"'";
                        var onclick = 'onclick="deleteRecord(' + dltLink + ')"';
                    }else{
                        var alert = "'"+ data[i]['assigned'] +"'";
                        var onclick = 'onclick="alert(' + alert + ')"';
                    }
                    
					apend += '<tr><td>'+ (i+1) +'</td><td>'+ data[i]['eq_unit'] +'</td><td>'+ data[i]['eq_type'] +'</td><td>'+ data[i]['eq_year'] +'</td><td>'+ data[i]['eq_make'] +'</td><td>'+ data[i]['eq_vin'] +'</td><td>'+ data[i]['eq_lpn'] +'</td><td>'+ data[i]['eq_si_expiry'] +'</td><td class="table-actions"><button type="button" class="btn btn-primary edit-eqp" data-dismiss="modal" data-eqp="'+ JSON.stringify(data[i]).replace(/"/g, '~') +'"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button><button type="button" class="btn-danger" ' + onclick + '><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button></td></tr>';
				}
				
				apend += '</tbody></table></div>';
			}
			else{
				apend = '<p style="margin:50px 0; font-size:18px; color:#c7c7c7; text-align:center;">No Equipments found!</p>';
			}   
			
            $('#append-title').empty();
            $('#append-title').append('Equipments');
			$('#drivers-div').empty();
			$('#drivers-div').append(apend);     

            $('#view-drivers').click();
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });
});

$(document).on('click','.edit-eqp',function(){    
    $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
    $(".popup-loader").fadeIn();
    
    var data = $(this).data('eqp');
    data = JSON.parse(data.replace(/~/g, '"'));
    
    $('#eq_id').val(data['eq_id']);
    $('#eq_unit').val(data['eq_unit']);
    $('#eq_type').val(data['eq_type']);
    $('#eq_year').val(data['eq_year']);
    $('#eq_make').val(data['eq_make']);
    $('#eq_vin').val(data['eq_vin']);
    $('#eq_lpn').val(data['eq_lpn']);
    $('#eq_si_expiry').val(data['eq_si_expiry']);              

    $('#eqModaledit').modal('toggle');

    $(".popup-loader").fadeOut();	
});
</script>