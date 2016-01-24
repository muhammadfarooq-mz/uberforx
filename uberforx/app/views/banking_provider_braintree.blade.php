@extends('layout')

@section('content')

<link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">





  <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title">Bank Detail</h3>
                                </div><!-- /.box-header -->

              <form method="post" action="{{ URL::Route('AdminProviderBBanking') }}" id="addressformadmin"  enctype="multipart/form-data">
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
                <br><input type="submit" value="Update Changes" class="btn btn-green">
                </div>
              </form>


</div>




<script type="text/javascript" src="{{asset('js/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('js/bootstrap-datetimepicker.js')}}"></script>
<?php
if($success == 1) { ?>
<script type="text/javascript">
    alert({{ trans('customize.Provider').' Profile Updated Successfully'; }});
</script>
<?php } ?>
<?php
if($success == 2) { ?>
<script type="text/javascript">
    alert('Sorry Something went Wrong');
</script>
<?php } ?>


@stop