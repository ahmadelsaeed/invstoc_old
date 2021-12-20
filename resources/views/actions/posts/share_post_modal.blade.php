<div class="modal fade share_modal_{{$post_data->post_id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{show_content($post_keywords,"share_post_label")}}</h4>
            </div>
            <div class="modal-body">
                <div class="edit_post_div">
                    <div class="create-post news-feed-form parent_add_post_div">
                        <div class="">

                            <form>

                                <div class="author-thumb">
                                    <img src="{{user_default_image($current_user)}}" alt="{{$current_user->full_name}}"
                                         title="{{$current_user->full_name}}" style="width: 36px;height: 36px" />
                                </div>

                                <div class="form-group with-icon label-floating is-empty">
                                    <label class="control-label">{{show_content($post_keywords,"write_your_post_label")}}</label>
                                    <textarea class="form-control post_text" name="post_text">{!! $post_data->post_body !!}</textarea>
                                    <span class="material-input"></span>
                                </div>

                                <?php if(false): ?>

                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group post_text_form_group">

                                        <img src="{{get_image_or_default($current_user->logo_path)}}" alt="{{$current_user->full_name}}" title="{{$current_user->full_name}}" class="profile-photo-md" />
                                        <textarea
                                                name="post_text" cols="30"
                                                rows="1" class="form-control post_text" placeholder="{{show_content($post_keywords,"write_your_post_label")}}"
                                        ></textarea>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="col-md-12 col-sm-12">
                                    <div class="progress post_progress_bar">
                                        <div class="progress-bar " role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                        </div>
                                    </div>

                                    <div class="col-md-12 tools">

                                        <div class="pull-right">
                                            <div class="form-inline">

                                            </div>
                                        </div>


                                        <button class="btn btn-primary add_post_btn pull-left" data-sharepost="1" data-postid="{{$post_data->post_id}}">{{show_content($post_keywords,"share_post_label")}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>