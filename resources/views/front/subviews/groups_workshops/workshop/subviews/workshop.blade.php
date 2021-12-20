@extends('front.subviews.groups_workshops.workshop.workshop_main_layout')

@section('workshop_subview')

    <input type="hidden" class="post_where" data-post_where="workshop" data-post_where_id="{{$workshop_obj->workshop_id}}">

    <div class="ui-block" style="width: 100%">

        @if(auth()->check())
            @include("actions.posts.add_post")
        @endif
    </div>

    <div id="newsfeed-items-grid" style="width: 100%">

        <div class="new_posts_created"></div>

        <div
                class="show_users_post"
                data-url="load_workshop_posts"
                data-workshop_id="{{$workshop_obj->workshop_id}}"
                data-offset="0"></div>

    </div>

    <a    class="btn btn-control btn-more load-more-posts">
                    <svg class="olymp-three-dots-icon">
                        <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                    </svg>
                </a>

@endsection