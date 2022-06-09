<!DOCTYPE HTML>
<html>
<head>
<title>Login - STAR CANADA</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<link rel="icon" href="<?php echo base_url(); ?>assets/images/fi.png" type="image/png">
<!-- Bootstrap Core CSS -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel='stylesheet' type='text/css' />

<!-- Custom CSS -->
<link href="<?php echo base_url(); ?>assets/css/style.css" rel='stylesheet' type='text/css' />
<!-- font CSS -->
<!-- font-awesome icons -->
<link href="<?php echo base_url(); ?>assets/css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
 <!-- js-->
<script src="<?php echo base_url(); ?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/modernizr.custom.js"></script>

<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">
<style>
html, body{
	background-color: #F1F1F1;
}
</style>
</head> 
<body>
<div id="page-wrapper" style="min-height:100%; padding-top: 50px;">
    <div class="main-page login-page ">
        <h3 class="title1">Login</h3>
        <div class="widget-shadow">
            <div class="login-top">
                <h4>Welcome to STAR CANADA (Admin Panel)</h4>
            </div>
            <div class="login-body">
                <form autocomplete="off" method="post" action="<?php echo base_url(); ?>index.php/users/login_process_">
                    <input type="email" class="user" name="email" placeholder="Enter your email" required>
                    <input type="password" name="password" class="lock" placeholder="Password" required>                                        
                    
                    <input type="submit" name="Sign In" value="Sign In">                                        
                </form>                
                <?php if($this->session->flashdata('error')){?>
                <div class="alert alert-danger" style="margin-bottom: 0px; margin-top:15px;">
                	<strong>Fail!</strong> <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php } ?>    
            </div>
        </div>        
    </div>
</div>
		
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"> </script>
</body>
</html>