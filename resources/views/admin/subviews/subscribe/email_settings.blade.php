
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

    ?>

    <!--new_editor-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/ckeditor.js"></script>
    <!--END new_editor-->

    <div class="row">
        <form action="<?=url("admin/subscribe/email_settings")?>" method="POST" enctype="multipart/form-data">

            <h1>Default Data For Email</h1>

            <?php echo generate_inputs_html(
                    $labels_name = array("sender_email","email_subject","email_body","limit"),
                    $fields_name = array("sender_email","email_subject","email_body","limit"),
                    $required = array("required","required","required","required"),
                    $type = array("email","text","textarea","text"),
                    $values = array($email_settings->sender_email,$email_settings->email_subject,$email_settings->email_body,$email_settings->limit),
                    $class = array("form-control","form-control","form-control","form-control"))
            ?>

            {{csrf_field()}}
            <input id="submit" type="submit" value="Save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">
        </form>
    </div>

    <script>
        CKEDITOR.replace( 'email_body_id' );
    </script>

@endsection