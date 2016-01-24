@extends('layout')

@section('content')

<script src="https://bitbucket.org/pellepim/jstimezonedetect/downloads/jstz-1.0.4.min.js"></script>
<script src="http://momentjs.com/downloads/moment.min.js"></script>
<script src="http://momentjs.com/downloads/moment-timezone-with-data.min.js"></script> 

<div class="box box-danger">
    <form method="get" action="{{ URL::Route('/admin/searchrev') }}" >
            <div class="box-header">
                <h3 class="box-title">Filter</h3>
            </div>
            <div class="box-body row">
                <div class="col-md-6 col-sm-12">
                    <select id="searchdrop" class="form-control" name="type">
                        <option value="owner" id="owner">{{ trans('customize.User');}} Name</option>
                        <option value="walker" id="walker">{{ trans('customize.Provider');}}</option>
                    </select>
                    <br>
                </div>
                <div class="col-md-6 col-sm-12">
                    <input class="form-control" type="text" name="valu" value="<?php if(Session::has('valu')){echo Session::get('valu');} ?>" id="insearch" placeholder="keyword"/>
                    <br>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="btnsearch" class="btn btn-flat btn-block btn-success">Search</button>
            </div>
    </form>
</div>

<div class="box box-info tbl-box">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">{{ trans('customize.Provider');}} Reviews</a></li>
            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">{{ trans('customize.User');}} Reviews</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div align="left" id="paglink"><?php echo $provider_reviews->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>{{ trans('customize.User');}} Name</th>
                            <th>{{ trans('customize.Provider');}}</th>
                            <th>Rating</th>
                            <th>Date and Time</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                        <?php $i =0; ?>
                        <?php foreach ($provider_reviews as $reviewp) { ?>
                            <tr>
                                <td><?php echo $reviewp->owner_first_name." ".$reviewp->owner_last_name; ?> </td>
                                <td><?php echo $reviewp->walker_first_name." ".$reviewp->walker_last_name; ?> </td>
                                <td><?= $reviewp->rating ?></td>
                                <td id= 'Datetime<?php echo $i; ?>' >
                                <script>
                                var timezone = jstz.determine();
                                 // console.log(timezone.name());
                                var timevar = moment.utc("<?php echo $reviewp->created_at; ?>");
                                timevar.toDate();
                                timevar.tz(timezone.name());
                                // console.log(timevar);
                                document.getElementById("Datetime<?php echo $i; ?>").innerHTML = timevar;
                                <?php  $i++; ?>
                                </script>
                                <td><?= $reviewp->comment ?></td>
                                <td><a href="{{ URL::Route('AdminReviewsDelete', $reviewp->review_id) }}"><input type="button" class="btn btn-success" value="Delete"></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div align="left" id="paglink"><?php echo $provider_reviews->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
            </div><!-- /.tab-pane -->
             <div class="tab-pane" id="tab_2">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>{{ trans('customize.Provider');}} Name</th>
                            <th>{{ trans('customize.User');}}</th>
                            <th>Rating</th>
                            <th>Date and Time</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                        <?php $i =0; ?>
                        <?php foreach ($user_reviews as $reviewu) { ?>
                            <tr>
                                <td><?php echo $reviewu->walker_first_name." ".$reviewu->walker_last_name; ?> </td>
                                <td><?php echo $reviewu->owner_first_name." ".$reviewu->owner_last_name; ?> </td>
                                <td><?= $reviewu->rating ?></td>
                                <td id= 'time<?php echo $i; ?>' >
                                <script>
                                var timezone = jstz.determine();
                                 // console.log(timezone.name());
                                var timevar = moment.utc("<?php echo $reviewu->created_at; ?>");
                                timevar.toDate();
                                timevar.tz(timezone.name());
                                // console.log(timevar);
                                document.getElementById("time<?php echo $i; ?>").innerHTML = timevar;
                                <?php  $i++; ?>
                                </script>
                                <td><?= $reviewu->comment ?></td>
                                <td><a href="{{ URL::Route('AdminReviewsDeleteDog', $reviewu->review_id) }}"><input type="button" class="btn btn-success" value="Delete"></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div align="left" id="paglink"><?php echo $provider_reviews->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
           </div>
       </div>
   </div>
</div>

@stop