/**
 * Created by sergiu on 3/14/14.
 */
$(function(){
    var map;
    function initialize() {
        var myLatlng = new google.maps.LatLng($('#center_lat').val(),$('#center_long').val());
        var mapOptions = {
            zoom: 14,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

    }
    //initialize();
    google.maps.event.addDomListener(window, 'load', initialize);
    function do_it(data){
        var marker_name = data[0].name;
        var cust_id = data[0].customer_id;
        var params = {};
        var x_marker = 0;
        var new_marker = [];
        var infowindow = [];

        $.getJSON('http://maps.google.com/maps/api/directions/json?origin='+encodeURIComponent($('#address').val())+'&destination='+encodeURIComponent(data[0].value)+'&sensor=false', function(d) {
            if(d.status=='OK')
            {
                if(d.routes[0].legs[0].distance.value<$('#range').val())
                {
                    new_marker = new google.maps.Marker({
                        position: new google.maps.LatLng(d.routes[0].legs[0].end_location.lat, d.routes[0].legs[0].end_location.lng),
                        map: map,
                        title: marker_name
                    });
                    infowindow = new google.maps.InfoWindow({
                        content:marker_name
                    });
                    google.maps.event.addListener(new_marker, 'click', function() {
                        infowindow.open(map,new_marker);
                    });
                    x_marker++;
                }
                params['do'] = 'company--customer-save_location';
                params['lat'] = d.routes[0].legs[0].end_location.lat;
                params['lng'] = d.routes[0].legs[0].end_location.lng;
                params['customer_id'] = cust_id;
                $.post("index.php",params,function(o){},'json');

            }else if(d.status=='NOT_FOUND')
            {
                params['do'] = 'company--customer-save_location';
                params['lat'] = '';
                params['lng'] = '';
                params['customer_id'] = cust_id;
                $.post("index.php",params,function(o){},'json');
            }
            data.splice(0, 1);
            if(data.length>0){
                setTimeout(function(){ do_it(data) },200);
            }
        })

    }
})