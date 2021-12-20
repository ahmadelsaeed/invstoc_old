
<div class="container">
    <div class="row">

        <main class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

            <section class="blog-post-wrap">

                <div class="row">
                    <?php if(isset($brokers_keywords->slider1) && isset($brokers_keywords->slider1->imgs)): ?>

                        <div class="col-md-12" style="margin-bottom: 20px;">

                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">

                                    <?php foreach ($brokers_keywords->slider1->imgs as $key => $img_obj): ?>
                                        <div class="carousel-item {{($key == 0)?"active":""}}">
                                            <img src="{{get_image_or_default($img_obj->path)}}" class="d-block w-100" style="height: 350px !important;">
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>

                        </div>
                    <?php endif; ?>

                    <?php if(count($brokers)): ?>
                        <?php foreach($brokers as $key => $broker): ?>
                            <div class="col col-xl-2 col-lg-2 col-md-4 col-sm-4 col-12" style="padding: 3px;">
                                @include('blocks.broker_block')
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if(!empty($brokers_pagination)): ?>
                        <nav aria-label="Page navigation">
                            {{$brokers_pagination->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                        </nav>
                    <?php endif; ?>

                </div>
            <!-- ... end Pagination -->

                <div class="row">
                    <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    {!! show_content($brokers_keywords,"sidebar_notes") !!}
                    </div>
                </div>

            </section>

        </main>

        <!-- Left Sidebar -->

        <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
            @include('front.main_components.left_sidebar')
        </aside>

        <!-- ... end Left Sidebar -->

    </div>
</div>
