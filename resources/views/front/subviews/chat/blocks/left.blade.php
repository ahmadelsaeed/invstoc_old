<li class="left clearfix">
    <span class="chat-img pull-left">
        <div class="display_username">
            <?php
                $link="";
                if (!in_array($message->from_user_user_type,["admin","dev"]))
                {
                    $link = url("information/$message->from_user_id");
                }
            ?>

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
            <strong class="primary-font pull-left">
                <?php if(!empty($link)): ?>
                <a href="{{$link}}" target="_blank" style="text-decoration: none;">
                    {{$message->from_user_full_name}}
                </a>
                <?php else: ?>
                    {{$message->from_user_full_name}}
                <?php endif; ?>

            </strong>
            <small class=" text-muted">
                <span class="glyphicon glyphicon-time"></span>
                {{\Carbon\Carbon::createFromTimestamp(strtotime($message->msg_date))->diffForHumans()}}
            </small>
        </div>

        <p style="clear: both;margin-top: -10px;">
            {!! search_text_url($message->message) !!}
        </p>
    </div>
</li>