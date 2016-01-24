@extends('layout')

@section('content')
<!--   summary start -->
<div class="row">
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
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
                    <?= $payment_default ?> Payment
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
        <div class="small-box bg-blue">
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

            <form role="form" method="get" action="{{ URL::Route('AdminPayment') }}">

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
                <th>Owner Name</th>
                <th>Provider</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Payment Mode</th>
                <th>Ledger Payment</th>
                <th><?= $payment_default ?> Payment</th>
                <th>Promo Discount</th>
                <th>Action</th>
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
                        <?php
                        if ($walk->payment_mode == 0) {
                            echo $payment_default;
                        } elseif ($walk->payment_mode == 1) {
                            echo "Cash";
                        } elseif ($walk->payment_mode == 2) {
                            echo "Paypal>";
                        }
                        ?>
                    </td>
                    <td>
                        <?= sprintf2($walk->ledger_payment, 2); ?>
                    </td>
                    <td>
                        <?= sprintf2($walk->card_payment, 2); ?>
                    </td>
                    <td>
                        <?php
                        if ($walk->promo_id !== NULL) {
                            $promo = PromoCodes::where('id', $walk->promo_id)->first();
                            if ($promo) {
                                if ($promo->type == 2) {
                                    echo sprintf2($promo->value, 2);
                                } elseif ($promo->type == 1) {
                                    echo sprintf2(($promo->value * $walk->total / 100), 2);
                                } else {
                                    echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                                }
                            } else {
                                echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                            }
                        } else {
                            echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                Actions
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">


                                <li role="presentation"><a role="menuitem" id="map" tabindex="-1" href="{{ URL::Route('AdminRequestsMap', $walk->id) }}">View Map</a></li>
                                @if($walk->is_paid==0 && $walk->is_completed==1 && $walk->payment_mode!=1 && $walk->total!=0)
                                <li role="presentation"><a role="menuitem" id="map" tabindex="-1" href="{{ URL::Route('AdminChargeUser', $walk->id) }}">Charge User</a></li>
                                @endif



                                <!--
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo web_url(); ?>/admin/walk/delete/<?= $walk->id; ?>">Delete Walk</a></li>
                                -->
                            </ul>
                        </div>
                    </td>
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