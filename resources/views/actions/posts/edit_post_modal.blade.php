<div class="modal fade edit_modal_{{$post_data->post_id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{show_content($post_keywords,"edit_post_header")}}</h4>
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
                                <div class="form-group with-icon-right label-floating is-empty">
                                    <!-- <label class="control-label">{{show_content($post_keywords,"write_your_post_label")}}</label> -->
                                    <textarea class="form-control post_text" placeholder="{{show_content($post_keywords,"write_your_post_label")}}" name="post_text">{!! $post_data->post_body !!}</textarea>
                                    <span class="material-input"></span>
                                </div>
                                <div class="add-options-message">

                                    <a href="#" class="options-message" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" data-original-title="{{show_content($post_keywords,"upload_images_label")}}">
                                        <svg class="olymp-camera-icon">
                                            <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-camera-icon"></use>
                                        </svg>
                                        <input type="file" multiple name="post_imgs[]" accept="image/x-png,image/gif,image/jpeg,image/jpg" class="post_imgs" data-appendwhere=".append_new_imgs">
                                        <input type="hidden" name="post_imgs_data" class="post_imgs_data">
                                    </a>

                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <div class="preview_imgs">
                                        <?php if(isset($post_data->imgs_data)): ?>
                                            <?php foreach ($post_data->imgs_data as $key => $img): ?>
                                                <div class="preview_post_img_div">
                                                    <input type="hidden" class="old_post_imgs" name="old_post_imgs[]" value="{{$img->id}}">
                                                    <img src="{{url("/$img->path")}}" class="preview_post_img">
                                                    <div class="remove_img">
                                                        <a href="#" class="remove_img_from_arr">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <div class="append_new_imgs"></div>

                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <div class="youtube_parent_divs">

                                    </div>
                                </div>

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


                                        <button class="btn btn-primary add_post_btn pull-left" data-postid="{{$post_data->post_id}}">{{show_content($general_static_keywords,"save_btn")}}</button>
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