<script type="text/javascript"
    src='https://maps.google.com/maps/api/js?key=AIzaSyAvgN7X2UmjAXNC5GaVBBgLhaJKZd4SiFE&libraries=places'></script>
<script src="<?php echo base_url(); ?>assets/js/locationpicker.jquery.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>


<div id="page-wrapper">
    <div class="main-page">
    <div class="forms">
    
    	<div class=" form-grids row form-grids-right" style="margin-top:0">
            <div class="widget-shadow " data-example-id="basic-forms"> 
                <div class="form-title">
                    <h4>Edit Operation</h4>
                </div>
                <div class="form-body">
                <form action="<?php echo base_url(); ?>index.php/operations/edit_operation" method="post" class="form-horizontal" onsubmit="return check_form();">  
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Select customer</label> 
                        <div class="col-sm-9"> 
                        <select class="form-control" name="cust_id" required="required">
                        	<option selected="selected" value="0" disabled="disabled">Select customer</option>
                            <?php foreach($customers as $c){ ?>
                            	<option value="<?php echo $c['cust_id']; ?>" <?php echo $c['cust_id'] == $operation[0]['cust_id'] ? 'selected="selected"' : ''; ?>>
                                <?php echo $c['cust_name']; ?>
                                </option>
							<?php } ?>
                        </select> 
                        </div> 
                    </div>
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Product description</label> 
                        <div class="col-sm-9"> <textarea class="form-control" name="op_product_description" placeholder="Product description" required="required" rows="4"><?php echo $operation[0]['op_product_description']; ?></textarea></div> 
                    </div>
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Special notes</label> 
                        <div class="col-sm-9"> <textarea class="form-control" name="op_special_notes" placeholder="Special notes" required="required" rows="4"><?php echo $operation[0]['op_special_notes']; ?></textarea></div> 
                    </div>                                      
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Loading location</label> 
                        <div class="col-sm-9"> 
                        <input type="text" class="form-control" name="op_loading_location" id="op_loading_location" placeholder="Loading location" required="required" value="<?php echo $operation[0]['op_loading_location']; ?>">
                        
                        <div id="loading-location" style="width: 100%; height: 200px; margin-top: 15px;"></div>
                        <input type="hidden" name="op_loading_latlong" id="op_loading_latlong" value="<?php echo $operation[0]['op_loading_latlong']; ?>" />
                        </div> 
                    </div>                                                                                               
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Loading date</label> 
                        <div class="col-sm-9"> <input type="text" class="dp form-control" name="op_loading_date" required="required" value="<?php echo $operation[0]['op_loading_date']; ?>"> </div> 
                    </div>
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Loading time<br />(Scheduled)</label> 
                        <div class="col-sm-9"> <input type="text" class="tp form-control" name="op_loading_s_time" required="required" value="<?php echo $operation[0]['op_loading_s_time']; ?>"> </div> 
                    </div>                                                            
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Delivery location</label> 
                        <div class="col-sm-9"> 
                        <input type="text" class="form-control" name="op_delivery_location" id="op_delivery_location" placeholder="Delivery location" required="required" value="<?php echo $operation[0]['op_delivery_location']; ?>">
                        
                        <div id="delivery-location" style="width: 100%; height: 200px; margin-top: 15px;"></div>
                        <input type="hidden" name="op_delivery_latlong" id="op_delivery_latlong" value="<?php echo $operation[0]['op_delivery_latlong']; ?>" />
                        </div> 
                    </div>                                                                                               
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Delivery date</label> 
                        <div class="col-sm-9"> <input type="text" class="dp form-control" name="op_delivery_date" required="required" value="<?php echo $operation[0]['op_delivery_date']; ?>"> </div> 
                    </div>
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Delivery time<br />(Scheduled)</label> 
                        <div class="col-sm-9"> <input type="text" class="tp form-control" name="op_delivery_s_time" required="required" value="<?php echo $operation[0]['op_delivery_s_time']; ?>"> </div> 
                    </div>
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Operation cost</label> 
                        <div class="col-sm-9"> <input type="text" class="form-control" name="op_total_cost" id="op_total_cost" required="required" value="<?php echo $operation[0]['op_total_cost']; ?>"> 
                        <div style="margin:10px 0;min-height:30px;">
                        <span id="error" style="display:none; color:#F00; font-size:15px;"></span>
                        </div>
                        </div> 
                    </div>
                    
                    <div class="col-sm-offset-2"> 
                    <button type="submit" class="btn btn-default" style="margin-left:5px;">Save</button> 
                    </div> 
                    
                    <input type="hidden" name="op_id" value="<?php echo $operation[0]['op_id']; ?>"/>
                    
                    <input type="hidden" name="op_loading_city" id="op_loading_city" value="<?php echo $operation[0]['op_loading_city']; ?>"/>
                    
                    <input type="hidden" name="op_delivery_city" id="op_delivery_city" value="<?php echo $operation[0]['op_delivery_city']; ?>"/>
                </form>
                <input type="hidden" name="op_loading_latlong_lat" id="op_loading_latlong_lat" />
				<input type="hidden" name="op_loading_latlong_long" id="op_loading_latlong_long" />
                
                <input type="hidden" name="op_delivery_latlong_lat" id="op_delivery_latlong_lat" />
				<input type="hidden" name="op_delivery_latlong_long" id="op_delivery_latlong_long" />
                </div>
            </div>
        </div>
    
    </div>
    </div>
</div>

<script>
function check_form()
{			
	if(!$('select').val())
	{
		alert('Select customer!');
		$('select').focus();
		return false;
	}
	else if($('#op_loading_latlong_lat').val() == '')
	{
		alert('Please select proper Loading location on MAP!');		
		return false;
	}
	else if($('#op_delivery_latlong_lat').val() == '')
	{
		alert('Please select proper Delivery location on MAP!');
		return false;
	}
	else
	{
		$('#op_loading_latlong').val($('#op_loading_latlong_lat').val()+','+$('#op_loading_latlong_long').val());
		$('#op_delivery_latlong').val($('#op_delivery_latlong_lat').val()+','+$('#op_delivery_latlong_long').val());
		
		return true;
	}
}

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
        latitude: <?php echo explode(',', $operation[0]['op_loading_latlong'])[0]; ?>,
        longitude: <?php echo explode(',', $operation[0]['op_loading_latlong'])[1]; ?>
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
        updateControls(addressComponents, 'loading'); //Data
    }
});

$('#delivery-location').locationpicker({
    location: {
        latitude: <?php echo explode(',', $operation[0]['op_delivery_latlong'])[0]; ?>,
        longitude: <?php echo explode(',', $operation[0]['op_delivery_latlong'])[1]; ?>
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
        updateControls(addressComponents, 'delivery'); //Data
    }
});

//add cities to form for fares
function updateControls(addressComponents, which)
{
	if(which == 'loading')
	{
		$('#op_loading_city').val(addressComponents['city']);
	}
	else
	{
		$('#op_delivery_city').val(addressComponents['city']);
	}
	
	//get cost based on city
	if($('#op_loading_city').val() && $('#op_delivery_city').val())
	{
		$.ajax({
			url: '<?php echo base_url(); ?>index.php/operations/get_cost_by_city',
			type: 'POST',
			data: {
				'from': $('#op_loading_city').val(),
				'to': $('#op_delivery_city').val()
			},
			dataType: 'json',
			beforeSend: function() {
				 /*$(".popup-loader p").html('Please do not press <br>back button or refresh button.');
				 $(".popup-loader").fadeIn();*/
			 }, 
			success: function(data) {            	
				$('#error').fadeOut();
				$('#op_total_cost').val('');
				
				if(data == '0')
				{					
					$('#error').html('Rates not defined for these cities, Please add custom rates!').fadeIn();
				}
				else
				{
					$('#op_total_cost').val(data['base_fare']);
				}
			},
			error: function(request, error) {
				alert("Something went wrong! Try Again"); location.reload();
			},
			complete: function() {
				/*$(".popup-loader").fadeOut();*/			
			}
		});
	}
}

$(function() {
    $(".dp").datepicker({minDate: -0, dateFormat: 'yy-mm-dd'});
});

$(document).ready(function(){
    $(".tp").timepicker({});
});
</script>