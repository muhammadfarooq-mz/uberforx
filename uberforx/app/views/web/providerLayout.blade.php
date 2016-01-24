<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Dashboard">
        <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

        <title>{{Config::get('app.website_title')}}</title>


        <?php
        $theme = Theme::all();
        $active = '#000066';
        $logo = '/image/logo.png';
        $favicon = '/image/favicon.ico';
        foreach ($theme as $themes) {
            $active = $themes->active_color;
            $favicon = '/uploads/' . $themes->favicon;
            $logo = '/uploads/' . $themes->logo;
        }
        if ($logo == '/uploads/') {
            $logo = '/image/logo.png';
        }
        if ($favicon == '/uploads/') {
            $favicon = '/image/favicon.ico';
        }
        ?>


        <link rel="icon" type="image/ico" href="<?php echo asset_url(); ?><?php echo $favicon; ?>">
        <!-- Bootstrap core CSS -->
        <link href="<?php echo asset_url(); ?>/web/css/bootstrap.css" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo asset_url(); ?>/web/font-awesome/css/font-awesome.css" rel="stylesheet" />

        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <link href="<?php echo asset_url(); ?>/web/css/style.css" rel="stylesheet">
        <link href="<?php echo asset_url(); ?>/web/css/style-responsive.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>/web/js/gritter/css/jquery.gritter.css" />


        <script src="<?php echo asset_url(); ?>/web/js/jquery.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/bootstrap.min.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/bootstrap-tour.js"></script>

        <style>
            #map-canvas {
                height: 300px;
                width: 500px;
                margin: 0px;
                padding: 0px
            }
        </style>

        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
        <script type="text/javascript">
function initialize_map(lat, lng) {

    latitude = parseFloat(lat);
    longitude = parseFloat(lng);
    var myLatlng = new google.maps.LatLng(latitude, longitude);
    var mapOptions = {
        zoom: 14,
        center: myLatlng,
        scrollwheel: false,
    }
    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: 'Hello World!',
        draggable: false,
    });


}

        </script>

        <script type="text/javascript">
            function get_destination_address(lati, longi) {
                geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(lati, longi);
                geocoder.geocode({'latLng': latlng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {

                            document.getElementById('destination_address').value = results[1].formatted_address;

                        } else {
                            alert('No results found');
                        }
                    } else {
                        alert('Geocoder failed due to: ' + status);
                    }
                });
            }


        </script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
            #trip-table tr td {
                padding-top:20px;
                padding-bottom:20px;
                cursor: pointer;
                cursor: hand;
            }
            .trip-detail td {
                padding-left:20px;
                cursor: pointer;
                cursor: hand;
            }

            #trip-map{
                padding-left:20px;
            }

            #fare-table tr td {
                padding-top:3px;
                padding-bottom:3px;
                padding-right:20px;
            }

            .content-panel{
                padding-left: 20px;
                padding-top: 20px;
            }
        </style>
        <script src="<?php echo asset_url(); ?>/web/js/validation.js"></script>
    </head>

    <body>

        <section id="container" >
            <!-- **********************************************************************************************************************************************************
            TOP BAR CONTENT & NOTIFICATIONS
            *********************************************************************************************************************************************************** -->
            <!--header start-->
            <header class="header black-bg">
                <div class="sidebar-toggle-box">
                    <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
                </div>
                <!--logo start-->

                <a href="{{ URL::Route('ProviderTrips') }}" class="logo"><b><?php
                        $siteTitle = Config::get('app.website_title');
                        echo $siteTitle;
                        ?></b></a>

                <!--logo end-->
                <div class="nav notify-row" id="top_menu">
                    <!--  notification start -->

                    <!--  notification end -->
                </div>
                <div class="top-menu">
                    <ul class="nav pull-right top-menu">
                        <li><a class="logout" href="{{ URL::Route('ProviderLogout') }}">{{trans('customize.log_out'); }}</a></li>
                    </ul>
                </div>
            </header>
            <!--header end-->

            <!-- **********************************************************************************************************************************************************
            MAIN SIDEBAR MENU
            *********************************************************************************************************************************************************** -->
            <!--sidebar start-->
            <aside>
                <div id="sidebar"  class="nav-collapse ">
                    <!-- sidebar menu start-->
                    <ul class="sidebar-menu" id="nav-accordion">

                        <p class="centered"><a href="{{ URL::Route('ProviderTrips') }}"><img src="<?= Session::get('walker_pic') ? Session::get('walker_pic') : asset_url() . '/web/default_profile.png' ?>" class="img-circle" width="60"></a></p>
                        <h5 class="centered">{{ Session::get('walker_name') }}</h5>
                        <?php if (Session::get('is_approved') == 1) { ?>
                            <p  class="centered"><a href="{{ URL::Route('providerDocuments') }}"><span class="label label-success">Approved</span></a></p>
                        <?php } else { ?>
                            <p  class="centered"><a href="{{ URL::Route('providerDocuments') }}"><span class="label label-danger">Un Approved</span></a></p>
                        <?php } ?>

                        <li class="mt">
                            <a href="{{ URL::Route('ProviderTrips') }}">
                                <i class="fa fa-car"></i>
                                <span>My {{trans('customize.Trip')}}s</span>
                            </a>
                        </li>

                        <li class="" id="flow21">

                            <a href="{{ URL::Route('providerProfile') }}">
                                <i class="fa fa-user"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ URL::Route('providerDocuments') }}">
                                <i class="fa fa-file"></i>
                                <span>Documents</span>
                            </a>
                        </li>

                        <li class="">

                            <a href="{{ URL::Route('providerTripInProgress') }}">
                                <i class="fa fa-arrow-right"></i>
                                <span>On Going {{trans('customize.Trip')}}s</span>
                            </a>
                        </li>

                        <?php
                        /* $setting = Settings::where('key', 'allow_calendar')->first();
                          if ($setting->value == 1) { */
                        ?>
                        <!--<li class="">
                            <a href="{{route('ProviderAvail')}}">
                                <i class="fa fa-money"></i>
                                <span>Calendar</span>
                            </a>
                        </li>-->
                        <?php /* } */ ?>

                        <li class="">
                            <a href="{{ URL::Route('ProviderLogout') }}">
                                <i class="fa fa-power-off"></i>
                                <span>{{trans('customize.log_out'); }}</span>
                            </a>
                        </li>

                    </ul>

                    <!-- sidebar menu end-->
                </div>
            </aside>
            <!--sidebar end-->

            <!-- **********************************************************************************************************************************************************
            MAIN CONTENT
            *********************************************************************************************************************************************************** -->
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper site-min-height">
                    <h3><i class="fa fa-angle-right"></i> {{ $title }}</h3>
                    <div class="row mt">
                        <div class="col-lg-12">
                            @yield('content')

                        </div>
                    </div>

                </section>
            </section><!-- /MAIN CONTENT -->

            <!--main content end-->
            <!--footer start-->
            <footer class="site-footer">
                <div class="text-center">
                    2014 - Alvarez.is
                    <a href="#" class="go-top">
                        <i class="fa fa-angle-up"></i>
                    </a>
                </div>
            </footer>
            <!--footer end-->
        </section>


        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">New {{trans('customize.Trip')}} Request</h4>
                    </div>
                    <div class="modal-body">
                        <p>You got a new {{trans('customize.Trip')}} request. Please respond quickly&hellip;</p>
                        <div style="position:relative;float:left">
                            <img id="owner-image" src="" class="img-circle" width="60px">
                        </div>
                        <div style="position:relative;float:left;left:20px;">
                            <b id="owner-name"></b><br>
                            <i>Rating - </i><b id="owner-rating"> 3 </b><b>/ 5</b>

                        </div>
                        <div>
                            <img id="request-map" src="">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="" id="accept-url"><button type="button" id="flow23" class="btn btn-success">Accept</button></a>
                        <a href="" id="decline-url"><button type="button" class="btn btn-danger">Reject</button></a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- js placed at the end of the document so the pages load faster -->



        <script src="<?php echo asset_url(); ?>/web/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/jquery.ui.touch-punch.min.js"></script>
        <script class="include" type="text/javascript" src="<?php echo asset_url(); ?>/web/js/jquery.dcjqaccordion.2.7.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/jquery.nicescroll.js" type="text/javascript"></script>


        <!--common script for all pages-->
        <script src="<?php echo asset_url(); ?>/web/js/common-scripts.js"></script>

        <!--script for this page-->

        <script type="text/javascript" src="<?php echo asset_url(); ?>/web/js/gritter/js/jquery.gritter.js"></script>

        <script type="text/javascript">

            function notify(title, message, image_url) {
                var Gritter = function () {

                    var unique_id = $.gritter.add({
                        // (string | mandatory) the heading of the notification
                        title: title,
                        // (string | mandatory) the text inside the notification
                        text: message,
                        // (string | optional) the image to display on the left
                        image: image_url,
                        // (bool | optional) if you want it to fade out on its own or just sit there
                        sticky: true,
                        // (int | optional) the time you want it to be alive for before fading out
                        time: '',
                        // (string | optional) the class name you want to apply to that specific message
                        class_name: 'my-sticky-class'
                    });

                    return false;

                }();

            }

        </script>


        <script type="text/javascript">

            $(document).ready(function () {
                var flag = 0;
                window.setInterval(function () {
                    $.ajax({
                        url: "{{ URL::Route('providerRequestPing') }}",
                        type: "GET",
                        success: function (data) {
                            console.log(data);
                            var res = $.map(data, function (el) {
                                return el;
                            });
                            console.log(res);
                            if (res.length) {
                                var requestId = res[1];
                                $('#owner-name').html(res[3].name);
                                $('#owner-rating').html(Math.round(res[3].rating));
                                $('#owner-image').attr('src', res[3].picture);
                                var mapUrl = "http://maps.googleapis.com/maps/api/staticmap?center=" + res[3].latitude + "," + res[3].longitude + "&zoom=13&scale=false&size=570x200&maptype=roadmap&sensor=false&format=png&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:%7C12.99728400,77.61107890";
                                $('#request-map').attr('src', mapUrl);
                                var acceptUrl = '<?php echo url("/") ?>/provider/request/accept/' + requestId;
                                var declineUrl = '<?php echo url("/") ?>/provider/request/decline/' + requestId;
                                $('#accept-url').attr('href', acceptUrl);
                                $('#decline-url').attr('href', declineUrl);
                                $('#myModal').modal('show');
                                flag = 1;

                            }
                            else {
                                console.log("empty");
                                if (flag == 1)
                                {
                                    console.log("close modal");
                                    $('#myModal').modal('hide');
                                }
                                flag = 0;
                            }
                        },
                        cache: false
                    });
                }, 3000);


            });

            //$('#myModal').modal('show');
        </script>
        <script src="<?php echo asset_url(); ?>/web/js/bootstrap-switch.js"></script>
        <script >
            $(function () {
                // Switch
                $("[data-toggle='switch']").wrap('<div class="switch" />').parent().bootstrapSwitch();
            });
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
                var url = '<?php echo url("/") ?>/provider/location/set?lat=' + lat + '&lng=' + lng;
                console.log(url);
                $.ajax({
                    type: 'get',
                    url: '<?php echo url("/") ?>/provider/location/set?lat=' + lat + '&lng=' + lng,
                    success: function (msg) {
                        console.log("Location captured");
                    },
                    processData: false,
                });
            }

        </script>

        <script type="text/javascript">
            var tour = new Tour(
                    {
                        name: "providerappProfile",
                    });

            // Add your steps. Not too many, you don't really want to get your users sleepy
            tour.addSteps([
                {
                    element: "#flow23",
                    title: "Accept Walk",
                    content: "Click on accept to accept the {{trans('customize.Trip')}} else reject the {{trans('customize.Trip')}} request",
                },
            ]);

            // Initialize the tour
            tour.init();

            // Start the tour
            tour.start();
        </script>




    </body>
</html>


