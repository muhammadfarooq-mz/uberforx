<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Dashboard">
        <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

        <title>{{Config::get('app.website_title');}}</title>


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
        <link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>/web/js/gritter/css/jquery.gritter.css" />

        <!-- Custom styles for this template -->
        <link href="<?php echo asset_url(); ?>/web/css/style.css" rel="stylesheet">
        <link href="<?php echo asset_url(); ?>/web/css/style-responsive.css" rel="stylesheet">
        <script src="<?php echo asset_url(); ?>/web/js/jquery.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/bootstrap.min.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/bootstrap-tour.js"></script>

        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

        <?php
        if (Config::get('app.default_payment') == 'stripe') {
            ?>
            <script type="text/javascript">
    // This identifies your website in the createToken call below
    Stripe.setPublishableKey('<?= Config::get("app.stripe_publishable_key"); ?>');

    var stripeResponseHandler = function (status, response) {
    var $form = $('#payment-form');

    if (response.error) {
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('button').prop('disabled', false);
    } else {
        // token contains id, last4, and card type
        var token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $form.append($('<input type="hidden" id="stripeToken" name="stripeToken" />').val(token));
        // and re-submit

        jQuery($form.get(0)).submit();

    }
    };

    jQuery(function ($) {

    $('#payment-form').submit(function (e) {
        console.log($('#stripeToken').length);
        if ($('#stripeToken').length == 0)
        {
            var $form = $(this);
            // Disable the submit button to prevent repeated clicks
            $form.find('button').prop('disabled', true);

            Stripe.card.createToken($form, stripeResponseHandler);
            // Prevent the form from submitting with the default action
            return false;
        }
    });

    });
    // ...
            </script>

        <?php } else { ?>



        <?php } ?>


        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->


        @if (isset($page) && $page == 'request-trip') 
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

        @endif




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

                <a href="{{route('/user/trips')}}" class="logo"><b><?php $siteTitle = Config::get('app.website_title');
        echo $siteTitle;
        ?></b></a>

                <!--logo end-->
                <div class="nav notify-row" id="top_menu">
                    <!--  notification start -->

                    <!--  notification end -->
                </div>
                <div class="top-menu">
                    <ul class="nav pull-right top-menu">
                        <li><a class="logout" href="{{route('/user/logout')}}">{{trans('customize.log_out'); }}</a></li>
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


                        <p class="centered"><a href="{{route('/user/profile')}}"><img src="<?= Session::get('user_pic') ? Session::get('user_pic') : asset_url() . '/web/default_profile.png' ?>" class="img-circle" width="60"></a></p>

                        <h5 class="centered">{{ Session::get('user_name') }}</h5>

                        <li class="mt">
                            <a id="trips" href="{{route('/user/trips')}}">
                                <i class="fa fa-car"></i>
                                <span>My {{trans('customize.Trip')}}s</span>
                            </a>
                        </li>
                        <li class="">
                            <a id="profile" href="{{route('/user/profile')}}">
                                <i class="fa fa-user"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li class="">
                            <a id="payment" href="{{route('userPayment')}}">
                                <i class="fa fa-money"></i>
                                <span>Payments</span>
                            </a>
                        </li>

                        <li class="" id="flow1">

                            <a id="request" href="{{route('userrequestTrip')}}">
                                <i class="fa fa-arrow-right"></i>
                                <span>Request {{trans('customize.Trip')}}</span>
                            </a>
                        </li>


                        <li class="">
                            <a id="logout" href="{{route('/user/logout')}}">
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
<?php date("Y"); ?> - <?php echo Config::get('app.website_title'); ?>
                    <a href="#" class="go-top">
                        <i class="fa fa-angle-up"></i>
                    </a>
                </div>
            </footer>
            <!--footer end-->
        </section>

        <!-- js placed at the end of the document so the pages load faster -->

        <script src="<?php echo asset_url(); ?>/web/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/jquery.ui.touch-punch.min.js"></script>
        <script class="include" type="text/javascript" src="<?php echo asset_url(); ?>/web/js/jquery.dcjqaccordion.2.7.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo asset_url(); ?>/web/js/jquery.nicescroll.js" type="text/javascript"></script>
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

            var current_state = "<?php echo Session::get('status'); ?>";

            var send_url = "<?php echo route('/user/trip/status', Session::get('request_id')) ?>";

            $(document).ready(function () {

                window.setInterval(function () {
                    $.ajax({
                        url: send_url,
                        type: "GET",
                        success: function (data) {
                            if (data != '') {
                                data = parseInt(data) | 0;
                                if (parseInt(data) != parseInt(current_state)) {
                                    current_state = data;
                                    if (data == 1) {
                                        document.location = "{{route('userrequestTrip')}}";
                                    }
                                    else if (data == 5) {
                                        document.location = "{{route('userrequestTrip')}}";
                                    }
                                    else {

                                        image_url = '<?= isset($walker->picture) ? $walker->picture : "" ?>';
                                        if (data == 2) {
                                            title = "{{trans('customize.Provider')}} Started";
                                            message = "Your {{trans('customize.Provider')}} has started from his place.Be prepared for the {{trans('customize.Trip')}}.";

                                        }
                                        else if (data == 3) {

                                            title = "{{trans('customize.Provider')}} Arrived";
                                            message = "Your {{trans('customize.Provider')}} has arrived at your place.";
                                        }
                                        else if (data == 4) {

                                            title = "{{trans('customize.Trip')}} Started";
                                            message = "Your {{trans('customize.Trip')}} has been started. Please dont forget to rate the {{trans('customize.Provider')}} once the {{trans('customize.Trip')}} is completed.";
                                        }
                                        else {
                                            title = "No nearyby {{trans('customize.Provider')}} Found";
                                            message = "No nearyby {{trans('customize.Provider')}} has accepted your {{trans('customize.Trip')}} request. Please dont forget to try again after sometime.";

                                        }

                                        notify(title, message, image_url);
                                    }


                                }
                            }

                        },
                        cache: false
                    });
                }, 3000);


            });


        </script>



        <!--common script for all pages-->
        <script src="<?php echo asset_url(); ?>/web/js/common-scripts.js"></script>

    </body>
</html>


