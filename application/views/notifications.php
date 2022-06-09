<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Notifications</h3>
            <div class="bs-example widget-shadow">
                <?php if($this->Notifications_model->get_all()){ ?>
                <table class="table">                    
                    <tbody>
                        <?php foreach($this->Notifications_model->get_all() as $key){ 
                            $red = $key['clicked'] == '0' ? base_url().'index.php/operations/op_notification_open/'.$key['id'] : $key['redirect'];
                        ?>
                        <tr style="cursor: pointer;" onclick="window.location='<?php echo $red; ?>'" <?php echo $key['clicked'] == '0' ? 'class="active"' : '' ?>>                            
                            <td><?php echo $key['title']; ?><br><span style="font-size: 14px;color: #9c9c9c;"><?php echo $key['body']; ?></span></td>                            
                            <td style="text-align: right;color: #b1b1b1;font-size: 14px;"><?php echo date('Y-m-d', strtotime($key['created_at'])); ?></td>                                                                          
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>         
                <?php }else{ ?>
                <p style="text-align: center;font-size: 20px;color: #c7c7c7;margin-bottom: 20px;">No Notifications!</p>
                <?php } ?>       
            </div>
        </div>
    </div>
</div>