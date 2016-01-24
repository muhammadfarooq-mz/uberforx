@extends('web.layout')

@section('body')
<div class="row first-fold">
    <div class="landing">
        <div class="row uber-logo second-uber-logo">
            <div class="col-md-1 col-xs-12"><img src="<?php echo web_url(); ?>/web/img/logo.png" alt=""></div>
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
        <div class="col-md-4 col-md-offset-4">
            <div class="row">
                <div class="col-md-6 col-xs-6 locate-button">
                    <button id="locate" class="btn btn-blue"> Locate Me! </button>
                </div>
                <div class="col-md-6 col-xs-6 send-here-button">
                    <button id="set_location" class="btn btn-blue"> Send Here </button>
                </div>
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

<script>
    function startIntro(){
        var intro = introJs();
        intro.setOptions({
            showBullets: false,
            exitOnOverlayClick: false,
            showStepNumbers: false,
            steps: [
                {
                    intro: "Hello username, this is the user dashboard. You could use this to find a mechanic to fix your car."
                },
                {
                    element: '#locate',
                    intro: "Clicking this will locate your approx position. You could drag the marker and place it anywhere you want the mechanic to arrive"
                },
                {
                    intro: "Try moving the marker to some other place"
                },
                {
                    element: '#set_location',
                    intro: 'Now click this to send a request.'
                },
                {
                    intro: 'After you have sent a request, go to admin panel and request a payment of $1 from the client. Then Come back to Client dashboard to pay the same.'
                }
            ]
        });

        intro.start();
    }
</script>


<script>
    var map, pos, marker, new_lat =0, new_lng= 0;
    var contentStart = '<div id="content">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<div id="bodyContent">';
    var contentData;
    var contentEnd = '</div>'+'</div>';
    var infowindow,payinfowindow;

    var mapOptions = {
        zoom: 18,
        scrollwheel: false,
    };
    function locate(){
        map = new google.maps.Map(document.getElementById('map'),
            mapOptions);
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                new_lat = position.coords.latitude;
                new_lng = position.coords.longitude;
                marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    draggable:true,
                    title: 'Current Location'
                });
                google.maps.event.addListener(marker, 'dragend', function(event)
                {
                    new_lat = this.getPosition().lat();
                    new_lng = this.getPosition().lng();
                });
                google.maps.event.addListener(marker, 'click', function(event)
                {
                    if(payinfowindow){
                        payinfowindow.open(map,marker);
                    }
                    else
                    {
                        infowindow.open(map,marker);
                    }
                });
                marker.setAnimation(google.maps.Animation.DROP);
                map.setCenter(pos);
            }, function() {
                handleNoGeolocation(true);
            });
        }
    }
    jQuery(document).ready(function(){
        locate();
        startIntro();
    });
    $('#locate').on('click',function(e){
        locate();
        marker.setAnimation(google.maps.Animation.DROP);
        e.preventDefault();
    });

    //SETTING LOCATION
    $('#set_location').on('click',function(e){
        $('button.submit').text('Wait!');
        $('button.submit').attr("disabled", true);
        $('input.locate').attr("disabled",true);
        marker.set('draggable',false);
        //SENDS LOCATION
        $.ajax({
            type: "POST",
            url : "",
            data : {lat: new_lat, lng: new_lng}
        }).done(function(data){
            //DISPLAY WAITING
            content='<h3>Please wait! we are finding someone near you</h3>';
            infowindow = new google.maps.InfoWindow({
                content: contentStart+content+contentEnd
            });
            infowindow.open(map,marker);
            //GET REQUEST STATUS
            var refreshId = setInterval(function() {
                $.ajax({
                    type: "POST",
                    url:"{{}}",
                    data: {request_id: data}
                }).done(function(status){
                    if(status.status){
                        var paybutton = '<a class="btn btn-success" href="../pay/'+data+'">Pay $'+status.amount+'</a>';
                        var cancelbutton = '<a class="btn btn-success" href="../cancel/'+data+'">Cancel</a>';
                        payinfowindow = new google.maps.InfoWindow({
                            content: paybutton+" "+cancelbutton
                        });
                        infowindow.close();
                        payinfowindow.open(map,marker);
                        clearInterval(refreshId);
                    }

                });
            }, 5000);

        }).fail(function( jqXHR, textStatus ) {
            console.log( "Request failed: " + textStatus );
        });
        e.preventDefault();
    });
</script>




@stop