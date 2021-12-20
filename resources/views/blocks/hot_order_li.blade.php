<li class="hot_order_li hot_order_{{$post_id}}">
    <a href="{{url("/posts/$poster_full_name/$poster_user_id/$post_id")}}">
        <strong>{{$poster_full_name}}</strong>

        {{show_content($user_homepage_keywords,"make_new_order_label")}}

        <?php
            $post_datetime = \Carbon\Carbon::createFromTimestamp(strtotime($created_at))->setTimezone($user_timezone);

            $post_time = $post_datetime->format('H:i A');
        ?>
        <span class="convert_utc" title="{{$post_datetime}}">{{$post_time}}</span>
        <br>


        <div class="post__author author vcard inline-items">
            <span class="badge badge-success post_li_item_label">{{$post_data->pair_currency_name}}</span>

            <span class="badge badge-success post_li_item_label">{{show_content($post_keywords,"$post_data->sell_or_buy"."_label")}}</span>
            <span class="badge badge-success post_li_item_label">{{$post_data->cat_name}}</span>

            <?php if(!empty($post_data->recommendation_status)): ?>
            <?php

            $diff_price = calc_order_diff_price($post_data);

            ?>

                    <span class="badge badge-success post_li_item_label recommendation_status_{{$post_data->recommendation_status}}">  {{$post_data->recommendation_status}} {{$diff_price}} p </span>
            <?php endif; ?>

            <?php if($post_data->order_is_not_closed=="1"): ?>
                <span class='badge badge-success post_li_item_label'>{{show_content($post_keywords,"not_closed_label")}}</span>
            <?php endif; ?>


                <span class='badge badge-success post_li_item_label'>{{show_content($post_keywords,"open_price_label")}} : {{$post_data->expected_price}}</span>

                <?php if(!empty($post_data->recommendation_status)): ?>
                <span class='badge badge-success post_li_item_label'>{{show_content($post_keywords,"closed_price_label")}} : {{$post_data->closed_price}}</span>
                <?php endif; ?>


            <!--
                <span class="recommendation_status_lose badge badge-pill badge-primary">
                    <strong>
                        lose

                                        331 p


                    </strong>
                </span>

                <span class="badge badge-pill badge-primary">
                    <strong> Elliott Wave</strong>
                </span>

                <span class="badge badge-pill badge-primary">
                    <strong>Buy</strong>
                </span>

                <span class="badge badge-pill badge-primary">
                    <strong>CADCHF</strong>
                </span>
            -->
        </div>


    </a>
</li>