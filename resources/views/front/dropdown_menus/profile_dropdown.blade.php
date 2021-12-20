<label class="link-anew-groub dropdown" data-toggle="dropdown" data-target=".profile_dropdown_div" >
    <img src="{{get_image_or_default($current_user->logo_path)}}"
         width="35px" height="35px" class="img-circle" alt="{{$current_user->full_name}}"
         title="{{$current_user->full_name}}" >

</label>

<div class="box-hermonic dropdown-menu stop_close_dropdown">
    <i class="fa fa-sort-asc right_m" aria-hidden="true"></i>
    <div class="col-md-12">
        <h1 class="left_sidebar_header">{{show_content($user_homepage_keywords,"profile_settings_header")}}</h1>
    </div>

    <ul class="profile_setting_ul">
        <li>
            <a href="{{url("user/posts/all/$current_user->user_id")}}">
                <i class="fa fa-user"></i> {{show_content($user_homepage_keywords,"your_profile_link")}}
            </a>
        </li>
        <li>
            <a href="{{url("load_saved_posts")}}">
                <i class="fa fa-bookmark"></i> {{show_content($user_homepage_keywords,"saved_posts_link")}}
            </a>
        </li>
        <li>
            <a href="{{url("information/$current_user->user_id")}}">
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
    </ul>

</div>
