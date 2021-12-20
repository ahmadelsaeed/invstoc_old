@extends('front.main_layout')

@section('subview')

    <div class="container">
        <div class="row">

            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="container">
                    <div class="row">

                        <div id="ui-block" style="width: 100%;">

                            <div class="tab_new advanced_search_tab_container">

                                <!-- Nav tabs -->
                                <ul class="nav nav-pills mb-3 advanced_search_tab" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{(!isset($_GET['tab_type']))?"active":""}}"
                                           href="{{url(strtolower(session('language_locale', 'en'))."/advanced_search?search=".clean($_GET['search']))}}">
                                            All
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{(isset($_GET['tab_type']) && $_GET['tab_type'] == 'friends')?"active":""}}"
                                           href="{{url(strtolower(session('language_locale', 'en'))."/advanced_search?search=".clean($_GET['search']) . "&tab_type=friends")}}">
                                            {{show_content($advanced_search,"firends_label")}}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{(isset($_GET['tab_type']) && $_GET['tab_type'] == "orders")?"active":""}}"
                                           href="{{url(strtolower(session('language_locale', 'en'))."/advanced_search?search=".clean($_GET['search'])."&tab_type=orders")}}">{{show_content($advanced_search,"orders_label")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{(isset($_GET['tab_type']) && $_GET['tab_type'] == "posts")?"active":""}}"
                                           href="{{url(strtolower(session('language_locale', 'en'))."/advanced_search?search=".clean($_GET['search'])."&tab_type=posts")}}">{{show_content($advanced_search,"posts_label")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{(isset($_GET['tab_type']) && $_GET['tab_type'] == "books")?"active":""}}"
                                           href="{{url(strtolower(session('language_locale', 'en'))."/advanced_search?search=".clean($_GET['search'])."&tab_type=books")}}">{{show_content($advanced_search,"books_label")}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{(isset($_GET['tab_type']) && $_GET['tab_type'] == "workshops")?"active":""}}"
                                           href="{{url(strtolower(session('language_locale', 'en'))."/advanced_search?search=".clean($_GET['search'])."&tab_type=workshops")}}">{{show_content($advanced_search,"workshops_label")}}</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content content_tab" id="pills-tabContent">

                                    <div role="tabpanel" class="tab-pane {{(!isset($_GET['tab_type']))?"active":""}}" id="tab_1">
                                        <?php if(!isset($_GET['tab_type'])): ?>
                                            <?php if(isset($suggested_users) && count($suggested_users)): ?>
                                                <div class="row">
                                                    <?php foreach($suggested_users as $key => $user_obj_item): ?>
                                                        <div class="col col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                                                            @include("blocks.user_block_general")
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <hr/>
                                            <?php elseif((count($workshops))) : ?>
                                                <div class="row">
                                                    <?php foreach($workshops as $key => $workshop): ?>
                                                    <div class="col col-xl-4 col-lg-3 col-md-4 col-sm-4 col-12 book_item">
                                                        @include('blocks.workshop_block')
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <hr/>
                                            <?php elseif(count($books)): ?>
                                                <div class="row get_uploaded_items">
                                                    <?php foreach($books as $key => $book): ?>
                                                    <div class="col col-xl-4 col-lg-3 col-md-4 col-sm-4 col-12 book_item">
                                                        @include('blocks.book_block')
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <hr/>
                                            <?php else: ?>
                                            <div class="alert alert-warning no_results_alert">
                                                {{show_content($advanced_search,"no_results_label")}} "<b>{{clean($_GET['search'])}}</b>"
                                            </div>
                                            <?php endif; ?>

                                        <div class="break-page">
                                            <?php if(isset($suggested_users_paginate) && !empty($suggested_users_paginate)): ?>
                                            {{$suggested_users_paginate->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div role="tabpanel" class="tab-pane {{(isset($_GET['tab_type']) && $_GET['tab_type'] == "orders")?"active":""}}" id="tab_2">
                                        <?php if(isset($_GET['tab_type']) && $_GET['tab_type'] == "orders"): ?>
                                            <?php if($post_type == "order" && isset($_GET['not_closed'])): ?>
                                            <input type="hidden" class="not_closed">
                                            <?php endif; ?>

                                            <div id="newsfeed-items-grid">

                                                <div class="show_users_post" data-target_search="{{clean($_GET['search'])}}" data-url="user/get_matched_posts/{{$post_type}}/{{$user_id}}" data-offset="0"></div>

                                            </div>

                                        <?php endif; ?>
                                    </div>

                                    <div role="tabpanel" class="tab-pane {{(isset($_GET['tab_type']) && $_GET['tab_type'] == "posts")?"active":""}}" id="tab_3">
                                        <?php if(isset($_GET['tab_type']) && $_GET['tab_type'] == "posts"): ?>
                                        <?php if($post_type == "order" && isset($_GET['not_closed'])): ?>
                                        <input type="hidden" class="not_closed">
                                        <?php endif; ?>

                                        <div id="newsfeed-items-grid">
                                            <div class="show_users_post" data-target_search="{{clean($_GET['search'])}}" data-url="user/get_matched_posts/{{$post_type}}/{{$user_id}}" data-offset="0"></div>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div role="tabpanel" class="tab-pane {{(isset($_GET['tab_type']) && $_GET['tab_type'] == "books")?"active":""}}" id="tab_4">
                                        <?php if(isset($_GET['tab_type']) && $_GET['tab_type'] == "books"): ?>

                                        <?php if(count($books)): ?>

                                            <div class="row get_uploaded_items">
                                                <?php foreach($books as $key => $book): ?>
                                                    <div class="col col-xl-4 col-lg-3 col-md-4 col-sm-4 col-12 book_item">
                                                        @include('blocks.book_block')
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                        <?php else: ?>
                                            <div class="alert alert-warning no_results_alert">
                                                {{show_content($advanced_search,"no_results_label")}} "<b>{{clean($_GET['search'])}}</b>"
                                            </div>
                                        <?php endif; ?>

                                        <?php if(count($books) == 8): ?>
                                            <div class="col-md-12">
                                                <a href="#" class="btn btn-primary load-more load_more_books" data-cat_id="0" data-target_search="{{clean($_GET['search'])}}">{{show_content($general_static_keywords,'more')}}</a>
                                            </div>
                                        <?php endif; ?>

                                        <?php endif; ?>
                                    </div>

                                    <div role="tabpanel" class="tab-pane {{(isset($_GET['tab_type']) && $_GET['tab_type'] == "workshops")?"active":""}}" id="tab_5">
                                        <?php if(isset($_GET['tab_type']) && $_GET['tab_type'] == "workshops"): ?>

                                            <?php if(count($workshops)): ?>
                                                <div class="row">
                                                    <?php foreach($workshops as $key => $workshop): ?>
                                                        <div class="col col-xl-4 col-lg-3 col-md-4 col-sm-4 col-12 book_item">
                                                            @include('blocks.workshop_block')
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                            <div class="alert alert-warning no_results_alert">
                                                {{show_content($advanced_search,"no_results_label")}} "<b>{{clean($_GET['search'])}}</b>"
                                            </div>
                                        <?php endif; ?>

                                        <?php if(count($workshops)): ?>
                                            <div class="break-page">
                                                {{$workshops->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                                            </div>
                                        <?php endif; ?>

                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>

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
