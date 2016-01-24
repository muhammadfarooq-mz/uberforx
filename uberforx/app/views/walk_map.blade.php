@extends('layout')

@section('content')

<script src="https://bitbucket.org/pellepim/jstimezonedetect/downloads/jstz-1.0.4.min.js"></script>
<script src="http://momentjs.com/downloads/moment.min.js"></script>
<script src="http://momentjs.com/downloads/moment-timezone-with-data.min.js"></script> 

<div class="box box-success">
    <div id="map" style="height:600px;width:100%;"></div>
</div>
<div class="box box-info tbl-box">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>Request ID</th>
                <th>{{ trans('customize.Provider') }} Name</th>
                <th>{{ trans('customize.User') }} Name</th>
                <th>Date/Time</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Payment Mode</th>
                <th>Payment Status</th>
                <th>Response</th>
            </tr>
            <?php $i = 0; ?>
            <?php foreach ($request_meta as $meta) { ?>
                <tr>
                    <td><?= $walk_id ?></td>
                    <td><?= $meta->first_name ?> <?= $meta->last_name ?></td>
                    <td><?= $owner_name ?></td>
                    <td id= 'time<?php echo $i; ?>' >
                        <script>
        var timezone = jstz.determine();
        // console.log(timezone.name());
        var timevar = moment.utc("<?php echo $start_time; ?>");
        timevar.toDate();
        timevar.tz(timezone.name());
        // console.log(timevar);
        document.getElementById("time<?php echo $i; ?>").innerHTML = timevar;
    <?php $i++; ?>
                        </script>
                    </td>
                    <td><?= $status1 ?></td>
                    <td><?= Config::get('app.generic_keywords.Currency') . " " . sprintf2($amount, 2) ?></td>
                    <td><?= $pay_mode ?></td>
                    <td><?= $pay_status ?></td>
                    <td>
                        <?php
                        if ($meta->status == 0) {
                            echo "<span class='badge bg-yellow'>In Queue</span>";
                        } elseif ($meta->status == 1) {
                            echo "<span class='badge bg-green'>Accepted</span>";
                        } elseif ($meta->status == 3) {
                            echo "<span class='badge bg-red'>Rejected</span>";
                        } else {
                            echo "<span class='badge bg-red'>No Response</span>";
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp" type="text/javascript"></script>
<script type="text/javascript">

        var map = null;
        var infowindow = new google.maps.InfoWindow();
        var bounds = new google.maps.LatLngBounds();
        var customIcons = {
        restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
                shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
        },
                bar: {
                icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
                        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                client: {
                icon: '<?php echo asset_url(); ?>/image/start_pin_flag.png',
                        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                client_stop: {
                icon: '<?php echo asset_url(); ?>/image/end_pin_flag.png',
                        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                driver: {
                icon: '<?php echo asset_url(); ?>/image/driver-70.png',
                        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                }
        };
        var markers1 = [
        {
        "lat": <?php echo $owner_latitude; ?>,
                "lng": <?php echo $owner_longitude; ?>,
        },
<?php if ($status != 'Provider Not Confirmed') { ?>
            {
            "lat": <?php echo $walker_latitude; ?>,
                    "lng": <?php echo $walker_longitude; ?>,
            }
<?php } ?>
        ];
        function load() {
        var mapOptions = {
        center: new google.maps.LatLng(
                parseFloat(markers1[0].lat),
                parseFloat(markers1[0].lng)),
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP
        };
                var path = new google.maps.MVCArray();
                var service = new google.maps.DirectionsService();
                var infoWindow = new google.maps.InfoWindow();
                map = new google.maps.Map(document.getElementById("map"), mapOptions);
                var poly = new google.maps.Polyline({
                map: map,
                        strokeColor: '#F3443C'
                });
                var lat_lng = new Array();
                /* path.push(new google.maps.LatLng(parseFloat(markers1[0].lat),
                 parseFloat(markers1[0].lng)));
                 */
                var start_icon = customIcons['client'] || {};
                var stop_icon = customIcons['client_stop'] || {};
                var marker = new google.maps.Marker({
                position: map.getCenter(),
                        map: map,
                        icon: start_icon.icon,
                        shadow: start_icon.shadow,
                        draggable: false
                });
                bounds.extend(marker.getPosition());
                google.maps.event.addListener(marker, "click", function () {
                infowindow.setContent("<p><b>User </b><br/>Walk ID : <?php echo $walk_id; ?><br/>Name :  <?php echo $owner_name; ?><br/>Phone :  <?php echo $owner_phone; ?><br/>Status :  <span style='color:red'><?php echo $status; ?></span></p>");
                        infowindow.open(map, marker);
                });
                for (var i = 0; i < markers1.length; i++) {
        if ((i + 1) < markers1.length) {
        var src = new google.maps.LatLng(parseFloat(markers1[i].lat),
                parseFloat(markers1[i].lng));
                var smarker = new google.maps.Marker({position: src, draggable: false, icon: start_icon.icon, shadow: start_icon.shadow});
                bounds.extend(smarker.getPosition());
                google.maps.event.addListener(smarker, "click", function () {
                infowindow.setContent("<p><b>User </b><br/>Walk ID : <?php echo $walk_id; ?><br/>Name :  <?php echo $owner_name; ?><br/>Phone :  <?php echo $owner_phone; ?><br/>Status :  <span style='color:red'><?php echo $status; ?></span></p>");
                        infowindow.open(map, smarker);
                });
                var des = new google.maps.LatLng(parseFloat(markers1[i + 1].lat),
                        parseFloat(markers1[i + 1].lng));
                var dmarker = new google.maps.Marker({position: des, map: map, draggable: false, icon: stop_icon.icon, shadow: stop_icon.shadow});
                bounds.extend(dmarker.getPosition());
                google.maps.event.addListener(dmarker, "click", function () {
                infowindow.setContent("<p><b>Provider </b><br/>Walk ID :  <?php echo $walk_id; ?><br/>Name :  <?php echo $walker_name; ?><br/>Phone :  <?php echo $walker_phone; ?><br/>Status :  <span style='color:red'><?php echo $status; ?></span></p>");
                        infowindow.open(map, dmarker);
                });
                map.fitBounds(bounds);
                //  poly.setPath(path);

<?php if ($is_started == 1) { ?>
            var flightPlanCoordinates = [
    <?php
    $lat_long_count = 0;
    foreach ($full_walk as $ful_wlk) {
        $lat_long_count++;
    }
    $chk_count = 0;
    foreach ($full_walk as $ful_wlk1) {
        $chk_count++;
        ?>

        <?php if ($chk_count == $lat_long_count) { ?>
                    new google.maps.LatLng(<?php echo $ful_wlk1->latitude; ?>, <?php echo $ful_wlk1->longitude; ?>)
        <?php } else { ?>
                    new google.maps.LatLng(<?php echo $ful_wlk1->latitude; ?>, <?php echo $ful_wlk1->longitude; ?>),
        <?php } ?>

    <?php } ?>
            ];
                    var flightPath = new google.maps.Polyline({
                    path: flightPlanCoordinates,
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 2
                    });
                    flightPath.setMap(map);
                    /*service.route({
                     origin: src,
                     destination: des,
                     travelMode: google.maps.DirectionsTravelMode.DRIVING
                     }, function (result, status) {
                     if (status == google.maps.DirectionsStatus.OK) {
                     for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                     path.push(result.routes[0].overview_path[i]);
                     }
                     poly.setPath(path);
                     map.fitBounds(bounds);
                     }
                     });*/
<?php } ?>


        }
        }
        var legendDiv = document.createElement('DIV');
                var legend = new Legend(legendDiv, map);
                legendDiv.index = 1;
                map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legendDiv);
        }
function Legend(controlDiv, map) {
// Set CSS styles for the DIV containing the control
// Setting padding to 5 px will offset the control
// from the edge of the map
controlDiv.style.padding = '5px';
        // Set CSS for the control border
        var controlUI = document.createElement('DIV');
        controlUI.style.backgroundColor = 'white';
        controlUI.style.borderStyle = 'solid';
        controlUI.style.borderWidth = '1px';
        controlUI.title = 'Legend';
        controlDiv.appendChild(controlUI);
        // Set CSS for the control text
        var controlText = document.createElement('DIV');
        controlText.style.fontFamily = 'Arial,sans-serif';
        controlText.style.fontSize = '12px';
        controlText.style.paddingLeft = '4px';
        controlText.style.paddingRight = '4px';
        // Add the text
        controlText.innerHTML = '<b>Legends</b><br />' +
        '<img src="<?php echo asset_url(); ?>/image/start_pin_flag.png" style="height:25px;"/> <?= trans('customize.User') . ' Start' ?><br />' +
        '<img src="<?php echo asset_url(); ?>/image/end_pin_flag.png" style="height:25px;"/> <?= trans('customize.Provider') . ' End' ?><br />' +
        '<small>*Data is fictional</small>';
        controlUI.appendChild(controlText);
        }
google.maps.event.addDomListener(window, 'load', load);


</script>

@stop