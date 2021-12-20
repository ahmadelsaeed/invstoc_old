<?php
    $link = url("articles/$art_obj->page_slug");
?>

<div class="ui-block" style="min-height: 466px">
    <!-- Post -->
    <article class="hentry blog-post">

        <div class="post-thumb">
            <a href="{{$link}}">
                <img src="{{get_image_or_default($art_obj->small_img_path)}}" style="width: 402px;height: 282px;" />
            </a>
        </div>

        <div class="post-content">
            <a href="{{$link}}" class="h4 post-title">
                {!! split_word_into_chars_ar_without_more_link(
                $art_obj->page_title,70,
                " ...") !!}
            </a>
            <!-- 
            <p>
                {!! split_word_into_chars_ar_without_more_link(
                $art_obj->page_short_desc,100,
                " ...") !!}
            </p>
            
             --><div class="author-date">
                <div class="post__date">
                    <time class="published">
                        {{\Carbon\Carbon::createFromTimestamp(strtotime($art_obj->created_at))->diffForHumans()}}
                      <div class='starrr' id='article_star-{{$art_obj->page_id}}'></div>
                    </time>
                </div>
            </div>
            <script >
                $('#article_star-{{$art_obj->page_id}}').starrr({
                  readOnly: true,
                  rating: {{$rates[$art_obj->page_id]}},
                  
                });
            </script>
        </div>

    </article>
    <!-- ... end Post -->
</div>

