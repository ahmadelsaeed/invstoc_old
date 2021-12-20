<article class="timeline-entry">

    <div class="timeline-entry-inner">
        <time class="timeline-time">
            <span>{{date("H:i a",strtotime($post_data->post_created_at))}}</span> <span>{{date("Y-m-d",strtotime($post_data->post_created_at))}}</span>
        </time>

        <div class="timeline-icon bg-success">
            <i class="entypo-feather"></i>
        </div>

        <div class="timeline-label
            {{
                (
                $post_data->user_id==$current_user->user_id&&
                $post_data->post_or_recommendation=="recommendation"&&
                $post_data->closed_price==0&&
                $post_data->recommendation_end_date<date("Y-m-d")
                )?" red_box_timeline":""
            }}
">
            @include("actions.posts.view.post")
        </div>
    </div>

</article>