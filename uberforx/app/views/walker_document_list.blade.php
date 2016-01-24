@extends('layout')

@section('content')

<?php
$counter = 1;
?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= $title ?> : <?= ucwords($walker->first_name . " " . $walker->last_name) ?></h3>
    </div><!-- /.box-header -->

    <div class="box-info tbl-box">

        <table class="table table-bordered">
            <tbody>
                <?php foreach ($documents as $document) { ?>
                    <tr>
                        <th style="width: 50%;">
                            <?= $document->name ?>
                        </th>
                        <td style="width: 50%;">
                            <?php
                            if (isset($walker_document[0])) {
                                foreach ($walker_document as $walk_doc) {
                                    if ($document->id == $walk_doc->document_id) {
                                        ?>
                                        <a href="<?= $walk_doc->url ?>" target="_blank">View File</a>
                                        <?php
                                    } else {
                                        echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                                    }
                                }
                            } else {
                                echo "<span class='badge bg-red'>" . Config::get('app.blank_fiend_val') . "</span>";
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
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