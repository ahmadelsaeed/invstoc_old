
<!-- Fixed Sidebar Left -->
@if(auth()->check())
    <div class="fixed-sidebar">

    <div class="fixed-sidebar-left sidebar--small" id="sidebar-left">

        <a href="{{url("/home")}}" class="logo">
            <div class="img-wrap">
                <img src="{{url("/")}}/public_html/front/new_design/img/logo.png" alt="Invstoc">
            </div>
        </a>

        <div class="mCustomScrollbar" data-mcs-theme="dark">
            <ul class="left-menu">

                <li>
                    <a href="#" class="js-sidebar-open">
                        <svg class="olymp-menu-icon left-menu-icon"  data-toggle="tooltip"  data-original-title="OPEN MENU"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-menu-icon"></use></svg>
                    </a>
                </li>

                <li class="hot_orders_div">
                    <a href="#" class="hot_orders_btn"  data-toggle="modal" data-target="#hot_orders_modal">
                        <i data-toggle="tooltip" data-original-title='{{show_content($user_homepage_keywords,"hot_orders_header")}}' class="fa fa-fire" aria-hidden="true"></i>
                    </a>
                </li>

                <li class="orders_list_div">
                    <a href="#" class="orders_list_btn" data-toggle="modal" data-target="#orders_list_modal">
                        <i data-toggle="tooltip"   data-original-title='{{show_content($user_homepage_keywords,"your_orders_list")}}' class="fa fa-shopping-bag" aria-hidden="true"></i>
                    </a>
                </li>

                <li class="show_comming_events_div">
                    <a href="#" class="show_comming_events" data-toggle="modal" data-target="#show_comming_events_modal">
                        <i data-toggle="tooltip"   data-original-title='{{show_content($user_homepage_keywords,"events_notification_header")}}' class="fa fa-clock-o" aria-hidden="true"></i>
                    </a>
                </li>

                <li class="load_groups_workshops_div">
                    <a href="#" class="load_groups_workshops_btn" data-toggle="modal" data-target="#load_groups_workshops_modal">
                        <i data-toggle="tooltip"   data-original-title='{{show_content($user_homepage_keywords,"create_new_workshop_group_header")}}' class="fa fa-users" aria-hidden="true"></i>
                    </a>
                </li>

                <li class="get_my_cash_back_accounts_div">
                    <a href="#" class="get_my_cash_back_accounts" data-toggle="modal" data-target="#get_my_cash_back_accounts_modal">
                        <i data-toggle="tooltip"    data-original-title='{{show_content($user_homepage_keywords,"cashback_header")}}' class="fa fa-briefcase" aria-hidden="true"></i>
                    </a>
                </li>

                <?php if(isset($banks->slider1) && isset($banks->slider1->imgs) && count($banks->slider1->imgs)): ?>
                    <li class="get_banks_div">
                        <a href="#" class="get_banks_btn" data-toggle="modal" data-target="#get_banks_modal">
                            <i data-toggle="tooltip"    data-original-title='{{show_content($user_homepage_keywords,"bank_accounts_header")}}' class="fa fa-dollar" aria-hidden="true"></i>
                        </a>
                    </li>
                <?php endif; ?>


                <li class="workshops_trending_div">
                    <a href="#" class="workshops_trending_hover" data-toggle="modal" data-target="#workshops_trending_modal">
                        <i data-toggle="tooltip"  data-original-title='{{show_content($user_homepage_keywords,"workshops_trending_header")}}' class="fa fa-building" aria-hidden="true"></i>
                    </a>
                </li>

                <li class="books_trending_div">
                    <a href="#" class="books_trending_hover" data-toggle="modal" data-target="#books_trending_modal">
                        <i data-toggle="tooltip"    data-original-title='{{show_content($user_homepage_keywords,"books_trending_header")}}' class="fa fa-book" aria-hidden="true"></i>
                    </a>
                </li>

                <?php if($settings->show_brokers_trending): ?>
                    <li class="brokers_trending_div">
                        <a href="#" class="brokers_trending_hover" data-toggle="modal" data-target="#brokers_trending_modal">
                            <i data-toggle="tooltip"   data-original-title='{{show_content($user_homepage_keywords,"brokers_trending")}}' class="fa fa-user-secret" aria-hidden="true"></i>
                        </a>
                    </li>
                <?php endif; ?>

                <li class="users_trending_div">
                    <a href="#" class="users_trending_hover" data-toggle="modal" data-target="#users_trending_modal">
                        <i  data-toggle="tooltip"   data-original-title='{{show_content($user_homepage_keywords,"profile_trending_header")}}' class="fa fa-user" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="fixed-sidebar-left sidebar--large" id="sidebar-left-1">
        <a href="{{url("/home")}}" class="logo">
            <div class="img-wrap">
                <?php if(isset($logo_and_icon->logo) && isset($logo_and_icon->logo->path)): ?>
                    <img src="{{get_image_or_default($logo_and_icon->logo->path)}}"
                         title="{{$logo_and_icon->logo->title}}"
                         alt="{{$logo_and_icon->logo->alt}}"
                    />
                <?php endif; ?>
            </div>
            <div class="title-block">
                <h6 class="logo-title"></h6>
            </div>
        </a>

        <div class="mCustomScrollbar" data-mcs-theme="dark">
            <ul class="left-menu">
                <li>
                    <a href="#" class="js-sidebar-open">
                        <svg class="olymp-close-icon left-menu-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
                        <span class="left-menu-title">
                        </span>
                    </a>
                </li>

                @include('front.main_components.left_sidebar_menu_collapsed_items')
            </ul>
        </div>
    </div>
</div>
@endif
<!-- ... end Fixed Sidebar Left -->


<!-- Fixed Sidebar Left -->

<div class="fixed-sidebar fixed-sidebar-responsive">

    <div class="fixed-sidebar-left sidebar--small" id="sidebar-left-responsive">
        <a href="#" class="logo js-sidebar-open">
            <img src="{{url("/")}}/public_html/front/new_design/img/logo.png" alt="Invstoc">
        </a>

    </div>

    <div class="fixed-sidebar-left sidebar--large" id="sidebar-left-1-responsive">
        <a href="{{url("/home")}}" class="logo">
            <div class="img-wrap">
                <?php if(isset($logo_and_icon->logo) && isset($logo_and_icon->logo->path)): ?>
                    <img src="{{get_image_or_default($logo_and_icon->logo->path)}}"
                         title="{{$logo_and_icon->logo->title}}"
                         alt="{{$logo_and_icon->logo->alt}}"
                    />
                <?php endif; ?>
            </div>
            <div class="title-block">
                <h6 class="logo-title"></h6>
            </div>
        </a>

        @if(auth()->check())
            <div class="mCustomScrollbar" data-mcs-theme="dark">

            <div class="control-block">
                <div class="author-page author vcard inline-items">
                    <div class="author-thumb">

                        <img alt="{{$current_user->full_name}}" title="{{$current_user->full_name}}" style="width: 36px;height: 36px;"
                             src="{{user_default_image($current_user)}}" class="avatar">

                        <?php if(false): ?>
                            <span class="icon-status online"></span>
                        <?php endif; ?>
                    </div>
                    <a href="{{url("user/posts/all/$current_user->user_id")}}" class="author-name fn">
                        <div class="author-title">
                            {{$current_user->full_name}}
                            <br>
                            $ {{number_format($total_user_balance,2)}}
                            <svg class="olymp-dropdown-arrow-icon">
                                <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon"></use>
                            </svg>
                        </div>
                        <span class="author-subtitle"></span>
                    </a>
                </div>
            </div>

            <div class="ui-block-title ui-block-title-small">
                <h6 class="title">MAIN SECTIONS</h6>
            </div>

            <ul class="left-menu">

                <li>
                    <a href="#" class="js-sidebar-open">
                        <svg class="olymp-close-icon left-menu-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
                        <span class="left-menu-title"></span>
                    </a>
                </li>

                @include('front.main_components.left_sidebar_menu_collapsed_items')

            </ul>

            <div class="ui-block-title ui-block-title-small">
                <h6 class="title">{{show_content($user_homepage_keywords,"profile_settings_header")}}</h6>
            </div>

            <ul class="account-settings">

                <li>
                    <a href="{{url("$language_locale/user/posts/all/$current_user->user_id")}}">
                        <i class="fa fa-user"></i> {{show_content($user_homepage_keywords,"your_profile_link")}}
                    </a>
                </li>
                <li>
                    <a href="{{url("load_saved_posts")}}">
                        <i class="fa fa-bookmark"></i> {{show_content($user_homepage_keywords,"saved_posts_link")}}
                    </a>
                </li>
                <li>
                    <a href="{{url("$language_locale/information/$current_user->user_id")}}">
                        <i class="fa fa-edit"></i> {{show_content($user_homepage_keywords,"edit_your_profile_link")}}
                    </a>
                </li>
                <?php if($current_user->is_privet_account == 0): ?>
                <li>
                    <a href="{{url("request_verification")}}">
                        <i class="fa fa-user-secret"></i> {{show_content($user_homepage_keywords,"request_verification_link")}}
                    </a>
                </li>
                <?php endif; ?>
                <?php if($current_user->request_referrer_link == 0 && empty($current_user->referrer_link)): ?>
                <li>
                    <a href="{{url("request_referrer_link")}}">
                        <i class="fa fa-link"></i> {{show_content($user_homepage_keywords,"request_referrer_link")}}
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="{{url("change_password")}}">
                        <i class="fa fa-key"></i> {{show_content($user_homepage_keywords,"change_your_password")}}
                    </a>
                </li>

                <li>
                    <a href="{{url("/logout")}}">
                        <svg class="olymp-logout-icon">
                            <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-logout-icon"></use>
                        </svg>
                        <span>Log Out</span>
                    </a>
                </li>

            </ul>

            <div class="ui-block-title ui-block-title-small">
                <h6 class="title">About Invstoc</h6>
            </div>

            <ul class="about-olympus">
                <?php if(is_object($privacy_page)): ?>
                    <li>
                        <a href="{{url("/".urlencode($privacy_page->page_slug))}}">
                            <span>{{$privacy_page->page_title}}</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="{{url("support")}}">
                        <span>{{show_content($user_homepage_keywords,"support_link")}}</span>
                    </a>
                </li>
            </ul>

            <div class="ui-block-title ui-block-title-small">
                <h6 class="title"><i class="fa fa-globe"></i> Languages</h6>
            </div>

            <ul class="about-olympus">
                <?php foreach($all_langs as $key => $lang_obj): ?>
                    <?php if($lang_obj->lang_id != $current_lang->lang_id): ?>
                        <li>
                            <a href="{{ URL::to('/').'/' . strtolower($lang_obj->lang_title) . '/'.  request()->segment(2) . '/' . request()->segment(3) }}" class="change_language" data-lang_id="{{$lang_obj->lang_id}}">
                                <img src="{{get_image_or_default($lang_obj->lang_img_path)}}" width="20px" height="20px">
                                {{$lang_obj->lang_text}}
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

            </ul>

        </div>
        @endif
    </div>
</div>

<!-- ... end Fixed Sidebar Left -->


<!-- Start Left Sidebar Modals -->

<div class="modal fade" id="hot_orders_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{show_content($user_homepage_keywords,"hot_orders_header")}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="work-shop hot_orders_ul scroll">

                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orders_list_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{show_content($user_homepage_keywords,"your_orders_list")}}
                    (<span class="orders_list_items">0</span>)
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <ul class="work-shop orders_list_ul sidebar_ul_height scroll">


                </ul>

                <textarea class="form-control orders_list_post_text hide_div"
                          placeholder="{{show_content($post_keywords,"write_your_post_label")}}"
                          style="resize: vertical;border: 1px solid cadetblue;" cols="3" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary make_orders_list_post" disabled>{{show_content($post_keywords,"post_btn")}}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="show_comming_events_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <a href="{{url("economic_calendar")}}">
                        {{show_content($user_homepage_keywords,"events_notification_header")}}
                    </a>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body events_stock_table">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="load_groups_workshops_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{show_content($user_homepage_keywords,"create_new_workshop_group_header")}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">

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
                            "col-md-6 group_or_workshop_grid"
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="get_my_cash_back_accounts_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{show_content($user_homepage_keywords,"cashback_header")}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">

                <div class="cash_back_body">

                    <div class="col-md-12 add_new_account_body">

                        <div class="col-md-12">
                            <h4>{{show_content($user_homepage_keywords,"add_new_cashback_header")}}</h4>
                        </div>

                        <div class="col-md-6 float_left_div">
                            <div class="creat-agroub">
                                <input type="text" class="form-control account_number" placeholder="{{show_content($user_homepage_keywords,"account_number_label")}}">
                            </div>
                        </div>
                        <div class="col-md-6 float_right_div">
                            <div class="creat-agroub">
                                <select name="page_id" class="form-control load_other_companies" style="height: auto;">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="margin-top: 10px;">
                            <a class="btn btn-success add_new_account add-gorub" data-account_id="0">{{show_content($general_static_keywords,"save_btn")}}</a>
                        </div>

                    </div>

                    <div class="box-menu-hover-2">

                        <table class="table table-bordered table-hover my_cash_back_accounts">
                            <tbody>

                            </tbody>
                        </table>

                        <div class="col-md-12">
                            <div class="all-items">

                                <div class="col-md-4 float_left_div">
                                    <div class="creat-agroub">
                                    <select name="select" class="form-control" style="height: auto;">
                                      <option value="1" >Cash U</option>
                                      <option value="2">Skirl</option>
                                      <option value="3">Neteller</option>
                                    </select>
                                </div>
                                </div>
                                <div class="col-md-4 float_right_div">
                                    <button  class="form-control btn btn-success request_to_withdraw">{{show_content($user_homepage_keywords,"withdraw_btn")}}</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php if(isset($banks->slider1) && isset($banks->slider1->imgs) && count($banks->slider1->imgs)): ?>
<div class="modal fade" id="get_banks_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{show_content($user_homepage_keywords,"bank_accounts_header")}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">

                <div class="row">

                        <div class="col-md-3">
                          <a href="https://www.cashu.com/" target="blank" title="Cash U">
                              <img src="{{url('uploads/banks/bank1.jpg')}}" width="100%" height="90px">
                          </a>
                        </div>

                        <div class="col-md-3">
                          <a href="{{url("support")}}" target="blank" title="Internal Transfer">
                              <img src="{{url('uploads/banks/bank2.jpg')}}" width="100%" height="90px">
                          </a>
                        </div>
                        <div class="col-md-3">
                          <a href="https://www.neteller.com/"  target="blank" target="blank" title="Neteller">
                              <img src="{{url('uploads/banks/bank3.jpg')}}" width="100%" height="90px">
                          </a>
                        </div>
                        <div class="col-md-3">
                          <a href="https://www.paysera.com" target="blank" title="Paysera">
                              <img src="{{url('uploads/banks/bank4.jpg')}}" width="100%" height="90px">
                          </a>
                        </div>
                        <div class="col-md-3">
                          <a href="https://www.skrill.com/"  target="blank" title="Skrill">
                              <img src="{{url('uploads/banks/bank5.jpg')}}" width="100%" height="90px">
                          </a>
                        </div>
                        <div class="col-md-3">
                          <a href="https://www.wmtransfer.com/" target="blank" title="Web Money">
                              <img src="{{url('uploads/banks/bank6.jpg')}}" width="100%" height="90px">
                          </a>
                        </div>



                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="modal fade" id="workshops_trending_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{show_content($user_homepage_keywords,"workshops_trending_header")}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <table class="table table-bordered table-hover work-shop sidebar_ul_height scroll">

                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="books_trending_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{show_content($user_homepage_keywords,"books_trending_header")}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered table-hover work-shop sidebar_ul_height scroll">

                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php if($settings->show_brokers_trending): ?>
<div class="modal fade" id="brokers_trending_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{show_content($user_homepage_keywords,"brokers_trending")}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">

                <table class="table table-bordered table-hover work-shop sidebar_ul_height scroll">

                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="modal fade" id="users_trending_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{show_content($user_homepage_keywords,"profile_trending_header")}}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table table-bordered table-hover work-shop sidebar_ul_height scroll">

                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- End Left Sidebar Modals -->


<!-- Header-BP -->

<header class="header header_web_v" id="site-header">

    <div class="page-title">
        <h6>
            <!--  <span class="beta_word_page">{{show_content($general_static_keywords,"beta_label")}}</span> -->
            <!-- {{get_image_or_default($logo_and_icon->logo->path)}} -->
            <a href="{{url("/home")}}">
                <?php if(isset($logo_and_icon->logo) && isset($logo_and_icon->logo->path)): ?>
                <img  src="{{url('/')}}/public_html/front/new_design/img/logo-2.png"
                     title="{{$logo_and_icon->logo->title}}"
                     alt="{{$logo_and_icon->logo->alt}}"

                />
                <?php endif; ?>
            </a>
        </h6>
    </div>

    <div class="header-content-wrapper">
        <!-- <form action="{{url('advanced_search')}}" class="search-bar w-search notification-list friend-requests">
            <div class="form-group with-button">

                <input class="form-control search_for_friends"
                       placeholder="{{show_content($user_homepage_keywords,"search_for_freinds")}}"
                       type="text" name="search" required
                       value="{{(isset($_GET['search'])?$_GET['search']:"")}}">

                <button>
                    <svg class="olymp-magnifying-glass-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-magnifying-glass-icon"></use></svg>
                </button>
            </div>
        </form> -->

        <?php if(false): ?>
        <i class="fa fa-dollar" aria-hidden="true"></i>{{number_format($total_user_balance,2)}}
        <a href="{{url("news")}}"><i class="fa fa-newspaper-o" aria-hidden="true"></i> {{show_content($user_homepage_keywords,"news_link")}}</a>
        <a href="{{url("economic_calendar")}}"><i class="fa fa-calendar" aria-hidden="true"></i> {{show_content($user_homepage_keywords,"date_time_link")}}</a>
        <a href="{{url("analytic_page")}}"><i class="fa fa-line-chart" aria-hidden="true"></i> {{show_content($user_homepage_keywords,"stock_link")}}</a>
        <a href="{{url("cashback")}}"><i class="fa fa-database" aria-hidden="true"></i> {{show_content($user_homepage_keywords,"brokers_link")}}</a>
        <?php endif; ?>

        <div class="control-block control-block-2 " >
            <div class="control-icon more has-items">
                <a style="color: #fff;font-size: 20px;"> | </a>
                <a href="{{url("cashback")}}" style="color: #fff;font-size: 14px;" aria-hidden="true">
                    {{show_content($user_homepage_keywords,"brokers_link")}}
                </a>
            </div>

            <div class="control-icon more has-items">
                <a style="color: #fff;font-size: 20px;"> | </a>
                <a href="{{url("news")}}" style="color: #fff;font-size: 14px;" aria-hidden="true" >
                   {{show_content($user_homepage_keywords,"news_link")}}
                </a>
            </div>

            <div class="control-icon more has-items">
                <a style="color: #fff;font-size: 20px;"> | </a>
                <a href="{{url("/articles")}}"  style="color: #fff;font-size: 14px;" aria-hidden="true">
                     {{show_content($user_homepage_keywords,"articles_link")}}

                </a>
            </div>

            <div class="control-icon more has-items">
                <a style="color: #fff;font-size: 20px;"> | </a>
                <a href="{{url("analytic_page")}}"  style="color: #fff;font-size: 14px;" aria-hidden="true" >
                    {{show_content($user_homepage_keywords,"stock_link")}}
                </a>
            </div>

            <div class="control-icon more has-items">
                <a style="color: #fff;font-size: 20px;"> | </a>
                <a href="{{url("economic_calendar")}}"  style="color: #fff;font-size: 14px;" aria-hidden="true">
                    {{show_content($user_homepage_keywords,"date_time_link")}}

                </a>
            </div>
            <div class="control-icon more has-items">
                <a style="color: #fff;font-size: 20px;"> | </a>
                <a href="{{url("signals")}}"  style="color: #fff;font-size: 14px;" aria-hidden="true">
                    {{show_content($user_homepage_keywords,"profile_trending_header")}}

                </a>
            </div>
            @if(!auth()->check())
                <div class="control-icon more has-items">
                    <a style="color: #fff;font-size: 20px;"> | </a>
                    <a href="{{url("/")}}"  style="color: #fff;font-size: 14px;" aria-hidden="true">
                        {{show_content($intro_keywords,"login_submit_btn")}}
                    </a>
                </div>
            @endif
        </div>
        @if(auth()->check())
            <div class="control-block">

                <ul class="list-unstyled">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" tabindex='1'>
                            <img src="{{get_image_or_default($current_lang->lang_img_path)}}" width="24" height="24"> {{$current_lang->lang_title}}
                        </a>
                        <div class="dropdown-menu">
                            <?php
                            foreach($all_langs as $key => $lang_obj): ?>
                            <?php if($lang_obj->lang_id != $current_lang->lang_id): ?>
                            <a href="{{ URL::to('/').'/' . strtolower($lang_obj->lang_title) . '/'.  request()->segment(2) . '/' . request()->segment(3) . '/'. request()->segment(4) .  '/'. request()->segment(5) }}"  title="{{$lang_obj->lang_title}}" style="color: #06793c;">
                                <img src="{{get_image_or_default($lang_obj->lang_img_path)}}" width="24" height="24"> {{$lang_obj->lang_title}}
                            </a>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </li>
                </ul>

                <div class="control-icon more has-items load_follow_notifications_btn">
                    <svg class="olymp-happy-face-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>
                    <?php if($current_user->not_seen_followers_notifications): ?>
                        <div class="label-avatar bg-blue not_alert_count">{{$current_user->not_seen_followers_notifications}}</div>
                    <?php else: ?>
                        <div class="label-avatar bg-blue not_alert_count hide_span"></div>
                    <?php endif; ?>

                    <div class="more-dropdown more-with-triangle triangle-top-center">
                        <div class="ui-block-title ui-block-title-small">
                            <h6 class="title">{{show_content($user_homepage_keywords,"friends_notification_link")}}</h6>
                        </div>

                        <div class="mCustomScrollbar all_follow_notifications_div" data-mcs-theme="dark">
                            <ul class="notification-list friend-requests all_follow_notifications_section">
                            </ul>
                        </div>

                        <a href="{{url("/friends/5?type=followers")}}" class="view-all bg-blue">{{show_content($general_static_keywords,"see_all")}}</a>
                    </div>
                </div>

                <div class="control-icon more has-items messages_notification_link">
                    <svg class="olymp-chat---messages-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
                    <?php if($current_user->not_seen_messages): ?>
                        <div class="label-avatar bg-purple not_alert_count">{{$current_user->not_seen_messages}}</div>
                    <?php else: ?>
                        <div class="label-avatar bg-purple not_alert_count hide_span"></div>
                    <?php endif; ?>

                    <div class="more-dropdown more-with-triangle triangle-top-center">
                        <div class="ui-block-title ui-block-title-small">
                            <h6 class="title">{{show_content($user_homepage_keywords,"messages_link")}}</h6>
                        </div>

                        <div class="mCustomScrollbar" data-mcs-theme="dark">

                            <div class="custom_chat_message">
                                <div class="panel panel-default">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading"></div>
                                    <div class="panel-body search_for_user_parent_div">
                                        <div class="col-md-12">
                                            <input type="text" class="form-control border_style search_for_user" placeholder="{{show_content($user_homepage_keywords,"search_for_user")}}">
                                        </div>
                                        <div class="col-md-12" style="margin-top: 10px;">
                                            <select class="form-control border_style hide_input load_search_users_results" style="padding: 1px;" disabled></select>
                                        </div>
                                        <br>
                                        <div class="col-md-12" style="margin-top: 10px;">
                                            <textarea class="form-control border_style custom_message" cols="10" rows="4" placeholder="{{show_content($user_homepage_keywords,"message_body")}}"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-success send_users_message" disabled="disabled">{{show_content($user_homepage_keywords,"send_btn")}} <i class="fa fa-send-o"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <ul class="notification-list chat-message chats_container">
                            </ul>
                        </div>

                        <a href="{{url("messages")}}" class="view-all bg-purple">{{show_content($general_static_keywords,"more")}}</a>
                    </div>
                </div>

                <div class="control-icon more has-items load_notifications_btn">
                    <svg class="olymp-thunder-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-thunder-icon"></use></svg>

                    <?php if($current_user->not_seen_all_notifications): ?>
                        <div class="label-avatar bg-primary not_alert_count">{{$current_user->not_seen_all_notifications}}</div>
                    <?php else: ?>
                        <div class="label-avatar bg-primary not_alert_count hide_span"></div>
                    <?php endif; ?>

                    <div class="more-dropdown more-with-triangle triangle-top-center">

                        <div class="ui-block-title ui-block-title-small">
                            <h6 class="title">{{show_content($user_homepage_keywords,"notification_link")}}</h6>
                        </div>

                        <div class="mCustomScrollbar all_notifications_div" data-mcs-theme="dark">
                            <ul class="notification-list all_notifications_section">

                            </ul>
                        </div>

                        <a href="{{url("/get_all_user_notifications")}}" class="view-all bg-primary">
                            {{show_content($general_static_keywords,"see_all")}}
                        </a>

                    </div>
                </div>

                <div class="author-page author vcard inline-items more">
                    <div class="author-thumb">

                        <img alt="{{$current_user->full_name}}" title="{{$current_user->full_name}}" style="width: 36px;height: 36px;"
                             src="{{user_default_image($current_user)}}" class="avatar">

                        <?php if(false): ?>
                            <span class="icon-status online"></span>
                        <?php endif; ?>

                        <div class="more-dropdown more-with-triangle">
                            <div class="mCustomScrollbar" data-mcs-theme="dark">
                                <div class="ui-block-title ui-block-title-small">
                                    <h6 class="title">{{show_content($user_homepage_keywords,"profile_settings_header")}}</h6>
                                </div>

                                <ul class="account-settings">

                                    <li>
                                        <a href="{{url("$language_locale/user/posts/all/$current_user->user_id")}}">
                                            <i class="fa fa-user"></i> {{show_content($user_homepage_keywords,"your_profile_link")}}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url("load_saved_posts")}}">
                                            <i class="fa fa-bookmark"></i> {{show_content($user_homepage_keywords,"saved_posts_link")}}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url("$language_locale/information/$current_user->user_id")}}">
                                            <i class="fa fa-edit"></i> {{show_content($user_homepage_keywords,"edit_your_profile_link")}}
                                        </a>
                                    </li>
                                    <?php if($current_user->is_privet_account == 0): ?>
                                    <li>
                                        <a href="{{url("request_verification")}}">
                                            <i class="fa fa-user-secret"></i> {{show_content($user_homepage_keywords,"request_verification_link")}}
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if($current_user->request_referrer_link == 0 && empty($current_user->referrer_link)): ?>
                                    <li>
                                        <a href="{{url("request_referrer_link")}}">
                                            <i class="fa fa-link"></i> {{show_content($user_homepage_keywords,"request_referrer_link")}}
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <li>
                                        <a href="{{url("change_password")}}">
                                            <i class="fa fa-key"></i> {{show_content($user_homepage_keywords,"change_your_password")}}
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{url("/logout")}}">
                                            <svg class="olymp-logout-icon">
                                                <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-logout-icon"></use>
                                            </svg>
                                            <span>Log Out</span>
                                        </a>
                                    </li>

                                </ul>

                                <ul>
                                    <?php if(is_object($privacy_page)): ?>
                                        <li>
                                            <a href="{{url("/".urlencode($privacy_page->page_slug))}}">
                                                <span>{{$privacy_page->page_title}}</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <li>
                                        <a href="{{url("support")}}">
                                            <span>{{show_content($user_homepage_keywords,"support_link")}}</span>
                                        </a>
                                    </li>

                                </ul>

                                <div class="ui-block-title ui-block-title-small">
                                    <h6 class="title"><i class="fa fa-globe"></i> Languages</h6>
                                </div>

                                <ul class="account-settings">

                                    <?php foreach($all_langs as $key => $lang_obj): ?>
                                        <?php if($lang_obj->lang_id != $current_lang->lang_id): ?>
                                        <li>
                                            <a href="{{ URL::to('/').'/' . strtolower($lang_obj->lang_title) . '/'.  request()->segment(2) . '/' . request()->segment(3) }}" class="change_language" data-lang_id="{{$lang_obj->lang_id}}">
                                                <img src="{{get_image_or_default($lang_obj->lang_img_path)}}" width="20px" height="20px">
                                                {{$lang_obj->lang_text}}
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                </ul>

                            </div>

                        </div>
                    </div>
                    <a  class="author-name fn">
                        <div class="author-title">
                            {{$current_user->full_name}}
                            <br>
                            $ {{number_format($total_user_balance,2)}}
                            <svg class="olymp-dropdown-arrow-icon">
                                <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon"></use>
                            </svg>
                        </div>
                        <span class="author-subtitle"></span>
                    </a>
                </div>
                <div class="control-icon more has-items " style="position: relative; padding: 10px; margin-right: 0px">
                  <form action="{{ URL::to('/').'/' . $language_locale . '/'.  'advanced_search' }}" class=" w-searc">
                    <input type="text" id="new_search" autocomplete="off" name="search"
                    placeholder="{{show_content($user_homepage_keywords,"search_for_freinds")}}">
                  </form>
                </div>

            </div>
        @else
            <div class="control-block">

            </div>
        @endif
    </div>

</header>

<!-- ... end Header-BP -->

<!-- Responsive Header-BP -->

<header class="header header-responsive header_mob_v" id="site-header-responsive">

    <div class="page-title">
        <h6>
            <span class="beta_word_page">{{show_content($general_static_keywords,"beta_label")}}</span>
            <a href="{{url("/home")}}">
                <?php if(isset($logo_and_icon->logo) && isset($logo_and_icon->logo->path)): ?>
                <img src="{{get_image_or_default($logo_and_icon->logo->path)}}"
                     title="{{$logo_and_icon->logo->title}}"
                     alt="{{$logo_and_icon->logo->alt}}"
                />
                <?php endif; ?>
            </a>
        </h6>
    </div>
    <div class="header-content-wrapper">
        <ul class="nav nav-tabs mobile-app-tabs" role="tablist">

            <li class="nav-item">
                <a class="nav-link" href="{{url("/$language_locale/cashback")}}" title="{{show_content($user_homepage_keywords,"brokers_link")}}">
                    <i class="fa fa-database" style="color: #fff;font-size: 18px;" aria-hidden="true"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url("news")}}" title="{{show_content($user_homepage_keywords,"news_link")}}">
                    <i class="fa fa-newspaper-o" style="color: #fff;font-size: 18px;" aria-hidden="true"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url("analytic_page")}}" title="{{show_content($user_homepage_keywords,"stock_link")}}">
                    <i class="fa fa-line-chart" style="color: #fff;font-size: 18px;" aria-hidden="true"></i>

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{url("economic_calendar")}}" title="{{show_content($user_homepage_keywords,"date_time_link")}}">
                    <i class="fa fa-calendar" style="color: #fff;font-size: 18px;" aria-hidden="true"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link load_follow_notifications_btn" data-toggle="tab" href="#request" role="tab">
                    <div class="control-icon has-items">
                        <svg class="olymp-happy-face-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>

                        <?php if($current_user->not_seen_followers_notifications): ?>
                            <div class="label-avatar bg-blue not_alert_count">{{$current_user->not_seen_followers_notifications}}</div>
                        <?php else: ?>
                            <div class="label-avatar bg-blue not_alert_count hide_span"></div>
                        <?php endif; ?>

                    </div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link messages_notification_link" data-toggle="tab" href="#chat" role="tab">
                    <div class="control-icon has-items">
                        <svg class="olymp-chat---messages-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>

                        <?php if($current_user->not_seen_messages): ?>
                            <div class="label-avatar bg-purple not_alert_count">{{$current_user->not_seen_messages}}</div>
                        <?php else: ?>
                            <div class="label-avatar bg-purple not_alert_count hide_span"></div>
                        <?php endif; ?>

                    </div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link load_notifications_btn" data-toggle="tab" href="#notification" role="tab">
                    <div class="control-icon has-items">
                        <svg class="olymp-thunder-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-thunder-icon"></use></svg>

                        <?php if($current_user->not_seen_all_notifications): ?>
                            <div class="label-avatar bg-primary not_alert_count">{{$current_user->not_seen_all_notifications}}</div>
                        <?php else: ?>
                            <div class="label-avatar bg-primary not_alert_count hide_span"></div>
                        <?php endif; ?>

                    </div>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#search" role="tab">
                    <svg class="olymp-magnifying-glass-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-magnifying-glass-icon"></use></svg>
                    <svg class="olymp-close-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
                </a>
            </li>
        </ul>
    </div>

    <!-- Tab panes -->
    <div class="tab-content tab-content-responsive">

        <div class="tab-pane" id="request" role="tabpanel">

            <div class="mCustomScrollbar all_follow_notifications_div" data-mcs-theme="dark">
                <div class="ui-block-title ui-block-title-small">
                    <h6 class="title">{{show_content($user_homepage_keywords,"friends_notification_link")}}</h6>
                </div>

                <ul class="notification-list friend-requests all_follow_notifications_section">
                </ul>

                <a href="{{url("/friends/5?type=followers")}}" class="view-all bg-blue">{{show_content($general_static_keywords,"see_all")}}</a>

            </div>

        </div>

        <div class="tab-pane " id="chat" role="tabpanel">

            <div class="mCustomScrollbar" data-mcs-theme="dark">
                <div class="ui-block-title ui-block-title-small">
                    <h6 class="title">{{show_content($user_homepage_keywords,"messages_link")}}</h6>
                </div>

                <div class="custom_chat_message">
                    <div class="panel panel-default">
                        <!-- Default panel contents -->
                        <div class="panel-heading"></div>
                        <div class="panel-body search_for_user_parent_div">
                            <div class="col-md-12">
                                <input type="text" class="form-control border_style search_for_user" placeholder="{{show_content($user_homepage_keywords,"search_for_user")}}">
                            </div>
                            <div class="col-md-12" style="margin-top: 10px;">
                                <select class="form-control border_style hide_input load_search_users_results" disabled multiple></select>
                            </div>
                            <br>
                            <div class="col-md-12" style="margin-top: 10px;">
                                <textarea class="form-control border_style custom_message" cols="10" rows="4" placeholder="{{show_content($user_homepage_keywords,"message_body")}}"></textarea>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-success send_users_message" disabled="disabled">{{show_content($user_homepage_keywords,"send_btn")}} <i class="fa fa-send-o"></i></button>
                            </div>
                        </div>
                    </div>

                </div>

                <ul class="notification-list chat-message chats_container">
                </ul>

                <a href="{{url("messages")}}" class="view-all bg-purple">{{show_content($general_static_keywords,"more")}}</a>
            </div>

        </div>

        <div class="tab-pane " id="notification" role="tabpanel">

            <div class="mCustomScrollbar all_notifications_div" data-mcs-theme="dark">
                <div class="ui-block-title ui-block-title-small">
                    <h6 class="title">{{show_content($user_homepage_keywords,"notification_link")}}</h6>
                </div>

                <ul class="notification-list all_notifications_section">

                </ul>

                <a href="{{url("/get_all_user_notifications")}}" class="view-all bg-primary">
                    {{show_content($general_static_keywords,"see_all")}}
                </a>

            </div>

        </div>

        <div class="tab-pane " id="search" role="tabpanel">
            <form action="{{ URL::to('/').'/' . $language_locale . '/'.  'advanced_search' }}" class="search-bar w-search notification-list friend-requests">
                <div class="form-group with-button">

                    <input class="form-control search_for_friends"
                           placeholder="{{show_content($user_homepage_keywords,"search_for_freinds")}}"
                           type="text" name="search" required
                           value="{{(isset($_GET['search'])?$_GET['search']:"")}}">

                </div>
            </form>
        </div>

    </div>
    <!-- ... end  Tab panes -->

</header>

<!-- ... end Responsive Header-BP -->
<div class="header-spacer"></div>



<?php if(false): ?>

<nav class="navbar navbar-default navbar-fixed-top menu">
    <div class="container">
        <div class="row">


            <div class="col-md-1 col-xs-4 nopadding">
                <div class="select_lang">
                    <!-- Single button -->
                    <div class="btn-group">
                        <button type="button" class="btn_lang dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{get_image_or_default($current_lang->lang_img_path)}}" width="20px" height="20px">
                            {{$current_lang->lang_text}}                         <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu box_lang">
                            <?php foreach($all_langs as $key => $lang_obj): ?>
                                <li>
                                    <a href="#" class="change_language" data-lang_id="{{$lang_obj->lang_id}}">
                                        <img src="{{get_image_or_default($lang_obj->lang_img_path)}}" width="20px" height="20px">
                                        {{$lang_obj->lang_text}}
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div><!-- /.row -->
    </div><!-- /.container -->
</nav>

<div class="menu-left-fixed">

    <div class="st-menu-fied">

        <div class="hot_orders_div pos_relative">
            @include("front.dropdown_menus.hot_orders")
        </div>

        <div class="orders_list_div pos_relative">
            @include("front.dropdown_menus.orders_list")
        </div>

        <div class="events_stock_exchanges">
            @include("front.dropdown_menus.events_stock_exchanges")
        </div>

        <div class="events_notifications">
            @include("front.dropdown_menus.add_group_or_workshop")
        </div>

        <div class="cash_back_div pos_relative">
            @include("front.dropdown_menus.cash_back")
        </div>

        <?php if(isset($banks->slider1) && isset($banks->slider1->imgs) && count($banks->slider1->imgs)): ?>
            <div class="banks_div pos_relative">
                @include("front.dropdown_menus.banks_div")
            </div>
        <?php endif; ?>

        <div class="workshops_trending_div pos_relative">
            @include("front.dropdown_menus.workshops_trending")
        </div>

        <div class="books_trending_div pos_relative">
            @include("front.dropdown_menus.books_trending")
        </div>

        <?php if($settings->show_brokers_trending): ?>
        <div class="brokers_trending_div pos_relative">
            @include("front.dropdown_menus.brokers_trending")
        </div>
        <?php endif; ?>

        <div class="users_trending_div pos_relative">
            @include("front.dropdown_menus.users_trending")
        </div>

        <?php if(count($sidebar_pages)): ?>
            <div class="static_pages">
                <?php foreach($sidebar_pages as $key => $static_page): ?>
                    <a class="left_pages" href="{{url(urlencode($static_page->page_slug))}}">{{$static_page->page_title}}</a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit_timezone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{show_content($user_profile_keywords,"about_me_edit_timezone_header")}}</h4>
            </div>
            <form action="{{url("information/change_timezone")}}" method="POST">
                <div class="modal-body" style="text-align: center;">

                    <div class="col-md-12">
                        <label for="">
                            <b>{{show_content($user_profile_keywords,"about_me_edit_timezone_header")}}</b>
                        </label>
                        <select class="form-control" name="timezone" style="width: 95% !important;">
                            <?php foreach(TIMEZONES as $key => $value): ?>
                            <option {{($key == $current_user->timezone)?"selected":""}} value="{{$key}}">
                                {{$key}}
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    {{csrf_field()}}
                    <br>
                    <br>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{show_content($general_static_keywords,"close")}}</button>
                    <button type="submit" class="btn btn-primary">{{show_content($general_static_keywords,"save_btn")}}</button>
                </div>
            </form>

        </div>
    </div>
</div>
<?php endif; ?>
