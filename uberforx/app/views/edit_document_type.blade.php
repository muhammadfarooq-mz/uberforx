
@extends('layout')

@section('content')

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= $title ?></h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form method="post" id="basic" action="{{ URL::Route('AdminDocumentTypesUpdate') }}"  enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="box-body">
            <div class="form-group">
                <label>Document Name</label>
                <input type="text" class="form-control" name="name" placeholder="Document Type Name" value="<?= $name ?>">

            </div>




        </div><!-- /.box-body -->

        <div class="box-footer">


            <button id="doc" type="submit" class="btn btn-primary btn-flat btn-block">Save</button>
        </div>
    </form>
</div>




<?php if ($success == 1) { ?>
    <script type="text/javascript">
        alert('document Type Updated Successfully');
        document.location.href = "{{ URL::Route('AdminDocumentTypes') }}";
    </script>
<?php } ?>
<?php if ($success == 2) { ?>
    <script type="text/javascript">
        alert('Sorry Something went Wrong');
    </script>
<?php } ?>


<script type="text/javascript">
    $("#basic").validate({
        rules: {
            name: "required",
        }
    });

</script>

@stop