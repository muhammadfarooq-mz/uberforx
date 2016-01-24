@extends('layouts.master')

@section('styles')
@parent

<style>
    .customSelect{
        padding: 0px;
        width: auto;
        display: inline;
    }
</style>
@stop


@section('body')
<div class="row first-fold">
    <div class="landing">
        <div class="row uber-logo second-uber-logo">
            <div class="col-md-1 col-xs-12"><img src="{{ asset('web/img/logo.png') }}" alt=""></div>
            <div class="col-md-5 col-md-offset-6 col-xs-12">
                <ul class="inline">
                    <li><a href="#">Home</a> | </li>
                    <li><a href="#">Support</a> | </li>
                    <li><a href="#">About</a> | </li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row buttons">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <center>
                    <span class="eta" style="display: none"> Set pickup time
                       {{Form::selectRange('hours', 1, 12, null, array('class' => 'form-control customSelect'))}}
                       {{Form::selectRange('minutes', 00, 59, null, array('class' => 'form-control customSelect'))}}
                       {{Form::select('time', array('am' => 'AM', 'pm' => 'PM'), null, array('class' => 'form-control customSelect'))}}
                       <input type="submit" class="btn btn-blue time" value="SET"/>
                       </span>
                    <input type="submit" class="btn btn-blue locate" value="LOCATE ME!"/>
                    <input type="submit" class="btn btn-blue" id="reached_location" value="Reached Location" style="display: none"/>
                    <input type="submit" class="btn btn-blue" id="job_completed" value="Job Completed" style="display: none"/>
                    <input type="submit" class="btn btn-blue" id="update_location" value="Update My Location"/>
                </center>
            </div>
        </div>
    </div>
    <div class="row map-wrapper">
        <div id="map" class="map">

        </div>
    </div>
</div>
<div class="row uber-footer">
    <div class="col-md-6 col-md-offset-3">
        <div class="row">
            <div class="col-md-12">
                <h3>Say Hi, Get In Touch</h3>
            </div>
        </div>
        <div class="row social-icons">
            <ul class="col-md-12 icons text-center">
                <li><a href="#"><i class="icon-facebook"></i></a></li>
                <li><a href="#"><i class="icon-twitter"></i></a></li>
                <li><a href="#"><i class="icon-google-plus"></i></a></li>
            </ul>
        </div>
        <div class="row">
            <p> Copyright ProvenLogic. All Rights Reserved.</p>
        </div>
    </div>
</div>
@stop


@section('footer')
@parent
<script src="http://datejs.googlecode.com/svn/trunk/build/date-en-US.js"></script>

<script>

var map, pos, clientPos, marker,directionsDisplay,directionsService;
    var start,end,request_id;
    var contentStart = '<div id="content">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<div id="bodyContent">';
    var new_lat,new_lng;
    var contentData,function1,clientContent;
    var contentEnd = '</div>'+'</div>';
    var infowindow,requestinfowindow;
    var bounds, rendererOptions, request;

var mapOptions = {
        zoom: 18,
        scrollwheel: false,
    };
    function locate(){
        map = new google.maps.Map(document.getElementById('map'),mapOptions);
        pos = new google.maps.LatLng(,);
        marker = new google.maps.Marker({
            position: pos,
            map: map,
            draggable:true,
            title: 'Current Location'
        });
    marker.setAnimation(google.maps.Animation.DROP);
    content='<h3>No request yet!</h3>';
    infowindow = new google.maps.InfoWindow({
        content: contentStart+content+contentEnd
    });
    infowindow.open(map,marker);
    google.maps.event.addListener(marker, 'click', function(event)
    {
        infowindow.open(map,marker);
    });
    google.maps.event.addListener(marker, 'dragend', function(event)
    {
        new_lat = this.getPosition().lat();
        new_lng = this.getPosition().lng();
        $('#update_location').click();

    });
    map.setCenter(pos);
    }

    $('.time').on('click',function(e)
    {
        var hours = $('[name=hours]').val();
        var minutes = $('[name=minutes]').val();
        var time = $('[name=time]').val();
        $.ajax({
            type: "POST",
            url: "",
            data: {hours: hours, minutes: minutes, time: time, request_id: request_id}
        }).done(function(data){
            var date = (new Date).clearTime().addSeconds(data/1000).toString('H:mm:ss');
            $('span.eta').html("ETA "+date);
            $('#reached_location').css('display','');
            infowindow.setContent('<h4>Reach the client!</h4>');
            if(directionsDisplay)
            {
                directionsDisplay.setMap();
            }
            rendererOptions = {
                suppressMarkers: true
            };

            directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
            directionsDisplay.setMap(map);
            request = {
                origin : pos,
                destination : clientPos,
                travelMode : google.maps.TravelMode.DRIVING
            };
            directionsService = new google.maps.DirectionsService();
            directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                }
            });
            $('#update_location').click();

        });
        e.preventDefault();

    });

    function getRequestUpdate()
    {

    }

    function getDriverRequest()
    {
        $.ajax({
            type: "post",
            url: "",
            data: {driver_id: 4}
        }).done(function(data)
        {
            if(data.request)
            {
                $('input.locate').css('display','none');
                $('.eta').css('display','');
                clearInterval(function1);
                request_id = data.request.request_id;
                clientPos = new google.maps.LatLng(data.request.lattitude, data.request.logitude);
                infowindow.setContent('<h4>Set ETA</h4>');
                clientMarker = new google.maps.Marker({
                    position: clientPos,
                    map: map,
                    draggable:false,
                    title: 'Pickup Location'
                });
                clientMarker.setAnimation(google.maps.Animation.DROP);
                clientContent='<h3>Client!</h3>';
                requestinfowindow = new google.maps.InfoWindow({
                    content: contentStart+clientContent+contentEnd
                });
                requestinfowindow.open(map,clientMarker);
                bounds = new google.maps.LatLngBounds();
                bounds.extend(marker.getPosition());
                bounds.extend(clientMarker.getPosition());
                map.setCenter(bounds.getCenter());
                map.fitBounds(bounds);

            }
        });
    }
    $('input.locate').on('click',function(){
        locate();
    })

    $(document).ready(function(){
        locate();
        function1 = setInterval(function(){getDriverRequest()},5000);
    });

    $('#update_location').on('click',function(){
        $.ajax({
            url: "",
            type: "POST",
            data: {lat: new_lat, lng: new_lng}
        }).done(function(status){
            console.log(status);
            if(directionsDisplay)
            {
                directionsDisplay.setMap();
            }
            rendererOptions = {
                suppressMarkers: true
            };

            directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
            directionsDisplay.setMap(map);

            request = {
                origin : new google.maps.LatLng(new_lat,new_lng),
                destination : clientPos,
                travelMode : google.maps.TravelMode.DRIVING
            };
            directionsService = new google.maps.DirectionsService();
            directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                }
            });
        });
        return false;
    });
    $('#job_completed').on('click',function(){
        $('#job_completed').unbind("click");
        $.ajax({
            url: "",
            type: "post",
            data: {request_id: request_id}
        }).done(function(data){
            $('#job_completed').css('display','none');
            location.reload(true);
        });
        return false;
    });
    $('#reached_location').on('click',function(){
        $.ajax({
            url: "",
            type: "post",
            data: {request_id: request_id}
        }).done(function(data){
            $('span.eta').css('display','none');
            $('#job_completed').css('display','');
            $('#reached_location').css('display','none');
        });
        return false;
    });

</script>

@stop