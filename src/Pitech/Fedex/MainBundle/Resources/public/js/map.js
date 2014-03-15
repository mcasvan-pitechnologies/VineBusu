/**
 * Created by sergiu on 3/14/14.
 */
$(function(){
    var map;
    var markers = 0;
    var routes, stations;
    var startPos, endPos;
    var routesContainingStartPos = [];
    var routesContainingEndPos = [];
    var validRoutes = [];
    var polylines = [];
    var busmarkers = [];
    var infowindows = [];

    $.arrayIntersect = function(a, b){
        return $.grep(a, function(i)
        {
            return $.inArray(i, b) > -1;
        });
    };

    $.ajax({
        url: $('#getRouteUrl').val(),
        method: 'GET',
        contentType: 'application/json; charset=UTF-8',
        success: function (response) {
            routes = response.routes;
            stations = response.stations;
            initialize();
            google.maps.event.addDomListener(window, 'load', initialize);
        }
    });
    function initialize() {
        google.maps.Circle.prototype.contains = function(latLng) {
            return this.getBounds().contains(latLng) && google.maps.geometry.spherical.computeDistanceBetween(this.getCenter(), latLng) <= this.getRadius();
        }

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
                placeMarkerStart(event.latLng);
                markers++;
            }else if(markers==1)
            {
                placeMarkerEnd(event.latLng);
                markers++;
            }
        });

        /*$.each(routes, function(key,item){
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
        });*/
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
        infowindows.push(infowindow);
    }

    function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.round(Math.random() * 15)];
        }
        return color;
    }

    function placeMarkerStart(location) {
        var clickedLocation = new google.maps.LatLng(location);
        startPos = new google.maps.Marker({
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
        startPos.circle = new google.maps.Circle(circleLocation1);
        google.maps.event.addListener(startPos,'dragend',function(event){
            startPos.circle.setCenter(event.latLng);
            computeRoutes();
            calculateDistance(startPos.position, endPos.position);
        });
    }

    function placeMarkerEnd(location) {
        var clickedLocation = new google.maps.LatLng(location);
        endPos = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true
        });
        var circleLocation2 = {
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
        endPos.circle = new google.maps.Circle(circleLocation2);
        google.maps.event.addListener(endPos,'dragend',function(event){
            endPos.circle.setCenter(event.latLng);
            computeRoutes();
            calculateDistance(startPos.position, endPos.position);
        });
        calculateDistance(startPos.position, endPos.position);
        computeRoutes();
    }

    // In general, x and y must satisfy (x - center_x)^2 + (y - center_y)^2 < radius^2
    function computeRoutes(){
        var new_marker = [];
        routesContainingStartPos = [];
        routesContainingEndPos = [];
        $.each(routes, function(key,route){
            $.each(route, function(index, coords){
                if(startPos.circle.contains(new google.maps.LatLng(coords.lat, coords.lon))){
                    routesContainingStartPos.push(key);
                    return false;
                }
            });

            $.each(route, function(index, coords){
                if(endPos.circle.contains(new google.maps.LatLng(coords.lat, coords.lon))){
                    routesContainingEndPos.push(key);
                    return false;
                }
            });
        });
        validRoutes = $.arrayIntersect(routesContainingStartPos, routesContainingEndPos);


        $.each(polylines, function(index, polyline){
            polyline.setMap(null);
            polyline = null;
        });
        polylines = [];
        $.each(busmarkers, function(index, marker){
            marker.setMap(null);
            marker = null;
        });
        busmarkers = [];
        $.each(infowindows, function(index, infowindow){
            infowindow.close();
        });
        infowindows = [];


        $.each(validRoutes, function(key,item){
            var flightPlanCoordinates = [];
            for (var i = 0; i < routes[item].length; i++) {
                flightPlanCoordinates.push(new google.maps.LatLng(routes[item][i].lat, routes[item][i].lon));
            }
            if(stations[item])
            {
                for (var i = 0; i < stations[item].length; i++) {
                    new_marker = new google.maps.Marker({
                        position: new google.maps.LatLng(stations[item][i].lat,stations[item][i].lon),
                        map: map,
                        title: stations[item][i].name,
                        icon: $('#busIcon').val()
                    });
                    busmarkers.push(new_marker);
                    mapsInfoWindow(new_marker, stations[item][i].name);
                }
            }

            var flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                strokeColor: getRandomColor(),
                strokeOpacity: 1,
                strokeWeight: 4
            });
            polylines.push(flightPath);

            flightPath.setMap(map);
            mapsInfoWindow(flightPath, item);

        });
        /*$.each(routes, function(key,item){
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
            polylines.push(flightPath);

            flightPath.setMap(map);
            mapsInfoWindow(flightPath, key);
        });*/
    }

    function calculateDistance(pointA, pointB)
    {
        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix(
            {
                origins: [pointA],
                destinations: [pointB],
                travelMode: google.maps.TravelMode.DRIVING,
                avoidHighways: false,
                avoidTolls: false
            }, callback);

    }
    function callback(response, status) {
        if (status == google.maps.DistanceMatrixStatus.OK) {
            var origins = response.originAddresses;
            var destinations = response.destinationAddresses;

            for (var i = 0; i < origins.length; i++) {
                var results = response.rows[i].elements;
                for (var j = 0; j < results.length; j++) {
                    var element = results[j];
                    var distance = element.distance.text;
                    var duration = element.duration.text;
                    var from = origins[i];
                    var to = destinations[j];
                    $('#distanceVal').text(distance);
                    $('#timeVal').text(duration);
                    $('#fromVal').text(from);
                    $('#toVal').text(to);
                }
            }
        }

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