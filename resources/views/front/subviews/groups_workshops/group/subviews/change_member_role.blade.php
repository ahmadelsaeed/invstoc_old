@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')

    <div class="panel panel-info">
        <div class="panel-heading">{{show_content($group_keywords,"change_role_header")}} {{$member_data->full_name}}</div>
        <div class="panel-body">

            <form action="{{url(strtolower(session('language_locale', 'en'))."/group/change_member_role/$group_obj->group_name/$group_obj->group_id?member_id=$member_data->g_m_id")}}" method="post">

                <?php
                    echo generate_select_tags(
                        $field_name="user_role",
                        $label_name=show_content($group_keywords,"change_role_label"),
                        $text=[
                            show_content($group_keywords,"admin_role_label"),
                            show_content($group_keywords,"member_role_label")
                        ],
                        $values=["admin","member"],
                        $selected_value=[""],
                        $class="form-control",
                        $multiple="",
                        $required="",
                        $disabled = "",
                        $data = $member_data,
                        $parent_div_class = "form-group col-md-12",
                        $hide_label=false
                    );
                ?>


                <div class="col-md-12 text-center" style="margin-top: 10px;">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-green">{{show_content($group_keywords,"change_role_btn")}}</button>
                </div>
            </form>

        </div>
    </div>

@endsection
