<div class="modal fade edit_comment_modal_{{$comment_data->post_comment_id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{show_content($post_keywords,"edit_comment_header")}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">


                    <?php if(!empty($comment_data->comment_img_path)): ?>
                        <div class="col-md-12 col-sm-12">

                            <div class="preview_imgs">

                                <div class="preview_post_img_div">
                                    <input type="hidden" name="keep_image" class="remove_image keep_image">
                                    <img src="{{get_image_or_default($comment_data->comment_img_path)}}" class="preview_post_img">
                                    <div class="remove_img">
                                        <a href="#" class="remove_img_from_arr">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="append_new_imgs"></div>

                            </div>

                        </div>
                    <?php endif; ?>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group write-comment">



                            <div class="comment_upload_img">
                                <div class="comment_upload_img_btn"><i class="fa fa-image"></i></div>
                                <input type="file" class="comment_upload_img_input">
                            </div>

                            <textarea
                                    name="edit_comment_text" cols="30"
                                    rows="1" class="form-control edit_comment_text"
                            >{!! $comment_data->post_comment_body !!}</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-primary post_edit_comment" data-commentid="{{$comment_data->post_comment_id}}">
                            {{show_content($general_static_keywords,"edit_btn")}}
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>