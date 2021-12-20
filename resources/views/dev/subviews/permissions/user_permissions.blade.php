@extends('dev.main_layout')

@section('subview')


    <!-- Select 2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- END Select 2 -->

    <script>
        $(function(){
            $('.select_user_permissions').select2();
        });
    </script>

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


                <div class="panel panel-info">
                    <div class="panel-heading"><h2>Edit User ({{$user_obj->full_name}}) Permissions</h2></div>
                    <div class="panel-body">
                        <div class="row">
                            <form id="save_form" action="<?=url("dev/permissions/assign_permission_for_this_user")?>" method="POST" enctype="multipart/form-data">


                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Page Name</th>
                                        <th>Show Action</th>
                                        <th>Add Action</th>
                                        <th>Edit Action</th>
                                        <th>Remove Name</th>
                                        <th>Other Permissions</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($all_user_permissions as $user_per_key=>$user_per_val): ?>
                                    <tr>
                                        <th>{{$all_permission_pages[$user_per_key]->page_name}}</th>
                                        <th>
                                            <?php
                                            echo generate_multi_accepters(
                                                $accepturl="",
                                                $item_obj=$user_per_val,
                                                $item_primary_col="per_id",
                                                $accept_or_refuse_col="show_action",
                                                $model='App\models\permissions\permissions_m',
                                                $accepters_data=["0"=>"Refused","1"=>"Accepted"]
                                            );
                                            ?>
                                        </th>
                                        <th>
                                            <?php
                                            echo generate_multi_accepters(
                                                $accepturl="",
                                                $item_obj=$user_per_val,
                                                $item_primary_col="per_id",
                                                $accept_or_refuse_col="add_action",
                                                $model='App\models\permissions\permissions_m',
                                                $accepters_data=["0"=>"Refused","1"=>"Accepted"]
                                            );
                                            ?>
                                        </th>
                                        <th>
                                            <?php
                                            echo generate_multi_accepters(
                                                $accepturl="",
                                                $item_obj=$user_per_val,
                                                $item_primary_col="per_id",
                                                $accept_or_refuse_col="edit_action",
                                                $model='App\models\permissions\permissions_m',
                                                $accepters_data=["0"=>"Refused","1"=>"Accepted"]
                                            );
                                            ?>
                                        </th>
                                        <th>
                                            <?php
                                            echo generate_multi_accepters(
                                                $accepturl="",
                                                $item_obj=$user_per_val,
                                                $item_primary_col="per_id",
                                                $accept_or_refuse_col="delete_action",
                                                $model='App\models\permissions\permissions_m',
                                                $accepters_data=["0"=>"Refused","1"=>"Accepted"]
                                            );
                                            ?>
                                        </th>
                                        <th>

                                            <?php
                                            $all_additional_permissions=$all_permission_pages[$user_per_key]->all_additional_permissions;
                                            $all_additional_permissions=json_decode($all_additional_permissions,true);

                                            if(!isset_and_array($all_additional_permissions)){
                                                $all_additional_permissions=[];
                                            }

                                            $selected_additional_permissions=json_decode($user_per_val->additional_permissions);
                                            if(!isset_and_array($selected_additional_permissions)){
                                                $selected_additional_permissions=[];
                                            }

                                            echo generate_select_tags(
                                                $field_name="additional_perms_new".$user_per_val->per_id,
                                                $label_name="Select Other Permissions",
                                                $text=$all_additional_permissions,
                                                $values=$all_additional_permissions,
                                                $selected_value=$selected_additional_permissions,
                                                $class="form-control select_user_permissions",
                                                $multiple="multiple",
                                                $required="",
                                                $disabled = "",
                                                $data = ""
                                            );

                                            ?>

                                        </th>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>




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
