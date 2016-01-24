
@extends('layout')

@section('content')

 <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title"><?= $title ?></h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                 <form method="post" id="basic-form" action="{{ URL::Route('AdminInformationUpdate') }}"  enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $id ?>">

                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="title" class="form-control" placeholder="Title" value="<?= $info_title ?>">
                                                                                   
                                        </div>

                                                                           

                                        <div class="form-group">
                                            <label>Icon File</label>

                                           
                                             <input type="file" name="icon" class="form-control" >
                                             <br>
                                              <?php if($icon != "") {?>
                                            <img src="<?= $icon; ?>" height="50" width="50">
                                            <?php } ?><br>
                                            
                                            <p class="help-block">Please Upload image in jpg, png format.</p>
                                        </div>

                                        <div class="form-group">
                                        <label>Description </label>
                                        <textarea id="editor1" name="description" rows="10" cols="124">
                                            <?= $description ?>  
                                        </textarea>
                                        </div>
                                  

                                   
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">

                                        <button id="add_info" type="submit" class="btn btn-primary btn-flat btn-block">Save Page</button>
                                    </div>
                                </form>
                            </div>




<?php
if($success == 1) { ?>
<script type="text/javascript">
    alert('Page Updated Successfully');
</script>
<?php } ?>
<?php
if($success == 2) { ?>
<script type="text/javascript">
    alert('Sorry Something went Wrong');
</script>
<?php } ?>

<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

<script type="text/javascript">
$("#basic-form").validate({
  rules: {
    title: "required",
    description: "required",
  
  }
});

</script>

@stop