@extends('layout')

@section('content')


                        <div class="row">
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>
                                       {{ $ledger?$ledger->total_referrals:0 }}
                                    </h3>
                                    <p>
                                        Total Referrals
                                    </p>
                                </div>
                                <div class="icon">
                                    {{$ledger?$ledger->referral_code:0}}
                                </div>
                              
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>
                                       {{ $ledger?round($ledger->amount_earned):0 }}
                                    </h3>
                                    <p>
                                        Credits Earned
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-cash"></i>
                                </div>
                                
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        {{ $ledger?round($ledger->amount_spent):0 }}
                                    </h3>
                                    <p>
                                       Credits Spent
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-battery-low"></i>
                                </div>
                                
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-purple">
                                <div class="inner">
                                    <h3>
                                        {{ $ledger?round($ledger->amount_earned - $ledger->amount_spent):0 }}
                                    </h3>
                                    <p>
                                       Balance Credits
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                
                            </div>
                        </div><!-- ./col -->
      
                    </div>




                    <div class="col-md-6 col-sm-12">

                    <div class="box box-danger">

                        <form method="get" action="{{ URL::Route('/admin/sortur') }}">
                                <div class="box-header">
                                    <h3 class="box-title">Sort</h3>
                                </div>
                                <div class="box-body row">

                                <div class="col-md-6 col-sm-12">
                                    <select class="form-control" id="searchdrop" name="type">

                                    <option value="userid" <?php if(isset($_GET['type']) && $_GET['type']=='userid') {echo 'selected="selected"';}?> id="provid">{{ trans('customize.User');}} ID</option>
                                    <option value="username" <?php if(isset($_GET['type']) && $_GET['type']=='username') {echo 'selected="selected"';}?> id="pvname">{{ trans('customize.User');}} Name</option>
                                    <option value="useremail" <?php if(isset($_GET['type']) && $_GET['type']=='useremail') {echo 'selected="selected"';}?> id="pvemail">{{ trans('customize.User');}} Email</option>
                                </select>
                                    <br>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <select class="form-control" id="searchdroporder" name="valu">
                                    <option value="asc" <?php if(isset($_GET['valu']) && $_GET['valu']=='asc') {echo 'selected="selected"';}?> id="asc">Ascending</option>
                                    <option value="desc" <?php if(isset($_GET['valu']) && $_GET['valu']=='desc') {echo 'selected="selected"';}?> id="desc">Descending</option>
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

                       <form method="get" action="{{ URL::Route('/admin/searchur') }}">
                                <div class="box-header">
                                    <h3 class="box-title">Filter</h3>
                                </div>
                                <div class="box-body row">

                                <div class="col-md-6 col-sm-12">

                                <select class="form-control" id="searchdrop" name="type">
                                  <option value="userid" id="userid">{{ trans('customize.User');}} ID</option>
                                  <option value="username" id="username">{{ trans('customize.User');}} Name</option>
                                  <option value="useremail" id="useremail">{{ trans('customize.User');}} Email</option>
                                  <option value="useraddress" id="useraddress">{{ trans('customize.User');}} Address</option>
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
                <div align="left" id="paglink"><?php echo $owners->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                <table class="table table-bordered">
                                <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>State</th>
                                            <th>Zipcode</th>

                                        </tr>
                                     <?php foreach ($owners as $owner) { ?>
                                    <tr>
                                        <td><?= $owner->id ?></td>
                                        <td><?php echo $owner->first_name." ".$owner->last_name; ?> </td>
                                        <td><?= $owner->email ?></td>
                                        <td><?= $owner->phone ?></td>
                                        <td><?= $owner->address ?></td>
                                        <td><?= $owner->state ?></td>
                                        <td><?= $owner->zipcode ?></td>
                                        </tr>
                                    <?php } ?>
                    </tbody>
                </table>

                <div align="left" id="paglink"><?php echo $owners->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                </div>




@stop