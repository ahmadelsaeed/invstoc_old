@extends('admin.main_layout')


@section('subview')

    <style>
        hr{
            width: 100%;
            height:1px;
        }

        .hide_input{
            display: none;
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

    $header_text="Add New $selected_cat_type";
    $cat_id="";
    $parent_id="0";
    $parent_or_child="0";

    if ($cat_data!="") {
        $header_text="Edit ".$cat_data->cat_name. " Last Edit At: ".$cat_data->updated_at;

        $cat_id=$cat_data->cat_id;
        $parent_id=$cat_data->parent_cat_id;

        if ($parent_id>0&&in_array($selected_cat_type,["trip","article"])) {
            $parent_or_child="1";
        }


    }


    ?>

    <!--new_editor-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/ckeditor.js"></script>
    <!--END new_editor-->


    <div class="panel panel-primary">
        <div class="panel-heading">
            <?=$header_text?>
        </div>
        <div class="panel-body">
            <div>

                <form id="save_form" action="<?=url("admin/category/save_cat/$selected_cat_type/$cat_id")?>" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="selected_cat_type" value="{{$selected_cat_type}}">

                    <?php
                        if(in_array($selected_cat_type,["activity","article"])){
                                echo generate_depended_selects(
                                    $field_name_1="parent_cat",
                                    $field_label_1="Parent Or Child",
                                    $field_text_1=["Parent","Child"],
                                    $field_values_1=["0","1"],
                                    $field_selected_value_1=$parent_or_child,
                                    $field_required_1="",
                                    $field_class_1="form-control",
                                    $field_name_2="parent_id",
                                    $field_label_2="Select Parent Cat",
                                    $field_text_2=convert_inside_obj_to_arr($all_parent_cats,"cat_name"),
                                    $field_values_2=convert_inside_obj_to_arr($all_parent_cats,"cat_id"),
                                    $field_selected_value_2=$parent_id,
                                    $field_2_depend_values=return_numbet_of_elments_based_on_arr_number($all_parent_cats,"1"),
                                    $field_required_2="",
                                    $field_class_2="form-control"
                                );
                        }
                        elseif($selected_cat_type == "book")
                        {
                            echo generate_depended_selects(
                                $field_name_1="main_activity",
                                $field_label_1="Select Parent Activity",
                                $field_text_1=convert_inside_obj_to_arr($all_parent_cats,"cat_name"),
                                $field_values_1=convert_inside_obj_to_arr($all_parent_cats,"cat_id"),
                                $field_selected_value_1=(is_object($cat_data)?$cat_data->main_activity:""),
                                $field_required_1="",
                                $field_class_1="form-control",
                                $field_name_2="parent_id",
                                $field_label_2="Select Child Activity",
                                $field_text_2=convert_inside_obj_to_arr($all_child_cats,"cat_name"),
                                $field_values_2=convert_inside_obj_to_arr($all_child_cats,"cat_id"),
                                $field_selected_value_2=(is_object($cat_data)?$cat_data->parent_id:""),
                                $field_2_depend_values=convert_inside_obj_to_arr($all_child_cats,"parent_id"),
                                $field_required_2="",
                                $field_class_2="form-control"
                            );
                        }
                        else{
                            echo '<input type="hidden" name="parent_id" value="0">';
                        }
                    ?>


                    <div class="col-md-12">

                        <div class="panel-group" id="accordion">

                            <?php foreach($lang_ids as $lang_key=>$lang_item): ?>
                            <?php
                            $lang_id=$lang_item->lang_id;
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$lang_id}}">
                                    <h4 class="panel-title" >
                                        <a>
                                            <img src="<?=get_image_or_default($lang_item->lang_img_path)?>" width="25"> Category Data For Lang {{$lang_item->lang_title}}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$lang_id}}" class="panel-collapse collapse <?php echo ($lang_key==0)?"in":""; ?>">
                                    <div class="panel-body">

                                        <input type="hidden" name="lang_id[]" value="{{$lang_id}}">
                                        <?php

                                        $translate_data=array();


                                        $current_row = $cat_data_translate_rows->filter(function ($value, $key) use($lang_id) {
                                            if ($value->lang_id == $lang_id)
                                            {
                                                return $value;
                                            }

                                        });

                                        if(is_object($current_row->first())){
                                            $translate_data=$current_row->first();
                                        }


                                        //$required=($lang_key==0)?"required":"";
                                        $required="";

                                        $normal_tags=array(
                                            "cat_name","owner_name","cat_slug","cat_short_desc","cat_body",
                                            "cat_meta_title","cat_meta_desc","cat_meta_keywords"
                                        );

                                        $attrs = generate_default_array_inputs_html(
                                            $normal_tags,
                                            $translate_data,
                                            "yes",
                                            $required
                                        );


                                        foreach ($attrs[1] as $key => $value) {
                                            $attrs[1][$key].="[]";
                                        }

                                        $attrs[0]["cat_name"]="Name";
                                        $attrs[0]["owner_name"]="Book Owner Name";
                                        $attrs[0]["cat_short_desc"]="Short Description";
                                        $attrs[0]["cat_body"]="Body";
                                        $attrs[0]["cat_slug"]="Slug <span style='color:#f56954;'>(Spaces & Symboles will removed)";
                                        $attrs[0]["cat_meta_title"]="Meta Title";
                                        $attrs[0]["cat_meta_desc"]="Meta Desc";
                                        $attrs[0]["cat_meta_keywords"]="Meta Keywords";

                                        $attrs[3]["cat_short_desc"]="textarea";
                                        $attrs[3]["cat_body"]="textarea";
                                        $attrs[3]["cat_meta_desc"]="textarea";
                                        $attrs[3]["cat_meta_keywords"]="textarea";

                                        $attrs[5]["cat_body"].=" ckeditor";

                                        if($selected_cat_type !=="book"){
                                            $hide_arr=[
                                                "owner_name"
                                            ];
                                            foreach ($hide_arr as $key => $val) {
                                                $attrs[0][$val]="";
                                                $attrs[5][$val]="hide_input";
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

                                    </div>
                                </div>
                            </div>

                            <?php endforeach;?>
                        </div>

                    </div>

                    <?php if(in_array($selected_cat_type,["activity","article","book"])): ?>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Images
                            </div>
                            <div class="panel-body">
                                <?php
                                $img_obj=isset($cat_data->cat_small_img)?$cat_data->cat_small_img:"";
                                echo generate_img_tags_for_form(
                                    $filed_name="small_img",
                                    $filed_label="small_img",
                                    $required_field="",
                                    $checkbox_field_name="small_img_checkbox",
                                    $need_alt_title="yes",
                                    $required_alt_title="no",
                                    $old_path_value="",
                                    $old_title_value="",
                                    $old_alt_value="",
                                    $recomended_size="",
                                    $disalbed="",
                                    $displayed_img_width="100",
                                    $display_label="Upload Small Image",
                                    $img_obj
                                );
                                ?>


                                <?php if(false): ?>
                                    <hr>

                                    <?php
                                    $img_obj = isset($cat_data->cat_big_img) ? $cat_data->cat_big_img : "";
                                    echo generate_img_tags_for_form(
                                        $filed_name="big_img",
                                        $filed_label="big_img",
                                        $required_field="",
                                        $checkbox_field_name="big_img_checkbox",
                                        $need_alt_title="yes",
                                        $required_alt_title="no",
                                        $old_path_value="",
                                        $old_title_value="",
                                        $old_alt_value="",
                                        $recomended_size="",
                                        $disalbed="",
                                        $displayed_img_width="100",
                                        $display_label="Upload Big Image",
                                        $img_obj
                                    );
                                    ?>

                                    <hr>

                                    <?php
                                    echo
                                    generate_slider_imgs_tags(
                                        $slider_photos=(isset($cat_data->slider_imgs)&&isset_and_array($cat_data->slider_imgs))?$cat_data->slider_imgs:"",
                                        $field_name="cat_slider_file",
                                        $field_label="Category Slider",
                                        $field_id="cat_slider_file_id",
                                        $accept="image/*",
                                        $need_alt_title="yes"
                                    );
                                    ?>
                                <?php endif;


                                ?>


                            </div>
                        </div>
                    <?php endif; ?>

                      <?php if(in_array($selected_cat_type,["book"])): ?>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                PDF Book
                            </div>
                            <div class="panel-body">
                                <?php
                                $img_obj=isset($cat_data->cat_pdf_book)?$cat_data->cat_pdf_book:"";
                                echo generate_img_tags_for_form(
                                    $filed_name="pdf_book",
                                    $filed_label="pdf_book",
                                    $required_field="",
                                    $checkbox_field_name="pdf",
                                    $need_alt_title="no",
                                    $required_alt_title="no",
                                    $old_path_value="",
                                    $old_title_value="",
                                    $old_alt_value="",
                                    $recomended_size="",
                                    $disalbed="",
                                    $displayed_img_width="100",
                                    $display_label="Upload PDF Book",
                                    $img_obj
                                );
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    {{csrf_field()}}
                    <button type="submit" class="col-md-3 col-md-offset-2 btn btn-primary btn-lg">Save</button>
                    <!--general_check_validation-->

                    <button type="button" data-formid="save_form" data-link="<?=url("admin/category/check_validation_for_save_cat/$cat_id")?>" class="col-md-3 col-md-offset-2 btn btn-warning btn-lg general_check_validation">Check Data</button>

                    <div class="col-md-8 col-md-offset-2">
                        <div class="general_check_validation_msg"></div>
                    </div>
                    <!--END general_check_validation-->

                </form>
            </div>
        </div>
    </div>






    @endsection



