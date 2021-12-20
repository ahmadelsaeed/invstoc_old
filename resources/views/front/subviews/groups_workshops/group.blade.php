@extends('front.main_layout')

@section('subview')

    <div id="page-contents">
        <div class="container">
            <div class="row">

                <div class="col-md-2 static padd-left padd-right">
                    @include('front.main_components.right_sidebar')
                </div>

                <div class="col-md-8">

                    <div class="tit-harmonic">
                        <h3>{{$group_obj->group_name}} </h3>
                    </div>

                    <input type="hidden" class="post_where" data-post_where="group" data-post_where_id="{{$group_obj->group_id}}">

                    @include("actions.posts.add_post")

                    <div
                            class="show_users_post"
                            data-url="load_group_posts"
                            data-group_id="{{$group_obj->group_id}}"
                            data-offset="0"></div>

                </div>

                <div class="col-md-2 static nopadding">
                    @include('front.main_components.left_sidebar')
                </div>

            </div>
        </div>
    </div>

@endsection