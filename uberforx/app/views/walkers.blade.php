@extends('layout')

@section('content')

<!--<div class="row">
    <div class="col-md-12 col-sm-12">
        <a id="addpro" href="{{ URL::Route('AdminProviderAdd') }}"><button class="btn btn-flat btn-block btn-info" type="button">Add Provider</button></a>
        <br/>
    </div>
</div>-->
<div class="col-md-6 col-sm-12">
    <div class="box box-danger">
        <form method="get" action="{{ URL::Route('/admin/sortpv') }}">
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
                        ?> id="provid">{{ trans('customize.Provider');}} ID</option>
                        <option value="pvname" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvname') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvname">{{ trans('customize.Provider'); }} Name</option>
                        <option value="pvemail" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvemail') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvemail">{{ trans('customize.Provider'); }} Email</option>
                        <option value="pvaddress" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvaddress') {
                            echo 'selected="selected"';
                        }
                        ?>  id="pvaddress">{{ trans('customize.Provider'); }} Address</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <select class="form-control" id="sortdroporder" name="valu">
                        <option value="asc" <?php
                        if (isset($_GET['valu']) && $_GET['valu'] == 'asc') {
                            echo 'selected="selected"';
                        }
                        ?> selected id="asc">Ascending</option>
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

        <form method="get" action="{{ URL::Route('/admin/searchpv') }}">
            <div class="box-header">
                <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body row">

                <div class="col-md-6 col-sm-12">
                    <select class="form-control" id="sortdrop" name="type">
                        <option value="provid" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'provid') {
                            echo 'selected="selected"';
                        }
                        ?> id="provid"> {{ trans('customize.Provider'); }} ID</option>
                        <option value="pvname" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvname') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvname">{{ trans('customize.Provider'); }} Name</option>
                        <option value="pvemail" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvemail') {
                            echo 'selected="selected"';
                        }
                        ?> id="pvemail">{{ trans('customize.Provider'); }} Email</option>
                        <option value="pvaddress" <?php
                        if (isset($_GET['type']) && $_GET['type'] == 'pvaddress') {
                            echo 'selected="selected"';
                        }
                        ?>  id="pvaddress"> {{ trans('customize.Provider'); }} Address</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input class="form-control" type="text" name="valu" value="<?php
                    if (Session::has('valu')) {
                        echo Session::get('valu');
                    }
                    ?>" id="insearch" placeholder="keyword"/>
                    <br>
                </div>

            </div>

            <div class="box-footer">

                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success">Search</button>


            </div>
        </form>

    </div>
</div>
<div class="col-md-12 col-sm-12">
    <?php if (Session::get('che')) { ?>
        <a id="providers" href="{{ URL::Route('AdminProviders') }}"><button class="col-md-12 col-sm-12 btn btn-warning" type="button">All {{ trans('customize.Provider');}}s</button></a><br/>
    <?php } else { ?>
        <a id="currently" href="{{ URL::Route('AdminProviderCurrent') }}"><button class="col-md-12 col-sm-12 btn btn-warning"  type="button">Currently Providing</button></a><br/>
    <?php } ?>
    <br><br>
</div>
<div class="box box-info tbl-box">
    <div align="left" id="paglink"><?php echo $walkers->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
    <table class="table table-bordered">
        <tbody><tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Photo</th>
                <th>Bio</th>
                <th>Total Requests</th>
                <th>Acceptance Rate</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($walkers as $walker) { ?>
                <tr>
                    <td><?= $walker->id ?></td>
                    <td><?php echo $walker->first_name . " " . $walker->last_name; ?> </td>
                    <td><?= $walker->email ?></td>
                    <td><?= $walker->phone ?></td>
                    <td><a href="<?php echo $walker->picture; ?> target="_blank" onclick="window.open('<?php echo $walker->picture; ?>', 'popup', 'height=500px, width=400px'); return false;"">View Photo</a></td>
                    <td>
                        <?php
                        if ($walker->bio) {
                            echo $walker->bio;
                        } else {
                            echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                        }
                        ?>
                    </td>
                    <td><?= $walker->total_requests ?></td>
                    <td><?php
                        if ($walker->total_requests != 0) {
                            echo round(($walker->accepted_requests / $walker->total_requests) * 100, 2);
                        } else {
                            echo 0;
                        }
                        ?> %</td>
                    <td><?php
                        if ($walker->is_approved == 1) {
                            echo "<span class='badge bg-green'>Approved</span>";
                        } else {
                            echo "<span class='badge bg-red'>Pending</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1" name="action" data-toggle="dropdown">
                                Actions
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::Route('AdminProviderEdit', $walker->id) }}">Edit Details</a></li>
                                <?php if ($walker->merchant_id == NULL && (Config::get('app.generic_keywords.Currency') == '$' || Config::get('app.default_payment') != 'stripe')) {
                                    ?>
                                    <li role="presentation"><a id="addbank" role="menuitem" tabindex="-1" href="{{ URL::Route('AdminProviderBanking', $walker->id) }}">Add Banking Details</a></li>
                                <?php } ?>
                                <li role="presentation"><a role="menuitem" id="history" tabindex="-1" href="{{ URL::Route('AdminProviderHistory', $walker->id) }}">View History</a></li>

                                <?php if ($walker->is_approved == 0) { ?>
                                    <li role="presentation"><a role="menuitem" id="approve" tabindex="-1" href="{{ URL::Route('AdminProviderApprove', $walker->id) }}">Approve</a></li>
                                <?php } else { ?>
                                    <li role="presentation"><a role="menuitem" id="decline" tabindex="-1" href="{{ URL::Route('AdminProviderDecline', $walker->id) }}">Decline</a></li>
                                    <li role="presentation"><a role="menuitem" id="decline" tabindex="-1" href="{{ URL::Route('AdminProviderDelete', $walker->id) }}">Delete</a></li>

                                <?php } ?>
                                <?php
                                /* $settng = Settings::where('key', 'allow_calendar')->first();
                                  if ($settng->value == 1) { */
                                ?>
                                <!--<li role="presentation"><a role="menuitem" id="avail" tabindex="-1" href="{{ URL::Route('AdminProviderAvailability', $walker->id) }}">View Calendar</a></li>-->
                                <?php /* } */ ?>
                                <?php
                                $walker_doc = WalkerDocument::where('walker_id', $walker->id)->first();
                                if ($walker_doc != NULL) {
                                    ?>
                                    <li role="presentation"><a id="view_walker_doc" role="menuitem" tabindex="-1" href="{{ URL::Route('AdminViewProviderDoc', $walker->id) }}">View Documents</a></li>
                                <?php } else { ?>
                                    <li role="presentation"><a id="view_walker_doc" role="menuitem" tabindex="-1" href="#"><span class='badge bg-red'>No Documents</span></a></li>
                                <?php } ?>
                                <!--<li role="presentation"><a role="menuitem" id="history" tabindex="-1" href="{{ web_url().'/admin/provider/documents/'.$walker->id }}">View Documents</a></li>-->
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody></table>
    <div align="left" id="paglink"><?php echo $walkers->appends(array('type' => Session::get('type'), 'valu' => Session::get('valu')))->links(); ?></div>
</div>



@stop