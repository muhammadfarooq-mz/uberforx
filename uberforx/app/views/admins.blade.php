@extends('layout')

@section('content')
<a id="addinfo" href="{{ URL::Route('AdminAddAdmin') }}"><input type="button" class="btn btn-info btn-flat btn-block" value="Add Admin"></a>
<br >
<div class="box box-success">
 <div align="left" id="paglink"><?php echo $admin->links(); ?></div>
                <table class="table table-bordered">
                 <thead>
                        <tr>
                            <th>AdminID</th>
                            <th>Username</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                            <?php foreach ($admin as $admins) { ?>
                            <tr>
                                <td>{{$admins->id}}</td>
                                <td>{{$admins->username}}</td>
                                <td><?php if($admins->address != NULL){ echo $admins->address; }else{ echo "";} ?> </td>
                                <td >
                                    <div class="dropdown">
                                      <button class="btn btn-flat btn-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                        Actions
                                        <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::Route('AdminAdminsEdit', $admins->id) }}">Edit Admin</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::Route('AdminAdminsDelete', $admins->id) }}">Delete Admin</a></li>

                                      </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                    </tbody>
                </table>
         


</div>

        <div align="left" id="paglink"><?php echo $admin->links(); ?></div>
      </div>
@stop