

<div class="container">
    <div class="row">

        <!-- Main Content -->

        <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

            <div class="container">
                <div class="row">
                    <div class="ui-block">

                        <!-- Single Post -->

                        <article class="hentry blog-post single-post single-post-v2" style="padding: 70px 5px;">

                            <h1 class="h1 post-title">{{$broker_data->page_title}}</h1>
                            <p>{!! $broker_data->page_short_desc !!}</p>
                            <div class="row">

                                <?php if(!empty($broker_data->page_url)): ?>
                                    <div class="col col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <a href="{{$broker_data->page_url}}" target="_blank" class="btn btn-primary">{{show_content($brokers_keywords,"create_demo_account_label")}}</a>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($broker_data->page_url2)): ?>
                                    <div class="col col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <a href="{{$broker_data->page_url2}}" target="_blank" class="btn btn-success">{{show_content($brokers_keywords,"create_real_time_account_label")}}</a>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($broker_data->page_url3)): ?>
                                    <div class="col col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                                        <a href="{{$broker_data->page_url3}}" target="_blank" class="btn btn-primary">
                                            <?php if(!empty($broker_data->page_broker_third_link_title)): ?>
                                            {{$broker_data->page_broker_third_link_title}}
                                            <?php else: ?>
                                            {{show_content($brokers_keywords,"create_another_account_label")}}
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                <?php endif; ?>


                            </div>

                            <div class="row">
                                <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <img src="{{get_image_or_default($broker_data->big_img_path)}}" style="width: 100%;height: 300px;">
                                </div>
                            </div>

                            <div class="post-content-wrap">

                                <div class="post-content">
                                    <p>{!! $broker_data->page_body !!}</p>
                                </div>
                            </div>

                        </article>

                        <!-- ... end Single Post -->

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

