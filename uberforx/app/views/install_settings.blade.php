@extends('layout')

@section('content')

<p align="right"><a href="{{ URL::Route('AdminSettings') }}"><button class="btn btn-info btn-flat">Back to Settings </button></a></p>




<div class="box box-primary">

    <div class="box-header">
        <h3 class="box-title">SMS Configuration</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" action="{{ URL::Route('AdminInstallFinish') }}">

        <div class="box-body">
            <div class="form-group">
                <label>Twilio Account SID</label>

                <input type="text"  name="twillo_account_sid" class="form-control" placeholder="Twilio Account SID" value="{{$install['twillo_account_sid']?$install['twillo_account_sid']:''}}">

            </div>
            <div class="form-group">
                <label>Twilio Auth Token</label>
                <input type="text" name="twillo_auth_token" class="form-control" placeholder="Twilio Auth Token" value="{{$install['twillo_auth_token']?$install['twillo_auth_token']:''}}">

            </div>
            <div class="form-group">
                <label>Twilio Number</label>

                <input type="text" name="twillo_number" class="form-control" placeholder="Twilio Number" value="{{$install['twillo_number']?$install['twillo_number']:''}}">

            </div>




        </div><!-- /.box-body -->

        <div class="box-footer">


            <button type="submit" name="sms" class="btn btn-primary btn-flat btn-block">Update Changes</button>
        </div>
    </form>
</div>






<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Email Configuration</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" action="{{ URL::Route('AdminInstallFinish') }}">

        <div class="box-body">
            <div class="form-group">
                <label>Mail Driver</label>
                <select name="mail_driver" id="mail_driver" class="form-control">
                    <option value=''>---Select Mail Driver---</option>
                    <option value="mail" <?php
                    if ($install['mail_driver'] == 'mail') {
                        echo 'selected';
                    } else {
                        echo'';
                    }
                    ?>>Mail</option>
                    <option value="mandrill"  <?php
                    if ($install['mail_driver'] == 'mandrill') {
                        echo 'selected';
                    } else {
                        echo'';
                    }
                    ?>>Mandrill</option>
                </select>



            </div>
            <div class="form-group">
                <label>Email Address</label> <span id="no_email_error1" style="display: none"> </span>
                <input type="text" class="form-control"  name="email_address" placeholder="Email Address" value="{{$install['email_address']?$install['email_address']:''}}"  onblur="ValidateEmail(1)" id="email_check1" required="" >


            </div>
            <div class="form-group">
                <label>Display Name</label>
                <input type="text" class="form-control"  name="email_name" placeholder="Display Name" value="{{$install['email_name']?$install['email_name']:''}}">


            </div>
            <div class="form-group" id="mandrill1" style="display:<?php
            if ($install['mail_driver'] == 'mandrill')
                echo 'block';
            else
                echo 'none';
            ?>">
                <label>Mandrill Secret</label>
                <input type="text" class="form-control"  name="mandrill_secret" placeholder="Mandrill Secret" value="{{$install['mandrill_secret']?$install['mandrill_secret']:''}}">

            </div>
            <div class="form-group" id="mandrill2" style="display:<?php
            if ($install['mail_driver'] == 'mandrill')
                echo 'block';
            else
                echo 'none';
            ?>">
                <label>Mandrill Host Name</label>
                <input type="text" class="form-control"  name="host_name" placeholder="Mandrill Host Name" value="{{$install['host']?$install['host']:''}}">

            </div>
            <div class="form-group" id="mandrill3" style="display:<?php
            if ($install['mail_driver'] == 'mandrill')
                echo 'block';
            else
                echo 'none';
            ?>">
                <label>Mandrill Username</label>
                <input type="text" class="form-control"  name="user_name" placeholder="Mandrill Username" value="{{$install['mandrill_username']?$install['mandrill_username']:''}}">

            </div>



        </div><!-- /.box-body -->

        <div class="box-footer">

            <button type="submit" name="mail" class="btn btn-primary btn-flat btn-block">Update Changes</button>
        </div>
    </form>
</div>



<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Payment Configuration</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form" method="POST" action="{{ URL::Route('AdminInstallFinish') }}">

        <div class="box-body">

            <div class="form-group">
                <label>Default Payment Gateway</label>
                <select name="default_payment" id="default_payment" class="form-control">
                    <?php if (Config::get('app.default_payment') == 'stripe') { ?>
                        <option value="stripe" selected="">Stripe</option>
                        <option value="braintree">Brain Tree</option>
                    <?php } else { ?>
                        <option value="stripe">Stripe</option>
                        <option value="braintree" selected="">Brain Tree</option>
                    <?php } ?>
                </select>

            </div>

            <div class="form-group stripe">
                <label>Stripe Secret Key</label>
                <input type="text" class="form-control"  name="stripe_secret_key" placeholder="Stripe Secret Key" value="{{$install['stripe_secret_key']?$install['stripe_secret_key']:''}}">

            </div>

            <div class="form-group stripe">
                <label>Stripe Publishable Key</label>
                <input type="text" class="form-control"  name="stripe_publishable_key" placeholder="Stripe Publishable Key" value="{{$install['stripe_publishable_key']?$install['stripe_publishable_key']:''}}">

            </div>


            <div class="form-group braintree" style="display:none" >
                <label>Brain Tree Environment</label>
                <input type="text" class="form-control"  name="braintree_environment" placeholder="Brain Tree Environment" value="{{$install['braintree_environment']?$install['braintree_environment']:''}}">

            </div>

            <div class="form-group braintree" style="display:none" >
                <label>Brain Tree Merchant ID</label>
                <input type="text" class="form-control"  name="braintree_merchant_id" placeholder="Brain Tree Merchant ID" value="{{$install['braintree_merchant_id']?$install['braintree_merchant_id']:''}}">

            </div>

            <div class="form-group braintree" style="display:none" >
                <label>Brain Tree Public Key</label>
                <input type="text" class="form-control"  name="braintree_public_key" placeholder="Brain Tree Public Key" value="{{$install['braintree_public_key']?$install['braintree_public_key']:''}}">

            </div>

            <div class="form-group braintree" style="display:none" >
                <label>Brain Tree Private Key</label>
                <input type="text" class="form-control"  name="braintree_private_key" placeholder="Brain Tree Private Key" value="{{$install['braintree_private_key']?$install['braintree_private_key']:''}}">

            </div>

            <div class="form-group braintree" style="display:none" >
                <label>Brain Tree Client Side Encryption Key</label>
                <input type="text" class="form-control"  name="braintree_cse" placeholder="Brain Tree Client Side Encryption Key" value="{{$install['braintree_cse']?$install['braintree_cse']:''}}">

            </div>



        </div><!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" name="payment" class="btn btn-primary btn-flat btn-block">Update Changes</button>
        </div>
    </form>
</div>

<div class="box box-primary">

    <div class="box-header">
        <h3 class="box-title">Certificates</h3>
    </div>
    <form role="form" method="POST" action="{{ URL::Route('AdminAddCerti') }}" enctype="multipart/form-data">
        <div class="box-body">
            <h3>iOS</h3>
            <div class="form-group">
                <label>Certificate Type</label>
                <select class="form-control" name="cert_type_a">
                    <?php
                    if ($install['customer_certy_type']) {
                        ?>
                        <option value="0">Sandbox</option>
                        <option value="1" selected="">Production</option>
                    <?php } else { ?>
                        <option value="0" selected="">Sandbox</option>
                        <option value="1">Production</option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label>User</label>
                <input type="file" class="form-control" name="user_certi_a" value="<?= $install['customer_certy_url'] ?>">
            </div>
            <div class="form-group col-sm-4">
                <label>User Passphrase</label>
                <input type="text" class="form-control" name="user_pass_a" value="<?= $install['customer_certy_pass'] ?>">
            </div>
            <div class="form-group col-sm-4">
                <label>&nbsp;</label>
                <a href="<?= $install['customer_certy_url'] ?>" target="_blank"><span class="btn btn-default form-control">View / Download User Certificate</span></a>
            </div>
            <div class="form-group col-sm-4">
                <label>Provider</label>
                <input class="form-control" type="file" name="prov_certi_a" value="<?= $install['provider_certy_url'] ?>">
            </div>

            <div class="form-group col-sm-4">
                <label>Provider Passphrase</label>
                <input class="form-control" type="text" name="prov_pass_a" value="<?= $install['provider_certy_pass'] ?>">
            </div>
            <div class="form-group col-sm-4">
                <label>&nbsp;</label>
                <a href="<?= $install['provider_certy_url'] ?>" target="_blank"><span class="btn btn-default form-control">View / Download User Certificate</span></a>
            </div>
            <div class="form-group col-sm-6">
                <label>IOS <?= Config::get('app.generic_keywords.User') ?> App Link</label>
                <input class="form-control" type="text" name="ios_client_app_url" value="<?= Config::get('app.ios_client_app_url') ?>">
            </div>
            <div class="form-group col-sm-6">
                <label>IOS <?= Config::get('app.generic_keywords.Provider') ?> App Link</label>
                <input class="form-control" type="text" name="ios_provider_app_url" value="<?= Config::get('app.ios_provider_app_url') ?>">
            </div>
            <!--<div class="form-group">
                <label>Choose Default</label>
                <select class="form-control" name="cert_default">
                    <option value="0" <?php
            if ($cert_def != 1) {
                echo "selected";
            }
            ?>>Sandbox</option>
                    <option value="1" <?php
            if ($cert_def == 1) {
                echo "selected";
            }
            ?>>Production</option>
                </select>
            </div>-->
        </div>
        <hr>
        <div class="box-body">
            <h3>GCM</h3>
            <div class="form-group">
                <label>Browser Key</label>
                <input type="text" class="form-control" name="gcm_key" placeholder="Enter your Browser GCM Key here." value="<?= $install['gcm_browser_key'] ?>">
            </div>
            <div class="form-group col-sm-6">
                <label>Android <?= Config::get('app.generic_keywords.User') ?> App Link</label>
                <input class="form-control" type="text" name="android_client_app_url" value="<?= Config::get('app.android_client_app_url') ?>">
            </div>
            <div class="form-group col-sm-6">
                <label>Android <?= Config::get('app.generic_keywords.Provider') ?> App Link</label>
                <input class="form-control" type="text" name="android_provider_app_url" value="<?= Config::get('app.android_provider_app_url') ?>">
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat btn-block">Update Changes</button>
            </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var payment = '<?php echo Config::get('app.default_payment'); ?>';
        if (payment == 'stripe') {
            $(".braintree").hide();
            $(".stripe").show();
        }
        else {
            $(".stripe").hide();
            $(".braintree").show();
        }
    });
    $(function () {
        $("#default_storage").change(function () {
            val = $("#default_storage").val();
            if (val == 2) {
                $("#s3").show();
            }
            else {
                $("#s3").hide();
            }
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        $("#mail_driver").change(function () {
            val = $("#mail_driver").val();
            if (val == 'mandrill') {
                $("#mandrill1").fadeIn(300);
                $("#mandrill2").fadeIn(300);
                $("#mandrill3").fadeIn(300);
            }
            else {
                $("#mandrill1").fadeOut(300);
                $("#mandrill2").fadeOut(300);
                $("#mandrill3").fadeOut(300);
            }
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        $("#default_payment").change(function () {
            val = $("#default_payment").val();
            if (val == 'stripe') {
                $(".braintree").hide();
                $(".stripe").show();
            }
            else {
                $(".stripe").hide();
                $(".braintree").show();
            }
        });
    });
</script>
<?php if ($success == 1) { ?>
    <script type="text/javascript">
        alert('Settings Updated Successfully');
    </script>
<?php } ?>
<?php if ($success == 2) { ?>
    <script type="text/javascript">
        alert('Sorry Something went Wrong');
    </script>
<?php } ?>
<?php if ($success == 3) { ?>
    <script type="text/javascript">
        alert('Please Upload Certificate in .pem formate.');
    </script>
<?php } ?>
<?php if ($success == 4) { ?>
    <script type="text/javascript">
        alert('Android GCM Browser Key must not be blank.');
    </script>
<?php } ?>
<?php if ($success == 5) { ?>
    <script type="text/javascript">
        alert('Mobil Notification settings were not changed.');
    </script>
<?php } ?>

<script>
    $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });
</script>
@stop