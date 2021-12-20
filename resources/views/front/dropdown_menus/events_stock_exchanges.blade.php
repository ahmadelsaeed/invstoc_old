<label href="#" class="link-anew-groub left_icons dropdown show_comming_events" data-toggle="dropdown"
   data-target=".events_stock_exchanges" title="{{show_content($user_homepage_keywords,"events_notification_header")}}">
    <i class="fa fa-clock-o" aria-hidden="true"></i>
</label>

<div class="dropdown-menu events_notifications_container" style="width: 800px !important;max-height: 350px;overflow-y: scroll;">
    <i class="fa fa-sort-asc right_m" aria-hidden="true"></i>

    <h1 class="left_sidebar_header">
        <a href="{{url("economic-calendar")}}" style="color: #fff;">
            {{show_content($user_homepage_keywords,"events_notification_header")}}
        </a>
    </h1>
    <div class="events_stock_table"></div>
</div>
