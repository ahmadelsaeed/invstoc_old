
@extends('admin.main_layout')

@section('subview')

    <?php
    if (count($errors->all()) > 0)
    {
        $dump = "<div class='alert alert-danger'>";
        foreach ($errors->all() as $key => $error)
        {
            $dump .= $error." <br>";
        }
        $dump .= "</div>";

        echo $dump;
    }

    if (isset($success)&&!empty($success)) {
        echo $success;
    }

    $header_text='Add Emails <i class="fa fa-plus"></i>';

    ?>


    <div class="row">
        <form action="<?=url("admin/subscribe/save_email")?>" method="POST" enctype="multipart/form-data">

            <h1><?=$header_text?></h1>

            <?php echo generate_inputs_html(
                    $labels_name = array("Email"),
                    $fields_name = array("email"),
                    $required = array(""),
                    $type = array("email"),
                    $values = array(""),
                    $class = array("form-control"))
            ?>

            <br />
            <br />
            <hr>
            <h2>You can also add emails by excel sheet</h2>

            <p> go to uploader
                <a href="<?= url("admin/uploader") ?>" target="_blank"> Here >> </a>
                 copy link and paste here
            </p>
            <?php echo generate_inputs_html(
                    $labels_name = array("sheet_url"),
                    $fields_name = array("sheet_url"),
                    $required = array(""),
                    $type = array("sheet_url"),
                    $values = array(""),
                    $class = array("form-control"))
            ?>

            {{csrf_field()}}
            <input id="submit" type="submit" value="Save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">
        </form>
    </div>

@endsection