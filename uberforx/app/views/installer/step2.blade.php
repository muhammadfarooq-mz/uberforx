@extends('installer.layout')

@section('content')
      <div class="col-lg-12">
      	<br>
        <p class="lead">Step 2 - Database Configuration</p>
      </div>

      <div class="row marketing">
        <div class="col-lg-8"  style="min-height:350px;">
        	<form role="form" method="POST" action="{{ web_url(); }}/install">
			  <div class="form-group">
			    <label for="exampleInputEmail1">Host</label>
			    <input type="text" class="form-control" name="host" placeholder="Host" value="{{ Session::get('host'); }}">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputPassword1">Database Name</label>
			    <input type="text" class="form-control"  name="database" placeholder="Database name" value="{{ Session::get('database'); }}">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputPassword1">User</label>
			    <input type="text" class="form-control"  name="username" placeholder="User"  value="{{ Session::get('username'); }}">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputPassword1">Password</label>
			    <input type="password" class="form-control"  name="password" placeholder="Password" value="{{ Session::get('password'); }}">
			  </div>
			 
			  <br>
			  <button type="submit" class="btn btn-primary" style="position:relative;float:left" name="back">
			  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back </button>
			  <button type="submit" class="btn btn-primary" style="position:relative;float:right">
			  Continue <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>
			  <br><br><br>
			</form>
        </div>

        <div class="col-lg-4" style="background:antiquewhite;padding-top:30px;padding-bottom:30px;color:brown;font-weight:500;min-height:350px;">
        <ul>
          <li>Basic Settings</li>
          <li><b>Database Configuration</b></li>
          <li>File Configuration</li>
          <li>SMS Configuration</li>
          <li>Email Configuration</li>
          <li>Payment Configuration</li>
          <li>Finished</li>
        </ul>
        </div>
      </div>

@stop 