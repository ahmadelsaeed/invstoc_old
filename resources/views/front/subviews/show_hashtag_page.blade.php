@extends('front.main_layout')

@section('subview')

    <div id="page-contents">
        <div class="container">
            <div class="row">

                <div class="col-md-2 static padd-left padd-right">
                    @include('front.main_components.right_sidebar')
                </div>

                <div class="col-md-8">

                    <div class="show_users_post" data-url="posts/load_hashtag_posts/{{$hashtag_text}}" data-offset="0"></div>
                </div>

                <div class="col-md-2 static nopadding">
                    <div class="col-md-12 static nopadding">

                        <div class="st-fixed-banner">

                            <?php if(isset($load_filter) && count($all_brokers)): ?>
                            @include("blocks.brokers_filter_block")
                            <?php endif; ?>

                            <?php if(count($latest_news)): ?>
                            <?php foreach($latest_news as $key => $news_obj): ?>
                            @include("blocks.homepage_news_block")
                            <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if(isset($get_ads['homepage_body1'])): ?>
                            <div class="banner-img">
                                {!! get_adv($get_ads['homepage_body1']->all(),"210px","120px") !!}
                            </div>
                            <?php endif; ?>

                            <?php if(isset($get_ads['homepage_body2'])): ?>
                            <div class="banner-img">
                                {!! get_adv($get_ads['homepage_body2']->all(),"210px","120px") !!}
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection