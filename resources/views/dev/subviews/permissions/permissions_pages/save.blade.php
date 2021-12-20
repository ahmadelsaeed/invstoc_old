@extends('dev.main_layout')

@section('subview')

    <div class="row">
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

                $header_text="Add New Permission Page";
                $per_page_id="";
                if ($permission_page_data!="") {
                    $header_text="Edit ".$permission_page_data->page_name;
                    $per_page_id=$permission_page_data->per_page_id;
                }

                ?>



                <div class="panel panel-info">
                    <div class="panel-heading"><h2><?=$header_text?></h2></div>
                    <div class="panel-body">
                        <div class="row">
                            <form id="save_form" action="<?=url("dev/permissions/permissions_pages/save/$per_page_id")?>" method="POST" enctype="multipart/form-data">

                                <?php

                                $normal_tags=array("page_name","sub_sys");

                                $attrs = generate_default_array_inputs_html(
                                    $normal_tags,
                                    $permission_page_data,
                                    "yes",
                                    "required"
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

                                <?php

                                    if(isset($permission_page_data->all_additional_permissions)){
                                        $permission_page_data->all_additional_permissions=json_decode($permission_page_data->all_additional_permissions);
                                    }

                                    echo generate_array_input(
                                        $label_name=["Add Another Permission"],
                                        $field_name=["all_additional_permissions"],
                                        $required=[""],
                                        $type=["text"],
                                        $values=[""],
                                        $class=["form-control"],
                                        $add_tiny_mce="",
                                        $default_values=array(),
                                        $data=$permission_page_data
                                    );

                                ?>


                                {{csrf_field()}}
                                <input type="submit" value="Save" class="col-md-4 col-md-offset-4 btn btn-primary btn-lg">
                            </form>

                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

@endsection
