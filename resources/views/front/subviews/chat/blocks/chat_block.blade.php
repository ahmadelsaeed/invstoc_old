
<li class="msg_block_{{$msg_obj->chat_msg_id}} block_div_link" data-full_url="{{url("/messages/$chat_id")}}">
    <div class="author-thumb">
        <img class="not_img" src="{{get_image_or_default($user_obj->logo_path)}}"
             style="width: 34px;height: 34px;" />
    </div>
    <div class="notification-event">
        <div>
            <a href="{{url("/messages/$chat_id")}}" class="h6 notification-friend">{{$user_obj->full_name}}</a>
            <p data-full_body="{{htmlentities(strip_tags($msg_obj->message),ENT_QUOTES,'UTF-8')}}">
                {!! split_word_into_chars_ar_without_more_link(
                strip_tags($msg_obj->message),70,
                " ...") !!}
            </p>
        </div>
        <span class="notification-date">
            <time class="entry-date updated">{{\Carbon\Carbon::createFromTimestamp(strtotime($msg_obj->created_at))->diffForHumans()}}</time>
        </span>
    </div>
    <span class="notification-icon">
        <svg class="olymp-chat---messages-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
    </span>

</li>

