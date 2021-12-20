
<?php
    $post_link = url("/posts/".string_safe($post_data->post_user->full_name)."/".$post_data->post_user->user_id."/".$post_data->post_id);
    $post_preview_link = url("/preview/posts/".string_safe($post_data->post_user->full_name)."/".$post_data->post_user->user_id."/".$post_data->post_id);

    if (isset($preview_post) && $preview_post && !isset($current_user))
    {
        $post_link = $post_preview_link;
    }
?>

<div class="post__author author vcard inline-items">
    <img src="{{user_default_image($post_data->post_user)}}"
         alt="{{$post_data->post_user->full_name}}" title="{{$post_data->post_user->full_name}}"
         style="width: 40px;height: 40px;"/>

    <div class="author-date">
        <a href="{{url("/user/posts/all/".$post_data->post_user->user_id)}}" class="h6 post__author-name fn">
            {{$post_data->post_user->full_name}}
            <?php if($post_data->post_user->is_privet_account): ?>
            @include('blocks.verify_padge_block')
            &nbsp;
            <?php endif; ?>
        </a>

        <?php if(isset($post_data->orginal_post_user)): ?>
            {{show_content($post_keywords,"shared_from_label")}} &nbsp;
            <a href="{{url("/user/posts/all/".$post_data->orginal_post_user->user_id)}}">
                {{$post_data->orginal_post_user->full_name}}
                <?php if($post_data->post_user->is_privet_account): ?>
                @include('blocks.verify_padge_block')
                &nbsp;
                <?php endif; ?>
            </a>
        <?php endif; ?>

        <div class="post__date">
            <time class="published">
                <?php
                    $post_datetime = \Carbon\Carbon::createFromTimestamp(strtotime($post_data->created_at))->setTimezone($post_data->post_user->timezone)->toDateTimeString();
                    date_default_timezone_set($post_data->post_user->timezone);
                ?>
                <a href="{{$post_link}}" title="{{$post_datetime}}" class="green_color">
                    {{\Carbon\Carbon::createFromTimestamp(strtotime($post_datetime))->diffForHumans()}}
                </a>
            </time>
        </div>
    </div>

    <?php if(!isset($preview_post)): ?>
        <div class="more">
        <svg class="olymp-three-dots-icon">
            <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
        </svg>
        <ul class="more-dropdown">
            <?php if( isset($current_user) && $current_user->user_id==$post_data->post_user->user_id): ?>

            <?php if($post_data->post_or_recommendation=="post"): ?>

            <?php if($post_data->is_not_editable == 0): ?>
            <li><a class="edit_post" data-postid="{{$post_data->post_id}}" href="#">{{show_content($general_static_keywords,"edit_btn")}}</a></li>
            <?php endif; ?>

            <li><a class="delete_post" data-postid="{{$post_data->post_id}}" href="#">{{show_content($general_static_keywords,"delete_btn")}}</a></li>

            <?php else: ?>

            <?php if($post_data->post_or_recommendation=="recommendation" && $post_data->closed_price=="0" && $post_data->post_share_id == 0): ?>
            <li><a class="add_order_closed_price" data-postid="{{$post_data->post_id}}" href="#">{{show_content($post_keywords,"add_closed_price")}}</a></li>
            <?php endif; ?>

            <?php if($post_data->post_or_recommendation=="recommendation" && $post_data->closed_price=="0" &&in_array($post_data->sell_or_buy,["pending_sell","pending_buy"])&&$post_data->order_is_not_closed=="0"): ?>
            <li><a class="make_order_not_closed" data-postid="{{$post_data->post_id}}" href="#">{{show_content($post_keywords,"make_order_not_closed")}}</a></li>
            <?php endif; ?>

            <?php if($post_data->post_or_recommendation=="recommendation" && $post_data->post_share_id == 0): ?>
                <li>
                    <a class="add_to_orders_list" data-postid="{{$post_data->post_id}}" href="#">
                        <i class="fa fa-shopping-bag"></i> {{show_content($post_keywords,"add_to_orders_list")}} </a>
                </li>
            <?php endif; ?>

            <?php if($post_data->post_or_recommendation=="recommendation" && $settings->allow_delete_order == 1): ?>
            <li><a class="delete_post" data-postid="{{$post_data->post_id}}" href="#">{{show_content($post_keywords,"delete_post_label")}}</a></li>
            <?php endif; ?>

            <?php endif; ?>

            <?php endif; ?>

            <li><a class="save_post" data-postid="{{$post_data->post_id}}" href="#">{{show_content($post_keywords,"save_post_label")}}</a></li>

            <?php if($post_data->post_or_recommendation=="recommendation" &&
            isset($current_user) && $current_user->user_id==$post_data->post_user->user_id): ?>
            <li>
                <a href="#" class="share_post" data-postid="{{$post_data->post_id}}">
                    <i class="fa fa-refresh"></i> {{show_content($post_keywords,"share_post_label")}}
                </a>
            </li>
            <?php endif; ?>

            <li>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{$post_preview_link}}" target="_blank">
                    <i class="fa fa-facebook"></i> {{show_content($post_keywords,"share_post_label")}}
                </a>
            </li>
            <li>
                <a href="https://plus.google.com/share?url={{$post_preview_link}}" target="_blank">
                    <i class="fa fa-google-plus"></i> {{show_content($post_keywords,"share_post_label")}}
                </a>
            </li>
            <li>
                <a href="https://twitter.com/intent/tweet?url={{$post_preview_link}}" target="_blank">
                    <i class="fa fa-twitter"></i> {{show_content($post_keywords,"share_post_label")}}
                </a>
            </li>
            <li>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{$post_preview_link}}&title={{$og_title}}&summary={{$og_description}}&source=LinkedIn" target="_blank">
                    <i class="fa fa-linkedin"></i> {{show_content($post_keywords,"share_post_label")}}
                </a>
            </li>
        </ul>
    </div>
    <?php endif; ?>

</div>


<?php if($post_data->post_or_recommendation=="recommendation"): ?>
    <style>
        .split-badge span{
            display:block;
            width:100%;
            margin:10px;
            padding:2px;
        }
    </style>
    <div class="post__author author vcard inline-items">
        <div class="row" style=" width: 100%; display: flex; ">
            <div class="col-md-4 split-badge">
              <!--  <?php if(!empty($post_data->recommendation_status)): ?>
              <span class="recommendation_status_{{$post_data->recommendation_status}} badge badge-pill badge-primary">
                  <strong>
                      {{$post_data->recommendation_status}}
                      <?php if($post_data->closed_price > 0): ?>
                      <?php

                      $diff_price = calc_order_diff_price($post_data);

                      ?>

                      <?php if($diff_price != 0): ?>
                      {{$diff_price}} p
                      <?php endif; ?>

                      <?php endif; ?>

                  </strong>
              </span>
              <?php endif; ?> -->

               <!-- <?php if(isset($post_data->cat_name)): ?>
               <span class="badge badge-pill badge-primary">
                   <strong> {{$post_data->cat_name}}</strong>
               </span>
               <?php endif; ?> -->

               <span class="badge badge-pill badge-primary">
                   <strong>{{show_content($post_keywords,"$post_data->sell_or_buy"."_label")}}</strong>
               </span>
               <span class="badge badge-pill badge-primary ">
                   <strong>{{$post_data->pair_currency_name}}</strong>
               </span>

               <?php if($post_data->order_is_not_closed=="1"): ?>
               <span class="badge badge-pill badge-primary">
                   <strong>{{show_content($post_keywords,"not_closed_label")}}</strong>
               </span>
               <?php endif; ?>

               <?php if(true || (is_object($current_user) && $current_user->user_id == $post_data->post_user->user_id)): ?>
               <span class="badge badge-pill badge-primary">
                   <strong> {{show_content($post_keywords,"open_price_label")}} : {{$post_data->expected_price}}</strong>
               </span>
               <?php endif; ?>

                  <?php if(true || (is_object($current_user) && $current_user->user_id == $post_data->post_user->user_id)): ?>
                  <span class="badge badge-pill badge-danger">
                   <strong> Stop loss : {{$post_data->stop_loss}}</strong>
               </span>
                  <?php endif; ?>

                  <?php if(true || (is_object($current_user) && $current_user->user_id == $post_data->post_user->user_id)): ?>
                  <span class="badge badge-pill badge-primary">
                   <strong> Take profit : {{$post_data->take_profit}}</strong>
               </span>
                  <?php endif; ?>


                  <?php if(!empty($post_data->recommendation_status)): ?>
               <span class="badge badge-pill badge-primary">
                   <strong> {{show_content($post_keywords,"closed_price_label")}} : {{$post_data->closed_price}}</strong>
               </span>
               <?php endif; ?>


            </div>
            <?php if(!empty($post_data->recommendation_status)): ?>
            <div class="col-md-4">
            </div>
            <div class="col-md-2">
                <span style=" font-size: 16px; margin-top: 45px; text-transform: uppercase; padding: 15px;"
                class="recommendation_status_{{$post_data->recommendation_status}} badge badge-pill badge-primary">
                    <strong>
                      {{$post_data->recommendation_status}}
                       <?php if($post_data->closed_price > 0): ?>
                       <?php

                       $diff_price = calc_order_diff_price($post_data);

                       ?>

                       <?php if($diff_price != 0): ?>
                       {{$diff_price}} p
                       <?php endif; ?>

                       <?php endif; ?>
                    </strong>
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>



<?php endif; ?>


