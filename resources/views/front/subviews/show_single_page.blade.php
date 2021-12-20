@extends('front.main_layout')

@section('subview')
    <input type="hidden" class="socket_link" value="{{env("socket_link")}}">


    <script src="{{env("socket_link")}}/socket.io/socket.io.js"></script>
    <script src="{{url("/public_html/jscode/instant_updates/comments.js")}}"></script>


    <input type="hidden" class="emit_input_value" value="">
    <input type="hidden" class="send_to_other_users" value="">


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
