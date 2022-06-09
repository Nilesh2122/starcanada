<!DOCTYPE HTML>
<html>

<head>
	<?php
		$currentURL = current_url();
		$baseurl = base_url();
		if( $currentURL == $baseurl.'index.php/home' ){
			$pgheading = "Dashboard";
                        echo '<meta http-equiv="refresh" content="90">';
		}else if( $currentURL == $baseurl.'index.php/invoice/receivable' ){
			$pgheading = " Receivable";
		}else if( $currentURL == $baseurl.'index.php/invoice/payable' ){
			$pgheading = " Payable Contractor Statements";
		}else if( $currentURL == $baseurl.'index.php/invoice/vendor_invoice' ){
			$pgheading = " Payable Vendor Invoices";
		}else if( $currentURL == $baseurl.'index.php/customers' ){
			$pgheading = " Customers";
		}else if( $currentURL == $baseurl.'index.php/vendors' ){
			$pgheading = " Vendors";
		}else if( $currentURL == $baseurl.'index.php/rates' ){
			$pgheading = " Operation's Rates";
		}else if( $currentURL == $baseurl.'index.php/users/accountants' ){
			$pgheading = " Accountants";
		}else if( $currentURL == $baseurl.'index.php/drivers' ){
			$pgheading = " Drivers";
		}else if( $currentURL == $baseurl.'index.php/contractors' ){
			$pgheading = " Contractors";
		}else if( $currentURL == $baseurl.'index.php/dispatchers' ){
			$pgheading = " Dispatchers";
		}else if( $currentURL == $baseurl.'index.php/operations' ){
			$pgheading = " Current";
		}else if( $currentURL == $baseurl.'index.php/operations/completed' ){
			$pgheading = " Completed";
                        echo '<meta http-equiv="refresh" content="90">';
		}else if( $currentURL == $baseurl.'index.php/operations/dispatch_sheet' ){
			$pgheading = " Dispatch Sheet";
                        echo '<meta http-equiv="refresh" content="90">';
		}else if( $currentURL == $baseurl.'index.php/chats' ){
			$pgheading = " Chats";                        
		}else if( $currentURL == $baseurl.'index.php/operations/track' ){
			if( isset($_REQUEST['orderno_id']) && !empty($_REQUEST['orderno_id']) ){
				$txtopid = trim($_REQUEST['orderno_id']); 
			}else{
				$txtopid = "";
			}
			$pgheading = " ". ucwords( strtoupper( $txtopid ) ) ." Operation Tracking";												
		}else{
                       $pgheading = "Star Canada ";
                }
	?>
	<title> <?=( !empty($pgheading) && $pgheading != NULL )?ucwords(strtolower( $pgheading )):'';?></title>
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
	<!--animate-->
	<link href="<?php echo base_url(); ?>assets/css/animate.css" rel="stylesheet" type="text/css" media="all">
	<script src="<?php echo base_url(); ?>assets/js/wow.min.js"></script>
	<script>
		new WOW().init();
	</script>
	<!--//end-animate-->
	<!-- Metis Menu -->
	<script src="<?php echo base_url(); ?>assets/js/metisMenu.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">	
	<!--//Metis Menu -->

	<!-- Action button hovers -->
	<script>	
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();   
	});	
	</script>
	<style type="text/css">
		.dropdown-user .dropdown-toggle{
			padding: 0px !important;
		}
	</style>
</head>

<body class="cbp-spmenu-push">
	<div class="popup-loader">
		<img src="<?php echo base_url(); ?>assets/images/loader.gif" height="100">
		<p style="text-align: center;font-size: 22px;margin-top: 9px;color: #fff;position: fixed;left: 50%;top: 50%;margin-left: -115px;margin-top: -100px;"></p>
	</div>
	<div class="main-content">
		<!--left-fixed -navigation-->
		<div class=" sidebar" role="navigation">
			<div class="navbar-collapse">
				<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
					<ul class="nav" id="side-menu">
						<li>
							<a href="<?php echo base_url(); ?>" <?php if ($this->router->fetch_class() == 'home' && $this->router->fetch_method() == 'index') {
																	echo 'class="active"';
																} ?>><i class="fa fa-home nav_icon"></i>Dashboard</a>
						</li>

						<li>
							<a href="#" <?php if ($this->router->fetch_class() == 'invoice') { echo 'class="active"';	} ?>><i class="fa fa-list-alt nav_icon"></i>Accounting <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<li>
									<a href="<?php echo base_url(); ?>index.php/invoice/receivable" <?php if ($this->router->fetch_class() == 'invoice' && $this->router->fetch_method() == 'receivable') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-level-down nav_icon" style="color: #fff;"></i>Receivable</a>
								</li>

								<li>
									<a href="<?php echo base_url(); ?>index.php/invoice/payable" <?php if ($this->router->fetch_class() == 'invoice' && $this->router->fetch_method() == 'payable') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-level-up nav_icon"></i>Payable [Contractors]</a>
								</li>

								<li>
									<a href="<?php echo base_url(); ?>index.php/invoice/vendor_invoice" <?php if ($this->router->fetch_class() == 'invoice' && $this->router->fetch_method() == 'vendor_invoice') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-level-up nav_icon"></i>Payable [Vendors]</a>
								</li>
							</ul>
						</li>

						<li>
							<a href="#" <?php if ($this->router->fetch_class() == 'customers' || $this->router->fetch_class() == 'vendors') { echo 'class="active"';	} ?>><i class="fa fa-signal nav_icon"></i>Marketing & Sales <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<li>
									<a href="<?php echo base_url(); ?>index.php/customers" <?php if ($this->router->fetch_class() == 'customers') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-users nav_icon"></i>Customers</a>
								</li>

								<li>
									<a href="<?php echo base_url(); ?>index.php/vendors" <?php if ($this->router->fetch_class() == 'vendors') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-sitemap nav_icon"></i>Vendors</a>
								</li>

								<li>
									<a href="<?php echo base_url(); ?>index.php/rates" <?php if ($this->router->fetch_class() == 'rates') {
																							echo 'class="active"';
																						} ?>><i class="fa fa-dollar nav_icon"></i>Rates</a>
								</li>
							</ul>
						</li>

						<li>
							<a href="#" <?php if ($this->router->fetch_class() == 'users' || $this->router->fetch_class() == 'drivers' || $this->router->fetch_class() == 'contractors' || $this->router->fetch_class() == 'dispatchers') { echo 'class="active"';	} ?>><i class="fa fa-search nav_icon"></i>Human Resources <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<li>
									<a href="<?php echo base_url(); ?>index.php/users/accountants" <?php if ($this->router->fetch_class() == 'users') {
																										echo 'class="active"';
																									} ?>><i class="fa fa-user nav_icon"></i>Accountants</a>
								</li>

								<li>
									<a href="<?php echo base_url(); ?>index.php/drivers" <?php if ($this->router->fetch_class() == 'drivers') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-male nav_icon"></i>Drivers</a>
								</li>

								<li>
									<a href="<?php echo base_url(); ?>index.php/contractors" <?php if ($this->router->fetch_class() == 'contractors') {
																									echo 'class="active"';
																								} ?>><i class="fa fa-compress nav_icon"></i>Contractors</a>
								</li>	
								
								<li>
									<a href="<?php echo base_url(); ?>index.php/dispatchers" <?php if ($this->router->fetch_class() == 'dispatchers') {
																									echo 'class="active"';
																								} ?>><i class="fa fa-exchange nav_icon"></i>Dispatchers</a>
								</li>
							</ul>							
						</li>							
						
						<li>
							<a href="#" <?php if ($this->router->fetch_class() == 'operations') { echo 'class="active"';	} ?>><i class="fa fa-truck nav_icon"></i>Operations <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<li>
									<a href="<?php echo base_url(); ?>index.php/operations" <?php if ($this->router->fetch_class() == 'operations' && $this->router->fetch_method() == 'index') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-tasks nav_icon" style="color: #fff;"></i>Current</a>
								</li>

								<li>
									<a href="<?php echo base_url(); ?>index.php/operations/completed" <?php if ($this->router->fetch_class() == 'operations' && $this->router->fetch_method() == 'completed') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-check nav_icon"></i>Completed</a>
								</li>

								<li>
									<a href="<?php echo base_url(); ?>index.php/operations/dispatch_sheet" <?php if ($this->router->fetch_class() == 'operations' && $this->router->fetch_method() == 'dispatch_sheet') {
																								echo 'class="active"';
																							} ?>><i class="fa fa-check nav_icon"></i>Dispatch Sheet</a>
								</li>
							</ul>
						</li>																		

						<?php //if($this->session->userdata['user_role'] == 'Dispatcher'){ ?>
						<li>
							<a href="<?php echo base_url(); ?>index.php/chats" <?php if ($this->router->fetch_class() == 'chats') {
																					echo 'class="active"';
																				} ?>><i class="fa fa-comments nav_icon"></i>Chats</a>
						</li>
						<?php //} ?>

						<!-- <li>
							<a href="<?php //echo base_url(); ?>index.php/home/notifications" <?php //if ($this->router->fetch_method() == 'notifications') {
																					//echo 'class="active"';
																				//} ?>><i class="fa fa-dollar nav_icon"></i>Notifications</a>
						</li> -->

						<li>
							<a href="<?php echo base_url(); ?>index.php/users/logout"><i class="fa fa-sign-out nav_icon"></i>Logout</a>
						</li>

						<!--<li>
							<a href="#"><i class="fa fa-cogs nav_icon"></i>Components <span class="nav-badge">12</span> <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level collapse">
								<li>
									<a href="grids.html">Grid System</a>
								</li>
								<li>
									<a href="media.html">Media Objects</a>
								</li>
							</ul>
							 /nav-second-level 
						</li>-->
					</ul>
					<!-- //sidebar-collapse -->
				</nav>
			</div>
		</div>
		<!--left-fixed -navigation-->
		<!-- header-starts -->
		<div class="sticky-header header-section ">
			<div class="header-left">
				<!--toggle button start-->
				<button id="showLeftPush"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
				<!--logo -->
				<div class="logo">
					<a href="<?php echo base_url(); ?>">
						<h1>S C</h1>
						<span>AdminPanel</span>
					</a>
				</div>
				<!--//logo-->
			</div>
			<div class="header-right">

				<div class="profile_details_left">
					<!--notifications of menu start -->
					<?php $noti = $this->Notifications_model->get_unread(); ?>
					<ul class="nofitications-dropdown">
						<li class="dropdown head-dpdn">
							<a href="#" class="dropdown-toggle unred-notification" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-bell"></i><?php if($noti){ ?><span class="badge blue badge-notification"><?php echo sizeof($noti); ?></span><?php } ?></a>
							<ul class="dropdown-menu">
								<li>
									<div class="notification_header">
										<h3>You have <?php echo sizeof($noti)>0 ? sizeof($noti) : 'no' ; ?> new notification</h3>
									</div>
								</li>

								<?php if($noti){ foreach($noti as $key){ ?>
								<li><a href="<?php echo base_url().'index.php/operations/op_notification_open/'.$key['id']; ?>">										
										<div class="notification_desc">
											<p><?php echo $key['title']; ?></p>
											<p><span><?php echo $key['body']; ?></span></p>
											<?php
											$end_date =  $key['created_at'];											
											$now = date('Y-m-d H:i:s');
											
											$diff = strtotime($now) - strtotime($end_date);
											$fullDays    = floor($diff/(60*60*24));   
											$fullHours   = floor(($diff-($fullDays*60*60*24))/(60*60));
											?>
											<p><span><?php echo $fullDays>0 ? $fullDays.' Days ago.' : $fullHours.' Hours ago.'; ?></span></p>
										</div>
										<div class="clearfix"></div>
									</a>
								</li>
								<?php } }else{ ?>
								<li>
									<div class="notification_desc" style="border:none;">
										<p style="text-align: center;color:#c7c7c7;margin:20px 0;">No Notifications!</p>
									</div>
									<div class="clearfix"></div>
								</li>
								<?php } ?>
								<li>
									<div class="notification_bottom">
										<a href="<?php echo base_url(); ?>index.php/home/notifications">See all notifications</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
					<div class="clearfix"> </div>
				</div>

				<!-- Profile -->
				<div class="profile_details">
					<ul>
						<li class="dropdown profile_details_drop">
							<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
								<div class="profile_img">
									<span class="prfil-img"><img src="<?php echo base_url(); ?>assets/images/userr.png" alt=""> </span>
									<div class="user-name">
										<p><?php echo $this->session->userdata['name']; ?></p>
										<span style="display:block;"><?php echo $this->session->userdata['user_role']; ?></span>
									</div>
									<div class="clearfix"></div>
								</div>
								 <div class="dropdown-menu scale-up" style="padding: 10px;">
			                        <ul class="dropdown-user">
			                            <li style="padding: 0px;">
			                                <div class="dw-user-box">
			                                    <div class="u-img"><img src="<?php echo base_url(); ?>assets/images/userr.png" alt="user"></div>
			                                    <div class="u-text">
			                                        <h4><?php echo $this->session->userdata['name']; ?></h4>
			                                        <p class="text-muted"><?php echo $this->session->userdata['user_role']; ?></p>
			                                    </div>
			                                </div>
			                            </li>
			                            <li role="separator" class="divider"></li>
			                            <li><a href="<?php echo base_url(); ?>index.php/users/myprofile"><i class="ti-user"></i> My Profile</a></li>
			                            <li role="separator" class="divider"></li>
			                            <li><a href="<?php echo base_url(); ?>index.php/users/logout"><i class="fa fa-power-off"></i> Logout</a></li>
			                        </ul>
			                    </div>
							</a>
						</li>
					</ul>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<!-- //header-ends -->

		<!-- Alerts -->
		<style>
			.SC-alerts{
				position: fixed;
				width: 100%;
				top: 75px;
				left: 225px;
				z-index: 10;
			}

			@media (max-width:768px){
				.SC-alerts{
				position: fixed;
				width: 105%;
				top: 65px;
				left: -5px;
				right: -5px;
				z-index: 10;
				}	
			}
		</style>

		<script>
			$(window).load(function(){
				<?php if($this->session->flashdata('error')){ ?>
					$('.SC-alert-error').html("<strong>Failed!</strong> <?php echo $this->session->flashdata('error'); ?>").fadeIn().delay(6000).fadeOut();
				<?php }else if($this->session->flashdata('success')){ ?>
					$('.SC-alert-success').html("<strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>").fadeIn().delay(6000).fadeOut();
				<?php } ?>				
			});
		</script>

		<div class="alert alert-success SC-alerts SC-alert-success" style="display: none;">
		
		</div>		

		<div class="alert alert-danger SC-alerts SC-alert-error" style="display: none;">
		
		</div>

		<script>
			$('.unred-notification').click(function(){
				var mu_id = <?php echo $this->session->userdata['mu_id']; ?>

				$.ajax({
					url: '<?php echo base_url(); ?>index.php/home/unread_notifications',
					type: 'POST',
					data: {
						'mu_id': mu_id
					},
					dataType: 'json',
					beforeSend: function() {
					}, 
					success: function(data) {
						if(data == '1'){
							$('.badge-notification').hide();
						}else{
							window.location="<?php echo base_url(); ?>";
						}
					},
					error: function(request, error) {						
					},
					complete: function() {						
					}
				});
			});
		</script>