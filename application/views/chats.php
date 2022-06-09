<script type="text/javascript"
    src='https://maps.google.com/maps/api/js?key=AIzaSyAvgN7X2UmjAXNC5GaVBBgLhaJKZd4SiFE&libraries=places'></script>
<script src="<?php echo base_url(); ?>assets/js/locationpicker.jquery.js"></script>

<link href="<?php echo base_url(); ?>assets/css/chosen.css" rel="stylesheet">
<style>
    .ct-name
    {
        display: inline-block;
    }
    .dot {
  height: 15px;
  width: 15px;
  background-color: #d70000;
  border-radius: 50%;
  display: inline-block !important;
  margin-left: 8px;
}
/*puts the google places autocomplete dropdown results above the bootstrap modal 1050 zindex.*/
.pac-container {
    z-index: 1051 !important;
}

.chosen-select {
    width: 100%;
}
.activity-desc-sub1 p,.activity-desc-sub p
{
    word-break: break-word;
    white-space: pre-wrap;
}
.chat-bottom input[type="text"]
{
    width: 91%;
}
#chat_message
{
    width: 68%;
    border: 1px solid #D4CFCF;
    outline: none;
}
</style>
<div id="page-wrapper">
    <div class="main-page">
       <div class="col-md-12">
            <div style="float:right;padding: 5px 0px;">
                <a href="javascript:location.reload(true)" class="btn btn-primary"> <i class="fa fa-refresh" aria-hidden='true'></i> Refresh </a>
            </div>
        </div>

        <?php //if($this->session->userdata('user_role') != 'Administrator'){ ?>
        <div class="col-md-12">
            <div class="create-chat">
                <p>Create new Chat</p>                
                <form method="post" action="<?php echo base_url(); ?>index.php/chats/create" id="create-chat-form">
                    <div class="row" style="margin-top: 15px">
                        <div class="col-md-9" style="padding: 0">
                            <select data-placeholder="Select Driver" name="chat_users[]" multiple class="chosen-select">
                                <?php foreach ($drivers as $d) { ?>
                                    <option value="<?php echo 'U_' . $d['driver_id'] . '_D'; ?>"><?php echo $d['eq_unit'].' - '.$d['driver_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>                        
                        <div class="col-md-3" style="padding: 0">
                            <input type="hidden" name="chat_name" id="chat_name">
                            <button class="create-chat-btn">Create chat</button>
                        </div>
                    </div>                    
                </form>
            </div>
        </div>
        <?php //} ?>

        <div class="elements  row" style="margin-top: 10px">

            <div class="col-md-6">
                <div class="widget-shadow">
                    <div class="activity_box activity_box2">
                        <h4 class="title3">All Chats</h4>
                        <div class="scrollbar scrollbar1" tabindex="5001" style="height: 425px; overflow: hidden; outline: none;padding-top: 5px;">
                            <div class="single-bottom">
                                <?php if($chats){ ?>
                                <ul>
                                    <?php foreach($chats as $c){ 
                                        if($c['seen'] == '0')
                                        {
                                            $cl = 'dot';
                                        }
                                        else
                                        {
                                            $cl = '';
                                        }
                                        ?>
                                        <li class="chats-li" data-chat-id="<?php echo $c['chat_id']; ?>"><p class="ct-name"><?php echo $c['chat_name'] ? $c['chat_name'] : $c['chat_title']; ?></p><span class="<?php echo $cl; ?>"></span><span><?php echo $c['chat_role']; ?></span></li>
                                    <?php } ?>                                    
                                </ul>
                                <?php }else{ ?>
                                <p style="color: #c7c7c7;text-align: center;margin-top: 100px;font-size: 18px;">No chats found!</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
            if(isset($chat) && $chat){
            //Chat title & chat role
            $i = 0;
            $users = array();
            $chat_role = '';
            $chat_title = '';
            foreach($chat['usersinfo'] as $key=>$val){
                $i++;
                if($chat_user != $key && $key != 'U0')
                {
                    $users[] = $val['name'];
                    $chat_role = $val['role'];
                }                
            }    
            
            $chat_title = implode(',', $users);            
            if($i > 2)
            {  
                $chat_role = 'Group';
            }            
            ?>                    
            <div class="col-md-6">
                <div class="widget-shadow">
                    <h4 class="title3"><?php echo $chat['chat']['chat_name'] ? $chat['chat']['chat_name'] : $chat_title; ?> <span>[<?php echo $chat_role; ?>]</span><i class="fa fa-times" onclick="$(this).parent().parent().remove();" style="float: right;cursor: pointer;"></i></h4>
                    <div class="scrollbar scrollbar1" tabindex="5000" style="overflow: hidden; outline: none;" id="chatscroll">

                        <div id="dynamic-chat-area">

                            <?php
                            $last_id = 0;
                            foreach($chat['messages'] as $m){                              
                            if($m['chat_user'] != $chat_user){
                            $last_id = $m['msg_id'];
                            ?>

                            <div class="activity-row activity-row1 activity-right">
                                <div class="col-xs-2 activity-img"><label class="chat-fl"><?php echo substr($chat['usersinfo'][$m['chat_user']]['name'], 0, 1); ?></label></div>
                                <div class="col-xs-8 activity-img1">
                                    <div class="activity-desc-sub">
                                        <?php if($m['msg_type'] == '0'){ ?>
                                        <p><?php echo $m['msg']; ?></p>
                                        <?php }else if($m['msg_type'] == '2'){ ?>
                                        <a target="_blank" href="https://www.google.com/maps/?q=<?php echo $m['msg']; ?>">
                                        <p><i class="fa fa-map-marker" style="font-size: 30px;margin-right: 5px;"></i> Location</p>
                                        </a>
                                        <?php }else{ ?>
                                        <img src="<?php echo base_url().'user_data/chat_data/'.$m['msg']; ?>" style="max-width:120px;margin-bottom: 5px;">
                                        <?php } ?>
                                        <span class="right"><?php echo date('d M y, h:i A', strtotime($m['created_at'])); ?></span>
                                    </div>
                                </div>
                                <div class="clearfix"> </div>
                            </div>

                            <?php
                            }else{   
                            ?>                            

                            <div class="activity-row activity-row1 activity-left">                                
                                <div class="col-xs-8 col-xs-offset-2 activity-img2">
                                    <div class="activity-desc-sub1">
                                        <?php if($m['msg_type'] == '0'){ ?>
                                        <p><?php echo $m['msg']; ?></p>
                                        <?php }else if($m['msg_type'] == '2'){ ?>
                                        <a target="_blank" href="https://www.google.com/maps/?q=<?php echo $m['msg']; ?>">
                                        <p><i class="fa fa-map-marker" style="font-size: 30px;margin-right: 5px;"></i> Location</p>
                                        </a>
                                        <?php }else{ ?>
                                        <img src="<?php echo base_url().'user_data/chat_data/'.$m['msg']; ?>" style="max-width:120px;margin-bottom: 5px;">
                                        <?php } ?>
                                        <span class="right"><?php echo date('d M y, h:i A', strtotime($m['created_at'])); ?></span>
                                    </div>
                                </div>
                                <div class="col-xs-2 activity-img"><label class="chat-fl"><?php echo substr($chat['usersinfo'][$m['chat_user']]['name'], 0, 1); ?></label></div>
                                <div class="clearfix"> </div>
                            </div>

                            <?php } } ?>
                            <input type="hidden" id="last-live-id" value="<?php echo $last_id; ?>">
                        </div>

                    </div>
                    <div class="chat-bottom">
                        <form id="send-msg-form" method="post">
                            <!-- <input type="text" placeholder="Type your message" required="" name="msg" id="chat_message" autocomplete="off"> -->
                            <textarea id="chat_message" name="msg" placeholder="Type your message" required=""></textarea>
                            <input type="hidden" id="chat-id" name="chat_id" value="<?php echo $chatId; ?>">
                            <input type="hidden" id="msg_type" name="msg_type" value="0">
                            
                            <button class="file-btn-chat" type="button" onclick="$('#chat-file').click();" style="right: 115px;"><i class="fa fa-picture-o"></i></button>                            
                            <button class="file-btn-chat" type="button" onclick="$('#location-modal-btn').click();" style="border-radius: 0;"><i class="fa fa-map-marker"></i></button>
                            <button class="send-btn-chat"><i class="fa fa-location-arrow"></i></button>
                        </form>
                        <form id="send-img-form" method="post" enctype="multipart/form-data">
                            <input type="file" style="display: none;" name="file_img" id="chat-file" accept="image/x-png,image/jpeg">
                            <input type="hidden" id="img-chat-id" name="chat_id" value="<?php echo $chatId; ?>">
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>

        </div>

    </div>
</div>

<!-- Modal for pick location -->
<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#LocationModal" id="location-modal-btn" style="display: none;"></button>

<!-- Modal -->
<div id="LocationModal" class="modal fade" role="dialog" data-backdrop="static">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">     
        <h4 class="modal-title">Pick Location</h4>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" id="send_loc_location">
        <input type="hidden" id="send_loc_latlong_lat">
        <input type="hidden" id="send_loc_latlong_long">        
        <div id="send-location" style="height: 250px;margin-top: 10px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default cancel-location-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-default send-location-btn" style="color: #fff;background: #81b3ff;">Send</button>
      </div>
    </div>

  </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/chosen.jquery.js"></script>
<script>
$(".chosen-select").chosen();

$('#create-chat-form').on( "submit", function( event ) {    
    var users = $( this ).serializeArray();   

    if(users.length > 2)
    {
        var groupname = '';
        while(groupname == ''){
            groupname = prompt("You have selected multiple recipient, Please enter group name.", "");
        }
        $('#chat_name').val(groupname);        
    }
    else
    {
        $('#chat_name').val('');        
    }            
});

$('.chats-li').click(function(){
    var chatId = $(this).data('chat-id');

    var form = document.createElement("form");
    var element1 = document.createElement("input");

    form.method = "POST";
    form.action = "<?php echo base_url(); ?>index.php/chats";

    element1.value = chatId;
    element1.name = "chatId";
    element1.type = "hidden";
    form.appendChild(element1);

    document.body.appendChild(form);

    form.submit(); 
});

<?php if(isset($chat['usersinfo']) && $chat['usersinfo']){ ?>
//user info to JS var from PHP if chat data is here
var usersinfo = '<?php echo json_encode($chat['usersinfo']); ?>';
usersinfo = JSON.parse(usersinfo);

//Ajax to fetch live current chat messages
document.addEventListener('DOMContentLoaded', function() {
    $('#chatscroll').scrollTop($('#chatscroll')[0].scrollHeight);
    livechat_init()
});

function livechat_init()
{
    setInterval(function(){
        livechat_on();
        console.log('Live call');
    }, 15000);
}

//fetch live messages 
function livechat_on()
{
    $.ajax({
        url: '<?php echo base_url(); ?>index.php/chats/fetch_live_replies',
        type: 'POST',
        data: {
            'lastID': $('#last-live-id').val(),
            'chatID': $('#chat-id').val()
        },
        dataType: 'json',        
        success: function(data) {                          
            if(data)
            {
                //console.log(data);
                for(var i=0; i<data.length; i++)
                {                    
                    if(data[i]['msg_type'] == '0'){
                        $('#dynamic-chat-area').append('<div class="activity-row activity-row1 activity-right"><div class="col-xs-2 activity-img"><label class="chat-fl">'+usersinfo[data[i]['chat_user']]['name'].substring(0, 1)+'</label></div><div class="col-xs-8 activity-img1"><div class="activity-desc-sub"><p>'+data[i]['msg']+'</p><span class="right">'+data[i]['created_at']+'</span></div></div><div class="clearfix"></div></div>');    
                        var msg_n = data[i]['msg'];
                    }else if(data[i]['msg_type'] == '2'){
                        $('#dynamic-chat-area').append('<div class="activity-row activity-row1 activity-right"><div class="col-xs-2 activity-img"><label class="chat-fl">'+usersinfo[data[i]['chat_user']]['name'].substring(0, 1)+'</label></div><div class="col-xs-8 activity-img1"><div class="activity-desc-sub"><a target="_blank" href="https://www.google.com/maps/?q='+data[i]['msg']+'"><p><i class="fa fa-map-marker" style="font-size: 30px;margin-right: 5px;"></i> Location</p></a><span class="right">'+data[i]['created_at']+'</span></div></div><div class="clearfix"></div></div>');    
                        var msg_n = 'Location';
                    }else{
                        $('#dynamic-chat-area').append('<div class="activity-row activity-row1 activity-right"><div class="col-xs-2 activity-img"><label class="chat-fl">'+usersinfo[data[i]['chat_user']]['name'].substring(0, 1)+'</label></div><div class="col-xs-8 activity-img1"><div class="activity-desc-sub"><img src="<?php echo base_url(); ?>user_data/chat_data/'+data[i]['msg']+'" style="max-width:120px;margin-bottom: 5px;"><span class="right">'+data[i]['created_at']+'</span></div></div><div class="clearfix"></div></div>');
                        var msg_n = 'Image';
                    }                    
                    $('#last-live-id').val(data[i]['msg_id']); //set latest message id for fetch new
                    $('#chatscroll').scrollTop($('#chatscroll')[0].scrollHeight); //scroll to down for new binded msg                    

                    //send notification if ON
                    /*if (Notification.permission == 'granted') {
                        notify(usersinfo[data[i]['chat_user']]['name'], msg_n, '<?php echo base_url(); ?>index.php/chats');                        
                    }else{
                        document.getElementById('notification').play(); //play sound
                    }*/

                    livechat_init();
                }
            }
            else if(data == '0')
            {
                alert("Session expired!");
                location.reload();
            }          
        },
        error: function(request, error) {        
            //alert("Something went wrong, Sory for inconvenience!");
            //location.reload();
        }        
    });
}
<?php } ?>

//send message AJAX
$('#send-msg-form').on( "submit", function( event ) {    
     console.log($(this).serialize());
    // return false;

    if($('#chat_message').val() != '')
    {
        $('.send-btn-chat').attr('disabled','disabled');

        $.ajax({
            url: '<?php echo base_url(); ?>index.php/chats/send_message',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',        
            success: function(data) {                 
                if(data == '2'){
                    alert("Something went wrong, Sory for inconvenience!");
                    location.reload();
                }
                else if(data == '0')
                {
                    alert("Session expired!");
                    location.reload();
                }
                else{
                    if(data['msg_type'] == '0')
                    {
                        $('#dynamic-chat-area').append('<div class="activity-row activity-row1 activity-left"><div class="col-xs-8 col-xs-offset-2 activity-img2"><div class="activity-desc-sub1"><p>'+data['msg']+'</p><span class="right">'+data['created_at']+'</span></div></div><div class="col-xs-2 activity-img"><label class="chat-fl">'+usersinfo[data['chat_user']]['name'].substring(0, 1)+'</label></div><div class="clearfix"></div></div>');
                    }
                    else
                    {
                        $('#dynamic-chat-area').append('<div class="activity-row activity-row1 activity-left"><div class="col-xs-8 col-xs-offset-2 activity-img2"><div class="activity-desc-sub1"><a target="_blank" href="https://www.google.com/maps/?q='+data['msg']+'"><p><i class="fa fa-map-marker" style="font-size: 30px;margin-right: 5px;"></i> Location</p></a><span class="right">'+data['created_at']+'</span></div></div><div class="col-xs-2 activity-img"><label class="chat-fl">'+usersinfo[data['chat_user']]['name'].substring(0, 1)+'</label></div><div class="clearfix"></div></div>');
                    }
                    $('#chatscroll').scrollTop($('#chatscroll')[0].scrollHeight);
                }            
            },
            error: function(request, error) {
                alert("Something went wrong, Sory for inconvenience!");
                location.reload();
            },
            complete: function() {
                $('.send-btn-chat').removeAttr('disabled');
                $('#chat_message').val('');
                $('#msg_type').val('0');
                location.reload();
            }
        });
    }
    event.preventDefault();
});

//send image ajax
$('#chat-file').change(function(){
    $('#send-img-form').submit();    
});

$('#send-img-form').on( "submit", function( event ) {    
    if(document.getElementById("chat-file").files.length != 0)
    {
        var form = $(this)[0];
        var formdata = new FormData(form);

        $('.file-btn-chat').attr('disabled','disabled');

        $('#dynamic-chat-area').append('<div id="temp-img" class="activity-row activity-row1 activity-left"><div class="col-xs-8 col-xs-offset-2 activity-img2"><div class="activity-desc-sub1"><img src="<?php echo base_url(); ?>assets/images/spiner.gif" style="max-width:120px;"></div></div><div class="col-xs-2 activity-img"><label class="chat-fl">'+usersinfo['<?php echo $chat_user; ?>']['name'].substring(0, 1)+'</label></div><div class="clearfix"></div></div>');
        $('#chatscroll').scrollTop($('#chatscroll')[0].scrollHeight);

        $.ajax({
            url: '<?php echo base_url(); ?>index.php/chats/send_image',
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            data: formdata,
            dataType: 'json',        
            success: function(data) {  
                console.log(data);
                if(data == '2' || data == '12' || data == '11'){
                    alert("Something went wrong, Sory for inconvenience!");
                    location.reload();
                }
                else if(data == '0')
                {
                    alert("Session expired!");
                    location.reload();
                }
                else
                {
                    $('#temp-img').remove();
                    $('#dynamic-chat-area').append('<div class="activity-row activity-row1 activity-left"><div class="col-xs-8 col-xs-offset-2 activity-img2"><div class="activity-desc-sub1"><img src="<?php echo base_url(); ?>user_data/chat_data/'+data['msg']+'" style="max-width:120px;margin-bottom: 5px;"><span class="right">'+data['created_at']+'</span></div></div><div class="col-xs-2 activity-img"><label class="chat-fl">'+usersinfo[data['chat_user']]['name'].substring(0, 1)+'</label></div><div class="clearfix"></div></div>');
                    $('#chatscroll').scrollTop($('#chatscroll')[0].scrollHeight);
                }            
            },
            error: function(request, error) {
                //alert("Something went wrong, Sory for inconvenience!");
                location.reload();
            },
            complete: function() {
                $('.file-btn-chat').removeAttr('disabled');                
            }
        });
    }
    event.preventDefault();
});

// Location pick
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

$('#send-location').locationpicker({	
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
        latitudeInput: $('#send_loc_latlong_lat'),
        longitudeInput: $('#send_loc_latlong_long'),        
        locationNameInput: $('#send_loc_location')
    },
	oninitialized: function(component) {		
		var addressComponents = $(component).locationpicker('map').location.addressComponents;        
	},
    onchanged: function(currentLocation, radius, isMarkerDropped) {
        var addressComponents = $(this).locationpicker('map').location.addressComponents;
        //console.log(currentLocation);  //latlon          
    }
});

}

//set msg_type for location and pass value of lat long
$('.send-location-btn').click(function(){
    $('#chat_message').val($('#send_loc_latlong_lat').val()+','+$('#send_loc_latlong_long').val());
    $('#msg_type').val('2');
    $('.send-btn-chat').click();
    $('.cancel-location-btn').click();
});
</script>