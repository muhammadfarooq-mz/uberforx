@extends('layout')

@section('content')

<div class="box box-success">
    <div class="row">
        <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>
                        <?php
                        $owner = Owner::where('id', $request->owner_id)->first();
                        if ($owner != NULL) {
                            echo $owner->first_name . ' ' . $owner->last_name;
                        }
                        ?>
                    </h3>
                    <p>
                        User
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>

            </div>
        </div><!-- ./col -->
        <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>
                        <?php
                        $walker = Walker::where('id', $request->confirmed_walker)->first();
                        if ($walker != NULL) {
                            echo $walker->first_name . ' ' . $walker->last_name;
                        }
                        ?>
                    </h3>
                    <p>
                        Providers
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>

            </div>
        </div><!-- ./col -->
        <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>
                        {{ $request->card_payment }}
                    </h3>
                    <p>
                        Payments
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-cash"></i>
                </div>

            </div>
        </div><!-- ./col -->
        <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>
                        {{$request->ledger_payment}}
                    </h3>
                    <p>
                        Referral Payments
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-people"></i>
                </div>

            </div>
        </div><!-- ./col -->
        <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>
                        {{$request->refund}}
                    </h3>
                    <p>
                        Refunds
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-social-usd"></i>
                </div>

            </div>
        </div>
        <div class="col-lg-6 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>
                        {{$request->transfer_amount}}
                    </h3>
                    <p>
                        Transfer
                    </p>
                </div>
                <div class="icon">
                    <i class="ion ion-paper-airplane"></i>
                </div>

            </div>
        </div>

    </div>

    <div class="box box-primary">
        <form method="post" action="{{ URL::Route('AdminProviderPay') }}"  enctype="multipart/form-data">
            <input type="hidden" name="id" value="0>">
            <input type="text" name="request_id" value="{{$request->id}}" hidden>
            <div class="box-body">
                <div class="form-group">
                    <label>Amount</label>
                    <input class="form-control" type="text" name="amount" value="">                                                                               
                </div>
                <div class="box-footer">
                    <button type="submit" id="theme" class="btn btn-primary btn-flat btn-block">Transfer Amount</button>
                </div>
        </form>
    </div>
</div>


@stop