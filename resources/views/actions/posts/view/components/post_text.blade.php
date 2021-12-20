<?php if(!empty($post_data->post_body)): ?>
<div class="">
    <div class="post-text">
        <?php
            $post_text=$post_data->post_body;

            $youtube_links=extract_youtube_links($post_text,"no");
            $youtube_blocks="";

            if(isset_and_array($youtube_links)){
                $youtube_links=array_unique($youtube_links);

                $posts_youtube_links=json_decode($post_data->posts_youtube_links);

                foreach ($youtube_links as $key => $link) {
                    $post_text=str_replace($link,"<a target='_blank' href='$link' class='green_color' >$link</a>",$post_text);

                    $parts=parse_url($link);
                    parse_str($parts['query'], $query);
                    $video_id= $query['v'];

                    if(!isset($posts_youtube_links->{$video_id}))continue;

                    $youtube_data=$posts_youtube_links->{$video_id};

                    $youtube_blocks.=View::make("actions.posts.view.components.post_youtube_block")->with([
                        "video_id"=>"$video_id",
                        "youtube_data"=>$youtube_data
                    ])->render();
                }
            }

            $post_data->post_hashtags=array_unique($post_data->post_hashtags);
            foreach ($post_data->post_hashtags as $key => $hashtag){
                $post_text=str_replace($hashtag->hashtag_name,"<a target='_blank' href='".url("/posts/show_hashtag/".urlencode($hashtag->hashtag_name))."'>$hashtag->hashtag_name</a>",$post_text);
            }

            $post_text=explode("\n",$post_text);
        ?>

        <?php foreach ($post_text as $key => $text): ?>
            <p>{!!$text!!}</p>
        <?php endforeach; ?>

        {!! $youtube_blocks !!}
    </div>
</div>
<?php endif; ?>
