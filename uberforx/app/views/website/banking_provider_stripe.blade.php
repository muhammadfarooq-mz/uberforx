<!DOCTYPE html>
<html>
  <head>
    <?php $theme = Theme::all();?>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/bootstrap.min.css">

    <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/font-awesome.min.css">

    <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/animate.css">

    <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/owl.theme.css">
    <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/styles.css">

    <link rel="stylesheet" href="<?php echo asset_url(); ?>/website/css/more.css">

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script src="<?php echo asset_url(); ?>/website/js/modernizr.custom.32033.js"></script>

     <?php 
            $active='#000066';
            $logo = '/image/logo.png';
            $favicon='/image/favicon.ico';
            foreach($theme as $themes) {
                $active = $themes->active_color; 
                $favicon = '/uploads/'.$themes->favicon;
                $logo= '/uploads/'.$themes->logo;
            }
            if($logo=='/uploads/')
            {
                $logo = '/image/logo.png';
            }
            if($favicon=='/uploads/')
            {
                $favicon='/image/favicon.ico';
            }
        ?>
    
    <!--[if IE]><script type="text/javascript" src="js/excanvas.compiled.js"></script><![endif]-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
     <div class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header pull-left">    
          <a class="navbar-brand" href="/"><img src="<?php echo asset_url(); ?><?php echo $logo;?>" alt="" height="100%" width="auto"> {{$app_name}}</a>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="col-sm-12 trans-blk">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">

    Stripe.setPublishableKey('<?php echo Config::get("app.stripe_publishable_key"); ?>');

  function stripeResponseHandler(status, response) {
    var $form = $('#payment-form');

    if (response.error) {
      // Show the errors on the form
      $form.find('.payment-errors').text(response.error.message);
      $form.find('button').prop('disabled', false);
    } else {
      console.log(response);
      // response contains id and card, which contains additional card details
      var token = response.id;
      // Insert the token into the form so it gets submitted to the server
      $form.append($('<input type="hidden" name="stripeToken" />').val(token));
      // and submit
      $form.get(0).submit();
    }
  };

  jQuery(function($) {

        $('#payment-form').submit(function(e) {
        console.log($('#stripeToken').length);
        if($('#stripeToken').length == 0)
        {
          var $form = $(this);
          // Disable the submit button to prevent repeated clicks
          $form.find('button').prop('disabled', true);

          // Stripe.bankAccount.createToken($form, stripeResponseHandler);
          Stripe.bankAccount.createToken({
            country: $('#country').val(),
            currency: 'USD',
            routing_number: $('#routingNumber').val(),
            account_number: $('#accountNumber').val()
          }, stripeResponseHandler);
          // Prevent the form from submitting with the default action
          return false;
        }
      });

  });

</script>
<h2>Add your Banking details</h2>
<hr>
  <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Bank Detail</h3>
    </div><!-- /.box-header -->

  	<form method="post" action="{{ route('ProviderSBanking') }}" id="payment-form" enctype="multipart/form-data">
  		<div class="form-group" style="margin-left:10px;margin-right:10px;">
  			<input type="hidden" name="id" value="<?= $provider_id ?>">
  			<input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ $provider_first_name }}" required><br>
  			<input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{$provider_last_name }}" required><br>
  			<input type="text" size="20" data-stripe="country" id="country" class="form-control" value="US" placeholder="Card Number" required /><br>
  			<input type="text" size="20" data-stripe="accountNumber" id="accountNumber" placeholder="Account number" class="form-control" name="account_number" required /><br>
  			<input type="text" size="40" data-stripe="routingNumber" id="routingNumber" class="form-control" placeholder="Routing number" name="routing_number" required /><br>
  			<select name="type" class="form-control">
  				<option value="individual">Individual</option>
  				<option value="corporate">Corporate</option>
  			</select><br>
  			<input type="text" name="email" class="form-control" placeholder="Email" value="{{$provider_email }}" required><br>
  			<br><input type="submit" value="Update Changes" class="btn btn-block btn-flat btn-success">
  		</div>
  	</form>
  </div>
    </div>
  </div>
  <script src="<?php echo asset_url(); ?>/website/js/bootstrap.min.js"></script>
</body>
</html>
