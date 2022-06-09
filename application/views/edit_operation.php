<script type="text/javascript"
    src='https://maps.google.com/maps/api/js?key=AIzaSyAvgN7X2UmjAXNC5GaVBBgLhaJKZd4SiFE&libraries=places'></script>
<script src="<?php echo base_url(); ?>assets/js/locationpicker.jquery.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>


<div id="page-wrapper">

    <a class="SITE-BACK" href="javascript:history.back();"><i class="fa fa-angle-double-left"></i> Back</a>

    <div class="main-page">
    <div class="forms">
    
    	<div class=" form-grids row form-grids-right" style="margin-top:0">
            <div class="widget-shadow " data-example-id="basic-forms"> 
                <div class="form-title">
                    <h4>Edit Operation</h4>
                </div>
                <div class="form-body">
                <form autocomplete="off" action="<?php echo base_url(); ?>index.php/operations/edit_operation" method="post" class="form-horizontal" onsubmit="return check_form();">  
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Select customer</label> 
                        <div class="col-sm-10"> 
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
                        <div class="col-sm-10"> <textarea class="form-control" name="op_product_description" placeholder="Product description" rows="4"><?php echo $operation[0]['op_product_description']; ?></textarea></div> 
                    </div>
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Special notes</label> 
                        <div class="col-sm-10"> <textarea class="form-control" name="op_special_notes" placeholder="Special notes" rows="4"><?php echo $operation[0]['op_special_notes']; ?></textarea></div> 
                    </div>        
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Order No.</label> 
                        <div class="col-sm-10"> <input type="text" class="form-control" name="op_c_load_reference" value="<?php echo $operation[0]['op_c_load_reference']; ?>" placeholder="Order No" required="required"></div> 
                    </div>

                    <!-- <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Quantity</label> 
                        <div class="col-sm-10"> <input type="number" class="form-control" name="op_quantity" value="<?php echo $operation[0]['op_quantity']; ?>" placeholder="Quantity" required="required"></div>
                    </div> -->

                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">From/To(City)</label> 
                        <div class="col-sm-10">
                            <select class="form-control" id="fromTo" onchange="update_rates(this.value);" required="required">
                                <option disabled selected>Select From/To(City)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Loading/Delivery</label> 
                        <div class="col-sm-5"> 
                        <label for="inputEmail3" class="control-label" style="margin-bottom:10px;">Loading location</label>
                        
                        <input type="text" class="form-control" name="op_loading_location" id="op_loading_location" placeholder="Loading location" required="required" value="<?php echo $operation[0]['op_loading_location']; ?>">
                        
                        <div id="loading-location" style="width: 100%; height: 200px; margin-top: 15px;"></div>
                        <input type="hidden" name="op_loading_latlong" id="op_loading_latlong" value="<?php echo $operation[0]['op_loading_latlong']; ?>" />
                        
                        <label for="inputEmail3" class="control-label" style="margin-bottom:10px;margin-top: 15px;">Loading date</label> 
                        <input type="text" class="dp form-control" name="op_loading_date" required="required" value="<?php echo $operation[0]['op_loading_date']; ?>">
                        
                        <label for="inputEmail3" class="control-label" style="margin-bottom:10px;margin-top: 15px;">Loading time(Scheduled)</label> 
                        <input type="text" class="tp form-control" name="op_loading_s_time" required="required" value="<?php echo $operation[0]['op_loading_s_time']; ?>">

                        <label for="inputEmail3" class="control-label" style="margin-bottom:10px;margin-top: 15px;">Pickup Charge</label> 
                        <input type="number" step="any" class="form-control" name="op_loading_charge" required="required" value="<?php echo $operation[0]['op_loading_charge']; ?>" onchange="count_total_cost();" onkeyup="count_total_cost();" />
                        </div> 
                        
                        <div class="col-sm-5">
                        <label for="inputEmail3" class="control-label" style="margin-bottom:10px;">Delivery location</label> 
                        <input type="text" class="form-control" name="op_delivery_location" id="op_delivery_location" placeholder="Delivery location" required="required" value="<?php echo $operation[0]['op_delivery_location']; ?>">
                        
                        <div id="delivery-location" style="width: 100%; height: 200px; margin-top: 15px;"></div>
                        <input type="hidden" name="op_delivery_latlong" id="op_delivery_latlong" value="<?php echo $operation[0]['op_delivery_latlong']; ?>" />
                        
                        <label for="inputEmail3" class="control-label" style="margin-bottom:10px;margin-top: 15px;">Delivery date</label> 
                        <input type="text" class="dp form-control" name="op_delivery_date" required="required" value="<?php echo $operation[0]['op_delivery_date']; ?>">
                        
                        <label for="inputEmail3" class="control-label" style="margin-bottom:10px;margin-top: 15px;">Delivery time(Scheduled)</label> 
                        <input type="text" class="tp form-control" name="op_delivery_s_time" required="required" value="<?php echo $operation[0]['op_delivery_s_time']; ?>">

                        <label for="inputEmail3" class="control-label" style="margin-bottom:10px;margin-top: 15px;">Drop Charge</label> 
                        <input type="number" step="any" class="form-control" name="op_delivery_charge" required="required" value="<?php echo $operation[0]['op_delivery_charge']; ?>" onchange="count_total_cost();" onkeyup="count_total_cost();" />
                        </div>
                    </div>

                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">GST/HST/PST</label> 
                        <div class="col-sm-10">
                        <select class="form-control" name="op_GST" onchange="count_total_cost();" required="required">                        	
                            <option value="5" <?php echo $operation[0]['op_GST'] === '5' ? 'selected="selected"' : ''; ?>>5 %</option>
                            <option value="6" <?php echo $operation[0]['op_GST'] === '6' ? 'selected="selected"' : ''; ?>>6 %</option>
                            <option value="7" <?php echo $operation[0]['op_GST'] === '7' ? 'selected="selected"' : ''; ?>>7 %</option>
                            <option value="8" <?php echo $operation[0]['op_GST'] === '8' ? 'selected="selected"' : ''; ?>>8 %</option>
                            <option value="9" <?php echo $operation[0]['op_GST'] === '9' ? 'selected="selected"' : ''; ?>>9 %</option>
                            <option value="10" <?php echo $operation[0]['op_GST'] === '10' ? 'selected="selected"' : ''; ?>>10 %</option>
                            <option value="11" <?php echo $operation[0]['op_GST'] === '11' ? 'selected="selected"' : ''; ?>>11 %</option>
                            <option value="12" <?php echo $operation[0]['op_GST'] === '12' ? 'selected="selected"' : ''; ?>>12 %</option>
                            <option value="13" <?php echo $operation[0]['op_GST'] === '13' ? 'selected="selected"' : ''; ?>>13 %</option>
                            <option value="14" <?php echo $operation[0]['op_GST'] === '14' ? 'selected="selected"' : ''; ?>>14 %</option>
                            <option value="15" <?php echo $operation[0]['op_GST'] === '15' ? 'selected="selected"' : ''; ?>>15 %</option>
                            <option value="16" <?php echo $operation[0]['op_GST'] === '16' ? 'selected="selected"' : ''; ?>>16 %</option>
                            <option value="17" <?php echo $operation[0]['op_GST'] === '17' ? 'selected="selected"' : ''; ?>>17 %</option>
                            <option value="18" <?php echo $operation[0]['op_GST'] === '18' ? 'selected="selected"' : ''; ?>>18 %</option>
                            <option value="19" <?php echo $operation[0]['op_GST'] === '19' ? 'selected="selected"' : ''; ?>>19 %</option>
                            <option value="20" <?php echo $operation[0]['op_GST'] === '20' ? 'selected="selected"' : ''; ?>>20 %</option>
                        </select> 
                        </div> 
                    </div>

                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Unit of Measure</label> 
                        <div class="col-sm-10" id="messures"> 
                        <select class="form-control" name="op_mesuare_unit" required="required">                        	
                            <option data-rate="0" value="Select Rate">Select Rate</option>
                            <option data-rate="0" value="Flat Rate" <?php echo $operation[0]['op_mesuare_unit'] == 'Flat Rate' ? 'selected="selected"' : ''; ?>>Flat Rate</option>
                            <option data-rate="0" value="Per Tonne" <?php echo $operation[0]['op_mesuare_unit'] == 'Per Tonne' ? 'selected="selected"' : ''; ?>>Per Tonne</option>
                            <option data-rate="0" value="Per CWT" <?php echo $operation[0]['op_mesuare_unit'] == 'Per CWT' ? 'selected="selected"' : ''; ?>>Per CWT</option>
                            <option data-rate="0" value="FBM" <?php echo $operation[0]['op_mesuare_unit'] == 'FBM' ? 'selected="selected"' : ''; ?>>FBM</option>
                            <option data-rate="0" value="Per Pallet" <?php echo $operation[0]['op_mesuare_unit'] == 'Per Pallet' ? 'selected="selected"' : ''; ?>>Per Pallet</option>
                        </select> 
                        </div> 
                    </div>

                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Unit Rate</label> 
                        <div class="col-sm-10"> <input type="number" step="any" class="form-control" name="op_mesuare_rate" value="<?php echo $operation[0]['op_mesuare_rate']; ?>" placeholder="Rate" required="required" onchange="count_total_cost();" onkeyup="count_total_cost();"></div> 
                    </div>

                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Units</label> 
                        <div class="col-sm-10"> <input type="number" step="any" class="form-control" name="op_sub_rate" value="<?php echo $operation[0]['op_sub_rate']; ?>" placeholder="Units" required="required" onchange="count_total_cost();" onkeyup="count_total_cost();"></div> 
                    </div>

                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Fuel surcharge(%)</label> 
                        <div class="col-sm-10"> <input type="number" step="any" class="form-control" name="op_fuel_surcharge" value="<?php echo $operation[0]['op_fuel_surcharge']; ?>" placeholder="Fuel surcharge(%)" required="required" onchange="count_total_cost();" onkeyup="count_total_cost();"></div> 
                    </div>

                    <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Tarp(Amount)</label> 
                        <div class="col-sm-10"> <input type="number" step="any" class="form-control" name="op_trap_amount" value="<?php echo $operation[0]['op_trap_amount']; ?>" placeholder="Trap(Amount)" id="op_total_cost" required="required" onchange="count_total_cost();" onkeyup="count_total_cost();">
                        
                        <div style="margin:10px 0;min-height:30px;">
                        <span id="error" style="display:none; color:#F00; font-size:15px;"></span>
                        </div>
                        </div> 
                    </div>
                    
                    <!-- <div class="form-group"> 
                        <label for="inputEmail3" class="col-sm-2 control-label">Operation cost</label> 
                        <div class="col-sm-10"> <input type="text" class="form-control" name="op_total_cost" id="op_total_cost" required="required" value="<?php echo $operation[0]['op_total_cost']; ?>"> 
                        <div style="margin:10px 0;min-height:30px;">
                        <span id="error" style="display:none; color:#F00; font-size:15px;"></span>
                        </div>
                        </div> 
                    </div> -->
                    
                    <div class="col-sm-offset-2"> 
                    <button type="submit" class="btn btn-default" style="margin-left:5px;">Save</button>
                    <label style="margin-left: 20px;">Total Cost : $<span id="ap-t-c">0</span></label> 
                    <label style="margin-left: 30px;">GST/HST/PST : $<span id="ap-gst">0</span></label> 
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
$(document).ready(function(){
    count_total_cost();
});

function count_total_cost()
{
    var Total = ((parseFloat($('input[name="op_mesuare_rate"]').val()) * parseFloat($('input[name="op_sub_rate"]').val()) * (parseFloat($('input[name="op_fuel_surcharge"]').val()) + 1)) + parseFloat($('input[name="op_trap_amount"]').val()) + parseFloat($('input[name="op_loading_charge"]').val()) + parseFloat($('input[name="op_delivery_charge"]').val())).toFixed(2);

    $('#ap-t-c').empty();
    $('#ap-t-c').html(Total);

    var GST = ((Total * (parseFloat($('select[name="op_GST"]').val())))/100);

    GST = GST ? GST : 0;

    $('#ap-gst').empty();
    $('#ap-gst').html(GST);
}

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

$(document).on('change', 'select[name="op_mesuare_unit"]', function(){
    $('input[name="op_mesuare_rate"]').val($(this).children("option:selected").data('rate'));
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
        // updateControls(addressComponents, 'loading', $('select[name="cust_id"]').val()); //Data
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
        // updateControls(addressComponents, 'delivery', $('select[name="cust_id"]').val()); //Data
    }
});

//add cities to form for fares
function updateControls(addressComponents, which, cust)
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
                'to': $('#op_delivery_city').val(),
                'cust': cust
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
                    var append = '<select class="form-control" name="op_mesuare_unit" required="required"><option data-rate="0" value="Select Rate">Select Rate</option><option data-rate="'+ data['flate_rate'] +'" value="Flat Rate">Flat Rate</option><option data-rate="'+ data['per_tonne'] +'" value="Per Tonne">Per Tonne</option><option data-rate="'+ data['per_cwt'] +'" value="Per CWT">Per CWT</option><option data-rate="'+ data['fbm'] +'" value="FBM">FBM</option><option data-rate="'+ data['per_pallet'] +'" value="Per Pallet">Per Pallet</option></select>';
                        
                    $('#messures').empty();
                    $('#messures').append(append);

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

function update_rates(fromTo) {
    updateControls_(fromTo.split('~')[0], fromTo.split('~')[1], $('select[name="cust_id"]').val());
}

function updateControls_(from, to, cust)
{
	$('#op_loading_city').val(from);
	$('#op_delivery_city').val(to);
	
	//get cost based on city
	if($('#op_loading_city').val() && $('#op_delivery_city').val())
	{
		$.ajax({
			url: '<?php echo base_url(); ?>index.php/operations/get_cost_by_city',
			type: 'POST',
			data: {
				'from': $('#op_loading_city').val(),
                'to': $('#op_delivery_city').val(),
                'cust': cust
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
                    var append = '<select class="form-control" name="op_mesuare_unit" required="required"><option data-rate="0" value="Select Rate">Select Rate</option><option data-rate="'+ data['flate_rate'] +'" value="Flat Rate">Flat Rate</option><option data-rate="'+ data['per_tonne'] +'" value="Per Tonne">Per Tonne</option><option data-rate="'+ data['per_cwt'] +'" value="Per CWT">Per CWT</option><option data-rate="'+ data['fbm'] +'" value="FBM">FBM</option><option data-rate="'+ data['per_pallet'] +'" value="Per Pallet">Per Pallet</option></select>';
                        
                    $('#messures').empty();
                    $('#messures').append(append);

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

$('select[name="cust_id"]').change(function(){
    let cust = $(this).val();
    fetch_cust_rates(cust);
});

$(document).ready(function(){
    fetch_cust_rates(<?php echo $operation[0]['cust_id']; ?>);
})

function fetch_cust_rates(cust) {
    $.ajax({
        url: '<?php echo base_url(); ?>index.php/operations/fetch_cust_rates',
        type: 'POST',
        data: {            
            'cust': cust
        },
        dataType: 'json',
        beforeSend: function() {
                /*$(".popup-loader p").html('Please do not press <br>back button or refresh button.');
                $(".popup-loader").fadeIn();*/
            }, 
        success: function(data) {            	
            var selected = $('#op_loading_city').val()+'~'+$('#op_delivery_city').val();
            
            var append = `<option disabled selected>Select From/To(City)</option>`;
            
            if(data)
            {	
                for(var i=0; i<data.length; i++){
                    var selectedThis = '';
                    if(selected == data[i]['from_city']+`~`+data[i]['to_city']) {
                        var selectedThis = 'selected';
                    }
                    append += `<option value="`+data[i]['from_city']+`~`+data[i]['to_city']+`" `+selectedThis+`>`+data[i]['from_city']+` - `+data[i]['to_city']+`</option>`;
                }
            }            
            
            $('#fromTo').empty();
            $('#fromTo').append(append);
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
            /*$(".popup-loader").fadeOut();*/			
        }
    });
}

$(function() {
    $(".dp").datepicker({minDate: -0, dateFormat: 'yy-mm-dd'});
});

$(document).ready(function(){
    $(".tp").timepicker({});
});
</script>