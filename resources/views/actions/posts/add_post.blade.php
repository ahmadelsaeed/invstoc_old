
<?php if(isset($current_user)&&is_object($current_user)&&isset($user_can_post)&&$user_can_post==true): ?>

<div class="news-feed-form parent_add_post_div">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active inline-items select_post_tab_type" data-type="post" data-toggle="tab" href="#post-form-tab" role="tab" aria-expanded="true">

                <svg class="olymp-status-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-status-icon"></use></svg>

                <span>{{show_content($post_keywords,"post_type_label")}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link inline-items select_post_tab_type" data-type="recommendation" data-toggle="tab" href="#order-form-tab" role="tab" aria-expanded="false">

                <svg class="olymp-multimedia-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-blog-icon"></use></svg>

                <span>{{show_content($post_keywords,"recommend_type_label")}}</span>
            </a>
        </li>

        <input type="hidden" name="post_or_recommendation" class="post_or_recommendation_class" value="post">

    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="post-form-tab" role="tabpanel" aria-expanded="true">

            <div class="container post_recommendation_options" style="margin-top: 10px;display: none;">
                <div class="row select_pair_currency" style="margin-bottom: 10px;">

                    <?php
                    echo generate_select_tags(
                        $field_name="pair_currency_id",
                        $label_name="اختار زوج العملات",
                        $text=$all_pair_currencies->pluck("pair_currency_name")->all(),
                        $values=$all_pair_currencies->pluck("pair_currency_id")->all(),
                        $selected_value=[""],
                        $class="form-control select_2_class",
                        $multiple="",
                        $required="",
                        $disabled = "",
                        $data = "",
                        $parent_div_class = "form-group col col-md-4 col-xs-6",
                        $hide_label=true
                    );
                    ?>

                    <div class="col col-md-4 col-xs-6">
                        <?php
                        echo generate_select_tags(
                            $field_name="sell_or_buy",
                            $label_name="",
                            $text=[
                                show_content($post_keywords,"sell_label"),
                                show_content($post_keywords,"buy_label"),
                                show_content($post_keywords,"pending_sell_label"),
                                show_content($post_keywords,"pending_buy_label"),
                            ],
                            $values=["sell","buy","pending_sell","pending_buy"],
                            $selected_value=["sell"],
                            $class="form-control sell_or_buy_class",
                            $multiple="",
                            $required="",
                            $disabled = "",
                            $data = "",
                            $parent_div_class = "form-group",
                            $hide_label=true
                        );
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-md-6 col-xs-6">
                        <input type="number" required min="0" step="0.000000001" class="form-control order_expected_price" placeholder="{{show_content($post_keywords,"expected_price_label")}}">
                    </div>
                    <div class="col col-md-6 col-xs-6">
                        <input type="number" required min="1" step="1" max="30" class="form-control order_stop_loss" placeholder="Stop loss">
                    </div>

                    <div class="col col-md-6 col-xs-6">
                        <input type="number" required min="0" step="0.000000001" class="form-control order_take_profit" placeholder="Take profit">
                    </div>
                </div>
            </div>

            <form>
                <div class="author-thumb">
                    <img src="{{user_default_image($current_user)}}" alt="{{$current_user->full_name}}"
                         title="{{$current_user->full_name}}" style="width: 36px;height: 36px" />
                </div>
                <div class="form-group  with-icon-right label-floating is-empty">
                   <!--  <label class="control-label">{{show_content($post_keywords,"write_your_post_label")}}</label> -->
                    <textarea class="form-control post_text " name="post_text" placeholder="{{show_content($post_keywords,"write_your_post_label")}}"></textarea>
                    <span class="material-input"></span>
                </div>
                <div class="add-options-message">

                    <a href="#" class="options-message" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" data-original-title="{{show_content($post_keywords,"upload_images_label")}}">
                        <svg class="olymp-camera-icon">
                            <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-camera-icon"></use>
                        </svg>
                        <input type="file" multiple name="post_imgs[]" accept="image/x-png,image/gif,image/jpeg,image/jpg" class="post_imgs" data-appendwhere=".preview_imgs">
                        <input type="hidden" name="post_imgs_data" class="post_imgs_data">
                    </a>

                    <button class="btn btn-primary btn-md-2 add_post_btn">{{show_content($post_keywords,"post_btn")}}</button>

                </div>

                <div>

                    <div class="col-md-12 col-sm-12">
                        <div class="preview_imgs"></div>
                    </div>

                    <div class="youtube_parent_divs">

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

                        </div>
                    </div>


                </div>

            </form>

        </div>

    </div>

</div>


<?php else: ?>

<div class="alert alert-info">
    <p>{{show_content($post_keywords,"can_not_post")}}
        <a href="{{url("/user/posts/order/$current_user->user_id?not_closed=true")}}">{{show_content($post_keywords,"close_orders_link")}}</a>
    </p>
</div>

<?php endif; ?>
