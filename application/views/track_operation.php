<script type="text/javascript"
    src='https://maps.google.com/maps/api/js?key=AIzaSyAvgN7X2UmjAXNC5GaVBBgLhaJKZd4SiFE&libraries=places'></script>
<script src="<?php echo base_url(); ?>assets/js/locationpicker.jquery.js"></script>

<style>
.forms label {
    font-weight: 400;
    margin-bottom: 25px;
    border-bottom: 1px solid #c7c7c7;
    padding-bottom: 4px;
}

.forms p {
	margin-bottom:10px;
}

.form-group{
	border-bottom: 1px solid #c7c7c7;
	padding-bottom:20px;
}
</style>

<div id="page-wrapper">
    <div class="main-page">
    <div class="forms">
    
    	<div class=" form-grids row form-grids-right" style="margin-top:0">
            <div class="widget-shadow " data-example-id="basic-forms"> 
                <div class="form-title">
                   <!--  <h4>Operation Tracking ( <?php echo $operation['op_ref_id']; ?> )</h4> -->
                    <h4>Order No ( <?php echo $operation['op_c_load_reference']; ?> )</h4>
                </div>
                <div class="form-body">
                		
                      <div class="form-group"> 
                      <label for="exampleInputEmail1">Operation status</label> 
                          <ul id="progressbar">
                            <?php $class='active'; foreach($op_status as $sk => $sv){  ?>
                            <li class="<?php echo $class; ?>"><?php echo $sv; ?></li>                               
                            <?php $class = $sk < $operation['op_status'] ? 'active' : ''; 'active'; } ?>
                          </ul>  
                      </div> 
                      
                      <!--<div class="form-group"> 
                      <label for="exampleInputEmail1">Live tracking location</label> 
                      <?php //if($operation['op_tracking_latlong']){ ?>
                      	<p>Last location : <br /><input id="LTL" style="font-size: 15px; width:100%; background:none; border:none;"></p>
                        <div id="LTL-DIV" style="width:100%; height:160px;">
                        </div>
                      <?php //}else{ ?>
                      	<p>Available soon</p>
                      <?php //} ?>
                      </div>--> 
                      
                      <div class="row">                                     
                      <div class="col-md-6" style="padding-left:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Customer information</label>
                          	<p>Name : <?php echo $operation['cust_name']; ?></p>
                            <p>Phone : <?php echo $operation['cust_contact']; ?></p>
                          </div>
                      </div>
                      <div class="col-md-6" style="padding-right:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Driver information</label> 
                          	<p>Name : <?php echo $operation['driver_name']; ?></p>
                            <p>Phone : <?php echo $operation['driver_contact']; ?></p>
                          </div>
                      </div>
                      </div>
                      
                      <div class="form-group" style="padding:0"></div>
                      
                      <div class="row">
                      <div class="col-md-6" style="padding-left:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Costing</label> 
                            <p>Pickup Charge : $<?php echo $operation['op_loading_charge']; ?> &nbsp;&nbsp;&nbsp; Drop Charge : $<?php echo $operation['op_delivery_charge']; ?></p>
                            <p>Measure Unit : <?php echo $operation['op_mesuare_unit']; ?></p>   
                            <p>Unit Rate : $<?php echo $operation['op_mesuare_rate']; ?></p>   
                            <p>Units : <?php echo $operation['op_sub_rate']; ?></p>
                            <p>Fuel Surcharge : <?php echo $operation['op_fuel_surcharge']; ?>%</p>
                            <p>Trap Amount : $<?php echo $operation['op_trap_amount']; ?></p>
                            <p>Operation Total Cost : $<?php echo $operation['op_total_cost']; ?></p>   
                            <p>GST/HST/PST : $<?php echo round(($operation['op_total_cost'] * $operation['op_GST'])/100, 2); ?> (<?php echo $operation['op_GST']; ?>%)</p>
                            <!-- <p>Commission : <?php //if($operation['driver_id'] != 0){ ?> <?php //echo $operation['con_id']=='0' ? 'To Driver ':'To Contractor '; echo $operation['op_dc_commission']; ?>% ( $<?php //echo ($operation['op_total_cost']*$operation['op_dc_commission'])/100; ?> ) <?php //}else{ echo '-'; } ?></p> -->
                            <!-- <p>Company earned : <?php //if($operation['driver_id'] != 0){ ?> $<?php //echo $operation['op_total_cost'] - (($operation['op_total_cost']*$operation['op_dc_commission'])/100); ?> <?php //}else{ echo '-'; } ?></p> -->
                          </div>
                      </div>
                      
                      <div class="col-md-6" style="padding-right:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Operation Created/Assigned</label> 
                           	<p>Created by - <br />
								Name : <?php echo $operation['created_by'] == '0' ? 'Administrator' : $this->Users_model->get_user_by_id($operation['created_by'])[0]['acu_name']; ?><br />
                            	Email : <?php echo $operation['created_by'] == '0' ? 'Not Available' : $this->Users_model->get_user_by_id($operation['created_by'])[0]['acu_email']; ?>
                            </p>
                            <br />
                            <p>Driver assigned by - <br />
								Name : <?php echo $operation['assigned_by'] == '0' ? 'Administrator' : $this->Dispatchers_model->get_dispatcher_by_id($operation['assigned_by'])[0]['du_name']; ?><br />
                                Email : <?php echo $operation['assigned_by'] == '0' ? 'Not Available' : $this->Dispatchers_model->get_dispatcher_by_id($operation['assigned_by'])[0]['du_email']; ?>
                            </p>
                          </div>
                      </div>    				  
                      </div>
                      
                      <div class="form-group" style="padding:0"></div> 
                      
                      <div class="row">
                      <div class="col-md-6" style="padding-left:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Product Description</label> 
                            <p><?php echo $operation['op_product_description']; ?></p>   
                          </div>
                      </div>    
                                                                 
                      <div class="col-md-6" style="padding-right:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Special notes</label>
                            <p><?php echo $operation['op_special_notes']; ?></p>   
                          </div>
                      </div>
                      </div>
                      
                      <div class="form-group" style="padding:0"></div> 
                      
                      <div class="row">
                      <div class="col-md-6" style="padding-left:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Loading information</label> 
                            <p>Date : <?php echo $operation['op_loading_date']; ?></p>
                            <p>Time(Scheduled) : <?php echo $operation['op_loading_s_time']; ?></p>
                            <p>Time(Actual) : <?php echo $operation['op_loading_a_time'] != '00:00:00' ? $operation['op_loading_a_time'] : 'Available soon'; ?></p>
                            <p>Notes :<br /><?php echo $operation['op_loading_notes'] != '' ? $operation['op_loading_notes'] : 'Available soon'; ?></p>
                            <?php if($operation['op_loading_note_images']){ ?>
                            <p>Images :
                            	<?php 
									$imgs = explode(',', $operation['op_loading_note_images']); 
									foreach($imgs as $key=>$value){ 
								?>
                            	<div style="width: 70px;height: 70px;overflow: hidden;position: relative;border: 1px solid #999;cursor: pointer;display: inline-block;margin-right: 10px;">
                                	
                                	<img class="previewImage" src="<?php echo base_url().'user_data/operation_data/'.$value; ?>" style="position: absolute;margin: auto;max-height: 100%;max-width: 100%;left: -100%;right: -100%;top: -100%;bottom: -100%;" />
                                    
                                </div>
                                <?php } ?>
                            </p>
                            <?php } ?>
                          </div>
                      </div>    
                                                                 
                      <div class="col-md-6" style="padding-right:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Delivery information</label>
                            <p>Date : <?php echo $operation['op_delivery_date']; ?></p>
                            <p>Time(Scheduled) : <?php echo $operation['op_delivery_s_time']; ?></p>
                            <p>Time(Actual) : <?php echo $operation['op_delivery_a_time'] != '00:00:00' ? $operation['op_loading_a_time'] : 'Available soon'; ?></p>
                            <p>Notes :<br /><?php echo $operation['op_delivery_notes'] != '' ? $operation['op_delivery_notes'] : 'Available soon'; ?></p>
                            <?php if($operation['op_delivery_note_images']){ ?>
                            <p>Images :
                            	<?php 
									$imgs = explode(',', $operation['op_delivery_note_images']); 
									foreach($imgs as $key=>$value){ 
								?>
                            	<div style="width: 70px;height: 70px;overflow: hidden;position: relative;border: 1px solid #999;cursor: pointer;display: inline-block;margin-right: 10px;">
                                	
                                	<img class="previewImage" src="<?php echo base_url().'user_data/operation_data/'.$value; ?>" style="position: absolute;margin: auto;max-height: 100%;max-width: 100%;left: -100%;right: -100%;top: -100%;bottom: -100%;" />
                                    
                                </div>
                                <?php } ?>
                            </p>
                            <?php } ?>
                          </div>
                      </div>
                      </div>
                      
                      <div class="form-group" style="padding:0"></div>    
                      
                      <div class="row">
                      <div class="col-md-6" style="padding-left:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Loading location</label> 
                            <div id="loading-location" style="width:100%; height:160px;">
                            </div>
                          	<p style="margin-top:15px;"><?php echo $operation['op_loading_location']; ?></p>
                          </div>
                      </div>    
                                                                 
                      <div class="col-md-6" style="padding-right:0;">
                          <div class="form-group" style="border:none;margin:0"> 
                          <label for="exampleInputEmail1">Delivery location</label>
                          	<div id="delivery-location" style="width:100%; height:160px;">
                            </div>
                            <p style="margin-top:15px;"><?php echo $operation['op_delivery_location']; ?></p>
                          </div>
                      </div>
                      </div>
                      
                      <div class="form-group" style="padding:0"></div>                                                                  
                 </div>
            </div>
        </div>
    
    </div>
    </div>

    <!-- Modal -->
    <div id="imgModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 80%;">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Preview</h4>
        </div>
        <div class="modal-body" style="text-align: center;">
            <center>
                <img id="previewImage" class="img-responsive" style="max-width: 80%; max-height: 80vh;" >
            </center>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>

    </div>
    </div>
</div>

<script>
$('.previewImage').click(function(){
    $('#previewImage').attr('src', $(this).attr('src'));

    $('#imgModal').modal('toggle');
});

var customStyles = [{
    "elementType": "geometry",
    "stylers": [{
        "hue": "#ff4400"
    }, {
        "saturation": -68
    }, {
        "lightness": -4
    }, {
        "gamma": 0.72
    }]
}, {
    "featureType": "road",
    "elementType": "labels.icon"
}, {
    "featureType": "landscape.man_made",
    "elementType": "geometry",
    "stylers": [{
        "hue": "#0077ff"
    }, {
        "gamma": 3.1
    }]
}, {
    "featureType": "water",
    "stylers": [{
        "hue": "#00ccff"
    }, {
        "gamma": 0.44
    }, {
        "saturation": -33
    }]
}, {
    "featureType": "poi.park",
    "stylers": [{
        "hue": "#44ff00"
    }, {
        "saturation": -23
    }]
}, {
    "featureType": "water",
    "elementType": "labels.text.fill",
    "stylers": [{
        "hue": "#007fff"
    }, {
        "gamma": 0.77
    }, {
        "saturation": 65
    }, {
        "lightness": 99
    }]
}, {
    "featureType": "water",
    "elementType": "labels.text.stroke",
    "stylers": [{
        "gamma": 0.11
    }, {
        "weight": 5.6
    }, {
        "saturation": 99
    }, {
        "hue": "#0091ff"
    }, {
        "lightness": -86
    }]
}, {
    "featureType": "transit.line",
    "elementType": "geometry",
    "stylers": [{
        "lightness": -48
    }, {
        "hue": "#ff5e00"
    }, {
        "gamma": 1.2
    }, {
        "saturation": -23
    }]
}, {
    "featureType": "transit",
    "elementType": "labels.text.stroke",
    "stylers": [{
        "saturation": -64
    }, {
        "hue": "#ff9100"
    }, {
        "lightness": 16
    }, {
        "gamma": 0.47
    }, {
        "weight": 2.7
    }]
}];

$('#loading-location').locationpicker({	
    location: {
        latitude: <?php echo explode(',', $operation['op_loading_latlong'])[0]; ?>,
        longitude: <?php echo explode(',', $operation['op_loading_latlong'])[1]; ?>
    },
    //markerInCenter: true,  
    //markerIcon: '<?php echo base_url(); ?>assets/images/map-marker.png', 
    radius: 0,
    enableAutocomplete: true,
    enableReverseGeocode: true,
    styles: customStyles,
    addressFormat: 'addressFormat',
    inputBinding: {
        latitudeInput: $('#op_loading_latlong_lat'),
        longitudeInput: $('#op_loading_latlong_long'),        
        locationNameInput: $('#op_loading_location')
    },
    onchanged: function(currentLocation, radius, isMarkerDropped) {
        var addressComponents = $(this).locationpicker('map').location.addressComponents;
        //console.log(currentLocation);  //latlon  
        //updateControls(addressComponents); //Data
    }
});

$('#delivery-location').locationpicker({
    location: {
        latitude: <?php echo explode(',', $operation['op_delivery_latlong'])[0]; ?>,
        longitude: <?php echo explode(',', $operation['op_delivery_latlong'])[1]; ?>
    },
    //markerInCenter: true,  
    //markerIcon: '<?php echo base_url(); ?>assets/images/map-marker.png', 
    radius: 0,
    enableAutocomplete: true,
    enableReverseGeocode: true,
    styles: customStyles,
    addressFormat: 'addressFormat',
    inputBinding: {
        latitudeInput: $('#op_delivery_latlong_lat'),
        longitudeInput: $('#op_delivery_latlong_long'),        
        locationNameInput: $('#op_delivery_location')
    },
    onchanged: function(currentLocation, radius, isMarkerDropped) {
        var addressComponents = $(this).locationpicker('map').location.addressComponents;
        //console.log(currentLocation);  //latlon  
        //updateControls(addressComponents); //Data
    }
});

<?php if($operation['op_tracking_latlong']){ ?>
$('#LTL-DIV').locationpicker({
    location: {
        latitude: <?php echo explode(',', $operation['op_tracking_latlong'])[0]; ?>,
        longitude: <?php echo explode(',', $operation['op_tracking_latlong'])[1]; ?>
    },
    //markerInCenter: true,  
    //markerIcon: '<?php echo base_url(); ?>assets/images/map-marker.png', 
    radius: 0,
    enableAutocomplete: true,
    enableReverseGeocode: true,
    styles: customStyles,
    addressFormat: 'addressFormat',
    inputBinding: {
        latitudeInput: $('#op_delivery_latlong_lat'),
        longitudeInput: $('#op_delivery_latlong_long'),        
        locationNameInput: $('#LTL')
    },
    onchanged: function(currentLocation, radius, isMarkerDropped) {
        var addressComponents = $(this).locationpicker('map').location.addressComponents;
        //console.log(currentLocation);  //latlon  
        //updateControls(addressComponents); //Data
    }
});
<?php } ?>
</script>