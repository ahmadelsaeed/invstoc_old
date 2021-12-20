<a class="link-icon-main load_follow_notifications_btn" href="#" data-toggle="dropdown" data-placement="bottom"
   title="{{show_content($user_homepage_keywords,"friends_notification_link")}}">
    <i class="fa fa-user" aria-hidden="true"></i>
    <?php if($current_user->not_seen_followers_notifications): ?>
        <span class="not_alert_count">{{$current_user->not_seen_followers_notifications}}</span>
    <?php else: ?>
        <span class="not_alert_count hide_span"></span>
    <?php endif; ?>
</a>

<div class="box-notifications all_follow_notifications_div scroll dropdown-menu" style="right: 0px;">
    <i class="fa fa-sort-asc up-not" aria-hidden="true"></i>
    <div class="all_follow_notifications_section"></div>



</div>