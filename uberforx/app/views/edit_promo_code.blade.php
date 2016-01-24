@extends('layout')

@section('content')

@if (Session::has('msg'))
<h4 class="alert alert-info">
    {{{ Session::get('msg') }}}
    {{{Session::put('msg',NULL)}}}
</h4>
@endif
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Edit Promo Code</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form" id="form" method="post" action="{{ URL::Route('AdminPromoUpdate') }}"  enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{$promo_code->id}}">
        <div class="box-body">
            <div class="form-group col-md-6 col-sm-6">
                <label>Promo Code Name</label>
                <input type="text" class="form-control" name="code_name" value="{{$promo_code->coupon_code}}" placeholder="ProvenLogic" >
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>Promo Code Value</label>
                <span id="no_amount_error1" style="display: none"></span>
                <input class="form-control" type="text" name="code_value" value="{{$promo_code->value}}" placeholder="20" onkeypress="return Isamount(event, 1);">
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>Promo Code Type</label>
                <select name="code_type" class="form-control">
                    <option value="1" <?php
                    if ($promo_code->type == 1) {
                        echo "selected";
                    }
                    ?>>Percent</option>
                    <option value="2" <?php
                    if ($promo_code->type == 2) {
                        echo "selected";
                    }
                    ?>>Absolute</option>
                </select>
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>Uses Allowed</label>
                <span id="no_number_error1" style="display: none"> </span>
                <input class="form-control" type="text" name="code_uses" value="{{$promo_code->uses}}" placeholder="50" onkeypress="return IsNumeric(event, 1);">
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>Start Date</label>
                <br>
                <input type="text" class="form-control" style="overflow:hidden;" id="start-date" name="start_date" value="{{date("m/d/Y", strtotime(trim($promo_code->start_date)))}}" placeholder="Start Date">
                <!--<div class='input-group date' id='startDate'>
                    <input type='text' class="form-control" name="code_expiry" value="{{date("m/d/Y", strtotime(trim($promo_code->expiry)))}}">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>-->
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <label>Expiry Date</label>
                <br>
                <input type="text" class="form-control" style="overflow:hidden;" id="end-date" name="code_expiry" placeholder="Expiry date"  value="{{date("m/d/Y", strtotime(trim($promo_code->expiry)))}}">
                <!--<div class='input-group date' id='startDate'>
                    <input type='text' class="form-control" name="code_expiry" value="{{date("m/d/Y", strtotime(trim($promo_code->expiry)))}}">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>-->
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-flat btn-block">Update Changes</button>
            </div>
        </div>
</div>
</form>
</div>

<script type="text/javascript">
    $("#form").validate({
        rules: {
            code_name: "required",
            code_value: "required",
            code_uses: "required",
            code_expiry: "required",
        }
    });

</script>

<script type="text/javascript">

    jQuery(function () {
        jQuery('#startDate').datetimepicker();
        jQuery("#startDate").on("dp.change", function (e) {
            jQuery('#endDate').data("DateTimePicker").setMinDate(e.date);
        });
        jQuery("#endDate").on("dp.change", function (e) {
            jQuery('#startDate').data("DateTimePicker").setMaxDate(e.date);
        });
    });

</script>

<script type="text/javascript" src="{{asset('javascript/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('javascript/bootstrap-datetimepicker.js')}}"></script>

@stop