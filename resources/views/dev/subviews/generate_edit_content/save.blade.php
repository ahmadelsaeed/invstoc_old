@extends('dev.main_layout')

@section('subview')

    <style>
        hr{
            width: 100%;
            height:1px;
        }
    </style>
    <?php

    $sess_obj=new \Illuminate\Support\Facades\Session();


    if (isset($success)&&!empty($success)) {
        echo $success;
    }

    echo \Illuminate\Support\Facades\Session::get("msg");



    $header_text="Add Method";
    $method_id="";

    $method_img_obj="";

    if ($method_data!="") {
        $header_text="Edit ".$method_data->method_title;
        $method_id=$method_data->id;

        $method_img_obj=$method_data->method_img;
    }

    ?>

    <div class="row">
        <form action="<?=url("/dev/generate_edit_content/save/$method_id")?>" method="POST" enctype="multipart/form-data">

            <h1><?=$header_text?></h1>

            <?php if(count($errors)>0): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach($errors->all() as $error): ?>
                    <li>{{ $error }}</li>
                    <?php endforeach;?>
                </ul>
            </div>
            <?php endif; ?>

            <?php

            $normal_tags=array('method_name', 'method_title');
            $attrs = generate_default_array_inputs_html(
                    $normal_tags,
                    $method_data,
                    true
            );

            echo
            generate_inputs_html(
                    reformate_arr_without_keys($attrs[0]),
                    reformate_arr_without_keys($attrs[1]),
                    reformate_arr_without_keys($attrs[2]),
                    reformate_arr_without_keys($attrs[3]),
                    reformate_arr_without_keys($attrs[4]),
                    reformate_arr_without_keys($attrs[5])
            );
            ?>

            <hr>

            <div class="panel-group" id="accordion">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#normal_fields">Generate Normal Fields</a>
                        </h4>
                    </div>
                    <div id="normal_fields" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php

                            $normal_tags=array("label_name","field_name","required","type","class");
                            $attrs = generate_default_array_inputs_html(
                                    $normal_tags,
                                    $method_data,
                                    true,
                                    ""
                            );

                            foreach ($normal_tags as $key => $value) {
                                $attrs[0][$value].="*";
                                $attrs[2][$value]="";
                            }

                            $attrs[0]["label_name"]='label_name* <span class="text-warning">if this field empty,it takes default value of field_name</span>';
                            $attrs[0]["required"]='required* <span class="text-warning">if this field empty,it takes default value required</span>';
                            $attrs[0]["type"]='type* <span class="text-warning">if this field empty,it takes default value text</span>';
                            $attrs[0]["class"]='class* <span class="text-warning">if this field empty,it takes default value form-control</span>';

                            echo
                            generate_inputs_html(
                                    reformate_arr_without_keys($attrs[0]),
                                    reformate_arr_without_keys($attrs[1]),
                                    reformate_arr_without_keys($attrs[2]),
                                    reformate_arr_without_keys($attrs[3]),
                                    reformate_arr_without_keys($attrs[4]),
                                    reformate_arr_without_keys($attrs[5])
                            );

                            ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#image_fields">Generate Image Fields</a>
                        </h4>
                    </div>
                    <div id="image_fields" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php
                            $image_tags=array("img_field_name","img_required","img_need_alt_title","img_width","img_height");
                            $attrs = generate_default_array_inputs_html(
                                    $image_tags,
                                    $method_data,
                                    true,
                                    ""
                            );

                            foreach ($image_tags as $key => $value) {
                                $attrs[0][$value].="*";
                            }

                            $attrs[0]["img_required"]="img_required* <span class=\"text-warning\">if this field empty,it takes default value required</span>";
                            $attrs[0]["img_need_alt_title"]="img_need_alt_title* <span class=\"text-warning\">if this field empty,it takes default value no</span>";
                            $attrs[0]["img_width"]="img_width* <span class=\"text-warning\">if this field empty,it takes default value 0</span>";
                            $attrs[0]["img_height"]="img_height* <span class=\"text-warning\">if this field empty,it takes default value 0</span>";


                            echo
                            generate_inputs_html(
                                    reformate_arr_without_keys($attrs[0]),
                                    reformate_arr_without_keys($attrs[1]),
                                    reformate_arr_without_keys($attrs[2]),
                                    reformate_arr_without_keys($attrs[3]),
                                    reformate_arr_without_keys($attrs[4]),
                                    reformate_arr_without_keys($attrs[5])
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#select_fields">Generate Select Fields</a>
                        </h4>
                    </div>
                    <div id="select_fields" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php
                            $select_tags=array("select_field_name","select_label_name","select_class","select_multiple","select_model","select_text_col","select_val_col");
                            $attrs = generate_default_array_inputs_html(
                                    $select_tags,
                                    $method_data,
                                    true,
                                    ""
                            );

                            foreach ($select_tags as $key => $value) {
                                $attrs[0][$value].="*";
                            }

                            $attrs[0]["select_label_name"]="select_label_name* <span class=\"text-warning\">if this field empty,it takes the value of select_field_name</span>";
                            $attrs[0]["select_class"]="select_class* <span class=\"text-warning\">if this field empty,it takes no value</span>";
                            $attrs[0]["select_multiple"]="select_multiple* <span class=\"text-warning\">if this field empty,it takes no value</span>";

                            echo
                            generate_inputs_html(
                                    reformate_arr_without_keys($attrs[0]),
                                    reformate_arr_without_keys($attrs[1]),
                                    reformate_arr_without_keys($attrs[2]),
                                    reformate_arr_without_keys($attrs[3]),
                                    reformate_arr_without_keys($attrs[4]),
                                    reformate_arr_without_keys($attrs[5])
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#array_fields">Generate array Fields</a>
                        </h4>
                    </div>
                    <div id="array_fields" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php

                            echo generate_array_input(
                                    $label_name=array(
                                            "set_name",
                                            "set_fields* ",
                                            "set_labels* <span class=\"text-warning\">if this field empty,it takes value of set_fields</span>",
                                            "set_types* <span class=\"text-warning\">if this field empty,it takes value text</span>",
                                            "set_classes* <span class=\"text-warning\">if this field empty,it takes no value</span>",
                                            "add_tiny_mce* <span class=\"text-warning\">if this field empty,it takes value no</span>"
                                    ),
                                    $field_name=array("set_name","set_fields","set_labels","set_types","set_classes","add_tiny_mce"),
                                    $required=array("","","","","",""),
                                    $type=array("text","text","text","text","text","text"),
                                    $values=array("","","","","",""),
                                    $class=array("form-control","form-control","form-control","form-control","form-control","form-control"),
                                    $add_tiny_mce="",
                                    $default_values=array(),
                                    $method_data
                            );

                            ?>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#slider_fields">Generate slider Fields</a>
                        </h4>
                    </div>
                    <div id="slider_fields" class="panel-collapse collapse">
                        <div class="panel-body">
                            <?php

                            echo generate_array_input(
                                    $label_name=array(
                                            "slider_field_name",
                                            "slider_label_name <span class=\"text-warning\">if this field empty,it takes the value of slider_field_name</span>",
                                            "slider_accept <span class=\"text-warning\">if this field empty,it takes value *</span>",
                                            "slider_need_alt_title <span class=\"text-warning\">if this field empty,it takes value no</span>",
                                            "slider_additional_inputs_arr* <span class=\"text-warning\">if this field empty,it takes value empty</span>",
                                            "slider_width <span class=\"text-warning\">if this field empty,it takes value 0</span>",
                                            "slider_height <span class=\"text-warning\">if this field empty,it takes value 0</span>"
                                    ),
                                    $field_name=array("slider_field_name","slider_label_name","slider_accept","slider_need_alt_title","slider_additional_inputs_arr","slider_width","slider_height"),
                                    $required=array("","","","","","",""),
                                    $type=array("text","text","text","text","text","text","text"),
                                    $values=array("","","","","","",""),
                                    $class=array("form-control","form-control","form-control","form-control","form-control","form-control","form-control"),
                                    $add_tiny_mce="",
                                    $default_values=array(),
                                    $method_data
                            );

                            ?>
                        </div>
                    </div>
                </div>






            </div>








            <hr>


            <?php
            echo generate_img_tags_for_form(
                    $filed_name="method_img",
                    $filed_label="method_img",
                    $required_field="",
                    $checkbox_field_name="method_img_checkbox",
                    $need_alt_title="no",
                    $required_alt_title="no",
                    $old_path_value="",
                    $old_title_value="",
                    $old_alt_value="",
                    $recomended_size="",
                    $disalbed="",
                    $displayed_img_width="50",
                    $display_label="",
                    $img_obj=$method_img_obj
            );
            ?>

            {{csrf_field()}}
            <input type="submit" value="Save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">
        </form>
    </div>

@endsection