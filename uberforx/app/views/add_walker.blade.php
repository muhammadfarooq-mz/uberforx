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
                                    <h3 class="box-title">Add {{ trans('customize.Provider');}}</h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form role="form" class="form" id="main-form" method="post" action="{{ URL::Route('AdminProviderUpdate') }}"  enctype="multipart/form-data">
                                <input type="hidden" name="id" value="0>">

                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" name="first_name" value="" placeholder="First Name" >
                                          
                                        </div>

                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" type="text" name="last_name" value="" placeholder="Last Name">
                                
                                        </div>

                                         <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" type="email" name="email" value="" placeholder="Email">
                                
                                        </div>

                                         <div class="form-group">
                                            <label>Phone</label>
                                            <input class="form-control" type="text" name="phone" value="" placeholder="Phone">
                                
                                        </div>

                                         <div class="form-group">
                                            <label>Bio</label>
                                            <input class="form-control" type="text" name="bio" value="" placeholder="Bio">
                                
                                        </div>


                                         <div class="form-group">
                                            <label>Address</label>
                                            <input class="form-control" type="text" name="address" value="" placeholder="Address">
                                
                                        </div>


                                         <div class="form-group">
                                            <label>State</label>
                                            <input class="form-control" type="text" name="state" value="" placeholder="State">
                                
                                        </div>


                                         <div class="form-group">
                                            <label>Country</label>
                                            <input class="form-control" type="text" name="country" value="" placeholder="Country">
                                
                                        </div>

                                        <div class="form-group">
                                            <label>Zip Code</label>
                                            <input class="form-control" type="text" name="zipcode" value="" placeholder="Zip Code">
                                
                                        </div>


                                        <div class="form-group">
                                            <label>Picture</label>
                                            <input class="form-control" type="file" name="pic" >
                                            <p class="help-block">Please Upload image in jpg, png format.</p>
                                        </div>
                                   
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary btn-flat btn-block">Update Changes</button>
                                    </div>
                                </form>
                            </div>

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