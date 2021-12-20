<?php
$comment_text=explode("\n",$original_obj->post_comment_body);
?>

<div class="comment_body_{{$original_obj->post_comment_id}}">

    <?php if(!empty($original_obj->comment_img_path)): ?>
    <img class="comment_img open_post_image" src="{{get_image_or_default($original_obj->comment_img_path)}}" alt="" style="max-width: 100%;">
    <?php endif; ?>
    <?php foreach ($comment_text as $key => $item): ?>
        <p>{!! $item !!}</p>
    <?php endforeach; ?>
</div>