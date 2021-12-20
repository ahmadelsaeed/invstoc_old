<a class="link-icon-main messages_notification_link" href="#" data-toggle="dropdown"
   data-placement="bottom" title="{{show_content($user_homepage_keywords,"messages_link")}}">
    <i class="fa fa-envelope" aria-hidden="true"></i>
    <?php if($current_user->not_seen_messages): ?>
        <span class="not_alert_count">{{$current_user->not_seen_messages}}</span>
    <?php else: ?>
        <span class="not_alert_count hide_span"></span>
    <?php endif; ?>
</a>
<div class="box-chat scroll dropdown-menu">
    <i class="fa fa-sort-asc up-mass" aria-hidden="true"></i>
    <div class="custom_chat_message">
        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">{{show_content($user_homepage_keywords,"send_message_header")}}</div>
            <div class="panel-body search_for_user_parent_div">
                <div class="row">
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

    </div>

    <div class="col-md-12">
        <hr class="chat_separator_line">
    </div>

    <div class="col-md-12 chats_container scroll"></div>

    <div class="col-md-12 go_to_messages">
        <hr class="chat_separator_line">
        <a href="{{url("messages")}}" class="more_messages_page">{{show_content($general_static_keywords,"more")}}</a>
    </div>
</div>