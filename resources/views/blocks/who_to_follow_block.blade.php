
<div class="ui-block">
    <div class="ui-block-title">
        <h6 class="title">{{show_content($user_homepage_keywords,"who_to_follow_header")}}</h6>
    </div>

    <!-- W-Action -->

    <ul class="widget w-friend-pages-added notification-list friend-requests">
        <?php foreach($random_users_indexes as $key => $index): ?>
            <?php
                if (!isset($random_users[$index]))
                    continue;

                $user_obj = $random_users[$index];
            ?>
            <li class="inline-items">
                <div class="author-thumb">
                    <a href="{{url("user/posts/all/$user_obj->user_id")}}">
                        <img src="{{user_default_image($user_obj)}}" title="{{$user_obj->full_name}}" style="width: 36px;height: 36px;" />
                    </a>
                </div>
                <div class="notification-event">
                    <a href="{{url("user/posts/all/$user_obj->user_id")}}" class="h6 notification-friend">
                        <?php if($user_obj->is_privet_account): ?>
                            @include('blocks.verify_padge_block')
                        <?php endif; ?>
                        {{$user_obj->full_name}}
                    </a>
                    <span class="chat-message-item"></span>
                </div>
                <span class="notification-icon">
                    <a href="#" class="accept-request follow_user" data-follower_id="{{$current_user->user_id}}"
                       data-target_id="{{$user_obj->user_id}}">
                        <span class="icon-add without-text">
                            {{show_content($general_static_keywords,"follow_label")}}
                        </span>
                    </a>
                </span>
            </li>
        <?php endforeach; ?>

    </ul>

    <!-- ... end W-Action -->
</div>
