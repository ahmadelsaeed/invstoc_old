@extends('admin.main_layout')

@section('subview')

    <!-- Select 2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- END Select 2 -->

    <script>
        $(function(){
            $('.select_user_langs').select2();
        });
    </script>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <style>
                    hr{
                        width: 100%;
                        height:1px;
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
                ?>


                <?php

                    $header_text="Add New Admin";
                    $user_id="";
                    $password_required = "required";
                    if ($user_data!="") {
                        $header_text="Edit ".$user_data->full_name;
                        $user_id=$user_data->user_id;
                        $password_required = "";
                    }

                ?>



                <div class="panel panel-info">
                    <div class="panel-heading"><h2><?=$header_text?></h2></div>
                    <div class="panel-body">
                        <div class="row">
                            <form id="save_form" action="<?=url("admin/users/save/$user_id")?>" method="POST" enctype="multipart/form-data">

                                <?php

                                $normal_tags=array("email","username","full_name","password");

                                $attrs = generate_default_array_inputs_html(
                                    $normal_tags,
                                    $user_data,
                                    "yes",
                                    "required"
                                );

                                $attrs[2]["password"]=$password_required;
                                $attrs[3]["password"]="password";
                                $attrs[4]["password"]="";


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
                                <br>
                                <?php

                                    $all_langs_txt=convert_inside_obj_to_arr($all_langs,'lang_text');
                                    $all_langs_values=convert_inside_obj_to_arr($all_langs,'lang_id');

                                    $selected_langs=[];
                                    if(!empty($user_data->allowed_lang_ids))
                                    {
                                        $selected_langs=json_decode($user_data->allowed_lang_ids);
                                    }

                                    echo generate_select_tags(
                                            $field_name="allowed_lang_ids",
                                            $label_name="Allowed Languages For This Admin",
                                            $text=$all_langs_txt,
                                            $values=$all_langs_values,
                                            $selected_value=$selected_langs,
                                            $class="form-control select_user_langs",
                                            $multiple="multiple",
                                            $required="required",
                                            $disabled = "",
                                            $data = ""
                                    );

                                ?>

                                <hr>

                                <?php
                                    if (false){
                                        $img_obj = isset($user_data->user_img_file) ? $user_data->user_img_file : "";

                                        echo generate_img_tags_for_form(
                                            $filed_name="user_img_file",
                                            $filed_label="user_img_file",
                                            $required_field="",
                                            $checkbox_field_name="user_img_checkbox",
                                            $need_alt_title="no",
                                            $required_alt_title="",
                                            $old_path_value="",
                                            $old_title_value="",
                                            $old_alt_value="",
                                            $recomended_size="",
                                            $disalbed="",
                                            $displayed_img_width="100",
                                            $display_label="Upload User Image",
                                            $img_obj
                                        );
                                    }


                                ?>


                                {{csrf_field()}}
                                <input type="submit" value="حفظ" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">
                            </form>

                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

@endsection
