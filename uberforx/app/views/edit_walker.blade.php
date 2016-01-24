@extends('layout')

@section('content')

<?php $counter = 1; ?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= $title ?></h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form method="post" id="main-form" action="{{ URL::Route('AdminProviderUpdate') }}"  enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $walker->id ?>">

        <div class="box-body">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="first_name" value="<?= $walker->first_name ?>" placeholder="First Name" >
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input class="form-control" type="text" name="last_name" value="<?= $walker->last_name ?>" placeholder="Last Name">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="<?= $walker->email ?>" placeholder="Email" readonly="true" >
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input class="form-control" type="text" name="phone" value="<?= $walker->phone ?>" placeholder="Phone">
            </div>

            <div class="form-group">
                <label>Bio</label>
                <input class="form-control" type="text" name="bio" value="<?= $walker->bio ?>" placeholder="Bio">
            </div>


            <div class="form-group">
                <label>Address</label>
                <input class="form-control" type="text" name="address" value="<?= $walker->address ?>" placeholder="Address">
            </div>


            <div class="form-group">
                <label>State</label>
                <input class="form-control" type="text" name="state" value="<?= $walker->state ?>" placeholder="State">
            </div>


            <div class="form-group">
                <label>Country</label>
                <input class="form-control" type="text" name="country" value="<?= $walker->country ?>" placeholder="Country">
            </div>

            <div class="form-group">
                <label>Zip Code</label>
                <input class="form-control" type="text" name="zipcode" value="<?= $walker->zipcode ?>" placeholder="Zip Code">
            </div>

            <div class="form-group">
                <label>Car Number</label>
                <input class="form-control" type="text" name="car_number" value="<?= $walker->car_number ?>" placeholder="Zip Code">
            </div>

            <div class="form-group">
                <label>Car Model</label>
                <input class="form-control" type="text" name="car_model" value="<?= $walker->car_model ?>" placeholder="Zip Code">
            </div>

            <div class="form-group">
                <label>Picture</label>
                <input class="form-control" type="file" name="pic" >
                <br>
                <img src="<?= $walker->picture; ?>" height="50" width="50"><br>
                <p class="help-block">Please Upload image in jpg, png format.</p>
            </div>
            <div class="form-group">
                <label>Is Currently Providing : </label>
                <?php
                $walk = DB::table('walk')
                        ->select('id')
                        ->where('walk.is_started', 1)
                        ->where('walk.is_completed', 0)
                        ->where('walker_id', $walker->id);
                $count = $walk->count();
                if ($count > 0) {
                    echo "Yes";
                } else {
                    echo "No";
                }
                ?>
            </div>
            <div class="form-group">
                <label>Is Provider Active : </label>
                <?php
                $walk = DB::table('walker')
                        ->select('id')
                        ->where('walker.is_active', 1)
                        ->where('walker.id', $walker->id);
                $count = $walk->count();
                if ($count > 0) {
                    echo "Yes";
                } else {
                    echo "No";
                }
                ?>
            </div>
            <div class="form-group">
                <label>Service Type</label>
                <table class="table table-bordered">
                    <tbody><tr>
                            <th>Type</th>
                            <th>Base Price</th>
                            <th>Price per unit distance</th>
                            <th>Price per unit time</th>
                        </tr>
                        @foreach($type as $types)

                        <tr>             
                            <td id="col2">
                                <?php
                                $ar = array();
                                foreach ($ps as $pss) {
                                    $ser = ProviderType::where('id', $pss->type)->first();
                                    if ($ser)
                                        $ar[] = $ser->name;
                                }
                                $servname = $types->name;
                                ?>
                                <input class="form-control" name="service[]" type="radio" value="{{$types->id}}" <?php
                                if (!empty($ar)) {
                                    if (in_array($servname, $ar))
                                        echo "checked='checked'";
                                }
                                ?>>{{$types->name}}<br>
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_base_price[{{$types->id}}]" type="text" onkeypress="return Isamount(event,<?php echo $counter; ?>);" value="<?php
                                $proviserv = ProviderServices::where('provider_id', $walker->id)->where('type', $types->id)->first();
                                if (empty($proviserv)) {
                                    echo "";
                                } else {
                                    echo sprintf2($proviserv->base_price, 2);
                                }
                                ?>" placeholder="Base Price" ><br>
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_price_distance[{{$types->id}}]" type="text" onkeypress="return Isamount(event,<?php echo $counter; ?>);" value="<?php
                                $proviserv = ProviderServices::where('provider_id', $walker->id)->where('type', $types->id)->first();
                                if (empty($proviserv)) {
                                    echo "";
                                } else {
                                    echo sprintf2($proviserv->price_per_unit_distance, 2);
                                }
                                ?>" placeholder="Price per unit distance" ><br>
                            </td>
                            <td>
                                <?php $counter++; ?>
                                <span id="no_amount_error<?php echo $counter; ?>" style="display: none"></span>
                                <input class="form-control" name="service_price_time[{{$types->id}}]" type="text" onkeypress="return Isamount(event,<?php echo $counter; ?>);" value="<?php
                                $proviserv = ProviderServices::where('provider_id', $walker->id)->where('type', $types->id)->first();
                                if (empty($proviserv)) {
                                    echo "";
                                } else {
                                    echo sprintf2($proviserv->price_per_unit_time, 2);
                                }
                                ?>" placeholder="Price per unit time" ><br>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>




            </div>








        </div><!-- /.box-body -->

        <div class="box-footer">

            <button type="submit" class="btn btn-primary btn-flat btn-block">Update Changes</button>
        </div>
    </form>
</div>



<?php if ($success == 1) { ?>
    <script type="text/javascript">
        alert('Walker Profile Updated Successfully');
    </script>
<?php } ?>
<?php if ($success == 2) { ?>
    <script type="text/javascript">
        alert('Sorry Something went Wrong');
    </script>
<?php } ?>

<script type="text/javascript">
    $("#main-form").validate({
        rules: {
            first_name: "required",
            last_name: "required",
            country: "required",
            email: {
                required: true,
                email: true
            },
            state: "required",
            address: "required",
            bio: "required",
            zipcode: {
                required: true,
                digits: true,
            },
            phone: {
                required: true,
                digits: true,
            }


        }
    });
</script>


@stop