/* global document */
//var new_lat =0,new_lng=0;
//jQuery(document).ready(function(){
//
//    var mapOptions = {
//        zoom: 18
//    };
//    var map = new google.maps.Map(document.getElementById('map'),
//        mapOptions);
//    if(navigator.geolocation) {
//        navigator.geolocation.getCurrentPosition(function(position) {
//            var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
//            var marker = new google.maps.Marker({
//                position: pos,
//                map: map,
//                draggable:true,
//                title: 'Current Location'
//            });
//            google.maps.event.addListener(marker, 'dragend', function(event)
//            {
//                new_lat = this.getPosition().lat();
//                new_lng = this.getPosition().lng();
//            });
//            map.setCenter(pos);
//        }, function() {
//            handleNoGeolocation(true);
//        });
//    }
//
//
//});