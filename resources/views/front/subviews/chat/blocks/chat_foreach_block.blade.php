<?php foreach ($msgs as $key => $msg): ?>

    <?php
        if(!isset($from_user_objs[$msg->from_user_id])){
            continue;
        }

        $user_obj=$from_user_objs[$msg->from_user_id][0];
        $chat_id=$msg->chat_id;
        $msg_obj=$msg;
    ?>

    @include("front.subviews.chat.blocks.chat_block")

<?php endforeach; ?>
