@extends('admin.main_layout')

@section('subview')
    <style>
        hr{
            width: 100%;
            height:1px;
        }
    </style>

    <!-- tinymce -->
    <script type="text/javascript" src="<?= url('public_html/tinymce/tinymce.min.js') ?>"></script>
    <script>
        tinymce.init({
            selector: "textarea.cat_body",
            theme: "modern",
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
            style_formats: [
                {title: 'Bold text', inline: 'b'},
                {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                {title: 'Example 1', inline: 'span', classes: 'example1'},
                {title: 'Example 2', inline: 'span', classes: 'example2'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ]
        });
    </script>
    <!-- END tinymce -->

    <!--new_editor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/ckeditor.js"></script>


    <!--END new_editor-->

    <div class="panel panel-primary">
        <div class="panel-heading">
            Edit <?=$content_title?> ({{$current_lang->lang_title}})
        </div>
        <div class="panel-body">
            <div class="">
                <form action="<?= url("admin/edit_content/$method_lang_id/$content_method") ?>" method="POST" enctype="multipart/form-data">

                    <?php if ($img_path!=""): ?>
                    <img src="<?=$img_path?>" style="max-width:100%;" />
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                    <div class="alert alert-info">
                        Success
                    </div>
                    <?php endif; ?>

                    <?php
                    foreach ($select_tags as $key => $select) {

                        echo generate_select_tags(
                            $select["field_name"],
                            $select["label_name"],
                            $select["text"],
                            $select["values"],
                            $select["selected_value"],
                            $select["class"],
                            $select["multiple"],
                            $select["required"],
                            $select["disabled"],
                            $content_data
                        );

                    }
                    ?>

                    <hr>
                    <?php

                    $input_fields=  convert_inside_obj_to_arr($normal_tags,"field_name","array");
                    $attrs = generate_default_array_inputs_html($input_fields,$content_data,true,"required");

                    foreach ($normal_tags as $key => $field) {
                        if(isset($field["label_name"])){
                            $attrs[0][$field["field_name"]]=$field["label_name"];
                        }
                        if(isset($field["required"])){
                            $attrs[2][$field["field_name"]]=$field["required"];
                        }
                        if(isset($field["field_type"])){
                            $attrs[3][$field["field_name"]]=$field["field_type"];
                        }
                        if(isset($field["type"])){
                            $attrs[3][$field["field_name"]]=$field["type"];
                        }
                        if(isset($field["class"])){
                            $attrs[5][$field["field_name"]]=$field["class"];
                        }

                    }


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

                    <hr />

                    <?php foreach ($imgs_tags as $key => $img_field): ?>


                    <?php
                    echo generate_img_tags_for_form(
                        $filed_name=$img_field["field_name"],
                        $filed_label=$img_field["field_name_without_brackets"],
                        $required_field=$img_field["required"],
                        $checkbox_field_name=$img_field["field_name_without_brackets"]."_upload_new_img_chcekbox",
                        $need_alt_title=$img_field["need_alt_title"],
                        $required_alt_title="no",

                        $old_path_value=  url($img_field["img"]->path),
                        $old_title_value=$img_field["img"]->title,
                        $old_alt_value=$img_field["img"]->alt,

                        $recomended_size="Recomended Width*Height (".$img_field["width"]."px*".$img_field["height"]."px)",
                        $disalbed="disabled",
                        $displayed_img_width="50",
                        $display_label="Upload New Image For "."<span class='text-danger'>".$img_field["field_name_without_brackets"]."</span>"
                    );
                    ?>

                    <hr/>
                    <?php endforeach; ?>


                    <hr />

                    <!--arr_inputs-->
                    <?php foreach ($arr_tags as $key => $set): ?>

                    <?php
                    $label_name=array();
                    $field_name=array();
                    $required=array();
                    $type=array();
                    $values=array();
                    $class=array();
                    $add_tiny_mce=array();

                    ?>

                    <?php foreach ($set as $key => $field): ?>

                <?php
                        $label_name[]=$field["label_name"];
                        $field_name[]=$field["field_name"];
                        $required[]="";
                        $type[]=$field["field_type"];


                        $old_values=array();
                        $dataFieldName=$field["field_name"];

                        if (isset($content_data->$dataFieldName)) {
                            $old_values=$content_data->$dataFieldName;
                        }
                        $values[]=$old_values;
                        $class[]=$field["field_class"];
                        $add_tiny_mce[]=$field["add_tiny_mce"];
                        ?>


            <?php endforeach; ?>

                    <?php

                    if(is_array($add_tiny_mce)){
                        $add_tiny_mce=$add_tiny_mce[0];
                    }

                    echo generate_array_input(
                        $label_name,
                        $field_name,
                        $required,
                        $type,
                        $values,
                        $class,
                        $add_tiny_mce
                    );
                    ?>

                    <hr>
                    <?php endforeach; ?>
                <!--END arr_inputs-->


                    <hr />
                    <!--sliders-->

                    <?php foreach ($slider_fields as $key => $field): ?>
                    <?php

                    $slider_name="slider".($key+1);
                    $slider_data=$content_data->$slider_name;

                    $additional_inputs_arr=array();
                    $attrs=array(array(),array(),array(),array(),array(),array());

                    if(!empty($field["additional_inputs_arr"])&&!is_array($field["additional_inputs_arr"])){
                        $field["additional_inputs_arr"]=explode(",",$field["additional_inputs_arr"]);
                    }

                    if (is_array($field["additional_inputs_arr"])&&  count($field["additional_inputs_arr"])) {
                        $slider_old_data=array();
                        if (isset($slider_data->other_fields)) {
                            $slider_old_data=$slider_data->other_fields;
                        }


                        $attrs = generate_default_array_inputs_html(
                            $input_fields=$field["additional_inputs_arr"]
                            ,$slider_old_data
                        );

                        foreach ($attrs[1] as $key => $value) {
                            $attrs[1][$key]=$value."[]";
                        }

                        foreach ($attrs[2] as $key => $value) {
                            $attrs[2][$key]="";
                        }


                    }

                    echo generate_slider_imgs_tags(
                        $slider_photos=$slider_data->slider_objs,
                        $field_name=$field["field_name"],
                        $field_label=$field["label_name"]." Recomended Size width*height (".$field["width"]."px*".$field["height"]."px)",
                        $field_id=$field["field_id"],
                        $accept="image/*",
                        $need_alt_title=$field["need_alt_title"],
                        $additional_inputs_arr=array($attrs[0], $attrs[1], $attrs[2], reformate_arr_without_keys($attrs[3]), $attrs[4], reformate_arr_without_keys($attrs[5]))
                    );


                    ?>
                    <br/>
                    <hr/>
                    <?php endforeach; ?>
                <!--END sliders-->

                    {{csrf_field()}}
                    <input type="submit" value="Save" name="submit" class="col-md-4 col-md-offset-4 btn btn-info btn-lg">
                </form>
            </div>
        </div>
    </div>







@endsection