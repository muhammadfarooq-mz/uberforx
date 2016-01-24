@extends('web.providerLayout')

@section('content')

<div class="col-md-12 mt">

    @if(Session::has('message'))
    <div class="alert alert-{{ Session::get('type') }}">
        <b>{{ Session::get('message') }}</b> 
    </div>
    @endif

    <?php if ($status == -1) { ?>
        <div class="alert alert-danger">
            <b>Please Upload all the documents to get your account verified.</b> 
        </div>
    <?php } elseif ($status == 0) { ?>
        <div class="alert alert-danger">
            <b>Thanks for submiting the documents. Your accont will be activated on verifying your documents.</b> 
        </div>
    <?php } else { ?>
        <div class="alert alert-success">
            <b>Your account is active now.</b> 
        </div>
    <?php } ?>
    <div class="content-panel">
        <h4>Update Documents</h4><br>
        <form class="form-horizontal style-form" method="post" action="{{ URL::Route('providerUpdateDocuments') }}" enctype="multipart/form-data">
            <?php foreach ($documents as $document) { ?>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label"><?= $document->name ?></label>
                    <div class="col-sm-1">
                        <?php
                        foreach ($walker_document as $walker_documents) {
                            if ($document->id == $walker_documents->document_id) {
                                ?>
                                <a href="<?= $walker_documents->url ?>" target="_blank">View File</a>
                            <?php }
                        }
                        ?>
                    </div>
                    <div class="col-sm-5" style="">
                        <input id="doc" type="file" class="form-control" name="<?= $document->id ?>" >
                    </div>
                </div>

<?php } ?>

            <span class="col-sm-2"></span>
            <button id="upload" type="submit" class="btn btn-info">Upload Documents</button>
            <button type="reset" class="btn btn-info">Reset</button>

        </form>
    </div>


</div>


@stop 