@extends('admin.main_layout')

@section('subview')

    <link rel="stylesheet" href="{{url('/public_html')}}/css/style.css" type="text/css" media="screen">
    <style>
        hr{
            width: 100%;
            height:1px;
        }
        .select_related_pages{
            width: 50%;
        }

        .select_related_sites{
            width: 50%;
        }
    </style>
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

    $header_text="Add New Language";
    $lang_id="";

    $lang_img_id="";
    $lang_img_path="";
    $lang_img_title="";
    $lang_img_alt="";


    $disabled_upload_imgs="";
    if (is_object($lang_data)) {
        $header_text="edit '".$lang_data->lang_title;
        $lang_id=$lang_data->lang_id;

        $lang_img_id=$lang_data->lang_img_id;
        if ($lang_img_id > 0)
        {
            $lang_img_path=url('/'.$lang_data->lang_img_path);
            $lang_img_title=$lang_data->lang_img_title;
            $lang_img_alt=$lang_data->lang_img_alt;
        }


        $disabled_upload_imgs="disabled";
    }


    ?>


    <div class="panel panel-info">
        <div class="panel-heading">
            <?=$header_text?>
        </div>
        <div class="panel-body">
            <div class="">
                <form id="save_form" action="<?=url("admin/langs/save_lang/$lang_id")?>" method="POST" enctype="multipart/form-data">

                    <?php

                    $normal_tags=array("lang_title","lang_text");
                    $attrs = generate_default_array_inputs_html(
                        $normal_tags,
                        $lang_data,
                        "yes",
                        $required = "required"
                    );

                    $attrs[0]["lang_title"]="Lang Symbol (Must be 2 Characters)  <span style='color:#f56954;'>";
                    $attrs[0]["lang_text"]="Lang Display Text";

                    echo
                    generate_inputs_html(
                        reformate_arr_without_keys($attrs[0]),
                        reformate_arr_without_keys($attrs[1]),
                        reformate_arr_without_keys($attrs[2]),
                        reformate_arr_without_keys($attrs[3]),
                        reformate_arr_without_keys($attrs[4]),
                        reformate_arr_without_keys($attrs[5])
                    );

                    echo
                    generate_select_tags(
                            $field_name="lang_direction",
                            $label_name="Lang Direction",
                            $text=["left to right","right to left"],
                            $values=["ltr","rtl"],
                            $selected_value="",
                            $class="form-control",
                            $multiple="",
                            $required="",
                            $disabled = "",
                            $data =$lang_data ,
                            $hide_label_grid = false
                    );


                    ?>
                    <hr>


                    <?=generate_img_tags_for_form(
                                "lang_img_file[]",
                                "lang_img_file",
                                "",
                                "lang_img_checkbox",
                                "yes",
                                "",
                                $lang_img_path,
                                $lang_img_title,
                                $lang_img_alt,
                                "",
                                $disabled_upload_imgs,
                                $displayed_img_width="100",
                                $display_label="Upload Language Icon" )?>


                    {{csrf_field()}}
                    <input id="submit" type="submit" value="save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">

                </form>
            </div>

        </div>
    </div>


@endsection



