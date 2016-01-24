
@extends('layout')

@section('content')

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= $title ?></h3>
    </div><!-- /.box-header -->
    <!--<div class="box-body"></div><!-- /.box-body -->
    <!-- form start -->
    <form method="post" id="basic" action="{{ URL::Route('AdminProviderTypeUpdate') }}"  enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group col-md-12 col-sm-12">
            <label>Type Name</label>
            <input type="text" class="form-control" name="name" placeholder="Type Name" name="name" value="<?= $name ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>Base Price Distance</label>
            <select name="base_distance" class="form-control">
                <?php
                for ($i = 1; $i <= 25; $i++) {
                    if ($base_distance == $i) {
                        ?>
                        <option value="<?= $i ?>" selected=""><?= $i . " " . $unit_set ?></option>
                    <?php } else { ?>
                        <option value="<?= $i ?>" ><?= $i . " " . $unit_set ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>Base Price (in US Dollar)</label> <span id="no_amount_error1" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return Isamount(event, 1);" placeholder="Base Price" name="base_price" value="<?= $base_price ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>Price Per Unit Distance (in US Dollar)</label> <span id="no_amount_error2" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return Isamount(event, 2);" placeholder="Price Per Unit Distance" name="distance_price" value="<?= $price_per_unit_distance ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>Price Per Unit Time (in US Dollar)</label> <span id="no_amount_error3" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return Isamount(event, 3);" placeholder="Price Per Unit Time" name="time_price" value="<?= $price_per_unit_time ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>Maximum Size</label> <span id="no_number_error1" style="display: none"> </span>
            <input type="text" class="form-control" onkeypress="return IsNumeric(event, 1);" placeholder="Maximum Size" name="max_size" value="<?= $max_size ?>">
        </div>
        <?php if (!$is_default == 1) { ?>
            <div class="form-group col-md-6 col-sm-6">
                <label>Visibility</label>
                <select name="is_visible" class="form-control">
                    <?php if ($is_visible == 1) { ?>
                        <option value="0" >Invisible</option>
                        <option value="1" selected="">Visible</option>
                    <?php } else { ?>
                        <option value="0" selected="">Invisible</option>
                        <option value="1" >Visible</option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-6 col-sm-6">
                <br>
                <br>
                <input class="form-control" type="checkbox" name="is_default" value="1">
                <label>Set as Default</label>
            </div>
        <?php } else { ?>
            <input type="hidden" name="is_default" value="1">
            <input type="hidden" name="is_visible" value="1">
        <?php } ?>
        <div class="form-group col-md-6 col-sm-6">
            <label>Icon File</label>
            <input type="file" name="icon" class="form-control" >
            <br>
            <?php if ($icon != "") { ?>
                <img src="<?= $icon; ?>" height="50" width="50">
            <?php } ?><br>
            <p class="help-block">Please Upload image in jpg, png format.</p>
        </div>
        <div class="box-footer">
            <button type="submit" id="add" class="btn btn-primary btn-flat btn-block">Save</button>
        </div>
    </form>
</div>



<?php if ($success == 1) { ?>
    <script type="text/javascript">
        alert('Provider Type Updated Successfully');
        document.location.href = "{{ URL::Route('AdminProviderTypes') }}";
    </script>
<?php } ?>
<?php if ($success == 2) { ?>
    <script type="text/javascript">
        alert('Sorry Something went Wrong');
    </script>
<?php } ?>


<script type="text/javascript">
    $("#basic").validate({
        rules: {
            name: "required",
        }
    });

</script>


@stop