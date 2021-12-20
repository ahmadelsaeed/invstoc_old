@extends('front.subviews.user.user_layout')
@section('page')
    <?php if($post_type == "order" && isset($_GET['not_closed'])): ?>
        <input type="hidden" class="not_closed">
    <?php endif; ?>


    <div class="container">
        <div class="row">

            <div class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">
                <div id="newsfeed-items-grid">
                    <div class="show_users_post" data-url="user/get_posts/{{$post_type}}/{{$user_id}}" data-offset="0"></div>
                </div>

                <a class="btn btn-control btn-more load-more-posts" data-load-link="items-to-load.html" data-container="newsfeed-items-grid">
                    <svg class="olymp-three-dots-icon">
                        <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                    </svg>
                </a>
            </div>

        @if($current_user)
            <!-- Left Sidebar -->

            @if(auth()->check())
                @include('front.subviews.user.pages.left_sidbar')
            @endif
                <!-- ... end Left Sidebar -->
            <!-- Right Sidebar -->
            @include('front.subviews.user.pages.right_sidebar')

                <!-- ... end Right Sidebar -->
        @endif


        </div>
    </div>

@endsection
