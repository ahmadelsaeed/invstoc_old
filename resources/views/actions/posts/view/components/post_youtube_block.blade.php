<?php
    $link=$youtube_data->link;
    $title=$youtube_data->title;
    $description=$youtube_data->description;
    $img=$youtube_data->img;

    $link_arr = explode('&',$link);
    $link = $link_arr[0];

?>

<div class="post-video">
    <div class="video-thumb">
        <a class="img_link post_play_video" href="{{$link}}">
            <img src="{{$img}}" title="{{$title}}" alt="{{$title}}" class="youtube_img" style="height: 110px;width: 110px;">
        </a>
    </div>

    <div class="video-content">
        <a href="{{$link}}" class="h4 title youtube_link post_play_video">{{$title}}</a>
        <p class="youtube_desc">
            <a href="{{$link}}" target="_blank" class="youtube_desc_link post_play_video">
                {!! split_word_into_chars_ar_without_more_link(
                $description,150,
                " ...") !!}
            </a>
        </p>
    </div>
</div>

<?php if(false): ?>
<div class="col-md-12 nopadding youtube_div embed_youtube_video" data-embedlink="https://www.youtube.com/embed/{{$video_id}}">
    <div class="col-md-12 nopadding youtube_inner_div">
        <div class="pull-right">

        </div>

        <div class="col-md-9">
            <div class="col-md-12">
                <h2><a href="{{$link}}" target="_blank" class="youtube_link">{{$title}}</a></h2>
            </div>

            <div class="col-md-12">
                <p class="youtube_desc"><a href="{{$link}}" target="_blank" class="youtube_desc_link">{{$description}}</a></p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
