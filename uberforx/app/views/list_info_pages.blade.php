@extends('layout')

@section('content')

<a id="addinfo" href="{{ URL::Route('AdminInformationEdit', 0) }}"><input type="button" class="btn btn-info btn-flat btn-block" value="Add New Page"></a>

<br>


                    <div class="box box-danger">

                       <form method="get" action="{{ URL::Route('/admin/searchinfo', 0) }}">
                                <div class="box-header">
                                    <h3 class="box-title">Filter</h3>
                                </div>
                                <div class="box-body row">

                                <div class="col-md-6 col-sm-12">

                                <select id="searchdrop" class="form-control" name="type">
                                    <option value="infoid" id="infoid">ID</option>
                                    <option value="infotitle" id="infotitle">Title</option>
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



                <div class="box box-info tbl-box">
                    <div align="left" id="paglink"><?php echo $informations->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                <table class="table table-bordered">
                                <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Actions</th>

                                        </tr>
                                    <?php foreach ($informations as $information) { ?>
                                    <tr>
                                        <td><?= $information->id ?></td>
                                        <td><?= $information->title ?></td>
                                        <td><a id="edit" href="{{ URL::Route('AdminInformationEdit', $information->id) }}"><input type="button" class="btn btn-success" value="Edit"></a>
                                        <a id="delete" href="{{ URL::Route('AdminInformationDelete', $information->id) }}"><input type="button" class="btn btn-danger" value="Delete"></a></td>
                                    </tr>
                                    <?php } ?>
                    </tbody>
                </table>

                <div align="left" id="paglink"><?php echo $informations->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>

                </div>


@stop