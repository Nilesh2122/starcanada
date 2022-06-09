//Base URL
var BASE_URL = window.location.origin + '/';

// request permission on page load
document.addEventListener('DOMContentLoaded', function() {
    if (!Notification) {
        alert('Desktop notifications not available in your browser. Update browser or use another for desktop notification!');
        return;
    }

    if (Notification.permission !== 'granted') {
        Notification.requestPermission();
    } else {
        fetch_notification();
        fetchlivereplies_ajax();
    }
});

//Notification function
function notify(title, message, redirect) {
    if (Notification.permission !== 'granted')
        Notification.requestPermission();
    else {
        var notification = new Notification(title, {
            icon: '../assets/images/fi.png',
            body: message,
        });

        notification.onclick = function() {
            window.open(redirect);
        };

        document.getElementById('notification').play();
    }
}

//Fetch notification on complete after every 5 seconds
function fetch_notification() {
    $.ajax({
        url: BASE_URL + 'index.php/operations/get_op_notification',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            if (data == '0') {
                window.location = BASE_URL;
            } else {
                for (var i = 0; i < data.length; i++) {
                    notify(data[i]['title'], data[i]['body'], BASE_URL + 'index.php/operations/op_notification_open/' + data[i]['id']);
                }
            }
        },
        error: function(request, error) {
            //alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
            setTimeout(function() {
                fetch_notification();
            }, 20000);
        }
    });
}

function fetchlivereplies_ajax() {
    $.ajax({
        url: BASE_URL + 'index.php/chats/fetchlivereplies_ajax',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            if (data != null) {
               
                    notify(data['chat_user_name'], data['msg'], BASE_URL + 'index.php/chats');
            }
        },
        error: function(request, error) {
            //alert("Something went wrong! Try Again"); location.reload();
        },
        complete: function() {
            setTimeout(function() {
                fetchlivereplies_ajax();
            }, 15000);
        }
    });
}