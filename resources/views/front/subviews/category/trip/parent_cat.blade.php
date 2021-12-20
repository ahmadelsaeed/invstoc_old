@extends('front.main_layout')

@section('subview')

    <style>

        .trip-price a:hover,.single-adventure:hover a{
            color: #fff !important;
        }
    </style>

    <!--Banner Area Start-->
    <div class="banner-area grid-two" style="background-image: url({{url("/$cat_data->big_img_path")}});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <div class="title-border">
                            <h1>
                                <span>
                                    {{$cat_data->cat_name}}
                                </span>
                            </h1>
                        </div>
                        <p class="text-white">
                            {{$cat_data->cat_short_desc}}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <ul class="breadcrumb">
                        <li>
                            <a href="{{url($lang_url_segment."/")}}">
                                {{show_content($general_static_keywords,"homepage")}}
                            </a>
                        </li>
                        <li> {{$cat_data->cat_name}} </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--End of Banner Area-->

    <!--Adventures Grid Start-->
    <div class="adventures-grid grid-two-tab section-padding">
        <div class="container">

            <div class="row">

                <div class="col-md-12">
                    <div class="section-title text-center">
                        <div class="title-border">
                            <h1> <span>{{$cat_data->cat_name}}</span></h1>
                        </div>
                        {!! $cat_data->cat_body !!}
                    </div>
                </div>

                <?php foreach ($child_cats as $key => $cat): ?>
                    <?php
                        $url=url($lang_url_segment."/".$cat->parent_cat_slug."/".$cat->cat_slug);
                    ?>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="single-adventure">
                            <a href="{{$url}}">
                                <img src="{{url("/$cat->small_img_path")}}" title="{{$cat->small_img_title}}" alt="{{$cat->small_img_alt}}" />
                            </a>
                            <div class="adventure-text effect-bottom">
                                <div class="transparent-overlay">
                                    {{--<h4><a href="{{$url}}">{{$cat->cat_name}}</a></h4>--}}
                                    <p>{{$cat->cat_short_desc}}</p>
                                </div>

                                <div class="adventure-price-link">
                                    <span class="trip-price st-sec">
                                        <a href="{{$url}}">{{$cat->cat_name}}</a>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>


            </div>


        </div>
    </div>
    <!--End of Adventures Grid-->

    @include("front.main_components.partners")

@endsection