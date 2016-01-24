@extends('web.layout')

@section('content')

<div class="col-md-12 mt">

    @if(Session::has('message'))
    <div class="alert alert-{{ Session::get('type') }}">
        <b>{{ Session::get('message') }}</b> 
    </div>

    @endif
    <div id='msg'></div>   
    <div class="content-panel">
        <div class="row">
            <h4>Request {{trans('customize.Trip')}}</h4><br>
            <div class="col-md-11">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="my_address" id="my-address" placeholder="Please enter Source address" style="margin-bottom:10px;width:65%;float:left;" onblur="codeAddress(1);">
                        <button id="getCords" class="btn btn-success pull-right" onClick="codeAddress(1);">Find Location</button>

                        <div id="map-canvas" style="width:100%;"></div>
                    </div>

                    <div class="col-md-6" id='destination' style="display:none">
                        <input type="text" class="form-control" name="my_address" id="my-dest"  placeholder="Please enter Destination address" style="margin-bottom:10px;width:65%;float:left;" onblur="codeAddress(2);">
                        <button id="getCords" class="btn btn-success pull-right" onClick="codeAddress(2);">Find Location</button>

                        <div id="map-dest" style="width:100%;height:300px;"></div>
                    </div>

                </div>


                <form  id="request-form" style="display:none;">
                    <div class="form-group">
                        <br>
                        <div class="col-sm-12">
                            <label class="col-sm-12 col-sm-12 control-label">Type of Service</label>
                        </div>
                        <br>
                        <select name="type" class="form-control" id="flow4">
                            <option value=''>--Select Type--</option>
                            <?php foreach ($types as $type) { ?>
                                <option value="<?= $type->id ?>"><?= $type->name ?></option>
                            <?php } ?>
                        </select>
                        <br>
                        <select id="provider" class="form-control" name='provider' style="display:none">

                        </select>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="col-sm-12 col-sm-12 control-label">How would you like to pay</label>
                        </div>
                        <br>
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
                        <br>
                    </div>
                    <div class="form-group">
                        <?php
                        $promosett = Settings::where('key', 'promo_code')->first();
                        if ($promosett->value == 1) {
                            ?>
                            <input type="text" class="form-control" name="promo_code" id="promo_code" placeholder="Promo Code">
<?php } ?>
                    </div>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="dest" id="destination_req" value='{{$destination}}'>
                    <input type="hidden" name="d_latitude" id="d_latitude">
                    <input type="hidden" name="d_longitude" id="d_longitude">
                    <input type="hidden" name="selection" id="selection" value='{{$selection}}'>
                    <?php if ($destination == 1) { ?>
                        <input type="button" class="btn btn-primary" value="Calculate Estimated Fare" href="{{ route('userrequestFare') }}" id="fare">
<?php } ?>
                    <input type="submit" class="btn btn-primary" value="Request Trip" id="flow5">
                    <br>

                    </div>
                </form>
                <div class="form-group">
                    <div class="col-sm-12" id="farediv" >
                        <label><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Estimated {{trans('customize.Trip')}} Fare :</label><label id="faretoatal"></label>
                    </div></div>
            </div>
        </div>
    </div>




</div>

<script type="text/javascript">

// provider manual automatic toggle script 

    $(document).ready(function () {
        var latitude = $("#latitude").val();
        var longitude = $("#longitude").val();
        var selection = $('#selection').val();
        var destination = $('#destination_req').val();

        if (destination == 1) {
            $('#destination').show();
        }

        $("#flow4").change(function () {
            initialize_map($("#latitude").val(), $("#longitude").val());
        });
        $("#flow4").change(function () {
            if (selection == '2') {

                var type = $(this).val();

                var dataString = 'longitude=' + $("#longitude").val() + '&latitude=' + $("#latitude").val() + '&type=' + type;
                console.log(dataString)
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
                    var msg = '<div class="alert alert-danger"><b>Please select a Type and provider</b></div';
                    $('#msg').append(msg);
                    return false;
                } else {
                    $('#request-form').attr('action', "<?php echo route('manualrequest') ?>");
                    $('#request-form').attr('method', "post");
                    return true;
                }
            }
            if ($('#selection').val() == 1 || $('#selection').val() == '')
            {
                $('#request-form').attr('action', "<?php echo route('userrequesttrips') ?>");
                $('#request-form').attr('method', "post");
                return true;
            }

        });


    });

    $("#farediv").hide();

    $('#fare').click(function () {
        $("#farediv").show();
        //$("#fare").attr("href");
        var url = $("#fare").attr("href");
        var formdata = $("#request-form").serialize();
        console.log(formdata)
        $.ajax(url, {
            data: formdata,
            type: "GET",
            success: function (response) {
                if (response.success)
                {
                    $("#faretoatal").html(response.total)
                }
                else
                {
                    $("#test1").html("something went wrong");
                }
            }
        });

    });

</script>

<script type="text/javascript">

    // destination map script

    function init_map(lati, lngi) {
        var mapOptions = {
            center: {lat: lati, lng: lngi},
            zoom: 16,
            scrollwheel: false,
        };
        var map = new google.maps.Map(document.getElementById('map-dest'),
                mapOptions);
        var myLatlng = new google.maps.LatLng(lati, lngi);
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'You!',
            animation: google.maps.Animation.DROP,
            draggable: true,
        });

        var infowindow = new google.maps.InfoWindow({
            content: "Mark your Destination"
        });
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
            if (marker.getAnimation() != null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        });
        infowindow.open(map, marker);



        google.maps.event.addListener(marker, 'dragend', function () {
            // updating the marker position
            var latLng2 = marker.getPosition();
            var geocoder = new google.maps.Geocoder();
            document.getElementById("d_latitude").value = latLng2.lat();
            document.getElementById("d_longitude").value = latLng2.lng();

            var latlngplace = new google.maps.LatLng(latLng2.lat(), latLng2.lng());
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
    google.maps.event.addDomListener(window, 'load', init_map);

</script>

<script>
    // source map script
    var gmarkers = [];

    function initialize_map(lat, lng) {
        $("#request-form").show();
        latitude = parseFloat(lat);
        longitude = parseFloat(lng);
        var marker_icon = '<?php echo asset_url() . "/web/images/map_uberx.png"; ?>';

        var myLatlng = new google.maps.LatLng(latitude, longitude);
        var mapOptions = {
            zoom: 16,
            center: myLatlng,
            scrollwheel: false,
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
                            icon: marker_icon,
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
                                icon: marker_icon,
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


<!--script for this page-->
<script type="text/javascript">
    var tour = new Tour(
            {
                name: "userapprequest",
            });

    // Add your steps. Not too many, you don't really want to get your users sleepy
    tour.addSteps([
        {
            element: "#flow2",
            title: "Choosing address",
            content: "Please Enter your address and click on find location",
        },
        {
            element: "#flow3",
            title: "Adjust location",
            content: "You can move the marker to adjust your pick up location"
        },
        {
            element: "#flow4",
            title: "Choosing Type of service",
            content: "You can select the type of service in the drop down"
        },
        {
            element: "#flow5",
            title: "Requesting a  request",
            content: "Now click on request {{trans('customize.Trip')}} to request your first {{trans('customize.Trip')}}",
        },
    ]);

    // Initialize the tour
    tour.init();

    // Start the tour
    tour.start();
</script>



@stop 