<!DOCTYPE HTML>
<html>
<head>
<title>Forgot Password - STAR CANADA</title>
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
#page-wrapper{
    min-height:100vh;     
    background-image: url('<?php echo base_url(); ?>assets/images/truck-bg.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    padding: 0;
}
.head-login{
    background-color: #fff;
    padding: 8px 15px 10px;
    margin-bottom: 80px;
}
.head-login p{    
    margin: 0;
    font-size: 13px;
    letter-spacing: 4px;    
    text-align: center;
    color: #999;
}
</style>
</head> 
<body>
<div id="page-wrapper">
    <div class="head-login">
        <center>
            <img src="<?php echo base_url(); ?>assets/images/fi.png" height="40px" alt="SC LOGO">
        </center>
        <p>STAR CANADA INC</p>
    </div>
    <div class="main-page login-page ">        
        <div class="widget-shadow" style="border-radius: 25px;">
            <!-- <div class="login-top" style="border-top-left-radius: 25px;border-top-right-radius: 25px;">
                <h4>Login to STAR CANADA (Admin Panel)</h4>
            </div> -->
            <div class="login-body" style="border-bottom-left-radius: 25px;border-bottom-right-radius: 25px;">
                <h4 style="margin-bottom: 20px;font-size: 18px;color: #323232;border-bottom: 1px solid #ddd;padding-bottom: 12px;letter-spacing: 2px;">Reset Password</h4>

                <form autocomplete="off" method="post" action="<?php echo base_url(); ?>index.php/users/reset_password">
                    <div style="position:relative;">
                        <input type="email" class="user" name="email" placeholder="Enter your email" required>
                        <div class="user"></div>
                    </div>                    

                    <input type="submit" name="Reset Password" value="Submit">                                        
                </form>                
                <?php if($this->session->flashdata('error')){?>
                <div class="alert alert-danger" style="margin-bottom: 0px; margin-top:15px;">
                	<strong>Fail!</strong> <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php } ?>
                <?php if($this->session->flashdata('success')){?>
                <div class="alert alert-success" style="margin-bottom: 0px; margin-top:15px;">
                	<strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
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