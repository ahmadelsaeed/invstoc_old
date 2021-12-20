<?php
    $link = url("news/$news_obj->page_slug");
?>

<div class="ui-block" style="min-height: 440px">
    <!-- Post -->
    <article class="hentry blog-post">

        <div class="post-thumb">
            <a href="{{$link}}">
                <img src="{{get_image_or_default($news_obj->small_img_path)}}" style="width: 402px;height: 282px;" />
            </a>
        </div>

        <div class="post-content" style="height: 150px">
            <a href="{{$link}}" class="h4 post-title">
                {!! split_word_into_chars_ar_without_more_link(
                $news_obj->page_title,50,
                " ...") !!}
            </a>
        
            <div class="author-date">
                <div class="post__date">
                    <time class="published" >
                        {{ date ( 'd : m : Y',strtotime($news_obj->created_at))}}
                    </time>
                </div>
            </div>

        </div>

    </article>
    <!-- ... end Post -->
</div>

