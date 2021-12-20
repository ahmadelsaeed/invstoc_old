<?php
    $cat_path = url($lang_url_segment."/".urlencode($cat_obj->cat_slug));
?>

<div class="hover-effect">
    <div class="box-hover">
        <a href="{{$cat_path}}">
            <img src="{{get_image_or_default($cat_obj->small_img_path)}}"
                 title="{{$cat_obj->small_img_title}}" alt="{{$cat_obj->small_img_alt}}">
            <span>{{$cat_obj->cat_name}}</span>
        </a>
    </div>
</div>

