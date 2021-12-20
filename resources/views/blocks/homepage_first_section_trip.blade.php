<?php
    $trip_path = url($lang_url_segment."/".urlencode($trip_obj->parent_cat_slug)."/".urlencode($trip_obj->child_cat_slug)."/".urlencode($trip_obj->page_slug));
?>


<div class="single-portfolio">
    <a href="{{$trip_path}}">
        <img src="{{get_image_or_default($trip_obj->small_img_path)}}"
             title="{{$trip_obj->small_img_title}}" alt="{{$trip_obj->small_img_alt}}">
    </a>
    <div class="portfolio-text {{(isset($additional_class)?$additional_class:"")}}">
        <h4>
            <a href="{{$trip_path}}">{{$trip_obj->page_title}}</a>
        </h4>
        <p>{{$trip_obj->page_short_desc}}</p>
        <div class="portfolio-price">
            <label class="currency_value" data-original_price="{{$trip_obj->page_price}}">
                {{$trip_obj->page_price}}
            </label>
            <span class="currency_sign">$</span>

        </div>
    </div>
</div>
