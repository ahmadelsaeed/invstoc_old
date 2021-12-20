@extends('front.main_layout')

@section('subview')


    <div class="container">
        <div class="row">

            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="container">
                    <div class="row">

                        <div class="ui-block" style="width: 100%">

                            <!-- Single Post -->

                            <div class="ui-block-title">
                                <h6 class="title">{{$cat_data->cat_name}}</h6>
                            </div>

                            <!-- ... end Single Post -->

                        </div>

                    </div>

                    <div class="row">
                        <?php if(count($books)): ?>
                            <div class="row get_uploaded_items" style="width: 100%;">
                                <?php foreach($books as $key => $book): ?>
                                    <div class="col col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 book_item">
                                        @include('blocks.book_block')
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if(count($books) == 8): ?>
                            <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <a href="#" class="btn btn-primary load-more load_more_books" data-cat_id="{{$cat_data->cat_id}}">{{show_content($general_static_keywords,"more")}}</a>
                            </div>
                        <?php endif; ?>

                        <?php if(count($workshops)): ?>
                    </div>

                    <div class="row">

                        <div class="ui-block" style="width: 100%">

                            <!-- Single Post -->

                            <div class="ui-block-title">
                                <h6 class="title"> {{show_content($user_homepage_keywords,"workshops_label")}} </h6>
                            </div>

                            <!-- ... end Single Post -->


                            <ul class="table-careers">

                                <?php foreach($workshops as $key => $workshop): ?>

                                    <li>
                                        <span class="date">
                                            <a href="{{url("information/$workshop->user_id")}}">
                                                <img src="{{get_image_or_default($workshop->path)}}" width="50">
                                            </a>
                                        </span>
                                        <span class="type bold">{{$workshop->workshop_visits}} <i class="fa fa-eye"></i></span>
                                        <span>
                                            <a class="btn btn-primary btn-sm full-width" href="{{url("workshop/$workshop->workshop_name/$workshop->workshop_id")}}">{{$workshop->workshop_name}}</a>
                                        </span>
                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        </div>

                        <?php endif; ?>
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