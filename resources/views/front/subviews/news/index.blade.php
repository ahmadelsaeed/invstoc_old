
<section class="blog-post-wrap">
    <div class="container">
        <div class="row">

            <div class="col col-xl-9 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <?php if(count($news)): ?>
                    <div class="row">
                        <?php foreach($news as $key => $news_obj): ?>
                            <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                @include('blocks.news_block')
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if(!empty($news_pagination)): ?>
                    <nav aria-label="Page navigation">
                        {{$news_pagination->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                    </nav>
                <?php endif; ?>

                <!-- ... end Pagination -->

            </div>

            <!-- Left Sidebar -->

            <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
                @include('front.main_components.left_sidebar')
            </aside>

            <!-- ... end Left Sidebar -->


        </div>
    </div>

</section>

