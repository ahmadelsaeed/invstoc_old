<label href="#" class="link-anew-groub left_icons dropdown load_groups_workshops_btn"
       data-toggle="dropdown" data-target=".events_notifications" title="{{show_content($user_homepage_keywords,"create_new_workshop_group_header")}}">
    <i class="fa fa-users" aria-hidden="true"></i>
</label>

<div class="dropdown-menu events_notifications_container">
    <i class="fa fa-sort-asc right_m" aria-hidden="true"></i>
    <h1 class="left_sidebar_header">{{show_content($user_homepage_keywords,"create_new_workshop_group_header")}}</h1>


    <?php
        echo generate_radio_btns(
            $field_name="group_or_workshop",
            $label_name="",
            $text=[show_content($user_homepage_keywords,"group_label"), show_content($user_homepage_keywords,"workshop_label")],
            $values=["Group", "Workshop"],
            $selected_value="Group",
            $class="group_or_workshop_class",
            $data = "",
            $grid = "col-md-12",
            $hide_label=false,
            $additional_data="",
            $custom_style="",
            $field_type="radio",
            $make_ck_button=true,
            "col-md-6"
        );
    ?>

    <div class="col-md-12 workshop_selects_activity" style="display: none;">

        <?php
            echo generate_depended_selects(
                $field_name_1="workshop_parent_activity",
                $field_label_1=show_content($user_homepage_keywords,"parent_analytics"),
                $field_text_1=$parent_activities->pluck("cat_name")->all(),
                $field_values_1=$parent_activities->pluck("cat_id")->all(),
                $field_selected_value_1="",
                $field_required_1="",
                $field_class_1="form-control",
                $field_name_2="workshop_child_activity",
                $field_label_2=show_content($user_homepage_keywords,"sub_analytics"),
                $field_text_2=$child_activities->pluck("cat_name")->all(),
                $field_values_2=$child_activities->pluck("cat_id")->all(),
                $field_selected_value_2="",
                $field_2_depend_values=$child_activities->pluck("parent_id")->all(),
                $field_required_2="",
                $field_class_2="form-control",
                $field_data_name1 = "",
                $field_data_values1="",
                $field_data_name2 = "",
                $field_data_values2="",
                $first_grid="col-md-12 padd-left padd-right",
                $second_grid="col-md-12 padd-left padd-right"
            );
        ?>

    </div>


    <div class="col-md-12">
        <input type="text" class="form-control group_or_workshop_name_class"
               placeholder="{{show_content($user_homepage_keywords,"name_label")}}">
    </div>

    <div class="col-md-12" style="margin-top: 5px;">
        <button class="btn btn-green pull-right add_group_or_workshop">{{show_content($general_static_keywords,"add_btn")}}</button>
    </div>

    <div class="col-md-12">
        <hr class="chat_separator_line">
    </div>

    <div class="col-md-12 load_groups_workshops">
        <ul class="load_groups_workshops_ul scroll"></ul>
    </div>
</div>