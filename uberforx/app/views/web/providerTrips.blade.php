@extends('web.providerLayout')

@section('content')

<div class="col-lg-12 col-sm-12 mb">
    @if(Session::has('message'))
    <div class="alert alert-{{ Session::get('type') }}">
        <b>{{ Session::get('message') }}</b> 
    </div>
    @endif
    <div class="form-panel">
        <h4 class="mb"></i> Filter Results</h4>
        <form class="form-inline" role="form" method="get" action="{{ URL::Route('ProviderTrips') }}">
            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail2">Start Date</label>
                <input type="text" class="form-control" id="start-date" name="start_date" value="{{ Input::get('start_date') }}" placeholder="Start Date">
            </div>
            <div class="form-group">
                <label class="sr-only" for="exampleInputPassword2">End Date</label>
                <input type="text" class="form-control" id="end-date" name="end_date" placeholder="End Date"  value="{{ Input::get('end_date') }}">
            </div>
            <button id="filter" type="submit" name="submit" value="filter" class="btn btn-theme"><i class="fa fa-filter fa-x"></i> Filter Results</button>
            <button id="download" type="submit" name="submit" value="export" class="btn btn-theme"><i class="fa fa-download fa-x"></i> Download Report</button>

        </form>
    </div>
</div>


<div class="col-md-3 col-sm-3 mb" style="height:150px;">
    <div class="darkblue-panel pn"  style="height:200px;">
        <div class="darkblue-header">
            <h5>Total {{trans('customize.Request')}}s</h5>
        </div>
        <h1 class="mt"><i class="fa fa-user fa-2x"></i></h1>
        <p>  </p>
        <footer>
            <div class="centered">
                <h5><i class=""></i> {{ $total_rides }}</h5>
            </div>
        </footer>
    </div>
</div>
<div class="col-md-3 col-sm-3 mb" style="height:150px;">
    <div class="darkblue-panel pn"  style="height:200px;">
        <div class="darkblue-header">
            <h5>Total Distance Covered</h5>
        </div>
        <h1 class="mt"><i class="fa fa-map-marker fa-2x"></i></h1>
        <p>  </p>
        <footer>
            <div class="centered">
                <h5><i class=""></i> {{ sprintf2($total_distance, 2) }} Miles</h5>
            </div>
        </footer>
    </div>
</div>
<div class="col-md-3 col-sm-3 mb" style="height:150px;">
    <div class="darkblue-panel pn"  style="height:200px;">
        <div class="darkblue-header">
            <h5>Average Rating</h5>
        </div>
        <h1 class="mt"><i class="fa fa-star fa-2x"></i></h1>
        <p>  </p>
        <footer>
            <div class="centered">
                <h5><i class=""></i>{{ sprintf2($average_rating, 2) }}</h5>
            </div>
        </footer>
    </div>
</div>

<div class="col-md-3 col-sm-3 mb" style="height:150px;">
    <div class="darkblue-panel pn"  style="height:200px;">
        <div class="darkblue-header">
            <h5>Total Earnings</h5>
        </div>
        <h1 class="mt"><i class="fa fa-money fa-2x"></i></h1>
        <p>  </p>
        <footer>
            <div class="centered">
                <h5><i class=""></i> <?php echo $currency . '.'; ?> {{ sprintf2($total_earnings, 2) }}</h5>
            </div>
        </footer>
    </div>
</div>

<div class="col-md-12 mt" style="position:relative;top:25px;" >
    <div class="content-panel">
        <table class="table table-hover" id="trip-table">
            <thead>
                <tr>
                    <th>Pickup</th>
                    <th>User</th>
                    <th>Earning</th>
                    <th>Type of Service</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                <tr class="trip-basic" data-id="{{ route('ProviderTripDetail',$request->id)}}">
                    <td>{{ date('l, F d Y h:i A',strtotime($request->request_start_time)) }}</td>
                    <td>{{ $request->first_name }} {{ $request->last_name }}</td>
                    <td>{{ sprintf2($request->total, 2) }}</td>
                    <td>{{ $request->type }}</td>
                </tr>
                <tr class="trip-detail" style="display:none;">
                    <td colspan="4"><center>Loading...</center></td>
            </tr>
            @endforeach

            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        $(".trip-basic").click(function () {
            var $this = $(this);
            var id = $(this).data('id');
            $this.next().toggle();
            $.ajax({url: id,
                type: 'get',
                success:
                        function (msg) {
                            if (msg === 'false') {
                                alert('No Data Found');
                            }
                            else {
                                $this.next().html(msg);
                            }
                        }
            });

        });


    });

</script>

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
    var tour = new Tour(
            {
                name: "providerappTrips",
            });

    // Add your steps. Not too many, you don't really want to get your users sleepy
    tour.addSteps([
        {
            element: "#flow21",
            title: "Setting Availability",
            content: "Click on profile to change your availability and other {{trans('customize.Provider')}} details",
        },
    ]);

    // Initialize the tour
    tour.init();

    // Start the tour
    tour.start();
</script>


@stop 