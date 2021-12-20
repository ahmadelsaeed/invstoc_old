<div class="post-additional-info inline-items" style="display: inline-block;">

    <?php if(!isset($preview_post)): ?>

    <a href="#" class="post-add-icon inline-items like_post" data-postid="{{$post_data->post_id}}" style="fill: {{$this_user_like?"#00aa50":"#c2c5d9"}}">
        <svg class="olymp-heart-icon">
            <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-heart-icon"></use>
        </svg>
    </a>

    <a href="#" class="post-add-icon inline-items load_comments_setting" data-postid="{{$post_data->post_id}}">
        <svg class="olymp-speech-balloon-icon">
            <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-speech-balloon-icon"></use>
        </svg>
    </a>

    <?php if(isset($current_user) && $current_user->user_id!=$post_data->post_user->user_id): ?>
    <a href="#" class="post-add-icon inline-items share_post" data-postid="{{$post_data->post_id}}">
        <svg class="olymp-share-icon">
            <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-share-icon"></use>
        </svg>
    </a>
    <?php endif; ?>

    <?php endif; ?>

    <div class="comments-shared">

        <a href="#" class="post-add-icon inline-items get_post_username_likes"
           data-post_id="{{$post_data->post_id}}"
           data-toggle="tooltip"
           data-placement="top"
           data-html="true"
           title="{!! $post_likes_title !!}">
            {{show_content($post_keywords,"likes_count_label")}}
            <span class="post_likes_count">{{$post_data->post_likes_count}}</span>
        </a>

        <a href="#" class="post-add-icon inline-items get_post_username_comments"
           data-post_id="{{$post_data->post_id}}"
           data-toggle="tooltip"
           data-placement="top"
           data-html="true"
           title="{!! $post_comments_title !!}">
            {{show_content($post_keywords,"comments_count_label")}}
            <span class="post_comments_count">{{$post_data->post_comments_count}}</span>
        </a>

        <a href="#" class="post-add-icon inline-items get_post_username_shares"
           data-post_id="{{$post_data->post_id}}"
           data-toggle="tooltip"
           data-placement="top"
           data-html="true"
           title="{!! $post_shares_title !!}">
            {{show_content($post_keywords,"shares_count_label")}}
            <span class="post_comments_count">{{$post_data->post_shares_count}}</span>
        </a>

    </div>

</div>


<?php if(!isset($preview_post)): ?>
<div class="comments_section">
    <?php
    if(isset($comments_html))echo $comments_html;
    ?>
</div>
<?php endif; ?>
