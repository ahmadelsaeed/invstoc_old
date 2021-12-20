
<?php
    if ($current_user->user_type == "user")
    {
        $information_link = url("user/posts/all/$current_user->user_id");
    }
    else{
        $information_link = url("admin/dashboard");
    }
?>

<div class="profile-card">

    <a href="{{$information_link}}">
        <img src="{{user_default_image($current_user)}}" alt="user" class="profile-photo" />
    </a>
    <div class="name-pro">
        <h5>

            <a href="{{$information_link}}" title="{{$current_user->full_name}}">
                <span class="full_name">
                    {{split_word_into_chars_ar($current_user->full_name,20,"yes") }}
                </span>
                <?php if($current_user->is_privet_account): ?>
                    @include('blocks.verify_padge_block')
                <?php endif; ?>
            </a>
        </h5>
    </div>
    <?php if($current_user->user_type == "user"): ?>
    <div class="follw-me">
        <a href="{{url("friends/$current_user->user_id?type=followers")}}" class="text-white">
            <span>{{show_content($general_static_keywords,"followers_label")}}</span>
            <span>({{count($current_user->followers)}})</span>
        </a>
        <a href="{{url("friends/$current_user->user_id?type=following")}}" class="text-white">
            <span>{{show_content($general_static_keywords,"followings_label")}}</span>
            <span>({{count($current_user->following)}})</span>
        </a>
    </div>
    <?php endif; ?>
</div><!--profile card ends-->

