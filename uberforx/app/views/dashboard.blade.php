@extends('layout')

@section('content')
<?php
if (!isset($_COOKIE['skipInstallation'])) {
    if (Session::has('notify')) {
        $message = '';
        $message1 = $message2 = $message3 = '';
        if ($install['mail_driver'] == '' && $install['email_address'] == '' && $install['email_name'] == '') {
            $message1 = 'Mail Configuration Missing During Installation';
        }
        if ($install['twillo_account_sid'] == '' && $install['twillo_auth_token'] == '' && $install['twillo_number'] == '') {
            $message2 = 'SMS Configuration Missing During Installation';
        }
        if (($install['default_payment'] == '' && $install['braintree_environment'] == '' && $install['braintree_merchant_id'] == '' && $install['braintree_public_key'] == '' && $install['braintree_private_key'] == '' && $install['braintree_cse'] == '') && ( $install['stripe_publishable_key'] == '')) {
            $message3 = 'Payment Configuration Missing During Installation';
        }
        if ($message1 != '' && $message2 != '' && $message3 != '') {
            $message = "SMS, Mail, Payment Configuration Missing";
        } else if ($message1 != '' && $message2 != '') {
            $message = "SMS, Mail Configuration Missing";
        } else if ($message1 != '' && $message3 != '') {
            $message = "Mail, Payment Configuration Missing";
        } else if ($message3 != '' && $message2 != '') {
            $message = "SMS, Payment Configuration Missing";
        } else if ($message1 != '' && $message3 == '' && $message2 == '') {
            $message = $message1;
        } else if ($message2 != '' && $message1 == '' && $message3 == '') {
            $message = $message2;
        } else if ($message3 != '' && $message1 == '' && $message2 == '') {
            $message = $message3;
        }

        if ($message != '') {
            ?>
            <div id="myModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Installation Notification</h4>
                        </div>
                        <div class="modal-body">
                            <p><?php echo $message; ?></p>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ URL::Route('AdminSettingDontShow') }}"><button type="button" class="btn btn-default" >Don't Show Again</button></a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <a href="{{ URL::Route('AdminSettingInstallation') }}"><button type="button" class="btn btn-primary">Change Now</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>


<!--   summary start -->


<div class="row">
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    <?= $completed_rides + $cancelled_rides ?>
                </h3>
                <p>
                    Total Trips
                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'total_trip')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.total_trip'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?= $completed_rides ?>
                </h3>
                <p>
                    Completed {{ trans('customize.Trip');}}s
                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'completed_trip')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.completed_trip'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    <?= $cancelled_rides ?>
                </h3>
                <p>
                    Cancelled {{ trans('customize.Trip'); }}s
                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'cancelled_trip')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.cancelled_trip'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>
                    <?= $currency_sel ?> <?= sprintf2(($credit_payment + $card_payment + $cash_payment), 2) ?>
                </h3>
                <p>
                    Total Payment
                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'total_payment')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.total_payment'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <?= $currency_sel ?> <?= sprintf2($card_payment, 2) ?>
                </h3>
                <p>
                    Card Payment
                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'card_payment')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.card_payment'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-maroon">
            <div class="inner">
                <h3>
                    <?= $currency_sel ?> <?= sprintf2($credit_payment, 2) ?>
                </h3>
                <p>
                    Credit Payment
                </p>
            </div>
            <div class="icon">
                <?php /* $icon = Keywords::where('keyword', 'credit_payment')->first(); */ ?>
                <i class="fa"><?php
                    /* $show = Icons::find($icon->alias); */
                    $show = Icons::find(Config::get('app.generic_keywords.credit_payment'));
                    echo $show->icon_code;
                    ?></i>
            </div>

        </div>
    </div><!-- ./col -->
</div>



<!--  Summary end -->



<!-- filter start -->

<div class="box box-danger">
    <div class="box-header">
        <h3 class="box-title">Filter</h3>
    </div>
    <div class="box-body">
        <div class="row">

            <form role="form" method="get" action="{{ URL::Route('AdminReport') }}">

                <div class="col-md-6 col-sm-6 col-lg-6">
                    <input type="text" class="form-control" style="overflow:hidden;" id="start-date" name="start_date" value="{{ Input::get('start_date') }}" placeholder="Start Date">
                    <br>
                </div>

                <div class="col-md-6 col-sm-6 col-lg-6">
                    <input type="text" class="form-control" style="overflow:hidden;" id="end-date" name="end_date" placeholder="End Date"  value="{{ Input::get('end_date') }}">
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="status"  class="form-control">
                        <option value="0">Status</option>
                        <option value="1" <?php echo Input::get('status') == 1 ? "selected" : "" ?> >Completed</option>
                        <option value="2" <?php echo Input::get('status') == 2 ? "selected" : "" ?>>Cancelled</option>
                    </select>
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="walker_id" style="overflow:hidden;" class="form-control">
                        <option value="0">Provider</option>
                        <?php foreach ($walkers as $walker) { ?>
                            <option value="<?= $walker->id ?>" <?php echo Input::get('walker_id') == $walker->id ? "selected" : "" ?>><?= $walker->first_name; ?> <?= $walker->last_name ?></option>
                        <?php } ?>
                    </select>
                    <br>
                </div>

                <div class="col-md-4 col-sm-4 col-lg-4">

                    <select name="owner_id" style="overflow:hidden;" class="form-control">
                        <option value="0">User</option>
                        <?php foreach ($owners as $owner) { ?>
                            <option value="<?= $owner->id ?>" <?php echo Input::get('owner_id') == $owner->id ? "selected" : "" ?>><?= $owner->first_name; ?> <?= $owner->last_name ?></option>
                        <?php } ?>
                    </select>
                    <br>
                </div>


        </div>
    </div><!-- /.box-body -->
    <div class="box-footer">
        <button type="submit" name="submit" class="btn btn-primary" value="Filter_Data">Filter Data</button>
        <button type="submit" name="submit" class="btn btn-primary" value="Download_Report">Download Report</button>
    </div>

</form>

</div>

<!-- filter end-->




<div class="box box-info tbl-box">
    <div align="left" id="paglink"><?php echo $walks->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered">
        <tbody><tr>
                <th>Request ID</th>
                <th>{{ trans('customize.User');}} Name</th>
                <th>{{ trans('customize.Provider');}}</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Referral Bonus</th>
                <th>Promotional Bonus</th>
                <th>Card Payment</th>
                <th>Cash Payment</th>
            </tr>


            <?php foreach ($walks as $walk) { ?>

                <tr>
                    <td><?= $walk->id ?></td>

                    <td><?php echo $walk->owner_first_name . " " . $walk->owner_last_name; ?> </td>
                    <td>
                        <?php
                        if ($walk->confirmed_walker) {
                            echo $walk->walker_first_name . " " . $walk->walker_last_name;
                        } else {
                            echo "Un Assigned";
                        }
                        ?>
                    </td>
                    <td><?php echo date("d M Y", strtotime($walk->date)); ?></td>
                    <td><?php echo date("g:iA", strtotime($walk->date)); ?></td>

                    <td>
                        <?php
                        if ($walk->is_cancelled == 1) {

                            echo "<span class='badge bg-red'>Cancelled</span>";
                        } elseif ($walk->is_completed == 1) {
                            echo "<span class='badge bg-green'>Completed</span>";
                        } elseif ($walk->is_started == 1) {
                            echo "<span class='badge bg-yellow'>Started</span>";
                        } elseif ($walk->is_walker_arrived == 1) {
                            echo "<span class='badge bg-yellow'>Walker Arrived</span>";
                        } elseif ($walk->is_walker_started == 1) {
                            echo "<span class='badge bg-yellow'>Walker Started</span>";
                        } else {
                            
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo sprintf2($walk->total, 2); ?>
                    </td>
                    <td>
                        <?php
                        if ($walk->is_paid == 1) {

                            echo "<span class='badge bg-green'>Completed</span>";
                        } elseif ($walk->is_paid == 0 && $walk->is_completed == 1) {
                            echo "<span class='badge bg-red'>Pending</span>";
                        } else {
                            echo "<span class='badge bg-yellow'>Request Not Completed</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?= sprintf2($walk->ledger_payment, 2); ?>
                    </td>
                    <td>
                        <?= sprintf2($walk->promo_payment, 2); ?>
                    </td>
                    <?php if ($walk->payment_mode == 1) { ?>
                        <td>
                            <?= sprintf2(0, 2); ?>
                        </td>
                    <?php } else { ?>
                        <td>
                            <?= sprintf2($walk->card_payment, 2); ?>
                        </td>
                        <?php
                    }
                    if ($walk->payment_mode == 1) {
                        ?>
                        <td>
                            <?= sprintf2($walk->card_payment, 2); ?>
                        </td>
                    <?php } else { ?>
                        <td>
                            <?= sprintf2(0, 2); ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <div align="left" id="paglink"><?php echo $walks->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
</div>
<!--</form>-->
</div>
</div>
</div>

<script>
    $(function () {
        $("#start-date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $("#end-date").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#end-date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function (selectedDate) {
                $("#start-date").datepicker("option", "maxDate", selectedDate);
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#myModal").modal('show');
    });
</script>

@stop