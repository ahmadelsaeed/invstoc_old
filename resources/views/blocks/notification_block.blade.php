
<?php
    $not_datetime = \Carbon\Carbon::createFromTimestamp(strtotime($not_obj->note_created_at))->setTimezone($current_user_timezone)->toDateTimeString();
    date_default_timezone_set($current_user_timezone);
?>

<?php if(false): ?>
    {{\Carbon\Carbon::
    createFromTimestamp(strtotime($not_obj->note_created_at))->
    setTimezone($current_user_timezone)->
    diffForHumans()
    }}
<?php endif; ?>

<li class="not_block_{{$not_obj->not_id}} block_div_link" data-full_url="{{url("/$not_obj->not_link")}}">
    <div class="author-thumb">
        <img class="not_img" src="{{get_image_or_default($not_obj->from_user_logo_path)}}"
             title="{{$not_obj->from_user_full_name}}" alt="{{$not_obj->from_user_full_name}}"
            style="width: 34px;height: 34px;" />
    </div>
    <div class="notification-event">
        <div>
            <a href="{{url("/") . '/'. ($not_obj->not_link == 'stock_exchange' ? 'economic_calendar' : $not_obj->not_link)}}" class="h6 notification-friend">{{$not_obj->from_user_full_name}}</a>
            {{$not_obj->not_title}}.
        </div>
        <span class="notification-date">
            <time class="entry-date updated">{{\Carbon\Carbon::createFromTimestamp(strtotime($not_datetime))->diffForHumans()}}</time>
        </span>
    </div>
    <span class="notification-icon">
        <img src="{{url("public_html/front/images/notification/$not_obj->not_type.png")}}">
    </span>

</li>

