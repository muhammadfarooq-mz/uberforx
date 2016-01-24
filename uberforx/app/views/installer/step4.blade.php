@extends('installer.layout')

@section('content')
      <div class="col-lg-12">
        <br>
        <p class="lead">Step 4 - SMS Configuration</p>
      </div>

      <div class="row marketing">
        <div class="col-lg-8"  style="min-height:320px;">
        	<form role="form" method="POST" action="{{ web_url(); }}/install">
			  <div class="form-group">
          <label for="exampleInputEmail1">Twilio Account SID</label>
          <input type="text" name="twillo_account_sid" class="form-control" placeholder="Twilio Account SID" value="{{ Session::get('twillo_account_sid'); }}">
        </div>

        <div class="form-group">
          <label for="exampleInputEmail1">Twilio Auth Token</label>
          <input type="text" name="twillo_auth_token" class="form-control" placeholder="Twilio Auth Token" value="{{ Session::get('twillo_auth_token'); }}">
        </div>

        <div class="form-group">
          <label for="exampleInputEmail1">Twilio Number</label>
          <input type="text" name="twillo_number" class="form-control" placeholder="Twilio Number" value="{{ Session::get('twillo_number'); }}">
        </div>

			 
			
			  <br>
        <button type="submit" class="btn btn-primary" style="position:relative;float:left" name="back">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back </button>
			  <button type="submit" class="btn btn-primary" style="position:relative;float:right">
			  Continue <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>
			</form>
        </div>

        <div class="col-lg-4" style="background:antiquewhite;padding-top:30px;padding-bottom:30px;color:brown;font-weight:500;min-height:350px;">
        <ul>
          <li>Basic Settings</li>
          <li>Database Configuration</li>
          <li>File Configuration</li>
          <li><b>SMS Configuration</b></li>
          <li>Email Configuration</li>
          <li>Payment Configuration</li>
          <li>Finished</li>
        </ul>
        </div>
      </div>


@stop 