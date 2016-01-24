@extends('installer.layout')

@section('content')
      <div class="col-lg-12">
        <br>
        <p class="lead">Step 6 - Payment Configuration</p>
      </div>

      <div class="row marketing">
        <div class="col-lg-8"  style="min-height:320px;">
        	<form role="form" method="POST" action="{{ web_url(); }}/install">
			  <div class="form-group">
			    <label for="exampleInputEmail1">Default Payment Gateway</label>
			    <select name="default_payment" id="default_payment" class="form-control">
			    	<option value="stripe">Stripe</option>
			    	<option value="braintree">Brain Tree</option>
			    </select>
			  </div>

			  <div class="form-group stripe" >
          <label for="exampleInputPassword1">Stripe Secret Key</label>
          <input type="text" class="form-control"  name="stripe_secret_key" placeholder="Stripe Secret Key" value="{{ Session::get('stripe_secret_key'); }}">
        </div>
        <div class="form-group stripe" >
          <label for="exampleInputPassword1">Stripe Publishable Key</label>
          <input type="text" class="form-control"  name="stripe_publishable_key" placeholder="Stripe Publishable Key" value="{{ Session::get('stripe_publishable_key'); }}">
        </div>

        <div class="form-group braintree" style="display:none" >
          <label for="exampleInputPassword1">Brain Tree Environment</label>
          <input type="text" class="form-control"  name="braintree_environment" placeholder="Brain Tree Environment" value="{{ Session::get('braintree_environment'); }}">
        </div>

        <div class="form-group braintree" style="display:none" >
          <label for="exampleInputPassword1">Brain Tree Merchant ID</label>
          <input type="text" class="form-control"  name="braintree_merchant_id" placeholder="Brain Tree Merchant ID" value="{{ Session::get('braintree_merchant_id'); }}">
        </div>

        <div class="form-group braintree" style="display:none" >
          <label for="exampleInputPassword1">Brain Tree Public Key</label>
          <input type="text" class="form-control"  name="braintree_public_key" placeholder="Brain Tree Public Key" value="{{ Session::get('braintree_public_key'); }}">
        </div>

        <div class="form-group braintree" style="display:none" >
          <label for="exampleInputPassword1">Brain Tree Private Key</label>
          <input type="text" class="form-control"  name="braintree_private_key" placeholder="Brain Tree Private Key" value="{{ Session::get('braintree_private_key'); }}">
        </div>

        <div class="form-group braintree" style="display:none" >
          <label for="exampleInputPassword1">Brain Tree Client Side Encryption Key</label>
          <input type="text" class="form-control"  name="braintree_cse" placeholder="Brain Tree Client Side Encryption Key" value="{{ Session::get('braintree_cse'); }}">
        </div>



			 
			 
			  <br>
        <button type="submit" class="btn btn-primary" style="position:relative;float:left" name="back">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back </button>
			  <button type="submit" class="btn btn-primary" style="position:relative;float:right">
			  <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Finish Installation </button>
			</form>
        </div>

        <div class="col-lg-4" style="background:antiquewhite;padding-top:30px;padding-bottom:30px;color:brown;font-weight:500;min-height:350px;">
        <ul>
          <li>Basic Settings</li>
          <li>Database Configuration</li>
          <li>File Configuration</li>
          <li>SMS Configuration</li>
          <li>Email Configuration</li>
          <li><b>Payment Configuration</b></li>
          <li>Finished</li>
        </ul>
        </div>
      </div>

<script type="text/javascript">
$(function() {
    $( "#default_payment" ).change(function() {
        val = $("#default_payment").val();
        if( val == 'stripe'){
          $(".braintree").hide();
        	$(".stripe").show();
        }
        else{
        	$(".stripe").hide();
          $(".braintree").show();
        }
    });
    $( "#default_payment" ).val("{{ Session::get('default_payment') ? Session::get('default_payment') : 'stripe'; }}").change();
});
</script>

@stop 