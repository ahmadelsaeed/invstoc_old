<div class="all_comments_section" style="{{isset($all_post_comments[0])?"display:block;":"display:none;"}}">
    <div class="">

        <?php if(isset($show_previous_comments_btn)): ?>
            <a href="#" class="more-comments load_comments" data-get_next_or_prev="prev" data-commentid="0" data-postid="{{$post_data->post_id}}">
                {{show_content($post_keywords,"view_previous_comments")}}
            </a>
        <?php endif; ?>

            <ul class="comments-list post_comments comment_section_{{$post_data->post_id}}_0">
                <?php if(isset($all_post_comments[0])): ?>

                    <?php foreach ($all_post_comments[0] as $key => $parent_comment): ?>
                        @include("actions.posts.view.components.parent_comments")
                    <?php endforeach; ?>

                <?php endif; ?>
            </ul>

        <?php if($check_next_comments): ?>
            <a href="#" class="more-comments load_comments" data-get_next_or_prev="next" data-commentid="0" data-postid="{{$post_data->post_id}}">
                {{show_content($post_keywords,"view_more_comments")}}
            </a>
        <?php endif; ?>
    </div>
</div>


<!-- Comment Form  -->

<form class="comment-form inline-items write_comment_div">

    <div class="post__author author vcard inline-items">
        <img  src="{{user_default_image($current_user)}}" alt="{{$current_user->full_name}}"
              title="{{$current_user->full_name}}" style="width: 28px;height: 28px" />

        <div class="form-group with-icon-right ">
            <textarea class="form-control add_comment"
                      placeholder="{{show_content($post_keywords,"post_your_comment_label")}}"
                      data-postid="{{$post_data->post_id}}" data-commentid="0"></textarea>
            <div class="add-options-message comment_upload_img">
                <a  class="options-message comment_upload_img_btn" id="OpenImgUpload-{{$post_data->post_id}}">
                    <svg class="olymp-camera-icon">
                        <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-camera-icon"></use>
                    </svg>
                </a>
                <script type="text/javascript"> $('#OpenImgUpload-{{$post_data->post_id}}').click(function(){ $('#imgupload-{{$post_data->post_id}}').click(); return false;});</script> 
                <input type="file" class="comment_upload_img_input" style="display: none;"  id="imgupload-{{$post_data->post_id}}">
            </div>
        </div>
    </div>

    <div class="comment_btn">
        <button class="btn btn-md-2 btn-primary comment_upload_img_btn"><i class="fa fa-send-o"></i></button>
    </div>
</form>

<!-- ... end Comment Form  -->
