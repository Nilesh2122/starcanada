<script type="text/javascript"
    src='https://maps.google.com/maps/api/js?key=AIzaSyAvgN7X2UmjAXNC5GaVBBgLhaJKZd4SiFE&libraries=places'></script>
<script src="<?php echo base_url(); ?>assets/js/locationpicker.jquery.js"></script>

<style>
/*puts the google places autocomplete dropdown results above the bootstrap modal 1050 zindex.*/
.pac-container {
    z-index: 1051 !important;
}

.form-horizontal .form-group{
	margin:0;
	margin-bottom:20px;
}

.form-group label{
	margin-bottom:15px!important;
}

.form-body{
	padding:1px 10px 10px 10px;
}
</style>

<div id="page-wrapper">
    <div class="main-page">
        <div class="tables">
            <h3 class="title1">Operation's Rates</h3>
            <div class="table-responsive bs-example widget-shadow">
                <div class="form-group">
                    <form method="post" class="form-inline">
                        <input type="text" placeholder="Customer" class="form-control" id="title" name="title"
                            value="<?php if(!empty($search_title)){ echo $search_title; } ?>">
                        <input type="submit" class="btn btn-primary" style="border-radius:0" value="Search">
                        <a onclick="document.getElementById('add_user').click();" class="btn btn-primary"
                            style="float:right;border-radius:0"><i class="fa fa-plus"></i> &nbsp;Add Rate</a>
                    </form>
                </div>
                <table class="table table-bordered sortable">

                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Customer</th>
                            <!-- <th>From city</th>
                            <th>To city</th>
                            <th>Base fare</th>
                            <th>Created at</th>
                            <th style="text-align:center;">Active</th> -->
                            <th>Rates added</th>
                            <th width="200px" style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i= $this->uri->segment(3)?$this->uri->segment(3):0; 
							  foreach($datas as $key){ $i++; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $key['cust_name']; ?></td>
                            <!-- <td><?php //echo $key['from_city']; ?></td>
                            <td><?php //echo $key['to_city']; ?></td>
                            <td><?php //echo $key['base_fare']; ?></td>
	                        <td><?php //echo date('Y-m-d', strtotime($key['created_at'])); ?></td>
                            <td align="center">
                                <label class="switch">
                                    <input type="checkbox" <?php //echo $key['is_active'] == '1'?'checked':'' ?>
                                        class="chk-active" data-rate-id="<?php //echo $key['rate_id']; ?>">
                                    <span class="slider round"></span>
                                </label>
                            </td> -->
                            <td><?php echo $key['rates_total']; ?></td>
                            <!-- <td align="center"> -->
                            <td class="table-actions">
                                <button type="button" class="view-rate" data-cust-id="<?php echo $key['cust_id']; ?>" data-cust-name="<?php echo $key['cust_name']; ?>"><i class="fa fa-eye" data-toggle="tooltip" title="View"></i></button>
                                <!-- <button type="button" class="edit-rate" data-rate-id="<?php //echo $key['rate_id']; ?>"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button> -->
                            </td>                            
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <?php echo $nav; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Rates -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#ViewRates" style="display:none;"
    id="view-rates">Open Modal</button>

<!-- Modal -->
<div id="ViewRates" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width:90%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rates for <span id="rates-for"></span></h4>
            </div>
            <div class="modal-body" id="rates-div">
                
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
        </div>

    </div>
</div>

<!-- Add accountant modal -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;"
    id="add_user">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Rate</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/rates/add_rate" method="post" enctype="multipart/form-data">               
                                         
                                    <div class="row">

                                    <div class="com-md-12">
                                    <div class="form-group"> 
                                        <label for="inputEmail3" class="col-sm-12 control-label" style="text-align: left;">Select customer</label> 
                                        <div class="col-sm-12"> 
                                        <select class="form-control" name="cust_id" required="required">
                                            <option selected="selected" value="0" disabled="disabled">Select customer</option>
                                            <?php foreach($customers as $c){ ?>
                                                <option value="<?php echo $c['cust_id']; ?>">
                                                <?php echo $c['cust_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select> 
                                        </div> 
                                    </div>
                                    </div>
                                                
                                    <div class="col-md-6">                             
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">From city</label>
                                        
                                            <input type="text" class="form-control" id="from_city_in"
                                                required="required">
                                            <div id="from_city_map" style="width: 100%; height: 200px; margin-top: 15px;"></div>                                        
                                    </div>                                     
                                    </div>
                                    
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">To city</label>
                                        <input type="text" class="form-control" id="to_city_in"
                                                required="required">
                                        <div id="to_city_map" style="width: 100%; height: 200px; margin-top: 15px;"></div>
                                    </div>
                                    </div>
                                    
                                    <!-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Base fare</label>
                                        
                                        <input type="number" step="any" class="form-control" name="base_fare"
                                                required="required">
                                    </div>
                                    </div> -->

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Flat Rate</label>
                                        
                                        <input type="number" step="any" class="form-control" name="flate_rate"
                                                required="required">
                                    </div>
                                    </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Per tonne</label>
                                        
                                        <input type="number" step="any" class="form-control" name="per_tonne"
                                                required="required">
                                    </div>
                                    </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Per CWT</label>
                                        
                                        <input type="number" step="any" class="form-control" name="per_cwt"
                                                required="required">
                                    </div>
                                    </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">FBM</label>
                                        
                                        <input type="number" step="any" class="form-control" name="fbm"
                                                required="required">
                                    </div>
                                    </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Per pallet</label>
                                        
                                        <input type="number" step="any" class="form-control" name="per_pallet"
                                                required="required">
                                    </div>
                                    </div>
                                    
                                    </div>
                                    
                                    <input type="hidden" name="from_city_latlong_lat" id="from_city_latlong_lat" />
                                    <input type="hidden" name="from_city_latlong_lon" id="from_city_latlong_lon" />
                                    
                                    <input type="hidden" name="to_city_latlong_lat" id="to_city_latlong_lat" />
                                    <input type="hidden" name="to_city_latlong_lon" id="to_city_latlong_lon" />
                                    
                                    <input type="hidden" name="from_city" id="from_city" />
                                    <input type="hidden" name="to_city" id="to_city" /> 
                                                                                 
                                    <input type="submit" style="display:none;" id="add_user_submit" />

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button style="min-width:100px;" type="button" class="btn btn-primary"
                    onclick="document.getElementById('add_user_submit').click();">Add</button>
            </div>
        </div>

    </div>
</div>


<!-- Edit Modal -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal1" style="display:none;"
    id="edit_user">Open Modal</button>

<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background:#F1F1F1;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Rate</h4>
            </div>
            <div class="modal-body">
                <div class="forms">
                    <div class=" form-grids row form-grids-right">
                        <div class="widget-shadow " data-example-id="basic-forms">
                            <div class="form-body">
                                <form onsubmit="return check_img_()" class="form-horizontal"
                                    action="<?php echo base_url(); ?>index.php/rates/edit_rate" method="post"
                                    enctype="multipart/form-data">
                                    
                                    <div class="row">

                                    <div class="com-md-12">
                                    <div class="form-group"> 
                                        <label for="inputEmail3" class="col-sm-12 control-label" style="text-align: left;">Select customer</label> 
                                        <div class="col-sm-12"> 
                                        <select class="form-control" name="cust_id" id="cust_id" required="required">
                                            <option selected="selected" value="0" disabled="disabled">Select customer</option>
                                            <?php foreach($customers as $c){ ?>
                                                <option value="<?php echo $c['cust_id']; ?>">
                                                <?php echo $c['cust_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select> 
                                        </div> 
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">From city</label>
                                        <input type="text" class="form-control" id="from_city_in_e"
                                                required="required">
                                        <div id="from_city_map_e" style="width: 100%; height: 200px; margin-top: 15px;"></div>
                                    </div>
                                    </div>                                     
                                    
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">To city</label>                                        
                                        <input type="text" class="form-control" id="to_city_in_e"
                                                required="required">
                                        <div id="to_city_map_e" style="width: 100%; height: 200px; margin-top: 15px;"></div>
                                    </div>
                                    </div>              
                                                               
                                    <!-- <div class="col-md-12">                                 
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Base fare</label>
                                        <input type="number" step="any" class="form-control" name="base_fare" id="base_fare"
                                                required="required">
                                    </div>
                                    </div> -->

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Flat Rate</label>
                                        
                                        <input type="number" step="any" class="form-control" name="flate_rate" id="flate_rate"
                                                required="required">
                                    </div>
                                    </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Per tonne</label>
                                        
                                        <input type="number" step="any" class="form-control" name="per_tonne" id="per_tonne"
                                                required="required">
                                    </div>
                                    </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Per CWT</label>
                                        
                                        <input type="number" step="any" class="form-control" name="per_cwt" id="per_cwt"
                                                required="required">
                                    </div>
                                    </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">FBM</label>
                                        
                                        <input type="number" step="any" class="form-control" name="fbm" id="fbm"
                                                required="required">
                                    </div>
                                    </div>

                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">Per pallet</label>
                                        
                                        <input type="number" step="any" class="form-control" name="per_pallet" id="per_pallet"
                                                required="required">
                                    </div>
                                    </div>
                                    
                                    </div>
                                    
                                    <input type="hidden" name="from_city_latlong_lat" id="from_city_latlong_lat_e" />
                                    <input type="hidden" name="from_city_latlong_lon" id="from_city_latlong_lon_e" />
                                    
                                    <input type="hidden" name="to_city_latlong_lat" id="to_city_latlong_lat_e" />
                                    <input type="hidden" name="to_city_latlong_lon" id="to_city_latlong_lon_e" />
                                    
                                    <input type="hidden" name="from_city" id="from_city_e" />
                                    <input type="hidden" name="to_city" id="to_city_e" />
                                    
                                    <input type="hidden" name="rate_id" id="rate_id" />
                                    
                                    <input type="submit" style="display:none;" id="add_user_submit_" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background:#F1F1F1">
                <button style="min-width:100px;" type="button" class="btn btn-primary"
                    onclick="document.getElementById('add_user_submit_').click();">Save</button>
            </div>
        </div>

    </div>
</div>

<script>
$('.view-rate').click(function(){
	var cust_id = $(this).data('cust-id');
    var cust_name = $(this).data('cust-name');

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/rates/get_rate_by_cust',
        type: 'POST',
        data: {
            'cust_id': cust_id
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {                			
			var apend = '';
			
			if(data.length > 0){
				apend = '<div style="margin:0;" class="table-responsive bs-example widget-shadow"><table class="table table-bordered"><thead><tr><th>Sr.</th><th>From City</th><th>To City</th><th>Flat Rate</th><th>Per Tonne</th><th>Per CWT</th><th>FBM</th><th>Per Pallet</th><th>Active</th><th style="text-align:center;">Action</th></tr></thead><tbody>';
				
				for(var i=0; i<data.length; i++)
				{
                    var checked = data[i]['is_active'] == '1' ? 'checked' : '';
					apend += '<tr><td>'+ (i+1) +'</td><td>'+ data[i]['from_city'] +'</td><td>'+ data[i]['to_city'] +'</td><td>'+ data[i]['flate_rate'] +'</td><td>'+ data[i]['per_tonne'] +'</td><td>'+ data[i]['per_cwt'] +'</td><td>'+ data[i]['fbm'] +'</td><td>'+ data[i]['per_pallet'] +'</td><td><label class="switch"><input type="checkbox" '+ checked +' class="chk-active" data-rate-id="'+ data[i]['rate_id'] +'"><span class="slider round"></span></label></td><td class="table-actions"><button type="button" class="edit-rate" data-rate-id="'+ data[i]['rate_id'] +'"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></button></td></tr>';
				}
				
				apend += '</tbody></table></div>';
			}
			else{
				apend = '<p style="margin:50px 0; font-size:18px; color:#c7c7c7; text-align:center;">No Rates found!</p>';
			}   

            $('#rates-for').empty();
            $('#rates-for').html(cust_name);
			$('#rates-div').empty();
			$('#rates-div').append(apend);

            $('#view-rates').click();
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });
});

$(document).on('click', '.edit-rate', function() {
//$('.edit-rate').click(function() {

    $('#ViewRates').modal('toggle');

    setTimeout(() => {        
    
    var rate_id = $(this).data('rate-id');
	var i = 0;

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/rates/get_rate_by_id',
        type: 'POST',
        data: {
            'rate_id': rate_id
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {
            //console.log(data);
			i++;
			$('#rate_id').val(data['rate_id']);
            $('#from_city_e').val(data['from_city']);
			$('#to_city_e').val(data['to_city']);
			// $('#base_fare').val(data['base_fare']);
            $('#cust_id').val(data['cust_id']);
            $('#flate_rate').val(data['flate_rate']);
            $('#per_tonne').val(data['per_tonne']);
            $('#per_cwt').val(data['per_cwt']);
            $('#fbm').val(data['fbm']);
            $('#per_pallet').val(data['per_pallet']);
			
			$('#from_city_map_e').empty();
			$('#to_city_map_e').empty();
			
			$('#from_city_map_e').append('<div id="from_city_map_e_'+i+'" style="height:100%; width:100%;"></div>');
			$('#to_city_map_e').append('<div id="to_city_map_e_'+i+'" style="height:100%; width:100%;"></div>');
			
			$('#from_city_map_e_'+i).locationpicker({	
				location: {
					latitude: data['from_city_latlong'].split(',')[0],
					longitude: data['from_city_latlong'].split(',')[1]
				},
				//markerInCenter: true,  
				//markerIcon: '<?php echo base_url(); ?>assets/images/map-marker.png', 
				radius: 0,
				enableAutocomplete: true,
				enableReverseGeocode: true,
				styles: customStyles,
				addressFormat: 'addressFormat',
				inputBinding: {
					latitudeInput: $('#from_city_latlong_lat_e'),
					longitudeInput: $('#from_city_latlong_lon_e'),        
					locationNameInput: $('#from_city_in_e')
				},
				oninitialized: function(component) {		
					var addressComponents = $(component).locationpicker('map').location.addressComponents;
					updateControls_e(addressComponents, 'from'); //Data
				},
				onchanged: function(currentLocation, radius, isMarkerDropped) {
					var addressComponents = $(this).locationpicker('map').location.addressComponents;
					//console.log(currentLocation);  //latlon  
					updateControls_e(addressComponents, 'from'); //Data
				}
			});
			
			$('#to_city_map_e_'+i).locationpicker({
				location: {
					latitude: data['to_city_latlong'].split(',')[0],
					longitude: data['to_city_latlong'].split(',')[1]
				},
				//markerInCenter: true,  
				//markerIcon: '<?php echo base_url(); ?>assets/images/map-marker.png', 
				radius: 0,
				enableAutocomplete: true,
				enableReverseGeocode: true,
				styles: customStyles,
				addressFormat: 'addressFormat',
				inputBinding: {
					latitudeInput: $('#to_city_latlong_lat_e'),
					longitudeInput: $('#to_city_latlong_lon_e'),        
					locationNameInput: $('#to_city_in_e')
				},
				oninitialized: function(component) {		
					var addressComponents = $(component).locationpicker('map').location.addressComponents;
					updateControls_e(addressComponents, 'to'); //Data
				},
				onchanged: function(currentLocation, radius, isMarkerDropped) {
					var addressComponents = $(this).locationpicker('map').location.addressComponents;
					//console.log(currentLocation);  //latlon  
					updateControls_e(addressComponents, 'to'); //Data
				}
			});
            
            $('#edit_user').click();
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });

    }, 500);    
});

$(document).on('click', '.chk-active', function() {
//$('.chk-active').click(function() {
    var rate_id = $(this).data('rate-id');
    var flag = '0';

    if ($(this).is(":checked")) {
        flag = '1';
    } else if ($(this).is(":not(:checked)")) {
        flag = '0';
    }

    $.ajax({
        url: '<?php echo base_url(); ?>index.php/rates/change_status',
        type: 'POST',
        data: {
            'rate_id': rate_id,
            'flag': flag
        },
        dataType: 'json',
        beforeSend: function() {
			 $(".popup-loader p").html('Please do not press <br>back button or refresh button.');
			 $(".popup-loader").fadeIn();
		 }, 
        success: function(data) {            
            //alert('Success!');
        },
        error: function(request, error) {
            alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
			$(".popup-loader").fadeOut();			
		}
    });
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

$( document ).ready(function() {
	navigator.geolocation.getCurrentPosition(showPosition);
	function showPosition(position) {
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	buildMap(lat, lng);
	}
});

function buildMap(lat, lng) {

$('#from_city_map').locationpicker({	
    location: {
        latitude: +lat || 56.130366,
        longitude: +lng || -106.34677099999999
    },
    //markerInCenter: true,  
    //markerIcon: '<?php echo base_url(); ?>assets/images/map-marker.png', 
    radius: 0,
    enableAutocomplete: true,
    enableReverseGeocode: true,
    styles: customStyles,
    addressFormat: 'addressFormat',
    inputBinding: {
        latitudeInput: $('#from_city_latlong_lat'),
        longitudeInput: $('#from_city_latlong_lon'),        
        locationNameInput: $('#from_city_in')
    },
	oninitialized: function(component) {		
		var addressComponents = $(component).locationpicker('map').location.addressComponents;
        updateControls(addressComponents, 'from'); //Data
	},
    onchanged: function(currentLocation, radius, isMarkerDropped) {
        var addressComponents = $(this).locationpicker('map').location.addressComponents;
        //console.log(currentLocation);  //latlon  
        updateControls(addressComponents, 'from'); //Data
    }
});

$('#to_city_map').locationpicker({
    location: {
        latitude: +lat || 56.130366,
        longitude: +lng || -106.34677099999999
    },
    //markerInCenter: true,  
    //markerIcon: '<?php echo base_url(); ?>assets/images/map-marker.png', 
    radius: 0,
    enableAutocomplete: true,
    enableReverseGeocode: true,
    styles: customStyles,
    addressFormat: 'addressFormat',
    inputBinding: {
        latitudeInput: $('#to_city_latlong_lat'),
        longitudeInput: $('#to_city_latlong_lon'),        
        locationNameInput: $('#to_city_in')
    },
	oninitialized: function(component) {		
		var addressComponents = $(component).locationpicker('map').location.addressComponents;
        updateControls(addressComponents, 'to'); //Data
	},
    onchanged: function(currentLocation, radius, isMarkerDropped) {
        var addressComponents = $(this).locationpicker('map').location.addressComponents;
        //console.log(currentLocation);  //latlon  
        updateControls(addressComponents, 'to'); //Data
    }
});

}

//add cities to form for fares
function updateControls(addressComponents, which)
{
	if(which == 'from')
	{
		$('#from_city').val(addressComponents['city']);
	}
	else
	{
		$('#to_city').val(addressComponents['city']);
	}
}

//add cities to form for fares
function updateControls_e(addressComponents, which)
{
	if(which == 'from')
	{
		$('#from_city_e').val(addressComponents['city']);
	}
	else
	{
		$('#to_city_e').val(addressComponents['city']);
	}
}
</script>