<link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

<script type="text/javascript"
    src='https://maps.google.com/maps/api/js?key=AIzaSyAvgN7X2UmjAXNC5GaVBBgLhaJKZd4SiFE&libraries=places'></script>
<script src="<?php echo base_url(); ?>assets/js/locationpicker.jquery.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<style>
.dt-buttons,.dataTables_filter
{
    display: inline-block;
    margin: 20px 0px;
}
.dt-buttons button
{
     background-color: #337ab7;
    border-color: #2e6da4;
    color: #fff;
    outline: none;
    border: none;
    padding: 5px 15px;
    margin-right: 5px;
}
.btn-hidecls {
    border: none;
    background-color: #fff;
}
</style>
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
            <h3 class="title1">Operations [Dispatch Sheet]</h3>
            <div class="table-responsive bs-example widget-shadow"> 
                <label for="inputEmail3" class="control-label" style="margin-bottom:10px;margin-top: 15px;display: inline-block;">Filter : </label>
                <?php
                date_default_timezone_set('Canada/Mountain');
                //echo date('Y-m-d H:i:s');
                if($this->input->get('date'))
                {
                    $date = $this->input->get('date');
                }
                else
                {
                    $date = date('Y-m-d');
                     //$date = date('Y-m-d H:i:s');
                    //$datetime = new DateTime('tomorrow');
                    //$date = $datetime->format('Y-m-d');
                }

                ?>
                <input type="text" style="border-radius:0;width: 20%;display: inline-block;" class="dp form-control" name="op_delivery_date" value="<?php echo $date; ?>" id="op_delivery_date"> 
                <button class="prev-day btn btn-primary" title="Previous Date"><i class="fa fa-angle-left" aria-hidden='true'></i></button>
                <button class='next-day btn btn-primary' title='Next Date' ><i class='fa fa-angle-right' aria-hidden='true'></i></button> 
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr>
                            <th>Unit </th>
                            <th>Arrival Time</th>
                            <th>Pickup Date</th>
                            <th>Pickup Time</th>
                            <th>Delivery Date</th>
                            <th>Delivery Time</th>
                            <th>Customer</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <!--<th> Pickup Number </th>-->
                            <th> Order Tracking Number </th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        <?php
                        foreach($datas['driver'] as $key){
                        ?>
                        
                            
                            <?php
                            if(!empty($key['op']))
                            {
                            $i =0;
                            foreach ($key['op'] as $op) {
                                if($i== '0')
                                {
                            ?>
                            <tr>
                            <td><?php echo $key['unit_no']; ?></td>
                            <td></td>
                            <td><?php echo $op['op_loading_date']; ?></td>
                            <td><?php echo $op['op_loading_s_time']; ?></td>
                            <td><?php echo $op['op_delivery_date']; ?></td>
                            <td><?php echo $op['op_delivery_s_time']; ?></td>
                            <td><?php echo $op['cust_name']; ?></td>
                            <td><?php echo $op['op_loading_city']; ?></td>
                            <td><?php echo $op['op_delivery_city']; ?></td>
                            <!--<td><?php //echo $op['op_ref_id']; ?> </td> -->
                            <td>
                                <form action="<?php echo base_url(); ?>index.php/operations/track" method="post" target="_blank">
                                <input type="hidden" name="op_id" value="<?php echo $op['op_id'];?>" />
                                <input type="hidden" name="orderno_id" value="<?php echo $op['op_c_load_reference'];?>" />
                                <button class="btn-hidecls"> <?=$op['op_c_load_reference'];?> </button>
                                </form>
                            </td>
                            <td></td>
                            </tr>  
                            <?php }else{
                                ?>
                            <tr>
                            <td><?php echo $key['unit_no']; ?></td>
                            <td></td>
                            <td><?php echo $op['op_loading_date']; ?></td>
                            <td><?php echo $op['op_loading_s_time']; ?></td>
                            <td><?php echo $op['op_delivery_date']; ?></td>
                            <td><?php echo $op['op_delivery_s_time']; ?></td>
                            <td><?php echo $op['cust_name']; ?></td>
                            <td><?php echo $op['op_loading_city']; ?></td>
                            <td><?php echo $op['op_delivery_city']; ?></td>
                            <!--<td><?php echo $op['op_ref_id']; ?></td>-->
                            <td>
                                <form action="<?php echo base_url(); ?>index.php/operations/track" method="post" target="_blank">
                                <input type="hidden" name="op_id" value="<?php echo $op['op_id'];?>" />
                                <input type="hidden" name="orderno_id" value="<?php echo $op['op_c_load_reference'];?>" />
                                <button class="btn-hidecls"> <?=$op['op_c_load_reference'];?> </button>
                                </form>
                            </td>
                            <td></td>
                            </tr>  
                                <?php
                            } $i++; }    
                             }else{ ?>
                            <tr>
                            <td><?php echo $key['unit_no']; ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>  
                            </tr>
                             <?php } ?>         
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

<!-- Assign driver modal -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal1" style="display:none;"
    id="assign_driver">Open Modal</button>

<script>
$(function() {
    $(".dp").datepicker({ dateFormat: 'yy-mm-dd' });
});
$( "#op_delivery_date" ).change(function() 
{
    var da = $('#op_delivery_date').val();
    /*alert(da);*/
    window.location = "<?php echo base_url(); ?>index.php/operations/dispatch_sheet?date="+da;
});
 $('#myTable').DataTable( {
    "order": [[ 2, "desc" ]],
    bPaginate: false,
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
} );
</script>

<script type="text/javascript">
    $('.next-day').on('click', function () {
        var date = $('#op_delivery_date').datepicker('getDate');
        date.setDate(date.getDate() +1)
        $('#op_delivery_date').datepicker('setDate', date);
        var da = $('#op_delivery_date').val();    
        window.location = "<?php echo base_url(); ?>index.php/operations/dispatch_sheet?date="+da;
    });

    $('.prev-day').on('click', function () {
        var date = $('#op_delivery_date').datepicker('getDate');
        date.setDate(date.getDate() -1)
        $('#op_delivery_date').datepicker('setDate', date);
        var da = $('#op_delivery_date').val();    
        window.location = "<?php echo base_url(); ?>index.php/operations/dispatch_sheet?date="+da;
    });
</script>