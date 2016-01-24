@extends('layout')

@section('content')

<a id="adddoc" href="{{ URL::Route('AdminDocumentTypesEdit', 0) }}"><input type="button" class="btn btn-info btn-flat btn-block" value="Add New Document Type"></a>


<br>


                    <div class="box box-danger">

                       <form method="get" action="{{ URL::Route('/admin/searchdoc') }}">
                                <div class="box-header">
                                    <h3 class="box-title">Filter</h3>
                                </div>
                                <div class="box-body row">

                                <div class="col-md-6 col-sm-12">

                                <select id="searchdrop" class="form-control" name="type">
                                    <option value="docid" id="docid">ID</option>
                                    <option value="docname" id="docname">Name</option>
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
                     <div align="left" id="paglink"><?php echo $types->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>
                <table class="table table-bordered">
                                <tbody>
                                        <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Actions</th>

                                        </tr>

                            <?php foreach ($types as $type) { ?>
                            <tr>
                                <td><?= $type->id ?></td>
                                <td><?= $type->name ?>
                                    <?php if($type->is_default){ ?>
                                         <font style="color:green">(Default)</font>
                                    <?php } ?>
                                </td>
                                <td><a id="edit" href="{{ URL::Route('AdminDocumentTypesEdit', $type->id) }}"><input type="button" class="btn btn-success" value="Edit"></a>
                                <?php if(!$type->is_default){ ?><a id="delete" href="{{ URL::Route('AdminDocumentTypesDelete', $type->id) }}"><input type="button" class="btn btn-danger" value="Delete"></a><?php } ?></td>
                            </tr>
                            <?php } ?>
                    </tbody>
                </table>

                 <div align="left" id="paglink"><?php echo $types->appends(array('type'=>Session::get('type'), 'valu'=>Session::get('valu')))->links(); ?></div>

                </div>


@stop