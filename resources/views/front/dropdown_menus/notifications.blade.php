<a class="link-icon-main load_notifications_btn" href="#" data-toggle="dropdown" data-placement="right" title="{{show_content($user_homepage_keywords,"notification_link")}}">
    <i class="fa fa-globe" aria-hidden="true"></i>
    <?php if($current_user->not_seen_all_notifications): ?>
        <span class="not_alert_count">{{$current_user->not_seen_all_notifications}}</span>
    <?php else: ?>
        <span class="not_alert_count hide_span"></span>
    <?php endif; ?>
</a>
<div class="box-notifications all_notifications_div scroll dropdown-menu">
    <i class="fa fa-sort-asc up-not" aria-hidden="true"></i>
    <div class="all_notifications_section"></div>


    <div class="show_all_notifications">
        <p>
            <a href="{{url("/get_all_user_notifications")}}">
                {{show_content($general_static_keywords,"see_all")}}
            </a>
        </p>
    </div>
</div>