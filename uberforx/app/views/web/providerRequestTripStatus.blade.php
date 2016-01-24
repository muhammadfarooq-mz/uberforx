@extends('web.providerLayout')

@section('content')

<div class="col-md-12 mt">

    @if(Session::has('message'))
            <div class="alert alert-{{ Session::get('type') }}">
                <b>{{ Session::get('message') }}</b> 
            </div>
    @endif

    @if($status == 5)
            <div class="alert alert-success">
                <b>Your Request is Completed. Please Rate the Owner.</b> 
            </div>
    @endif

    @if($status== 1)
            <div class="alert alert-success">
                <b>You have successfully accepted the request. Please be there on time.</b> 
            </div>
    @endif

    <div class="content-panel" style="min-height:600px;">
        <h4>Trip Status</h4><br>
        <div class="col-md-6">
          <div class="col-md-8">
          <br>
              <div id="map-canvas"></div>
          </div>
        </div>
        <div  class="col-md-6">
          
          <div class="col-md-12">
            <div  class="col-md-12">
            <h3>Request ID #<?= $request->id ?></h3>
            <img src="<?= $type->icon ?>" class="img-circle" width="60">
            <b>&nbsp; <?= $type->name ?></b>
            </div>
            <?php if (isset($request->confirmed_walker) && $request->confirmed_walker != 0) { ?>
              <div  class="col-md-12">
              <div class="col-lg-12" style="height:50px;postion:relative;top:30px;">
              <b>Owner Profile</b>
              </div>

              <div class="col-lg-2">
                <p><a href="profile.html"><img src="<?php if($user->picture!=''){echo $user->picture;}else{echo asset_url().'/web/default_profile.png';} ?>" class="img-circle" width="50"></a></p>
              </div>
              <div class="col-lg-8">
                <div class="col-lg-12">
                  <b>{{ isset($user->first_name)?$user->first_name:'' }} {{ isset($user->last_name)?$user->last_name:'' }}</b>
                </div>
                <div class="col-lg-12">
                @for ($i = 1; $i <= $rating; $i++)
                    <span><img src="{{ asset_url() }}/web/star.png"></span>
                @endfor

                </div>


              </div>
              <div class="col-lg-12">
              <br>
                
                @if($status && $status == 1)
                <a href="{{ URL::Route('providerTripChangeState',2) }}"><button class="btn btn-primary" style="width:50%" id="flow24">Started</button></a>
                @endif

                @if($status && $status == 2)
                <a href="{{ URL::Route('providerTripChangeState',3) }}"><button class="btn btn-primary" style="width:50%" id="flow25">Arrived</button></a>
                @endif

                @if($status && $status == 3)
                <a href="{{ URL::Route('providerTripChangeState',4) }}"><button class="btn btn-primary" style="width:50%" id="flow26">{{trans('customize.Trip')}} Started</button></a>
                @endif



              </div>
            

              </div>
            <?php } ?>

            @if($status && $status == 4)

                <div class="col-lg-12">
                    
                    <h3></h3>
                    <form method="get" action="{{ URL::Route('providerTripChangeState',5) }}">
                      <input type="hidden" name="request_id" value="{{ $request->id }}">
                      <div class="col-lg-7">
                       
                      <br>
                      <label>Drop Address</label>

                      <textarea class="form-control" rows="5" required name="address" id="destination_address"></textarea>

                      <br>
                      <input type="Submit" class="btn btn-primary" value="Complete Trip">
                      </div>
                    </form>
                    
                </div>

                @endif
            @if($status && $status == 5)
            <?php if(Session::get('skipReviewProvider') == NULL){ ?>

                <div class="col-lg-12">
                    
                    <h3>Leave Your Review</h3>
                    <form method="get" action="{{ URL::Route('providerTripChangeState',6) }}">
                      <input type="hidden" name="request_id" value="{{ $request->id }}">
                      <div class="col-lg-7">

                        <select class="form-control" name="rating" >

                        <option value="5">5 Star</option>
                        <option value="4">4 Star</option>
                        <option value="3">3 Star</option>
                        <option value="2">2 Star</option>
                        <option value="1">1 Star</option>
                      </select>
                      <br>
                      <textarea class="form-control" rows="5" name="review"></textarea>
                      <br>

                      <input type="Submit" class="btn btn-primary" value="Submit Review" id="flow28">
                      <a href="{{route('providerSkipReview',Session::get('request_id'))}}"><span class="btn btn-default">Skip</span></a>

                      </div>
                    </form>
                    
                </div>
                  <?php } ?>
                @endif

          </div>
          
        </div>
    </div>



          
</div>

<script type="text/javascript">
  initialize_map(<?= $user->latitude ?>,<?= $user->longitude ?>);
  <?php if($request->D_latitude !='') {?>
  get_destination_address(<?php echo $request->D_latitude.','.$request->D_longitude ?>);
  <?php } ?>

</script>


<script type="text/javascript">
      var tour = new Tour(
        {
          name: "providerappProfile",
        });

        // Add your steps. Not too many, you don't really want to get your users sleepy
        tour.addSteps([
          {
            element: "#flow24", 
            title: "{{trans('customize.Provider')}} Started", 
            content: "Click on Started button when you are ready to start the {{trans('customize.Trip')}}", 
            
          },
          {
            element: "#flow25", 
            title: "{{trans('customize.Provider')}} Arrived", 
            content: "Click on Arrived button to start the {{trans('customize.Trip')}}", 
            
          },
          {
            element: "#flow26", 
            title: "Start {{trans('customize.Trip')}}", 
            content: "Click on {{trans('customize.Trip')}} Started button to start the {{trans('customize.Trip')}}", 
            
          },
          {
            element: "#flow27", 
            title: "Complete {{trans('customize.Trip')}}", 
            content: "Enter the drop address and complete the {{trans('customize.Trip')}}", 
            placement: "right",
          },
          {
            element: "#flow28", 
            title: "Leave your Review", 
            content: "Leave a rating and review for the user", 
            
          },
       ]);

     // Initialize the tour
     tour.init();

     // Start the tour
     tour.start();
</script>




@stop 