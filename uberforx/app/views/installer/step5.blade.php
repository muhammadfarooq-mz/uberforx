@extends('installer.layout')

@section('content')
      <div class="col-lg-12">
        <br>
        <p class="lead">Step 5 - Email Configuration</p>
      </div>

      <div class="row marketing">
        <div class="col-lg-8"  style="min-height:320px;">
        	<form role="form" method="POST" action="{{ web_url(); }}/install">
			  <div class="form-group">
			    <label for="exampleInputEmail1">Mail Driver</label>
			    <select name="mail_driver" id="mail_driver" class="form-control">
			    	<option value="mail">Mail</option>
			    	<option value="mandrill">Mandrill</option>
			    </select>
			  </div>

			  <div class="form-group" >
          <label for="exampleInputPassword1">Email Address</label>
          <input type="text" class="form-control"  name="email_address" placeholder="Email Address" value="{{ Session::get('email_address'); }}">
        </div>

        <div class="form-group" >
          <label for="exampleInputPassword1">Display Name</label>
          <input type="text" class="form-control"  name="email_name" placeholder="Display Name" value="{{ Session::get('email_name'); }}">
        </div>

        <div class="form-group mandrill1" style="display:none">
          <label for="exampleInputPassword1">Mandrill Secret</label>
          <input type="text" class="form-control"  name="mandrill_secret" placeholder="Mandrill Secret" value="{{ Session::get('mandrill_secret'); }}">
        </div>		


        <div class="form-group mandrill" style="display:none">
          <label for="exampleInputPassword1">Mandrill Username</label>
          <input type="text" class="form-control"  name="mandrill_username" placeholder="Mandrill Username" value="{{ Session::get('mandrill_username'); }}">
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
          <li>SMS Configuration</li>
          <li><b>Email Configuration</b></li>
          <li>Payment Configuration</li>
          <li>Finished</li>
        </ul>
        </div>
      </div>

<script type="text/javascript">
$(function() {
    $( "#mail_driver" ).change(function() {
        val = $("#mail_driver").val();
        if( val == 'mandrill'){
        	$(".mandrill").show();
          $(".mandrill1").show();
        }
        else{
        	$(".mandrill").hide();
          $(".mandrill1").hide();
        }
    });
    $( "#mail_driver" ).val("{{ Session::get('mail_driver') ? Session::get('mail_driver') : 'mail'; }}").change();
});
</script>

@stop 