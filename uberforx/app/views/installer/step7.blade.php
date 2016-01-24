@extends('installer.layout')

@section('content')
<div class="col-lg-12">
    <br>
    <p class="lead">Step 7 - Finished!</p>
</div>

<div class="row marketing">
    <div class="col-lg-8"  style="min-height:350px;">

        <div class="col-lg-12">
            <a href="{{ web_url() }}"><img src="{{ asset_url() }}/installer/website.png"  width="100%">

                </div>
                <div class="col-lg-12">
                    <center> <br>Go to the Website</a></center>
                </div>
                <div class="col-lg-12" style="min-height:50px;">
                    <hr>
                </div>
                <div class="col-lg-12">
                    <a href="{{ web_url() }}/admin"><img src="{{ asset_url() }}/installer/admin.png" width="100%"  >
                        </div>
                        <div class="col-lg-12">
                            <center> <br>Login to Admin Panel</a></center>
                        </div>

                        <div class="col-lg-12">

                        </div>
                        <div class="col-lg-12">
                            <center> <br>Note: Make sure you add a cronjob to run every minute for the command:

                                <br><a href="{{ web_url() }}/server/schedulerequest">curl {{ web_url() }}/server/schedulerequest</a></center>
                        </div>


                </div>

                <div class="col-lg-4" style="background:antiquewhite;padding-top:30px;padding-bottom:30px;color:brown;font-weight:500;min-height:350px;">
                    <ul>
                        <li>Basic Settings</li>
                        <li>Database Configuration</li>
                        <li>File Configuration</li>
                        <li>SMS Configuration</li>
                        <li>Email Configuration</li>
                        <li>Payment Configuration</li>
                        <li><b>Finished</b></li>
                    </ul>
                </div>
        </div>

        @stop 