<!DOCTYPE html>
<html>
    <head>
        <title id="tit">{{$title}}</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <style>
            html, body, #map-canvas {
                height: 100%;
                margin: 0px;
                padding: 0px
            }
        </style>
        <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/bootstrap.min.css">

        <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/font-awesome.min.css">

        <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/styles.css">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>

        <script>
// This example creates an interactive map which constructs a
// polyline based on user clicks. Note that the polyline only appears
// once its path property contains two LatLng coordinates.

var poly;
var map;

function initialize(cur_lat, cur_lon, prev_lat, prev_lon) {
    // Try HTML5 geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            var marker = new google.maps.Marker({
                position: pos,
                map: map,
                title: 'You are here!'
            });

        }, function () {
            handleNoGeolocation(true);
        });
    } else {
        // Browser doesn't support Geolocation
        handleNoGeolocation(false);
    }
    var mapOptions = {
        zoom: 14,
        // Center the map on Chicago, USA.
        center: new google.maps.LatLng(cur_lat, cur_lon),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
    };

    var markers = [
        {
            "lat": prev_lat,
            "lng": prev_lon,
        }
        ,
        {
            "lat": cur_lat,
            "lng": cur_lon,
        }
    ];

    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var infoWindow = new google.maps.InfoWindow();
    var lat_lng = new Array();
    var latlngbounds = new google.maps.LatLngBounds();
    var image = '<?php echo asset_url() . "/website/img/car.png"; ?>';
    image.height = 20;
    image.width = 20;
    for (i = 0; i < markers.length; i++) {
        var data = markers[i]
        var myLatlng = new google.maps.LatLng(data.lat, data.lng);
        lat_lng.push(myLatlng);
        if (i == markers.length - 1) {
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                icon: image,
            });
        } else {
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
            });
        }
        latlngbounds.extend(marker.position);
        (function (marker, data) {
            google.maps.event.addListener(marker, "click", function (e) {
                // infoWindow.setContent(data.description);
                infoWindow.open(map, marker);
            });
        })(marker, data);
    }
    map.setCenter(latlngbounds.getCenter());
    map.fitBounds(latlngbounds);

    //***********ROUTING****************//

    //Initialize the Path Array
    var path = new google.maps.MVCArray();

    //Initialize the Direction Service
    var service = new google.maps.DirectionsService();

    //Set the Path Stroke Color
    var poly = new google.maps.Polyline({map: map, strokeColor: '#4986E7'});

    //Loop and Draw Path Route between the Points on MAP
    for (var i = 0; i < lat_lng.length; i++) {
        if ((i + 1) < lat_lng.length) {
            var src = lat_lng[i];
            var des = lat_lng[i + 1];
            path.push(src);
            poly.setPath(path);
            service.route({
                origin: src,
                destination: des,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            }, function (result, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                        path.push(result.routes[0].overview_path[i]);
                    }
                }
            });
        }
    }

    function handleNoGeolocation(errorFlag) {
        if (errorFlag) {
            var content = 'Error: The Geolocation service failed.';
        } else {
            var content = 'Error: Your browser doesn\'t support geolocation.';
        }
    }
}
        </script>

    </head>
    <body>
        <div class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header pull-left">    
                    <a class="navbar-brand" href="/"><img id="logo" src="<?php echo asset_url(); ?><?php echo $logo; ?>" alt="" height="100%" width="auto"> {{$app_name}}</a>
                </div>
                <div class="navbar-header pull-right">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="/">Home</a></li>
                        <li><a href="<?php echo web_url(); ?>/user/signin">Login</a></li>
                        <li><a href="<?php echo web_url(); ?>/user/signup">SignUp</a></li>
                    </ul>
                </div>
            </div>
            <input type="hidden" href="{{ route('getTrackLoc',$track_id) }}" id = "trackURL"> </input>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12" id="map-canvas"></div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="<?php echo asset_url(); ?>/website/js/bootstrap.min.js"></script>
        <script>
$(document).ready(function () {
    var datas;
    setInterval(function () {

        var url = $("#trackURL").attr("href");
        ;
        var formdata = '';
        $.ajax(url, {
            data: formdata,
            type: "GET",
            success: function (response) {
                var at = "<?php echo web_url(); ?>";
                if (response.success)
                {
                    $("#tit").html(response.titl);
                    $("#log").attr("src", at + response.logo);
                    initialize(response.cur_lat, response.cur_lon, response.prev_lat, response.prev_lon);
                }
                else
                {
                    console.log("Something went wrong!!");
                }
                google.maps.event.addDomListener(window, 'load', initialize);
            }
        });

    }, 10000);
});
        </script>
    </body>
</html>
