@extends('front.main_layout')

@section('subview')

    <style>
        .range_input{
            border: 1px solid #bcbcbc;
            color: #4e4e4e;
            margin-top: 20px;
            padding: 6px;
            text-align: center;
            width: 45%;
            margin-left: 3%;
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
                    <ul class="breadcrumb" style="width: 500px;padding-left: 0;padding-right: 0;">
                        <li>
                            <a href="{{url($lang_url_segment."/")}}">
                                {{show_content($general_static_keywords,"homepage")}}
                            </a>
                        </li>
                        <li>
                            <a href="{{url($lang_url_segment."/".$cat_data->parent_cat_slug)}}">
                                {{$cat_data->parent_cat_name}}
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
                <div class="col-md-3">
                    <div class="sidebar-widget">

                        <form method="get">

                            <?php if(isset($all_activities)): ?>
                                <div class="single-sidebar-widget country-select">
                                    <h4>Select <span>Activities</span></h4>
                                    <ul class="widget-categories">

                                        <?php foreach ($all_activities as $key => $activity): ?>
                                            <?php
                                                $checked="";
                                                if(isset($all_post_data->filter_activity)&&in_array($activity->cat_id,$all_post_data->filter_activity)){
                                                    $checked="checked";
                                                }
                                            ?>

                                            <li>
                                                <span>
                                                    <input type="checkbox" {{$checked}} value="{{$activity->cat_id}}" name="filter_activity[]" class="checkbox">
                                                </span>
                                                <a>{{$activity->cat_name}}</a>
                                            </li>
                                        <?php endforeach; ?>



                                    </ul>
                                </div>
                                <div class="clearfix"></div>
                            <?php endif; ?>


                            <div class="single-sidebar-widget country-select">
                                <h4>Price <span>Range</span></h4>
                                <div class="form-group">
                                    <input type="number" name="min_price" placeholder="min" class="range_input" value="{{isset($all_post_data->min_price)?$all_post_data->min_price:""}}">
                                    <input type="number" name="max_price" placeholder="max" class="range_input" value="{{isset($all_post_data->max_price)?$all_post_data->max_price:""}}">
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Filter</button>
                        </form>



                        <hr>

                        <div class="single-sidebar-widget">
                            <h4>Recent <span>tours</span></h4>

                            <?php foreach ($last_trips as $key => $trip_obj): ?>
                                <?php
                                    $trip_path = url($lang_url_segment."/".urlencode($trip_obj->parent_cat_slug)."/".urlencode($trip_obj->child_cat_slug)."/".urlencode($trip_obj->page_slug));
                                ?>
                                <div class="single-widget-posts">
                                    <div class="post-img">
                                        <a href="{{$trip_path}}">
                                            <img src="{{get_image_or_default($trip_obj->small_img_path)}}" alt="" style="width: 74px;height: 74px;">
                                        </a>
                                    </div>
                                    <div class="posts-text">
                                        <h4>
                                            <a href="{{$trip_path}}">{{$trip_obj->page_title}} | {{$trip_obj->page_country}}</a>
                                        </h4>
                                        <p><i class="fa fa-clock-o"></i> {{$trip_obj->page_period}}</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-9">

                    <div class="adventure-grid-two-area">
                        <div class="row">
                            <?php foreach($cat_trips as $key=>$trip_obj): ?>
                                <?php
                                    $trip_path = url($lang_url_segment."/".urlencode($trip_obj->parent_cat_slug)."/".urlencode($trip_obj->child_cat_slug)."/".urlencode($trip_obj->page_slug));
                                ?>

                                <div class="col-md-4 col-sm-6">
                                    <div class="single-adventure-two">
                                        <a href="{{$trip_path}}">
                                            <img src="{{get_image_or_default($trip_obj->small_img_path)}}" title="{{$trip_obj->small_img_title}}" alt="{{$trip_obj->small_img_alt}}" />
                                        </a>
                                        <div class="adventure-text-two">
                                            <div class="adventure-text-container">
                                                <h4><a href="{{$trip_path}}">{{$trip_obj->page_title}} | <span>{{$trip_obj->page_country}}</span></a></h4>
                                                <span class="trip-time"><i class="fa fa-clock-o"></i>{{$trip_obj->page_period}}</span>
                                                <p style="height: 79px;overflow: hidden;">
                                                    {{$trip_obj->page_short_desc}}
                                                </p>
                                                <a href="{{$trip_path}}" class="button-one">{{show_content($general_static_keywords,"more")}}</a>
                                            </div>
                                            <div class="adventure-price-link-two">
                                                <span class="trip-price">
                                                    <span class="currency_value" data-original_price="{{$trip_obj->page_price}}">{{$trip_obj->page_price}}</span>
                                                    <span class="currency_sign">$</span>
                                                </span>
                                                <span class="trip-person">Per Person</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>


                        </div>
                    </div>

                    <div class="clearfix"></div>


                </div>
            </div>
        </div>
    </div>
    <!--End of Adventures Grid-->

    @include("front.main_components.partners")



@endsection