@extends('layout')

@section('content')

<a id="addtype" href="{{ URL::Route('AdminProviderTypeEdit', 0) }}"><input type="button" class="btn btn-info btn-flat btn-block" value="Add New {{ trans('customize.Provider');}} Type"></a>


<br>


<div class="col-md-6 col-sm-12">

    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/sortpvtype') }}">
            <div class="box-header">
                <h3 class="box-title">Sort</h3>
            </div>
            <div class="box-body row">

                <div class="col-md-6 col-sm-12">
                    <select class="form-control" id="sortdrop" name="type">
                        <option value="provid" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'provid') {
                            echo 'selected="selected"';
                        }
                        ?> id="provid">{{ trans('customize.Provider');}} Type ID</option>
                        <option value="pvname" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvname') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvname">{{ trans('customize.Provider');}} Name</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <select class="form-control" id="sortdroporder" name="valu">
                        <option value="asc" <?php
                        if (isset($_GET['valu']) && $_GET['valu'] == 'asc') {
                            echo 'selected="selected"';
                        }
                        ?> id="asc">Ascending</option>
                        <option value="desc" <?php
                        if (isset($_GET['valu']) && $_GET['valu'] == 'desc') {
                            echo 'selected="selected"';
                        }
                        ?> id="desc">Descending</option>
                    </select>
                    <br>
                </div>

            </div>

            <div class="box-footer">

                <button type="submit" id="btnsort" class="btn btn-flat btn-block btn-success">Sort</button>


            </div>
        </form>

    </div>
</div>

<div class="col-md-6 col-sm-12">

    <div class="box box-danger">

        <form method="get" action="{{ URL::Route('/admin/searchpvtype') }}">
            <div class="box-header">
                <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body row">

                <div class="col-md-6 col-sm-12">

                    <select id="searchdrop" class="form-control" name="type">
                        <option value="provid" id="provid">{{ trans('customize.Provider');}} Type ID</option>
                        <option value="provname" id="provname">{{ trans('customize.Provider');}} Name</option>
                    </select>


                    <br>
                </div>
                <div class="col-md-6 col-sm-12">

                    <input class="form-control" type="text" name="valu" id="insearch" placeholder="keyword"/>
                    <br>
                </div>

            </div>

            <div class="box-footer">


                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success">Search</button>


            </div>
        </form>

    </div>

</div>



<div class="box box-info tbl-box">
    <div align="left" id="paglink"><?php echo $types->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Base Price Distance</th>
                <th>Base Price</th>
                <th>Price Per Unit Distance</th>
                <th>Price Per Unit Time</th>
                <th>Maximum space</th>
                <th>Visibility</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($types as $type) {
                ?>
                <tr>
                    <td><?= $type->id ?></td>
                    <td><?= $type->name ?>
                        <?php if ($type->is_default) { ?>
                            <font style="color:green">(Default)</font>
                        <?php } ?>
                    </td>
                    <td><?= $type->base_distance . " " . $unit_set ?></td>
                    <td><?= Config::get('app.generic_keywords.Currency') . " " . sprintf2($type->base_price, 2) ?></td>
                    <td><?= Config::get('app.generic_keywords.Currency') . " " . sprintf2($type->price_per_unit_distance, 2) ?></td>
                    <td><?= Config::get('app.generic_keywords.Currency') . " " . sprintf2($type->price_per_unit_time, 2) ?></td>
                    <td><?= $type->max_size ?></td>
                    <td>
                        <?php
                        if ($type->is_visible == 1) {
                            echo "<span class='badge bg-green'>Visible</span>";
                        } else {
                            echo "<span class='badge bg-red'>Invisible</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <a href="{{ URL::Route('AdminProviderTypeEdit', $type->id) }}"><input type="button" class="btn btn-success" value="Edit"></a>
                        <?php /* if (!$type->is_default) { ?>
                          <a href="{{ URL::Route('AdminProviderTypeDelete', $type->id) }}"><input type="button" class="btn btn-danger" value="Delete"></a>
                          <?php } */ ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div align="left" id="paglink"><?php echo $types->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>

</div>





@stop