@extends('front.intro_layout')

@section('subview')
    <?php if($current_lang->lang_direction == "rtl"): ?>
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/style.css" />
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/extend_style.css" />
    <!-- emotions -->
    <link href="{{url('/public_html/front/css/emotions.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/profile.css" />

    <link rel="stylesheet" href="{{url("public_html/front/css")}}/screen.css" />

    <?php else: ?>
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/style-ltr.css" />
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/extend_style-ltr.css" />
    <!-- emotions -->
    <link href="{{url('/public_html/front/css/emotions-ltr.css')}}" rel="stylesheet">
    <?php endif; ?>

    <link href="{{url('/public_html/front/')}}/css/add_post.css" rel='stylesheet' type='text/css'/>

    <div class="container">
        <div class="row">

            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="container">
                    <div class="row">

                        <div id="newsfeed-items-grid" style="width: 100%;">

                            <?php if(isset($highlight_comment_id)): ?>
                            <input type="hidden" class="highlight_comment_id" value=".post_comment_{{$highlight_comment_id}}">
                            <?php endif; ?>

                            {!! $post_html !!}
                        </div>

                    </div>
                </div>

            </main>

            <!-- ... end Main Content -->

            <!-- Left Sidebar -->

            <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
                @include('front.main_components.left_sidebar')
            </aside>

            <!-- ... end Left Sidebar -->

            <!-- Right Sidebar -->

            <aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
                @include('front.main_components.right_sidebar')
            </aside>

            <!-- ... end Right Sidebar -->

        </div>
    </div>

@endsection
