@extends('front.subviews.groups_workshops.workshop.workshop_main_layout')

@section('workshop_subview')

    <div class="ui-block" style="width: 100%;">
        <div class="ui-block-title">
            <h6 class="title">
                {{show_content($general_static_keywords,"change_activity_btn")}} {{$workshop_obj->workshop_name}}
            </h6>
        </div>
        <div class="card-body">

            <form action="{{url(strtolower(session('language_locale', 'en'))."/workshop/change_activity/$workshop_obj->workshop_name/$workshop_obj->workshop_id")}}" method="post">
                <div class="col-md-12 text-center" style="margin-top: 10px;">

                    <div class="col-md-12 workshop_selects_activity">
                        <?php

                            echo generate_depended_selects(
                                $field_name_1="parent_activity",
                                $field_label_1=show_content($user_homepage_keywords,"parent_analytics"),
                                $field_text_1=$parent_activities->pluck("cat_name")->all(),
                                $field_values_1=$parent_activities->pluck("cat_id")->all(),
                                $field_selected_value_1=$parent_activity->parent_id,
                                $field_required_1="",
                                $field_class_1="form-control",
                                $field_name_2="cat_id",
                                $field_label_2=show_content($user_homepage_keywords,"sub_analytics"),
                                $field_text_2=$child_activities->pluck("cat_name")->all(),
                                $field_values_2=$child_activities->pluck("cat_id")->all(),
                                $field_selected_value_2=$workshop_obj->cat_id,
                                $field_2_depend_values=$child_activities->pluck("parent_id")->all(),
                                $field_required_2="",
                                $field_class_2="form-control",
                                $field_data_name1 = "",
                                $field_data_values1="",
                                $field_data_name2 = "",
                                $field_data_values2="",
                                $first_grid="col-md-6 padd-left padd-right",
                                $second_grid="col-md-6 padd-left padd-right"
                            );
                        ?>
                    </div>

                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary">{{show_content($general_static_keywords,"save_btn")}}</button>
                </div>
            </form>

        </div>
    </div>

@endsection
