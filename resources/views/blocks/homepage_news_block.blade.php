<?php
    $link = url("news/$news_obj->page_slug");
?>
<div class="ui-block  homepage_news_block {{($key > 0)?"hide_div":""}}">
    <!-- Post -->
    <article class="hentry blog-post">

        <div class="post-thumb">
            <a href="{{$link}}">
                <img src="{{get_image_or_default($news_obj->small_img_path)}}" style="width: 402px;height: 282px;" />
            </a>
        </div>

        <div class="post-content" style="height: 100px">
            <a href="{{$link}}" style="font-size: 16px ; font-weight:  bold;" class="h4 post-title">
                {!! split_word_into_chars_ar_without_more_link(
                $news_obj->page_title,50,
                " ...") !!}
            </a>
        </div>

    </article>
    <!-- ... end Post -->
</div>


