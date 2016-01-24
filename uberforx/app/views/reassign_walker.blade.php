@extends('layout')

@section('content')

<div class="row third-portion">
    <div class="tab-content">
        <div class="tab-pane fade" id="option3">
            <div class="row tab-content-caption">
                <div class="container">
                    <div class="col-md-10 big-text">
                        <p><?= $title ?></p>
                    </div>
                    
                </div>
            </div>
            
            <div class="row editable-content-div">
                <div class="container">
                   <div id="map"></div>
                </div>
            </div>
            <!--</form>-->
        </div>
    </div>
</div>


 <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">

var popup_pin = "";
var markersArray = [];
var newmarkersArray = [];
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
        icon: '<?php echo asset_url(); ?>/image/client-70.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
    },
    client_no_pay: {
        icon: '<?php echo asset_url(); ?>/image/client-red.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
    },
    client_pay_done: {
        icon: '<?php echo asset_url(); ?>/image/client_pay_done.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
    },
    driver: {
        icon: '<?php echo asset_url(); ?>/image/driver-70.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
    }
};


            function load() {

                var map = new google.maps.Map(document.getElementById("map"),
                {     center: new google.maps.LatLng(34.102475, -118.35401),
                zoom: 15,     mapTypeId: 'roadmap' }); 

                var infoWindow = new
                google.maps.InfoWindow; 
                (function() {     var f = function() {
                var marker = new google.maps.Marker();
                downloadUrl("<?php echo web_url(); ?>/admin/walk/alternative_walkers_xml/<?php echo $walk_id; ?>",
                function(data) {             
                var xml = data.responseXML;
                var markers = xml.documentElement.getElementsByTagName("marker");
                popup_pin = "";             
                for (var i = 0; i < markers.length; i++) { 
                var name = markers[i].getAttribute("name"); 
                var client_name = markers[i].getAttribute("client_name"); 
                var contact = markers[i].getAttribute("contact");
                var amount = markers[i].getAttribute("amount"); 
                var type = markers[i].getAttribute("type"); 
                var id = markers[i].getAttribute("id"); 
                var point = new google.maps.LatLng(
                parseFloat(markers[i].getAttribute("lat")),
                parseFloat(markers[i].getAttribute("lng"))); 
                var html = ""; 
                if(type == 'client_pay_done') {     
                html = "client_name </b></p><p><span class='icon-phone' style=''></span><span style='margin-left:5px;'>" + contact +"</span></p><p>Current Walker <br> " + name + "</p>"; 
                } else if (type == 'client') {
                html = "<form method='post' action='<?php echo web_url() ?>/admin/walk/change_walker'><p><b>" + client_name + "</b></p><p><span class ='icon-phone' style=''></span><span style='margin-left:5px;'>" + contact + "</span></p><p>Request id : " + name + "</p><p><select name='type'><option value='1'>Only This Walk</option><option value='2'>All Walks in Schedule</option></select><input type='hidden' name='walk_id' value='<?php echo $walk_id; ?>'/><input type='hidden' name='walker_id' value='"+id+"'/></p><b><button class='btn btn-sm btn-danger' name='name' value='" + name + "' type='submit'>Reassign</button></b><br/></form>"; 
                } else {     
                html = client_name + "</b></p><p><span class='icon-phone' style=''></span><span style='margin-left:5px;'>" + contact + "</span></p><p>Request id : " + name + "</p><b>"; 
                } 
                
                var icon = customIcons[type] || {}; marker =
                new google.maps.Marker({     map: map,     position: point,
                icon: icon.icon,     shadow: icon.shadow });
                newmarkersArray.push(marker); bindInfoWindow(marker, map,
                infoWindow, html, type, name, popup_pin);             }
                clearOverlays(markersArray);             markersArray =
                newmarkersArray;             newmarkersArray = [];         });
                };     window.setInterval(f, 15000);     f();
                    
                    var legendDiv = document.createElement('DIV');
                    var legend = new Legend(legendDiv, map);
                    legendDiv.index = 1;
                    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legendDiv);
                    
                })();
            }


            function clearOverlays(arr) {
                for (var i = 0; i < arr.length; i++) {
                    arr[i].setMap(null);
                }
            }

            function bindInfoWindow(marker, map, infoWindow, html, type, name, popup_pin) {
                if (name == popup_pin) {
                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                    popup_pin = "";
                }
                google.maps.event.addListener(marker, 'click', function() {
                    if (type == 'client') {
                        infoWindow.setContent(html);
                        infoWindow.open(map, marker);
                    } else if (type == 'client_pay_done') {
                        infoWindow.setContent(html);
                        infoWindow.open(map, marker);
                    } else {
                        alert("Sorry, This walker is Busy now...");
                    }
                });
            }

            function downloadUrl(url, callback) {
                var request = window.ActiveXObject ?
                        new ActiveXObject('Microsoft.XMLHTTP') :
                        new XMLHttpRequest;
                request.onreadystatechange = function() {
                    if (request.readyState == 4) {
                        request.onreadystatechange = doNothing;
                        callback(request, request.status);
                    }
                };
                request.open('GET', url, true);
                request.send(null);
            }


            function doNothing() {
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
                        '<img src="<?php echo asset_url(); ?>/image/client-70.png" style="height:25px;"/> Available Walkers<br />' +
                        '<img src="<?php echo asset_url(); ?>/image/client-red.png" style="height:25px;"/> Busy Walkers<br />' +
                        '<img src="<?php echo asset_url(); ?>/image/driver-70.png" style="height:25px;"/>Current Walker<br />' +
                        '<img src="<?php echo asset_url(); ?>/image/client_pay_done.png" style="height:25px;"/> Dog Owner<br />' +
                        '<small>*Data is fictional</small>';

                controlUI.appendChild(controlText);
            }

           
        </script>

@stop