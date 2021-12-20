@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')



    <div class="ui-block" style="width: 100%;">
        <div class="ui-block-title">
            <h6 class="title">{{show_content($group_keywords,"group_settings_header")}}</h6>
        </div>
        <div class="ui-block-content">


            <!-- Personal Information Form  -->

            <form action="{{url(strtolower(session('language_locale', 'en'))."/group/settings/$group_obj->group_name/$group_obj->group_id")}}" method="post">
                <div class="row">

                    <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group label-floating is-select">
                            <?php

                            echo generate_select_tags(
                                $field_name = "group_post_options",
                                $label_name = "",
                                $text = [
                                    show_content($group_keywords,"group_settings_creator_only_label"),
                                    show_content($group_keywords,"group_settings_all_members_label"),
                                    show_content($group_keywords,"group_settings_admins_only_label")
                                ],
                                $values=["0","1","2"],
                                $selected_value = "",
                                $class="form-control selectpicker",
                                $multiple="",
                                $required="required",
                                $disabled = "",
                                $data = $group_obj,
                                $parent_div_class = "");
                            ?>

                        </div>
                    </div>
                </div>
                {!! csrf_field() !!}
                <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                    <button class="btn btn-primary btn-lg full-width" type="submit">{{show_content($general_static_keywords,"save_btn")}}</button>
                </div>

            </form>
        </div>
    </div>




@endsection
