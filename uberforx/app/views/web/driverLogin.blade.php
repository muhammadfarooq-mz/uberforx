@extends('web.layout')

@section('body')
<div class="row first-fold">
    <div class="overlay"></div>
    <div class="landing">
        <div class="row uber-logo">
            <div class="col-md-1 col-sm-4 col-xs-4"><img src="{{ asset('web/img/logo.png') }}" alt=""></div>
            <div class="col-md-10 hidden-sm hidden-xs"></div>
            <div class="col-md-1 col-md-offset-0 col-sm-2 col-sm-offset-6 col-xs-4 col-xs-offset-4 login-button" data-toggle="modal" data-target="#myModal"><a href="#">LOGIN</a></div>
        </div>
        <div class="row landing-page-text">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <h1>WE ARE <span>LAUNCHING</span> SOON</h1>
            </div>
        </div>
        <div class="row input-register">
            <div class="col-md-4 uber-input"><input type="text" id="register" name="register" placeholder="Enter your email address" ></div>
            <div class="col-md-2 register-button" data-toggle="modal" data-target="#myModal1"><a href="#">Register</a></div>
        </div>
        <div class="row social-icons">
            <ul class="col-md-12 icons text-center">
                <li><a href="#"><i class="icon-facebook"></i></a></li>
                <li><a href="#"><i class="icon-twitter"></i></a></li>
                <li><a href="#"><i class="icon-google-plus"></i></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row features">
    <div class="col-md-12 feature-heading text-center">
        <h1>FEATURES</h1>
        <div class="row">
            <div class="col-md-1 col-xs-3 line"></div>
        </div>
    </div>
    <div class="row feature-list">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-2 col-xs-3">
                    <img src="{{ asset('web/img/maps.png') }}" alt="">
                </div>
                <div class="col-md-6 col-xs-8">
                    <h3>Lorem Ipsum</h3>
                    <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
                <div class="col-md-1">

                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-2 col-xs-3">
                    <img src="{{ asset('web/img/stack.png') }}" alt="">
                </div>
                <div class="col-md-6 col-xs-8">
                    <h3>Lorem Ipsum</h3>
                    <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
                <div class="col-md-1">

                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-2 col-xs-3">
                    <img src="{{ asset('web/img/heart.png') }}" alt="">
                </div>
                <div class="col-md-6 col-xs-8">
                    <h3>Lorem Ipsum</h3>
                    <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
                <div class="col-md-1">

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <img src="{{ asset('web/img/feature.png') }}" alt="">
        </div>
    </div>
</div>
<div class="row team">
    <div class="col-md-12 feature-heading text-center">
        <h1>MEET THE TEAM</h1>
        <div class="row">
            <div class="col-md-1 col-xs-3 line"></div>
        </div>
    </div>
    <div class="row">
        <div class="container">
            <div class="col-md-4">
                <h3>Michael Doe</h3>
                <img src="{{ asset('web/img/team-1.jpg') }}" alt="">
                <div class="row social-icons">
                    <ul class="col-md-12 icons text-center">
                        <li><a href="#"><i class="icon-facebook"></i></a></li>
                        <li><a href="#"><i class="icon-twitter"></i></a></li>
                        <li><a href="#"><i class="icon-google-plus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Jasmine Doe</h3>
                <img src="{{ asset('web/img/team-2.jpg') }}" alt="">
                <div class="row social-icons">
                    <ul class="col-md-12 icons text-center">
                        <li><a href="#"><i class="icon-facebook"></i></a></li>
                        <li><a href="#"><i class="icon-twitter"></i></a></li>
                        <li><a href="#"><i class="icon-google-plus"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Jessica Doe</h3>
                <img src="{{ asset('web/img/team-3.jpg') }}" alt="">
                <div class="row social-icons">
                    <ul class="col-md-12 icons text-center">
                        <li><a href="#"><i class="icon-facebook"></i></a></li>
                        <li><a href="#"><i class="icon-twitter"></i></a></li>
                        <li><a href="#"><i class="icon-google-plus"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row contact-us">
    <div class="col-md-12 feature-heading text-center">
        <h1>CONTACT US</h1>
        <div class="row">
            <div class="col-md-1 col-xs-3 line"></div>
        </div>
    </div>
    <div class="row contact-form">
        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <input type="text" name="name" class="form-control" value="Full Name"><br/><br/>
                <input type="text" name="email" class="form-control" value="Email"><br/><br/>
                <textarea name="message" class="form-control" rows="5" placeholder="Message"></textarea><br/><br/>
                <button type="submit" class="btn-blue form-control">Submit</button>
            </div>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" action="">
                    <input type="email" name="email" class="form-control" placeholder="Email" required><br/><br/>
                    <input type="password" name="password" class="form-control" placeholder="Password" required><br/><br/>
                    <button type="submit" class="btn-blue form-control">login</button><br/><br/>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" action="">
                    <input type="text" name="name" class="form-control" placeholder="Name" required="true"><br>
                    <input type="email" name="email" class="form-control email" placeholder="Email" required="true"><br>
                    <input type="password" name="password" class="form-control" placeholder="Password" required="true"><br>
                    <input type="text" name="country_code" class="form-control" placeholder="Country Code Ex: +1" required="true"><br>
                    <input type="text" name="contact" class="form-control" placeholder="Contact Number" required="true"><br>
                    <input type="text" name="dob" class="form-control" id="datepicker" placeholder="Date Of Birth YYYY-MM-DD" required="true"><br>
                    <input type="text" name="gender" class="form-control" placeholder="Gender: Male/Female" required="true"><br>
                    <input type="text" name="latitude" class="form-control" placeholder="latitude" required="true" style="display: none"><br>
                    <input type="text" name="longitude" class="form-control" placeholder="longitude" required="true" style="display: none"><br>
                    <button type="submit" class="btn-blue form-control">Register</button><br/><br/>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
@parent
<script>
    function startIntro(){
        var intro = introJs();
        @if(!Session::has('registration'))
        intro.setOptions({
            showBullets: false,
            exitOnOverlayClick: false,
            showStepNumbers: false,
            steps: [
                {
                    intro: "Welcome to UberForX, this will help you tour the application. Please follow the steps mentioned in this tooltip"
                },
                {
                    element: '.register-button',
                    intro: "Enter your email-id to register or login with next step",
                    position: 'top'

                },
                {
                    element: '.login-button',
                    intro: 'Click here to login if you are already registered',
                    position: 'left'
                }
            ]
        });
        @else
        intro.setOptions({
            showBullets: false,
            exitOnOverlayClick: false,
            showStepNumbers: false,
            steps: [
                {
                    intro: 'You are successfully registered. Please go to <a href="../admin/index.php/login" target="_blank">admin panel</a> and approve the regitered mechanic.'
                },
                {
                    element: '.login-button',
                    intro: 'Click here to login',
                    position: 'left'
                }
            ]
        });

        @endif

        intro.start();
    }
</script>
<script>
    $('.register-button').click(function(){
        getLocation();
        var abc = $('#register').val();

        $('#myModal1 .email').val(abc);
    });

    var overlayHeight = $('.first-fold').height();

    function showPosition(position) {
        $('input[name="latitude"]').val(position.coords.latitude)
        $('input[name="longitude"]').val(position.coords.longitude)

    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
    }

    $(document).ready(function(){
        $('.overlay').height(overlayHeight),

            $(window).resize(function(){
                $('.overlay').height(overlayHeight);
            });
        startIntro();
    });

    $(function() {
        $( "#datepicker" ).datepicker();
        $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
    });
</script>

@stop
