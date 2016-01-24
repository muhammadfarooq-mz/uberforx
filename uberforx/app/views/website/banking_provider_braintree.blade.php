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

<link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">

<h2>Add your Banking details</h2>
<hr>

  <div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Bank Detail</h3>
    </div><!-- /.box-header -->

      <form method="post" action="{{ URL::Route('ProviderBBanking') }}" id="addressformadmin"  enctype="multipart/form-data">
        <div class="form-group" style="margin-left:10px;margin-right:10px;">
        <input type="hidden" name="id" value="<?= $provider->id ?>">
        <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ $provider -> first_name }}" required><br>
        <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{$provider -> last_name }}" required><br>
        <input type="text" name="email" class="form-control" placeholder="Email" value="{{$provider -> email }}" required><br>
        <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{$provider -> phone }}" required><br>
        <input type='text' name="dob" class="form-control" placeholder="Date of Birth" id='datetimepicker6' required><br>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker6').datetimepicker({
                  pickTime: false,
              });
            });
        </script>
        <input type="text" name="ssn" class="form-control" placeholder="Social Security Number" required><br>
        <label>Address</label>
        <input type="text" name="streetAddress" class="form-control" placeholder="Street Address" required><br>
        <input type="text" name="locality" class="form-control" placeholder="Locality" required><br>
        <input type="text" name="region" class="form-control" placeholder="Region" required><br>
        <input type="text" name="postalCode" class="form-control" placeholder="Postal Code" required><br>
        <label>Funding</label>
        <input type="text" name="bankemail" class="form-control" value="{{$provider -> email }}" required><br>
        <input type="text" name="bankphone" class="form-control" value="{{$provider -> phone }}" required><br>
        <input type="text" name="accountNumber" class="form-control" placeholder="Account Number" required><br>
        <input type="text" name="routingNumber" class="form-control" placeholder="Routing Number" required><br>
        <br><input type="submit" value="Update Changes" class="btn btn-block btn-flat btn-green">
        </div>
      </form>
</div>

<script type="text/javascript" src="{{asset('js/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('js/bootstrap-datetimepicker.js')}}"></script>
      </div>
    </div>
    <script src="<?php echo asset_url(); ?>/website/js/bootstrap.min.js"></script>
  </body>
</html>
