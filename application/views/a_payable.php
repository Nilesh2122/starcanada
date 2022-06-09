<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Statements [Payable]</h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Search" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">

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
                            <th>Contractor Name</th>
                            <th>Contractor Number</th>                            
                            <th>Statement Of</th>                            
                            <th style="text-align: center;">Paid</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>                                                        
                            <td><?php echo $key['con_name']; ?></td>
                            <td><?php echo $key['con_number']; ?></td>                            
                            <td><?php echo $key['st_month_year']; ?></td>                            
                            <td align="center"><?php echo $key['st_paid'] == '1' ? 'YES' : 'NO'; ?></td>

                            <!-- <td align="center"> -->
                            <td class="table-actions">

                                <?php if($key['st_statement']){ ?>
                                <a target="_blank" href="<?php echo base_url().'user_data/statements_data/'.$key['st_statement']; ?>">
                                <button><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button>
                                </a>
                                <?php } ?>                                
                                
                                <a href="<?php echo base_url().'index.php/invoice/generate_statement/'.$key['st_id']; ?>">
                                <button><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button>
                                </a>    
                                
                                <?php //if($key['st_statement']){ ?>
                                <!-- <a href="<?php //echo base_url().'index.php/invoice/send_statement/'.$key['st_id']; ?>">
                                <button><i class="fa fa-mail"></i></button>
                                </a> -->
                                <?php //} ?>

                                <?php if($key['st_statement'] != '' && $key['st_paid'] == '0'){ ?>
                                <a href="<?php echo base_url().'index.php/invoice/statement_paid?st_id='.$key['st_id']; ?>">
                                <button><i class="fa fa-dollar" data-toggle="tooltip" title="Mark as Paid"></i></button>
                                </a>
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
$(function() {
    $(".dp").datepicker({dateFormat: 'yy-mm-dd'});
});


var last_op = '<?php if($datas){ echo $key['st_id']; } ?>';
var row = '<?php if($datas){ echo $i; } ?>';
<?php if($datas){ ?>
$(window).scroll(function() {    
    if($(window).scrollTop() + $(window).height() == $(document).height()) {        
        if(last_op){
        //ajax to call other data        
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/invoice/payable_more',
            type: 'POST',
            data: {
                'st_id': last_op,
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
                    data['datas'][j]['st_paid'] = data['datas'][j]['st_paid'] == '1' ? 'YES' : 'NO';
                    row++;
                    append += '<tr><td>'+ row +'</td><td>'+ data['datas'][j]['con_name'] +'</td><td>'+ data['datas'][j]['con_number'] +'</td><td>'+ data['datas'][j]['st_month_year']+'</td><td align="center">'+ data['datas'][j]['st_paid'] +'</td>';

                    // append += '<td align="center">';
                    append += '<td class="table-actions">';

                    if(data['datas'][j]['st_statement']){
                    append += '<a target="_blank" href="<?php echo base_url(); ?>user_data/statements_data/'+data['datas'][j]['st_statement']+'"><button><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button></a>';                    
                    }       
                    
                    append += '<a href="<?php echo base_url(); ?>index.php/invoice/generate_statement/'+data['datas'][j]['st_id']+'"><button><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button></a>';
                    
                    // if(data['datas'][j]['st_statement']){                    
                    // append += '<a href="<?php //echo base_url(); ?>index.php/invoice/send_statement/'+data['datas'][j]['st_id']+'"><button class="btn btn-primary" style="padding: 3px 12px;display: block;margin-bottom: 6px; width: 120px;">Send Statement</button></a>';
                    // }

                    if(data['datas'][j]['st_statement'] != '' && data['datas'][j]['st_paid'] == '0'){
                    append += '<a href="<?php echo base_url(); ?>index.php/invoice/statement_paid?st_id='+data['datas'][j]['st_id']+'"><button><i class="fa fa-dollar" data-toggle="tooltip" title="Mark as Paid"></i></button></a>';
                    }

                    append += '</td></tr>';              

                    last_op = data['datas'][j]['st_id'];
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