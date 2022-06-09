<?php 
$STATUSCOLORS[1] = '#81b3ff';
$STATUSCOLORS[2] = '#81b3ff';
$STATUSCOLORS[3] = '#ffc107';
$STATUSCOLORS[4] = '#03a9f4';
$STATUSCOLORS[5] = '#ff9800';
$STATUSCOLORS[6] = '#009688';
$STATUSCOLORS[7] = '#4caf50';
?>

<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Operations [Completed]</h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Customer name" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">                        
                    </form>
                </div>
                <table class="table table-bordered sortable">

                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <!--<th>Reference ID</th>
                            <th>Loading location</th>
                            <th>Delivery location</th>
                            <th>Total Cost</th>
                            <th>Commission</th>-->
                            <th style="text-align: center;">Status</th>
                            <th>Customer</th>
                            <th>Driver</th>
                            <!--<th>Loading date</th>
                            <th>Delivery date</th>-->
                            <th>Created on</th>                            
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <!--<td><?php //echo $key['op_ref_id']; ?></td>
                            <td><?php //echo $key['op_loading_location']; ?></td>
                            <td><?php //echo $key['op_delivery_location']; ?></td>
                            <td><?php //echo '$'.$key['op_total_cost']; ?></td>
                            <td><?php //if($key['op_status'] >= 3){ echo $key['con_id']=='0' ? 'To Driver ':'To Contractor '; echo $key['op_dc_commission']; ?>% <br>( <?php //echo '$'.($key['op_total_cost']*$key['op_dc_commission'])/100; ?> ) <?php //}else{ echo '-'; } ?></td>-->
                            <td align="center"><span style="color:#fff; padding:2px 15px 4px; border-radius:15px; background-color: <?php echo $STATUSCOLORS[$key['op_status']]; ?>"><?php echo $op_status[$key['op_status']]; ?></span></td>
                            <td><?php echo $key['cust_name']; ?></td>
                            <td><?php echo $key['driver_name']; ?></td>                         
                            <!--<td><?php //echo $key['op_loading_date']; ?></td>
                            <td><?php //echo $key['op_delivery_date']; ?></td>-->
                            <td><?php echo date('Y-m-d', strtotime($key['created_at'])); ?></td>                            
                            
                            <td class="table-actions">
                            <!-- <td align="center"> -->
                                
                                <form action="<?php echo base_url(); ?>index.php/operations/track" method="post" target="_blank">
                                <input type="hidden" name="op_id" value="<?php echo $key['op_id']; ?>" />
                                <button><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button>
                                </form>
                                
                                <?php if($this->session->userdata('user_role') == 'Administrator'){ ?>

                                <form action="<?php echo base_url(); ?>index.php/operations/modify_operation" method="post">
                                <input type="hidden" name="op_id" value="<?php echo $key['op_id']; ?>" />    
                                <button><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>
                                </form>                                                                 

                                <?php } ?>
                                                                                                
                            </td>                          
                        </tr>
                        <?php } ?>
                    </tbody>                    
                </table>                
                <div id="data-loader" style="display: none; text-align: center;">
                    <img src="<?php echo base_url(); ?>assets/images/data-loader.gif" style="max-width: 100px;" alt="Data loader">
                </div>                
            </div>
        </div>
    </div>
</div>

<script>
var last_op = '<?php if($datas){ echo $key['op_id']; } ?>';
var row = '<?php if($datas){ echo $i; } ?>';
var statuscolors = '<?php echo implode(',', $STATUSCOLORS); ?>';
statuscolors = statuscolors.split(',');
<?php if($datas){ ?>
$(window).scroll(function() {    
    if($(window).scrollTop() + $(window).height() == $(document).height()) {        
        if(last_op){
        //ajax to call other data        
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/operations/completed_more',
            type: 'POST',
            data: {
                'op_id': last_op,
                'title': '<?php echo $search_title; ?>'
            },
            dataType: 'json',
            beforeSend: function() {
                $('#data-loader').fadeIn();
            }, 
            success: function(data) {
                //console.log(data);
                //exit();
                
                var append = '';
                for(var j=0; j<data['datas'].length; j++)
                {
                    row++;                
                    append += '<tr><td>'+ row +'</td><td align="center"><span style="color:#fff; padding:2px 15px 4px; border-radius:15px; background-color: '+ statuscolors[(parseInt(data['datas'][j]['op_status'])-1)] +'">'+ data['op_status'][data['datas'][j]['op_status']] +'</span></td><td>'+ data['datas'][j]['cust_name'] +'</td><td>'+ data['datas'][j]['driver_name'] +'</td><td>'+ data['datas'][j]['created_at'].split(' ')[0] +'</td><td class="table-actions"><form action="<?php echo base_url(); ?>index.php/operations/track" method="post" target="_blank"><input type="hidden" name="op_id" value="'+ data['datas'][j]['op_id'] +'" /><button><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button></form>';

                    <?php if($this->session->userdata('user_role') == 'Administrator'){ ?>

                    append += '<form action="<?php echo base_url(); ?>index.php/operations/modify_operation" method="post"><input type="hidden" name="op_id" value="'+ data['datas'][j]['op_id'] +'" /><button><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button></form></td></tr>';

                    <?php }else{ ?>

                        append += '</td></tr>';

                    <?php } ?>

                    last_op = data['datas'][j]['op_id'];                    
                }                

                $('tbody').append(append);
            },
            error: function(request, error) {
                alert("Some thing went wrong!");
                location.reload();
            },
            complete: function() {
                $('#data-loader').fadeOut();			
            }
        });	
        }        
    }
});
<?php } ?>
</script>