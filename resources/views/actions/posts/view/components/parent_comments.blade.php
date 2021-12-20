<?php if(is_object($parent_comment)): ?>

    <?php
        $original_obj = $parent_comment;

        $this_comment_has_childs = false;
        if (isset($all_post_comments[$original_obj->post_comment_id]) && count($all_post_comments[$original_obj->post_comment_id]))
        {
            $this_comment_has_childs = true;
        }

    ?>


    <li class="comment-item post-comment write_comment_div {{($this_comment_has_childs == true)?"has-children":""}} post_comment_{{$original_obj->post_comment_id}} {{($original_obj->parent_post_comment_id==0)?"parent_comment":""}}"
         data-commentid="{{$original_obj->post_comment_id}}" >
        <div class="post__author author vcard inline-items">

            <a href="{{url("/user/posts/all/$original_obj->user_id")}}">
                <img src="{{user_default_image($original_obj)}}" title="{{$original_obj->full_name}}"
                     alt="{{$original_obj->full_name}}" style="width: 26px;height: 26px;" />
            </a>


            <div class="author-date">
                <a class="h6 post__author-name fn" href="{{url("/user/posts/all/$original_obj->user_id")}}">
                    {{$original_obj->full_name}}
                    <?php if($original_obj->is_privet_account): ?>
                    @include('blocks.verify_padge_block')
                    &nbsp;
                    <?php endif; ?>
                </a>
                <div class="post__date">
                    <time class="published">
                        {{\Carbon\Carbon::createFromTimestamp(strtotime($original_obj->comment_at))->diffForHumans()}}
                    </time>
                </div>
            </div>

            <?php if(in_array($current_user->user_id,[$post_data->user_id,$original_obj->user_id])): ?>
                <div class="more comment_options_{{$original_obj->post_comment_id}}">
                    <svg class="olymp-three-dots-icon">
                        <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                    </svg>
                    <ul class="more-dropdown">
                        <?php if($current_user->user_id==$original_obj->user_id): ?>
                            <li><a class="edit_comment" data-commentid="{{$original_obj->post_comment_id}}" href="#">{{show_content($general_static_keywords,"edit_btn")}}</a></li>
                        <?php endif; ?>

                        <li><a class="delete_comment" data-commentid="{{$original_obj->post_comment_id}}" href="#">{{show_content($general_static_keywords,"delete_btn")}}</a></li>
                    </ul>
                </div>
            <?php endif; ?>


        </div>

        <p>
            @include("actions.posts.view.components.comment_div_itsef")
        </p>

        <a href="#" class="post-add-icon inline-items like_comment {{isset($all_user_comment_likes[$original_obj->post_comment_id])?"is_liked_post":""}}" data-commentid="{{$original_obj->post_comment_id}}">
            <svg class="olymp-heart-icon">
                <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-heart-icon"></use>
            </svg>
            <span class="like_count">{{$original_obj->comment_likes_count}}</span>
        </a>

        <?php if($original_obj->parent_post_comment_id==0): ?>
            <a href="" class="post-add-icon inline-items reply make_comment">
                <svg class="olymp-speech-balloon-icon">
                    <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-speech-balloon-icon"></use>
                </svg>
                <span class="replies_count"> {{$original_obj->comment_replies_count}} </span>
            </a>
        <?php endif; ?>


        <div class="comments_section comment_section_{{$post_data->post_id}}_{{$original_obj->post_comment_id}}">
            <?php
                $this_comments_offset=0;
            ?>

            <?php if($this_comment_has_childs): ?>
                <ul class="children">
                    <?php
                        $this_comments_offset = count($all_post_comments[$original_obj->post_comment_id]);
                    ?>
                    <?php foreach ($all_post_comments[$original_obj->post_comment_id] as $key => $parent_comment): ?>
                        @include("actions.posts.view.components.parent_comments")
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if($original_obj->comment_replies_count>0 && $original_obj->comment_replies_count>$this_comments_offset): ?>
                <div class="post-comment">
                    <a href="#" class="more-comments load_comments" data-get_next_or_prev="next" data-commentid="{{$original_obj->post_comment_id}}" data-postid="{{$original_obj->post_id}}">
                        {{show_content($post_keywords,"view_previous_replies")}}
                    </a>
                </div>
            <?php endif; ?>
        </div>


        <?php if($original_obj->parent_post_comment_id == 0): ?>

        <!-- Comment Form  -->

            <form class="comment-form inline-items comment_replies post-comment write_comment_block" style="display: none;">

                <div class="post__author author vcard inline-items write_comment_block write_comment_div">
                    <img  src="{{user_default_image($current_user)}}" alt="{{$current_user->full_name}}"
                          title="{{$current_user->full_name}}" style="width: 28px;height: 28px" />

                    <div class="form-group with-icon-right">
                        <textarea class="form-control add_comment"
                          placeholder="{{show_content($post_keywords,"post_your_comment_label")}}"
                          data-postid="{{$post_data->post_id}}" data-commentid="{{$original_obj->post_comment_id}}"></textarea>
                        <div class="add-options-message comment_upload_img">
                            <a class="options-message comment_upload_img_btn" id="OpenImgUpload-{{$original_obj->post_comment_id}}">
                                <svg class="olymp-camera-icon">
                                    <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-camera-icon"></use>
                                </svg>
                            </a> 
                            <script type="text/javascript"> $('#OpenImgUpload-{{$original_obj->post_comment_id}}').click(function(){ $('#imgupload-{{$post_data->post_id}}').click(); return false;});</script> 
                            <input type="file" class="comment_upload_img_input" style="display: none;"  id="imgupload-{{$original_obj->post_comment_id}}">
                        </div>
                    </div>
                </div>

                <div class="comment_btn">
                    <button class="btn btn-md-2 btn-primary comment_upload_img_btn"><i class="fa fa-send-o"></i></button>
                </div>
            </form>

            <!-- ... end Comment Form  -->

        <?php endif; ?>


    </li>


<?php endif; ?>
