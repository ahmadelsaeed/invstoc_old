@extends('front.main_layout')

@section('subview')

    <div class="container">
        <div class="row">

            <!-- Left Sidebar -->

            <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
                @include('front.main_components.left_sidebar')
            </aside>

            <!-- ... end Left Sidebar -->


            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="ui-block">
                    <!-- News Feed Form  -->

                    @include("actions.posts.add_post")

                    <!-- ... end News Feed Form  -->
                </div>

                <div id="newsfeed-items-grid">
                    
                    <div class="new_posts_created"></div>

                    <div class="show_users_post" data-url="load_homepage_posts" data-offset="0"></div>

                </div>

                <a class="btn btn-control btn-more load-more-posts">
                    <svg class="olymp-three-dots-icon">
                        <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                    </svg>
                </a>

            </main>

            <!-- ... end Main Content -->


            <!-- Right Sidebar -->

            <aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
                @include('front.main_components.right_sidebar')
            </aside>

            <!-- ... end Right Sidebar -->

        </div>
    </div>

@endsection
