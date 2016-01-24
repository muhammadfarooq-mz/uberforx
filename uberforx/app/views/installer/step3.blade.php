@extends('installer.layout')

@section('content')
      <div class="col-lg-12">
        <br>
        <p class="lead">Step 3 - File Configuration</p>
      </div>

      <div class="row marketing">
        <div class="col-lg-8"  style="min-height:150px;">
        	<form role="form" method="POST" action="{{ web_url(); }}/install">
			  <div class="form-group">
			    <label for="exampleInputEmail1">Default Storage Location</label>
			    <select name="default_storage" id="default_storage" class="form-control">
			    	<option value="1">Local</option>
			    	<option value="2">Amazon S3 Bucket</option>
			    </select>
			  </div>

			  <div class="form-group" id="s3" style="display:none;">
			    <label for="exampleInputPassword1">S3 Bucket Name</label>
			    <input type="text" class="form-control"  name="s3_bucket" placeholder="S3 Bucket Name" value="{{ Session::get('s3_bucket'); }}">
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
          <li><b>File Configuration</b></li>
          <li>SMS Configuration</li>
          <li>Email Configuration</li>
          <li>Payment Configuration</li>
          <li>Finished</li>
        </ul>
        </div>
      </div>

<script type="text/javascript">
$(function() {
    $( "#default_storage" ).change(function() {
        val = $("#default_storage").val();
        if( val == 2){
        	$("#s3").show();
        }
        else{
        	$("#s3").hide();
        }
    });
    $( "#default_storage" ).val("{{ Session::get('default_storage') || 1; }}").change();
});
</script>

@stop 