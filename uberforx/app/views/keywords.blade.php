@extends('layout')
@section('content')
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Customize Application Backend Keywords</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form method="post" action="{{ URL::Route('AdminKeywordsSave') }}"  enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                        <label> 1. Provider </label>
                        <input class="form-control" type="text" name="key_provider" value="{{ Config::get('app.generic_keywords.Provider') }}" placeholder="Value for Provider Key Word">
                    </div>
                    <div class="form-group">
                        <label> 2. User </label>
                        <input class="form-control" type="text" name="key_user" value="{{ Config::get('app.generic_keywords.User') }}" placeholder="Value for User Key Word">
                    </div>
                    <div class="form-group">
                        <label> 3. Taxi </label>
                        <input class="form-control" type="text" name="key_taxi" value="{{ Config::get('app.generic_keywords.Services') }}" placeholder="Value for Taxi Key Word">
                    </div>
                    <div class="form-group">
                        <label> 4. Trip </label>
                        <input class="form-control" type="text" name="key_trip" value="{{ Config::get('app.generic_keywords.Trip') }}" placeholder="Value for Trip Key Word">
                    </div>
                    <div class="form-group">
                        <label> 5. Currency </label>
                        <select name="key_currency" id="currencies" class="form-control">
                            <option value="AUD" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'AUD') {
                                echo "selected";
                            }
                            ?>>Australia Dollar</option> 
                            <option value="CAD" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'CAD') {
                                echo "selected";
                            }
                            ?>>Canada Dollar</option>
                            <option value="CHF" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'CHF') {
                                echo "selected";
                            }
                            ?>>Switzerland Franc</option>
                            <option value="DKK" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'DKK') {
                                echo "selected";
                            }
                            ?>>Denmark Krone</option>
                            <option value="EUR" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'EUR') {
                                echo "selected";
                            }
                            ?>>Euro Member Countries</option>
                            <option value="GBP" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'GBP') {
                                echo "selected";
                            }
                            ?>>United Kingdom Pound</option> 
                            <option value="HKD" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'HKD') {
                                echo "selected";
                            }
                            ?>>Hong Kong Dollar</option>
                            <option value="JPY" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'JPY') {
                                echo "selected";
                            }
                            ?>>Japan Yen</option>
                            <option value="MXN" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'MXN') {
                                echo "selected";
                            }
                            ?>>Mexico Peso</option>
                            <option value="NZD" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'NZD') {
                                echo "selected";
                            }
                            ?>>New Zealand Dollar</option>
                            <option value="PHP" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'PHP') {
                                echo "selected";
                            }
                            ?>>Philippines Peso</option>
                            <option value="SEK" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'SEK') {
                                echo "selected";
                            }
                            ?>>Sweden Krona</option>
                            <option value="SGD" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'SGD') {
                                echo "selected";
                            }
                            ?>>Singapore Dollar</option>
                            <option value="SPL" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'SPL') {
                                echo "selected";
                            }
                            ?>>Seborga Luigino</option>
                            <option value="THB" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'THB') {
                                echo "selected";
                            }
                            ?>>Thailand Baht</option>
                            <option value="$" <?php
                            if (Config::get('app.generic_keywords.Currency') == '$') {
                                echo "selected";
                            }
                            ?>>United States Dollar</option>
                            <option value="ZAR" <?php
                            if (Config::get('app.generic_keywords.Currency') == 'ZAR') {
                                echo "selected";
                            }
                            ?>>South Africa Rand</option>

                        </select>
                    </div>
                    <?php
                    if (isset($keywords)) {
                        foreach ($keywords as $keyword) {
                            if ($keyword->id != 5) {
                                /* if ($keyword->id < 5) {
                                  ?>

                                  <div class="form-group">
                                  <label>
                                  <?php
                                  echo $keyword->id . ". " . ucfirst($keyword->alias);
                                  ?>
                                  </label>
                                  <input class="form-control" type="text" name="{{$keyword->id}}" value="{{$keyword->keyword}}" />
                                  </div>
                                  <?php
                                  } */
                            } else {
                                ?>
                                <!--<div class="form-group">
                                    <label>
                                <?php
                                echo $keyword->id . ". " . ucfirst($keyword->alias);
                                ?>
                                    </label>
                                    <select name="{{$keyword->id}}" id="currencies" class="form-control">
                                        <option value="AUD" <?php
                                if ($keyword->keyword == 'AUD') {
                                    echo "selected";
                                }
                                ?>>Australia Dollar</option> 
                                        <option value="CAD" <?php
                                if ($keyword->keyword == 'CAD') {
                                    echo "selected";
                                }
                                ?>>Canada Dollar</option>
                                        <option value="CHF" <?php
                                if ($keyword->keyword == 'CHF') {
                                    echo "selected";
                                }
                                ?>>Switzerland Franc</option>
                                        <option value="DKK" <?php
                                if ($keyword->keyword == 'DKK') {
                                    echo "selected";
                                }
                                ?>>Denmark Krone</option>
                                        <option value="EUR" <?php
                                if ($keyword->keyword == 'EUR') {
                                    echo "selected";
                                }
                                ?>>Euro Member Countries</option>
                                        <option value="GBP" <?php
                                if ($keyword->keyword == 'GBP') {
                                    echo "selected";
                                }
                                ?>>United Kingdom Pound</option> 
                                        <option value="HKD" <?php
                                if ($keyword->keyword == 'HKD') {
                                    echo "selected";
                                }
                                ?>>Hong Kong Dollar</option>
                                        <option value="JPY" <?php
                                if ($keyword->keyword == 'JPY') {
                                    echo "selected";
                                }
                                ?>>Japan Yen</option>
                                        <option value="MXN" <?php
                                if ($keyword->keyword == 'MXN') {
                                    echo "selected";
                                }
                                ?>>Mexico Peso</option>
                                        <option value="NZD" <?php
                                if ($keyword->keyword == 'NZD') {
                                    echo "selected";
                                }
                                ?>>New Zealand Dollar</option>
                                        <option value="PHP" <?php
                                if ($keyword->keyword == 'PHP') {
                                    echo "selected";
                                }
                                ?>>Philippines Peso</option>
                                        <option value="SEK" <?php
                                if ($keyword->keyword == 'SEK') {
                                    echo "selected";
                                }
                                ?>>Sweden Krona</option>
                                        <option value="SGD" <?php
                                if ($keyword->keyword == 'SGD') {
                                    echo "selected";
                                }
                                ?>>Singapore Dollar</option>
                                        <option value="SPL" <?php
                                if ($keyword->keyword == 'SPL') {
                                    echo "selected";
                                }
                                ?>>Seborga Luigino</option>
                                        <option value="THB" <?php
                                if ($keyword->keyword == 'THB') {
                                    echo "selected";
                                }
                                ?>>Thailand Baht</option>
                                        <option value="$" <?php
                                if ($keyword->keyword == '$') {
                                    echo "selected";
                                }
                                ?>>United States Dollar</option>
                                        <option value="ZAR" <?php
                                if ($keyword->keyword == 'ZAR') {
                                    echo "selected";
                                }
                                ?>>South Africa Rand</option>

                                    </select>
                                </div>-->
                                <?php
                            }
                        }
                    }
                    ?>
                    <div class="form-group">
                        <label>Total Trips Icon</label>

                        <select class="form-control" style="font-family: 'FontAwesome', Helvetica;" name="total_trip">
                            <?php foreach ($icons as $key) { ?>
                                <option value="<?php echo $key->id; ?>" 
                                <?php
                                /* $icon = Keywords::where('keyword', 'total_trip')->first(); */
                                if (Config::get('app.generic_keywords.total_trip') == $key->id) {
                                    echo "selected";
                                }
                                ?> >
                                            <?php echo "  " . $key->icon_code . "  " . $key->icon_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Completed Trips Icon</label>

                        <select class="form-control" style="font-family: 'FontAwesome', Helvetica;" name="completed_trip">
                            <?php foreach ($icons as $key) { ?>
                                <option value="<?php echo $key->id; ?>" 
                                <?php
                                /* $icon = Keywords::where('keyword', 'completed_trip')->first(); */
                                if (Config::get('app.generic_keywords.completed_trip') == $key->id) {
                                    echo "selected";
                                }
                                ?> >
                                            <?php echo "  " . $key->icon_code . "  " . $key->icon_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cancelled Trip Icon</label>

                        <select class="form-control" style="font-family: 'FontAwesome', Helvetica;" name="cancelled_trip">
                            <?php foreach ($icons as $key) { ?>
                                <option value="<?php echo $key->id; ?>" 
                                <?php
                                /* $icon = Keywords::where('keyword', 'cancelled_trip')->first(); */
                                if (Config::get('app.generic_keywords.cancelled_trip') == $key->id) {
                                    echo "selected";
                                }
                                ?> >
                                            <?php echo "  " . $key->icon_code . "  " . $key->icon_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Total Payment Icon</label>

                        <select class="form-control" style="font-family: 'FontAwesome', Helvetica;" name="total_payment">
                            <?php foreach ($icons as $key) { ?>
                                <option value="<?php echo $key->id; ?>" 
                                <?php
                                /* $icon = Keywords::where('keyword', 'total_payment')->first(); */
                                if (Config::get('app.generic_keywords.total_payment') == $key->id) {
                                    echo "selected";
                                }
                                ?> >
                                            <?php echo "  " . $key->icon_code . "  " . $key->icon_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Credit Payment Icon</label>

                        <select class="form-control" style="font-family: 'FontAwesome', Helvetica;" name="credit_payment">
                            <?php foreach ($icons as $key) { ?>
                                <option value="<?php echo $key->id; ?>" 
                                <?php
                                /* $icon = Keywords::where('keyword', 'credit_payment')->first(); */
                                if (Config::get('app.generic_keywords.credit_payment') == $key->id) {
                                    echo "selected";
                                }
                                ?> >
                                            <?php echo "  " . $key->icon_code . "  " . $key->icon_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Card Payment Icon</label>

                        <select class="form-control" style="font-family: 'FontAwesome', Helvetica;" name="card_payment">
                            <?php foreach ($icons as $key) { ?>
                                <option value="<?php echo $key->id; ?>" 
                                <?php
                                /* $icon = Keywords::where('keyword', 'card_payment')->first(); */
                                if (Config::get('app.generic_keywords.card_payment') == $key->id) {
                                    echo "selected";
                                }
                                ?> >
                                            <?php echo "  " . $key->icon_code . "  " . $key->icon_name; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <!--<div class="form-group">
                        <label> Referral Prefix </label>
                        <input class="form-control" type="text" name="key_ref_pre" value="{{ Config::get('app.referral_prefix') }}" placeholder="Fixed Prefix for Referral Code">
                    </div>-->
                    <input class="form-control" type="hidden" name="key_ref_pre" value="{{ Config::get('app.referral_prefix') }}" placeholder="Fixed Prefix for Referral Code">

                </div>



                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat btn-block">Update Changes</button>
                </div>
            </form>
        </div>

    </div>
    <div class="col-md-6 col-sm-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Edit Application UI keywords</h3>
            </div>
            <form role="form" method="POST" action="{{ URL::Route('AdminUIKeywordsSave') }}"  enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                        <label >Dashboard</label>
                        <input type="text" class="form-control" name="val_dashboard" placeholder="Keyword for Provider" value="{{$Uikeywords['keyDashboard']}}">
                    </div>
                    <div class="form-group">
                        <label >Map View</label>
                        <input type="text" class="form-control" name="val_map_view" placeholder="Keyword for Provider" value="{{$Uikeywords['keyMap_View']}}">
                    </div>
                    <div class="form-group">
                        <label >Provider</label>
                        <input type="text" class="form-control" name="val_provider" placeholder="Keyword for Provider" value="{{$Uikeywords['keyProvider']}}">
                    </div>
                    <div class="form-group">
                        <label >User</label>
                        <input type="text" class="form-control" name="val_user" placeholder="Keyword for User" value="{{$Uikeywords['keyUser']}}">
                    </div>
                    <div class="form-group">
                        <label >Taxi</label>
                        <input type="text" class="form-control" name="val_taxi" placeholder="Keyword for Taxi" value="{{$Uikeywords['keyTaxi']}}">
                    </div>
                    <div class="form-group">
                        <label >Trip</label>
                        <input type="text" class="form-control" name="val_trip" placeholder="Keyword for Trip" value="{{$Uikeywords['keyTrip']}}">
                    </div>
                    <div class="form-group">
                        <label>Walk</label>
                        <input type="text" class="form-control" name="val_walk" placeholder="Keyword for Walk" value="{{$Uikeywords['keyWalk']}}">
                    </div>
                    <div class="form-group">
                        <label>Request</label>
                        <input type="text" class="form-control" name="val_request" placeholder="Keyword for Request" value="{{$Uikeywords['keyRequest']}}">
                    </div>
                    <div class="form-group">
                        <label>Reviews</label>
                        <input type="text" class="form-control" name="val_reviews" placeholder="Keyword for Reviews" value="{{$Uikeywords['keyReviews']}}">
                    </div>
                    <div class="form-group">
                        <label>Information</label>
                        <input type="text" class="form-control" name="val_information" placeholder="Keyword for Information" value="{{$Uikeywords['keyInformation']}}">
                    </div>
                    <div class="form-group">
                        <label>Types</label>
                        <input type="text" class="form-control" name="val_types" placeholder="Keyword for Types" value="{{$Uikeywords['keyTypes']}}">
                    </div>
                    <div class="form-group">
                        <label>Documents</label>
                        <input type="text" class="form-control" name="val_documents" placeholder="Keyword for Documents" value="{{$Uikeywords['keyDocuments']}}">
                    </div>
                    <div class="form-group">
                        <label>Promo Codes</label>
                        <input type="text" class="form-control" name="val_promo_codes" placeholder="Keyword for Promo Codes" value="{{$Uikeywords['keyPromo_Codes']}}">
                    </div>
                    <div class="form-group">
                        <label>Customize</label>
                        <input type="text" class="form-control" name="val_customize" placeholder="Keyword for Customize" value="{{$Uikeywords['keyCustomize']}}">
                    </div>
                    <div class="form-group">
                        <label>Payment Details</label>
                        <input type="text" class="form-control" name="val_payment_details" placeholder="Keyword for Payment Details" value="{{$Uikeywords['keyPayment_Details']}}">
                    </div>
                    <div class="form-group">
                        <label>Settings</label>
                        <input type="text" class="form-control" name="val_settings" placeholder="Keyword for Settings" value="{{$Uikeywords['keySettings']}}">
                    </div>
                    <div class="form-group">
                        <label>Admin</label>
                        <input type="text" class="form-control" name="val_admin" placeholder="Keyword for Admin Button" value="{{$Uikeywords['keyAdmin']}}">
                    </div>
                    <div class="form-group">
                        <label>Admin Control</label>
                        <input type="text" class="form-control" name="val_admin_control" placeholder="Keyword for Admin Control Button" value="{{$Uikeywords['keyAdmin_Control']}}">
                    </div>
                    <div class="form-group">
                        <label>Log Out</label>
                        <input type="text" class="form-control" name="val_log_out" placeholder="Keyword for Log Out Button" value="{{$Uikeywords['keyLog_Out']}}">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat btn-block">Update Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function Checkfiles()
    {
        var fup = document.getElementById('logo');
        var fileName = fup.value;
        if (fileName != '')
        {
            var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
            if (ext == "PNG" || ext == "png")
            {
                return true;
            }
            else
            {
                alert("Upload PNG Images only for Logo");
                return false;
            }
        }
        var fup = document.getElementById('icon');
        var fileName1 = fup.value;
        if (fileName1 != '')
        {
            var ext = fileName1.substring(fileName1.lastIndexOf('.') + 1);

            if (ext == "ICO" || ext == "ico")
            {
                return true;
            }
            else
            {
                alert("Upload Icon Images only for Favicon");
                return false;
            }
        }
    }
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
<script>
    $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });
</script>
<script type="text/javascript">
    $("#currencies").change(function () {
        var currency_selected = $("#currencies option:selected").val();
        console.log(currency_selected);
        $.ajax({
            type: "POST",
            url: "{{route('adminCurrency')}}",
            data: {'currency_selected': currency_selected},
            success: function (data) {
                if (data.success == true) {
                    console.log(data.rate);
                } else {
                    console.log(data.error_message);
                }
            }
        });
    });
</script>
@stop