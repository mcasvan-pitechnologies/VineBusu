/**
 * Created by sergiu on 3/14/14.
 */
$(function(){
    var map;
    var markers = 0;
    var routes;
    $.ajax({
        url: $('#getRouteUrl').val(),
        method: 'GET',
        contentType: 'application/json; charset=UTF-8',
        success: function (response) {
            routes = response;

            initialize();
            google.maps.event.addDomListener(window, 'load', initialize);
        }
    });
    function initialize() {
        var myLatlng = new google.maps.LatLng($('#center_lat').val(),$('#center_long').val());
        var mapOptions = {
            zoom: 14,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

        google.maps.event.addListener(map, 'click', function(event) {
            if(markers==0)
            {
                placeMarker1(event.latLng);
                markers++;
            }else if(markers==1)
            {
                placeMarker2(event.latLng);
                markers++;
            }

        });

        $.each(routes, function(key,item){
            var flightPlanCoordinates = [];
            for (var i = 0; i < item.length; i++) {
                flightPlanCoordinates.push(new google.maps.LatLng(item[i].lat, item[i].lon));
            }

            var flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                strokeColor: getRandomColor(),
                strokeOpacity: 1,
                strokeWeight: 4
            });

            flightPath.setMap(map);
            mapsInfoWindow(flightPath, key);
        });
        /*$.ajax({
            url: $('#getRouteUrl').val(),
            method: 'GET',
            contentType: 'application/json; charset=UTF-8',
            success: function (response) {
                $.each(response, function(key,item){
                    var flightPlanCoordinates = [];
                    for (var i = 0; i < item.length; i++) {
                        flightPlanCoordinates.push(new google.maps.LatLng(item[i].lat, item[i].lon));
                    }

                    var flightPath = new google.maps.Polyline({
                        path: flightPlanCoordinates,
                        geodesic: true,
                        strokeColor: getRandomColor(),
                        strokeOpacity: 1,
                        strokeWeight: 4
                    });

                    flightPath.setMap(map);
                    mapsInfoWindow(flightPath, key);
                });

            }
        });*/

    }

    function mapsInfoWindow(polyline, content) {
        var infowindow = new google.maps.InfoWindow({
            content: content
        });
        google.maps.event.addListener(polyline, 'click', function(event) {
            infowindow.setPosition(event.latLng);
            infowindow.position = event.latLng;
            infowindow.open(map);
        });
    }

    function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.round(Math.random() * 15)];
        }
        return color;
    }

    function placeMarker1(location) {
        var clickedLocation = new google.maps.LatLng(location);
        var marker1 = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true
        });
        var circleLocation1 = {
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: location,
            radius: 500
        };
        // Add the circle for this city to the map.
        cityCircle1 = new google.maps.Circle(circleLocation1);
        google.maps.event.addListener(marker1,'dragend',function(event){
            cityCircle1.setCenter(event.latLng)
        });
    }
    function placeMarker2(location) {
        var clickedLocation = new google.maps.LatLng(location);
        var marker2 = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true
        });
        var circleLocation = {
            strokeColor: '#0000FF',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#0000FF',
            fillOpacity: 0.35,
            map: map,
            center: location,
            radius: 500
        };
        // Add the circle for this city to the map.
        cityCircle2 = new google.maps.Circle(circleLocation);
        google.maps.event.addListener(marker2,'dragend',function(event){
            cityCircle2.setCenter(event.latLng)
        });
    }

    /*initialize();
    google.maps.event.addDomListener(window, 'load', initialize);*/

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