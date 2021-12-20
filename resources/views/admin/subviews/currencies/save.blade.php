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

    $header_text="Add New Currency";
    $id="";

    $cur_img="";
    $cur_img_path="";


    $disabled_upload_imgs="";
    if (is_object($currency_data)) {
        $header_text="Edit >> ".$currency_data->cur_to;
        $id=$currency_data->id;

        $cur_img=$currency_data->cur_img;
        if ($cur_img > 0)
        {
            $cur_img_path=url('/'.$currency_data->cur_img_path);
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
                <form id="save_form" action="<?=url("admin/currencies/save_currency/$id")?>" method="POST" enctype="multipart/form-data">

                    <?php

                    $normal_tags=array("cur_to","cur_sign");
                    $attrs = generate_default_array_inputs_html(
                            $normal_tags,
                            $currency_data,
                            "yes",
                            $required = "required"
                    );

                    $attrs[0]["cur_to"]=" Currency Code ex.EGP | USD <span style='color:#f56954;'>(Spaces & Symboles will removed)";
                    $attrs[0]["cur_sign"]=" Currency Sign ex. $ | LE ";

                    echo
                    generate_inputs_html(
                            reformate_arr_without_keys($attrs[0]),
                            reformate_arr_without_keys($attrs[1]),
                            reformate_arr_without_keys($attrs[2]),
                            reformate_arr_without_keys($attrs[3]),
                            reformate_arr_without_keys($attrs[4]),
                            reformate_arr_without_keys($attrs[5])
                    );

                    if (false)
                    {
                        echo
                        generate_select_tags(
                            $field_name="show_in_homepage",
                            $label_name="Show in Homepage",
                            $text=["YES","NO"],
                            $values=[1,0],
                            $selected_value="",
                            $class="form-control",
                            $multiple="",
                            $required="",
                            $disabled = "",
                            $data =$currency_data ,
                            $hide_label_grid = false
                        );

                        echo
                        generate_select_tags(
                            $field_name="show_in_menu",
                            $label_name="Show in Menu",
                            $text=["YES","NO"],
                            $values=[1,0],
                            $selected_value="",
                            $class="form-control",
                            $multiple="",
                            $required="",
                            $disabled = "",
                            $data =$currency_data ,
                            $hide_label_grid = false
                        );

                    }

                    ?>
                    <hr>


                    <?=generate_img_tags_for_form(
                            "cur_img_file[]",
                            "cur_img_file",
                            "",
                            "cur_img_checkbox",
                            "no",
                            "",
                            $cur_img_path,
                            "",
                            "",
                            "",
                            $disabled_upload_imgs,
                            $displayed_img_width="24",
                            $display_label="Upload Currency Image in 24px x 24px" )?>


                    {{csrf_field()}}
                    <input id="submit" type="submit" value="Save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">

                </form>
            </div>

        </div>
    </div>


@endsection



