<script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyAvgN7X2UmjAXNC5GaVBBgLhaJKZd4SiFE&libraries=places'></script>
<script src="<?php echo base_url(); ?>assets/js/locationpicker.jquery.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>


<div id="page-wrapper">

    <a class="SITE-BACK" href="javascript:history.back();"><i class="fa fa-angle-double-left"></i> Back</a>

    <div class="main-page">
    <div class="forms">
    
    	<div class=" form-grids row form-grids-right" style="margin-top:0">
            <div class="widget-shadow " data-example-id="basic-forms"> 
                <div class="form-title">
                    <h4>My Profile</h4>
                </div>
                <div class="form-body">
                    <form autocomplete="off" action="<?php echo base_url(); ?>index.php/users/edit_profile" method="post" class="form-horizontal" onsubmit="return check_form();">  
                        <div class="form-group"> 
                            <label for="inputEmail3" class="col-sm-2 control-label">Name</label> 
                            <input type="hidden" name="id" value="<?php echo $customers['id']; ?>">
                            <input type="hidden" name="role" value="<?php echo $customers['role']; ?>">
                            <input type="hidden" name="old_password" value="<?php echo $customers['password']; ?>">
                            <div class="col-sm-10"> <input type="text" class="form-control" name="name" value="<?php echo $customers['name']; ?>" required="required"></div> 
                        </div>
                        
                        <div class="form-group"> 
                            <label for="inputEmail3" class="col-sm-2 control-label">Email</label> 
                            <div class="col-sm-10"> <input type="text" class="form-control" disabled name="email" value="<?php echo $customers['email']; ?>" required="required"></div> 
                        </div>
                        
                        <div class="form-group"> 
                            <label for="inputEmail3" class="col-sm-2 control-label">Password</label> 
                            <div class="col-sm-10"> <input type="password" class="form-control" name="password" required="required" value="<?php echo $customers['password']; ?>"></div> 
                        </div>
                        
                        <div class="col-sm-offset-2"> 
                            <button type="submit" class="btn btn-default">Edit</button> 
                        </div> 

                    </form>                             
                </div>
            </div>
        </div>
    
    </div>
    </div>
</div>