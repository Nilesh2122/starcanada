<div id="page-wrapper">
    <div class="main-page">
    
        <div class="row-one">
            <div class="col-md-4 widget">
                <div class="stats-left ">
                    <h5>Total</h5>
                    <h4>Operations</h4>
                </div>
                <div class="stats-right">
                    <label><?php echo $operations; ?></label>
                </div>
                <div class="clearfix"> </div>	
            </div>
            <div class="col-md-4 widget states-mdl">
                <div class="stats-left">
                    <h5>Total</h5>
                    <h4>Customers</h4>
                </div>
                <div class="stats-right">
                    <label><?php echo $customers; ?></label>
                </div>
                <div class="clearfix"> </div>	
            </div>
            <div class="col-md-4 widget states-last">
                <div class="stats-left">
                    <h5>Total</h5>
                    <h4>Drivers</h4>
                </div>
                <div class="stats-right">
                    <label><?php echo $drivers; ?></label>
                </div>
                <div class="clearfix"> </div>	
            </div>
        </div>

        <!-- <div class="row-one">  
            <div class="col-md-4 widget">
                <div class="stats-left ">
                    <h5>Total</h5>
                    <h4>Vendors</h4>
                </div>
                <div class="stats-right">
                    <label><?php //echo $vendors; ?></label>
                </div>
                <div class="clearfix"> </div>	
            </div>                      
            <div class="col-md-4 widget states-mdl">
                <div class="stats-left">
                    <h5>Total</h5>
                    <h4>Contractors</h4>
                </div>
                <div class="stats-right">
                    <label><?php //echo $contractors; ?></label>
                </div>
                <div class="clearfix"> </div>	
            </div>
            <div class="col-md-4 widget states-last">
                <div class="stats-left">
                    <h5>Total</h5>
                    <h4>Dispatchers</h4>
                </div>
                <div class="stats-right">
                    <label><?php //echo $dispatchers; ?></label>
                </div>
                <div class="clearfix"> </div>	
            </div>            
        </div>    -->
        
        <div class="row" style="margin-top:10px;">        	
            <div class="col-md-6 chrt-page-grids chrt-left">
	            <h4 class="title">More</h4>						
                <div class="list-group list-group-alternate">                     
                    <a href="<?php echo base_url(); ?>index.php/users/accountants" class="list-group-item"><span class="badge"><?php echo $accountants; ?></span> <i class="fa fa-user"></i> Accountants </a> 
                    <a href="<?php echo base_url(); ?>index.php/dispatchers" class="list-group-item"><span class="badge badge-warning"><?php echo $dispatchers; ?></span> <i class="fa fa-exchange"></i> Dispatchers </a>                    
                    <!-- <a href="<?php //echo base_url(); ?>index.php/customers" class="list-group-item"><span class="badge badge-primary"><?php echo $customers; ?></span> <i class="fa fa-users"></i> Customers </a>  -->
                    <a href="<?php echo base_url(); ?>index.php/contractors" class="list-group-item"><span class="badge badge-danger"><?php echo $contractors; ?></span> <i class="fa fa-compress"></i> Contractors </a> 
                    <!-- <a href="<?php //echo base_url(); ?>index.php/drivers" class="list-group-item"><span class="badge"><?php echo $drivers; ?></span> <i class="fa fa-male"></i> Drivers </a> -->                    
                    <!-- <a href="<?php //echo base_url(); ?>index.php/operations" class="list-group-item"><span class="badge badge-primary"><?php echo $operations; ?></span> <i class="fa fa-truck"></i> Operations </a> -->
                    <a href="<?php echo base_url(); ?>index.php/vendors" class="list-group-item"><span class="badge badge-primary"><?php echo $vendors; ?></span> <i class="fa fa-sitemap"></i> Vendors </a>
                </div>
            </div>
            
            <div class="col-md-6 chrt-page-grids chrt-right" style="min-height: 304px;">
	            <h4 class="title">Upcoming Operations</h4>						
                <div class="list-group list-group-alternate">     
                    <?php if($ops){ foreach($ops as $o){ ?>
                        <a href="<?php echo base_url(); ?>index.php/operations" class="list-group-item">
                            <span class="badge" style="font-size: 13px;font-weight: normal;padding: 5px 10px; background: #81b3ff;"><?php echo $statuses[$o['op_status']]; ?></span>
                            <?php echo $o['cust_name'].' ['.$o['op_c_load_reference'].']'; ?>
                        </a>
                    <?php } }else{ ?>
                        <p style="margin: 50px 0; text-align: center; color: #999;">No Upcoming Operations!</p>
                    <?php } ?>
                </div>
            </div>
        </div>
        
    </div>
</div>