@extends('layout')

@section('content')

@if (Session::has('msg'))
<h4 class="alert alert-info">
    {{{ Session::get('msg') }}}
    {{{Session::put('msg',NULL)}}}
</h4>
@endif
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Add Request</h3>
    </div>
    <form role="form" id='request-form'  enctype="multipart/form-data">
        <input type="hidden" name="id" value="0">
        <div id="msg"></div>
        <div class="box-body">
            <div class="form-group">
                <label>{{ trans('customize.User');}}</label>
                <input type="text" class="form-control" name="owner_name" value="{{$owner->first_name." ".$owner->last_name}}" placeholder="{{ trans('customize.User');}} Name" >
                <input type='hidden' value='{{$owner->id}}' name="owner_id">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="my_address" id="my-address" placeholder="Please enter Source address" style="margin-bottom:10px;width:65%;float:left;" onblur="codeAddress(1);">
                <button id="getCords" type="button" class="btn btn-success pull-right" onClick="codeAddress(1);">Find Location</button>
                <br>
                <div id="map-canvas" style="width:100%;"></div>
                <!-- Map for user location showing nearby providers -->
            </div>
            <?php if ($settdestination == '1') { ?>
                <div class="form-group">

                    <!-- Map for destination location -->

                    <input type="text" class="form-control" name="my_address" id="my-dest" value='' placeholder="Please enter Destination address" style="margin-bottom:10px;width:65%;float:left;" onblur="codeAddress(2);">
                    <button type="button" id="getCords" class="btn btn-success pull-right" onClick="codeAddress(2);">Find Location</button>
                    <br>
                    <div id="map-dest" style="width:100%;height:300px;"></div>

                <?php } ?>
                <br><br>
                <div class="form-group">
                    <label>Service Type</label>
                    <select name="type" class="form-control" id="flow4">
                        <option value=''>--Select Type--</option>
                        <?php foreach ($services as $service) { ?>
                            <option value="{{$service->id}}">{{$service->name}}</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Choose {{ trans('customize.Provider');}}</label>
                    <!-- Ajax providers -->
                    <select id="provider" class="form-control" name='provider'>
                        <option value=''>--Select {{ trans('customize.Provider');}}--</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Mode</label>
                    <select name="payment_type" class="form-control" required>
                        <option value="1" <?php
                        if ($payment_option['cod'] == 0) {
                            echo 'hidden';
                        }
                        ?>>Pay by Cash</option>
                        <option value="0" <?php
                        if ($payment_option['stored_cards'] == 0) {
                            echo 'hidden';
                        }
                        ?>>Pay by Stored Card</option>
                        <option value="2" <?php
                        if ($payment_option['paypal'] == 0) {
                            echo 'hidden';
                        }
                        ?>>Pay by Paypal</option>
                    </select>
                </div>
            </div><!-- /.box-body -->

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="d_latitude" id="d_latitude">
            <input type="hidden" name="d_longitude" id="d_longitude">
            <input type="hidden" name="selection" id="selection" value='2'>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat btn-block">Add Request</button>
            </div>
    </form>
</div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<style>
    #map-canvas {
        height: 300px;
        width: 500px;
        margin: 0px;
        padding: 0px
    }
</style>



<script type="text/javascript">
                        function initialize() {
                            var address = (document.getElementById('my-address'));
                            var autocomplete = new google.maps.places.Autocomplete(address);
                            autocomplete.setTypes(['geocode']);
                            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                                var place = autocomplete.getPlace();
                                if (!place.geometry) {
                                    return;
                                }


                                var address = '';
                                if (place.address_components) {
                                    address = [
                                        (place.address_components[0] && place.address_components[0].short_name || ''),
                                        (place.address_components[1] && place.address_components[1].short_name || ''),
                                        (place.address_components[2] && place.address_components[2].short_name || '')
                                    ].join(' ');
                                }
                            });

                            var dest = (document.getElementById('my-dest'));
                            var autocomplete = new google.maps.places.Autocomplete(dest);
                            autocomplete.setTypes(['geocode']);
                            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                                var place = autocomplete.getPlace();
                                if (!place.geometry) {
                                    return;
                                }


                                var address2 = '';
                                if (place.address_components) {
                                    address2 = [
                                        (place.address_components[0] && place.address_components[0].short_name || ''),
                                        (place.address_components[1] && place.address_components[1].short_name || ''),
                                        (place.address_components[2] && place.address_components[2].short_name || '')
                                    ].join(' ');
                                }
                            });
                        }

                        function codeAddress(id) {
                            geocoder = new google.maps.Geocoder();
                            if (id == 1) {
                                var address = document.getElementById("my-address").value;
                                geocoder.geocode({'address': address}, function (results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {

                                        document.getElementById('latitude').value = results[0].geometry.location.lat();
                                        document.getElementById('longitude').value = results[0].geometry.location.lng();
                                        initialize_map(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                                    }

                                    else {
                                        //alert("Geocode was not successful for the following reason: " + status);
                                    }
                                });
                            } else if (id == 2) {
                                var address = document.getElementById("my-dest").value;
                                geocoder.geocode({'address': address}, function (results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        document.getElementById('d_latitude').value = results[0].geometry.location.lat();
                                        document.getElementById('d_longitude').value = results[0].geometry.location.lng();
                                        init_map(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                                    }

                                    else {
                                        //alert("Geocode was not successful for the following reason: " + status);
                                    }
                                });
                            }
                        }
                        google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script type="text/javascript">
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successFunction);
    } else {
        alert('It seems like Geolocation, which is required for this page, is not enabled in your browser. Please use a browser which supports it.');
    }
    function successFunction(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        initialize_map(lat, lng);
        init_map(lat, lng);
    }
</script>
<script type="text/javascript">

// provider manual automatic toggle script 

    $(document).ready(function () {
        var latitude = $("#latitude").val();
        var longitude = $("#longitude").val();
        var selection = $('#selection').val();

        $("#flow4").change(function () {
            initialize_map($("#latitude").val(), $("#longitude").val());
        });
        $("#flow4").change(function () {
            if (selection == '2') {

                var type = $(this).val();

                var dataString = 'longitude=' + $("#longitude").val() + '&latitude=' + $("#latitude").val() + '&type=' + type;
                $.ajax({
                    type: "POST",
                    url: "<?php echo URL::Route('nearby') ?>",
                    data: dataString,
                    success: function (res) {
                        $('#provider').empty();
                        $('#provider').fadeIn(300);
                        $('#provider').append("<option value=''>--Select Provider--</option>");
                        $('#provider').append(res);
                    }
                });
            }
            return false;
        });

        $('#request-form').submit(function () {
            if (selection == '2') {
                var provider_value = $('#provider').val();
                var type_value = $('#flow4').val();
                if (type_value == '' || provider_value == '') {
                    $('#msg').empty();
                    var msg = '<div class="alert alert-danger"><b>Please select a Type and provider</b></div>';
                    $('#msg').append(msg);
                    return false;
                } else {
                    $('#request-form').attr('action', "<?php echo route('adminmanualrequest') ?>");
                    $('#request-form').attr('method', "post");
                    return true;
                }
            }
            return false;

        });

    });



</script>

<script type="text/javascript">

    // destination map script

    function init_map(lat, lng) {
        var mapOptions = {
            center: {lat: 12.3443, lng: 77.2342},
            zoom: 14
        };
        var map = new google.maps.Map(document.getElementById('map-dest'),
                mapOptions);
        var myLatlng = new google.maps.LatLng(lat, lng);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'You!',
            draggable: true,
        });

        var infowindow = new google.maps.InfoWindow({
            content: "Mark your Destination"
        });
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
        infowindow.open(map, marker);


        google.maps.event.addListener(marker, 'dragend', function () {
            // updating the marker position
            var latLng = marker.getPosition();
            var geocoder = new google.maps.Geocoder();
            document.getElementById("d_latitude").value = latLng.lat();
            document.getElementById("d_longitude").value = latLng.lng();

            var latlngplace = new google.maps.LatLng(latLng.lat(), latLng.lng());
            geocoder.geocode({'latLng': latlngplace}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        document.getElementById("my-dest").value = results[1].formatted_address;
                    } else {
                        alert('No Address Found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });

        });



    }
</script>

<script>
    // source map script
    var gmarkers = [];

    function initialize_map(lat, lng) {
        $("#request-form").show();
        latitude = parseFloat(lat);
        longitude = parseFloat(lng);


        var myLatlng = new google.maps.LatLng(latitude, longitude);
        var mapOptions = {
            zoom: 14,
            center: myLatlng
        }

        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);


        var marker_you = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'You!',
            draggable: true,
        });


        var latitude = $("#latitude").val();
        var longitude = $("#longitude").val();
        var data = 'longitude=' + lng + '&latitude=' + lat + '&type=' + $("#flow4").val();
        console.log(data)
        $.ajax({
            type: "GET",
            url: "<?php echo URL::Route('/find') ?>",
            data: data,
            success: function (response) {

                console.log(response)
                if (response.success) {
                    for (i = 0; i < response.inc; i++) {
                        var marker_ll = new google.maps.LatLng(response.walker[i][2], response.walker[i][3]);
                        var result = new google.maps.Marker({
                            position: marker_ll,
                            map: map,
                            icon: '<?php echo asset_url() . "/web/images/map_uberx.png"; ?>',
                            title: response.walker[i][1],
                        });
                        gmarkers.push(result);
                    }
                }
            }
        });


        google.maps.event.addListener(marker_you, 'dragend', function () {
            // updating the marker position
            var latLng = marker_you.getPosition();
            var geocoder = new google.maps.Geocoder();
            document.getElementById("latitude").value = latLng.lat();
            document.getElementById("longitude").value = latLng.lng();


            $("#flow4").trigger("change");

            for (var i = 0; i < gmarkers.length; i++) {
                gmarkers[i].setMap(null);
            }

            var latitude = latLng.lat();
            var longitude = latLng.lng();
            gmarkers = [];

            var data = 'longitude=' + longitude + '&latitude=' + latitude + '&type=' + $("#flow4").val();

            $.ajax({
                type: "GET",
                url: "<?php echo URL::Route('/find') ?>",
                data: data,
                success: function (response) {

                    if (response.success) {

                        for (i = 0; i < response.inc; i++) {
                            var marker_ll = new google.maps.LatLng(response.walker[i][2], response.walker[i][3]);
                            var result = new google.maps.Marker({
                                position: marker_ll,
                                map: map,
                                icon: '<?php echo asset_url() . "/web/images/map_uberx.png"; ?>',
                                title: response.walker[i][1],
                            });
                            gmarkers.push(result);

                        }
                    }
                }
            });

            var latlngplace = new google.maps.LatLng(latLng.lat(), latLng.lng());
            geocoder.geocode({'latLng': latlngplace}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        document.getElementById("my-address").value = results[1].formatted_address;
                    } else {
                        alert('No Address Found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });

        });

    }

</script>
@stop