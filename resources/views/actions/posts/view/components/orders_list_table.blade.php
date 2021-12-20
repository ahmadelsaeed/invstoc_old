
<div class="col-md-12" style="padding-bottom: 10px;">
    <p>{!! $post_body !!}</p>
</div>

<ul class="work-shop hot_orders_ul scroll">
    <?php foreach($get_list_items as $key => $post_obj): ?>

    <?php if($key == 3): ?>

        <li class="load_all_orders_list">

            <a href="#">
                <strong>{{show_content($general_static_keywords,"see_all")}} <i class="fa fa-refresh"></i> </strong>
            </a>

        </li>

    <?php endif; ?>

        <div class="post__author author vcard inline-items">
            <span class='badge badge-success post_li_item_label'>{{$post_obj->pair_currency_name}}</span>
            <span class='badge badge-success post_li_item_label'>{{show_content($post_keywords,"$post_obj->sell_or_buy"."_label")}}</span>
            <span class='badge badge-success post_li_item_label'>{{$post_obj->cat_name}}</span>
            <?php

            $diff_price = calc_order_diff_price($post_obj);

            ?>
            <span class='badge badge-success post_li_item_label recommendation_status_{{$post_obj->recommendation_status}}'> {{$post_obj->recommendation_status}} {{$diff_price}} p</span>

            <?php if($post_obj->order_is_not_closed=="1"): ?>
                <span class='badge badge-success post_li_item_label'>{{show_content($post_keywords,"not_closed_label")}}</span>
            <?php endif; ?>
            <?php if(!empty($post_obj->recommendation_status)): ?>
                <span class='badge badge-success post_li_item_label'>{{show_content($post_keywords,"closed_price_label")}} : {{$post_obj->closed_price}}</span>
            <?php endif; ?>
            <span class='badge badge-success post_li_item_label'>{{show_content($post_keywords,"open_price_label")}} : {{$post_obj->expected_price}}</span>

        </div>
    <?php endforeach; ?>

</ul>
