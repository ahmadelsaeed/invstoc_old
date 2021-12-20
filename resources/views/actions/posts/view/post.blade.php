
<div class="ui-block post_div
            show_post_div_{{$post_data->post_id}}
            {{($post_data->post_or_recommendation=="recommendation")?"recommendation_post_div recommendation_post_div_$post_data->recommendation_status":""}}
        ">

    <article class="hentry post">

        <?php if($current_lang->lang_direction == "rtl"): ?>
            @include("actions.posts.view.components.post_header_rtl")
        <?php else: ?>
            @include("actions.posts.view.components.post_header_ltr")
        <?php endif; ?>


        <?php if(isset($post_data->this_post_body) && trim($post_data->this_post_body) != "undefined"): ?>
            <p>
                {!! $post_data->this_post_body !!}
            </p>
        <?php endif; ?>


        @include("actions.posts.view.components.post_text")

        <?php if(isset($post_data->imgs_data)&&count($post_data->imgs_data)=="1"): ?>
            <img src="{{url("/".$post_data->imgs_data[0]->path)}}" alt="" class="img-responsive post-image open_post_image" />
        <?php elseif(isset($post_data->imgs_data)&&count($post_data->imgs_data)>1): ?>
            @include("actions.posts.view.components.post_slider")
        <?php endif; ?>

        <?php if($post_data->post_or_recommendation=="post"): ?>

            <?php if($current_lang->lang_direction == "rtl"): ?>
                @include("actions.posts.view.components.post_footer_rtl")
            <?php else: ?>
                @include("actions.posts.view.components.post_footer_ltr")
            <?php endif; ?>

        <?php endif; ?>


    </article>

</div>

