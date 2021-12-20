@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')

    <?php
        if(
            $group_obj->group_post_options=="1"||
            ($group_obj->group_post_options=="0"&&$group_obj->group_owner_id==$current_user->user_id) ||
            ($group_obj->group_post_options=="2"&&$this_user_group_membership->user_role=="admin")
        ):
    ?>
        <input type="hidden" class="post_where" data-post_where="group" data-post_where_id="{{$group_obj->group_id}}">

        <div class="ui-block" style="width: 100%">
            <!-- News Feed Form  -->

            @include("actions.posts.add_post")

            <!-- ... end News Feed Form  -->
        </div>

        <div id="newsfeed-items-grid" style="width: 100%">

            <div class="new_posts_created"></div>

            <div
                    class="show_users_post"
                    data-url="load_group_posts"
                    data-group_id="{{$group_obj->group_id}}"
                    data-offset="0"></div>

        </div>
    <?php endif; ?>

@endsection