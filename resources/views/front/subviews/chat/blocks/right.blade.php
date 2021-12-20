<li class="right clearfix">
    <span class="chat-img pull-right">
        <div class="display_username">
            <?php if(!empty($message->from_img_path)): ?>
                <img src="{{url($message->from_img_path)}}" title="{{$message->from_user_full_name}}"
                     class="rounded-circle" width="80" height="80">
            <?php else: ?>
                {{substr($message->from_user_full_name,0,2)}}
            <?php endif; ?>
        </div>
    </span>
    <div class="chat-body clearfix">
        <div>
            <strong class="pull-right primary-font">{{$message->from_user_full_name}}</strong>

            <small class=" text-muted pull-left">
                <span class="glyphicon glyphicon-time"></span>
                {{\Carbon\Carbon::createFromTimestamp(strtotime($message->msg_date))->diffForHumans()}}
            </small>
        </div>
        <p style="clear: both;">
            {!! search_text_url($message->message) !!}
        </p>
    </div>
</li>