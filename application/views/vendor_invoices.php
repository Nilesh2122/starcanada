<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Invoices [Payable - Vendors]</h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">                    
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Search" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createInvoiceModal" style="border-radius:0; margin-left: 10px;"
                        id="add_user">Create Record</button>                        

                        <div style="display: inline-block; float: right;">
                            <input type="text" placeholder="Start date" class="form-control dp" id="sdate" name="sdate"
                                value="<?php if(!empty($sdate)){ echo $sdate; } ?>">
                            <input type="text" placeholder="End date" class="form-control dp" id="edate" name="edate"
                                value="<?php if(!empty($edate)){ echo $edate; } ?>">
                            <input type="submit" class="btn btn-primary" style="border-radius:0" value="Go">
                        </div>
                    </form>                    
                </div>
                <table class="table table-bordered sortable" id="myTable">

                    <thead>
                        <tr>
                            <th>Sr.</th>                                                        
                            <th>Vendor Number</th>
                            <th>Vendor Name</th>                            
                            <th>Statement Date</th>                            
                            <th style="text-align: center;">Paid</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>                                                        
                            <td><?php echo $key['v_number']; ?></td>
                            <td><?php echo $key['v_name']; ?></td>
                            <td><?php echo date('M d Y', strtotime($key['created_at'])); ?></td>                            
                            <td align="center"><?php echo $key['vs_paid'] == '1' ? 'YES' : 'NO'; ?></td>

                            <!-- <td align="center"> -->
                            <td class="table-actions">                            

                                <?php if($key['vs_invoice']){ ?>
                                <a target="_blank" href="<?php echo base_url().'user_data/statements_v_data/'.$key['vs_invoice']; ?>">
                                <button><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button>
                                </a>
                                <?php } ?>                                
                                
                                <a href="<?php echo base_url().'index.php/invoice/generate_vendor_invoice/'.$key['vs_id']; ?>">
                                <button><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>
                                </a>    
                                
                                <?php //if($key['vs_invoice']){ ?>
                                <!-- <a href="<?php //echo base_url().'index.php/invoice/send_invoice/'.$key['vs_id']; ?>">
                                <button><i class="fa fa-mail"></i></button>
                                </a> -->
                                <?php //} ?>

                                <?php if($key['vs_invoice'] != '' && $key['vs_paid'] == '0'){ ?>
                                <a href="<?php echo base_url().'index.php/invoice/vendor_invoice_mark_paid?vs_id='.$key['vs_id']; ?>">
                                <button><i class="fa fa-dollar" data-toggle="tooltip" title="Mark as Paid"></i></button>
                                </a>
                                <?php } ?>

                                <?php if($key['vs_paid'] == '0'){ ?>
                                <button type="button" class="btn-danger" onclick="deleteRecord('<?php echo base_url(); ?>index.php/invoice/vendor_invoice_delete?id=<?php echo base64_encode($key['vs_id']); ?>')"><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button>
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


<!-- Create invoice Modal -->
<div id="createInvoiceModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 70%">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Statement</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/invoice/vendor_invoice_create" method="post"
                                    enctype="multipart/form-data">   
                                                                        
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Select Vendor</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="v_id">
                                                <?php foreach($vendors as $v){ ?>
                                                    <option value="<?php echo $v['v_id']; ?>"><?php echo $v['v_name'].' - '.$v['v_number']; ?></option>
                                                <?php } ?>
                                            </select>
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
                    onclick="document.getElementById('add_user_submit').click();">Create</button>
            </div>
        </div>

    </div>
</div>

<script>
$(function() {
    $(".dp").datepicker({dateFormat: 'yy-mm-dd'});
});


var last_op = '<?php if($datas){ echo $key['vs_id']; } ?>';
var row = '<?php if($datas){ echo $i; } ?>';
<?php if($datas){ ?>
$(window).scroll(function() {    
    if($(window).scrollTop() + $(window).height() == $(document).height()) {        
        if(last_op){
        //ajax to call other data        
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/invoice/vendor_invoice_more',
            type: 'POST',
            data: {
                'vs_id': last_op,
                'title': '<?php echo $search_title; ?>',
                'sdate': '<?php echo $sdate; ?>',
                'edate': '<?php echo $edate; ?>'
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
                    data['datas'][j]['vs_paid'] = data['datas'][j]['vs_paid'] == '1' ? 'YES' : 'NO';
                    row++;
                    append += '<tr><td>'+ row +'</td><td>'+ data['datas'][j]['v_number'] +'</td><td>'+ data['datas'][j]['v_name'] +'</td><td>'+ data['datas'][j]['created_at'].split(' ')[0] +'</td><td align="center">'+ data['datas'][j]['vs_paid'] +'</td>';

                    //append += '<td align="center">';
                    append += '<td class="table-actions">';                    

                    if(data['datas'][j]['vs_invoice']){
                    append += '<a target="_blank" href="<?php echo base_url() ; ?>user_data/statements_v_data/'+data['datas'][j]['vs_invoice']+'"><button><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button></a>';
                    }       
                    
                    append += '<a href="<?php echo base_url(); ?>index.php/invoice/generate_vendor_invoice/'+data['datas'][j]['vs_id']+'"><button><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button></a>';                    
                    
                    // if(data['datas'][j]['vs_invoice']){
                    // append += '<a href="<?php //echo base_url(); ?>index.php/invoice/send_invoice/'+data['datas'][j]['vs_id']+'"><button class="btn btn-primary" style="padding: 3px 12px;display: block;margin-bottom: 6px; width: 120px;">Send Invoice</button></a>';
                    // }

                    if(data['datas'][j]['vs_invoice'] != '' && data['datas'][j]['vs_paid'] == 'NO'){
                    append += '<a href="<?php echo base_url(); ?>index.php/invoice/vendor_invoice_mark_paid?vs_id='+data['datas'][j]['vs_id']+'"><button><i class="fa fa-dollar" data-toggle="tooltip" title="Mark as Paid"></i></button></a>';
                    }
                    
                    if(data['datas'][j]['vs_paid'] == 'NO'){
                    var deleteURL = "'<?php echo base_url(); ?>index.php/invoice/vendor_invoice_delete?id="+window.btoa(data['datas'][j]['vs_id'])+"'";
                    append += '<button type="button" class="btn-danger" onclick="deleteRecord('+deleteURL+')"><i class="fa fa-trash-o" data-toggle="tooltip" title="Delete"></i></button>'; 
                    }

                    append += '</td></tr>';              

                    last_op = data['datas'][j]['vs_id'];
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