
@extends('layout')

@section('content')

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
          google.maps.event.addListener(autocomplete, 'place_changed', function() {
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
    }

    function codeAddress(id) {
        geocoder = new google.maps.Geocoder();
            if(id==1){
            var address = document.getElementById("my-address").value;
            geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {

            document.getElementById('latitude').value = results[0].geometry.location.lat();
            document.getElementById('longitude').value = results[0].geometry.location.lng();
            initialize_map(results[0].geometry.location.lat(),results[0].geometry.location.lng());
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
            initialize_map(lat,lng);
        }

</script>

<script>
 // source map script
     var gmarkers = [];
      
      function initialize_map(lat,lng) {
  
        latitude = parseFloat(lat);
        longitude = parseFloat(lng);
          var marker_icon = '<?php echo asset_url()."/web/images/map_uberx.png"; ?>';

        var myLatlng = new google.maps.LatLng(latitude,longitude);
        var mapOptions = {
          zoom: 16,
          center: myLatlng
        }

        var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);


        var marker_you = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'You!',
            draggable: true,
        });

        google.maps.event.addListener(marker_you, 'dragend', function() {
            // updating the marker position
            var latLng = marker_you.getPosition();
            var geocoder= new google.maps.Geocoder();
            document.getElementById("latitude").value =latLng.lat();
            document.getElementById("longitude").value =latLng.lng();
            

            var latlngplace = new google.maps.LatLng(latLng.lat(), latLng.lng());
              geocoder.geocode({'latLng': latlngplace}, function(results, status){
              if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
            document.getElementById("my-address").value =results[1].formatted_address;                          
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

<div class="box box-success">
<br/>
                    @if (Session::has('msg'))
                    <h4 class="alert alert-info">
                    {{{ Session::get('msg') }}}
                    {{{Session::put('msg',NULL)}}}
                    </h4>
                   @endif
                    <br/>
                    <div class="box-body ">
            <form method="post" action="{{ URL::Route('AdminAdminsUpdate') }}"  enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $admin->id ?>">
           <div class="form-group">
                      <label>User Name</label>
                          <input class="form-control" type="text" name="username" value="{{$admin->username}}">
                      </div>
                      <div class="form-group">
                          <label>Old Password</label>
                          <input class="form-control" type="password" name="old_password" placeholder="Old Password">
                      </div>
                      <div class="form-group">
                          <label>New Password</label>
                          <input type="password" class="form-control" name="new_password" placeholder="New Password">
                      </div>
                      <div class="form-group">
                        <label>Address</label><br>
                      <input type="text" class="form-control" name="my_address" id="my-address" placeholder="Please enter address" style="width:40%;float:left;margin-right:20px;">
              <input type="button" id="getCords" class="btn btn-success" style="float:center;" value="Find Location" onClick="codeAddress(1);"></input>
              <br>
              <br>
               
              <div id="map-canvas" style="width:100%;"></div>
              <input type="hidden" name="latitude" id="latitude">
              <input type="hidden" name="longitude" id="longitude">
                </div>

                </div>
                <div class="box-footer">
                                  
                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success">Update Changes</button>                       
                </div>
                </form>
                </div>
                     

<?php
if($success == 1) { ?>
<script type="text/javascript">
    alert('Service Updated Successfully');
</script>
<?php } ?>
<?php
if($success == 2) { ?>
<script type="text/javascript">
    alert('Sorry Something went Wrong');
</script>
<?php } ?>
@stop