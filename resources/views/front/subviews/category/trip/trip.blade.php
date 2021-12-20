@extends('front.main_layout')

@section('subview')

    <!--Banner Area Start-->
    <div class="banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <ul class="breadcrumb b-pafe-view">
                        <li>
                            <a href="{{url($lang_url_segment."/")}}">
                                {{show_content($general_static_keywords,"homepage")}}
                            </a>
                        </li>
                        <li>
                            <a href="{{url($lang_url_segment."/".$trip_data->parent_cat_slug)}}">
                                {{$trip_data->parent_cat_name}}
                            </a>
                        </li>

                        <li>
                            <a href="{{url($lang_url_segment."/".$trip_data->parent_cat_slug."/".$trip_data->child_cat_slug)}}">
                                {{$trip_data->child_cat_name}}
                            </a>
                        </li>

                        <li class="active">{{$trip_data->page_title}}</li>
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



                <div class="col-md-4">
                    <div class="info-view">
                        <div class="col-md-12">
                            <h3> {{$trip_data->page_title}}</h3>
                        </div>

                        <div class="col-md-12">
                            <span>
                                {{$trip_data->page_short_desc}}
                            </span>
                        </div>



                        <div class="col-md-12">
                            <p>
                                <i class="fa fa-tag" aria-hidden="true"></i>
                                {{show_content($trip_page_keywords,"price")}} :
                                <span class="currency_value" data-original_price="{{$trip_data->page_price}}">
                                    {{$trip_data->page_price}}
                                    <span class="currency_sign">$</span>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <p><i class="fa fa-clock-o" aria-hidden="true"></i> {{$trip_data->page_period}} </p>
                        </div>

                        <div class="col-md-12">
                            <p><i class="fa fa-university" aria-hidden="true"></i> {{$trip_data->page_city}} </p>
                        </div>

                        <div class="col-md-12">
                            <p><i class="fa fa-map-marker" aria-hidden="true"></i> {{$trip_data->page_country}} </p>
                        </div>

                        <div class="col-md-12 text-left">
                            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5281d519602edaad"></script>
                            <div class="addthis_inline_follow_toolbox"></div>
                        </div>

                    </div>

                    <div id="check_availability" class="check-availability contact_us_parent_div">
                        <h3>{{show_content($trip_page_keywords,"check_availability")}}</h3>
                        <p>{{show_content($trip_page_keywords,"check_availability_text")}}</p>
                        <form>
                            <input type="text" class="form-control" id="name" placeholder="{{show_content($trip_page_keywords,"check_availability_name")}}">
                            <input type="email" class="form-control" id="email" placeholder="{{show_content($trip_page_keywords,"check_availability_email")}}">
                            <input type="text" class="form-control" id="country" placeholder="{{show_content($trip_page_keywords,"check_availability_country")}}">
                            <input type="text" class="form-control" id="address" placeholder="{{show_content($trip_page_keywords,"check_availability_address")}}">
                            <input type="text" class="form-control" id="phone" placeholder="{{show_content($trip_page_keywords,"check_availability_number_phone")}}">
                            <input type="text" class="form-control" id="fax" placeholder="{{show_content($trip_page_keywords,"check_availability_fax")}}">
                            <textarea class="form-control" rows="6" id="msg" placeholder="{{show_content($trip_page_keywords,"check_availability_comment")}}"></textarea>
                            <button class="btn btn-default contact_us_btn" type="button">{{show_content($trip_page_keywords,"check_availability_btn")}}</button>

                            <div class="col-md-12">
                                <div class="display_msgs center"></div>
                            </div>

                        </form>
                    </div>

                </div>





                <div class="col-md-8 padd-left">
                    <div class="slider-view">
                        <div id="slider1_container" style="position: relative; top: 0px; left: 0px; height: 480px; background: #191919; overflow: hidden;">

                            <!-- Loading Screen -->
                            <div u="loading" style="position: absolute; top: 0px; left: 0px;">
                                <div class="loadind-st-1"></div>
                                <div class="loadind-st-2"></div>
                            </div>

                            <!-- Slides Container -->
                            <div u="slides" style="position: absolute; left: 120px; top: 0px; width: 720px; height: 480px; overflow: hidden;">
                                <?php foreach($trip_data->slider_imgs as $key=>$img): ?>
                                <div>
                                    <img u="image" src="{{url("/$img->path")}}" />
                                    <img u="thumb" src="{{url("/$img->path")}}" />
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Arrow Left -->
                            <span u="arrowleft" class="jssora05l" style="top: 158px; left: 128px;">
					        </span>
                            <!-- Arrow Right -->
                            <span u="arrowright" class="jssora05r" style="top: 158px; right: 8px">
					        </span>

                            <div u="thumbnavigator" class="jssort02" style="left: 0px; bottom: 0px;">

                                <div u="slides" style="cursor: default;">
                                    <div u="prototype" class="p">
                                        <div class=w>
                                            <div u="thumbnailtemplate" class="t"></div>
                                        </div>
                                        <div class=c></div>
                                    </div>
                                </div>

                            </div>

                            <script>
                                jssor_slider1_starter('slider1_container');
                            </script>
                        </div>
                    </div>

                    <div class="col-md-12 padd-left padd-right">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-binoculars"></i> Overview</a></li>
                            <li role="presentation" class=""><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-map-o" aria-hidden="true"></i> Inclusions</a></li>
                            <li role="presentation" class=""><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-bed"></i> Exclusions</a></li>
                        </ul>
                    </div>

                    <div class="col-md-12 padd-left padd-right">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="home">
                                <div class="col-md-12 padd-left">
                                    <div class="content-tab">
                                        {!! $trip_data->page_body !!}

                                        <?php
                                        $page_itinerary_header=[];
                                        $page_itinerary_body=[];

                                        if(is_object($trip_data)){
                                            $page_itinerary=json_decode($trip_data->page_itinerary);
                                            if(is_object($page_itinerary)){
                                                $page_itinerary_header=$page_itinerary->page_itinerary_header;
                                                $page_itinerary_body=$page_itinerary->page_itinerary_body;
                                            }
                                        }
                                        ?>

                                        <?php if(isset_and_array($page_itinerary_header)): ?>
                                        <h3>
                                            {{show_content($trip_page_keywords,"Itinerary")}}
                                        </h3>

                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                            <?php foreach($page_itinerary_header as $key=>$header): ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="heading{{$key}}">
                                                    <h4 class="panel-title">
                                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                                            {!! $header !!}
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse{{$key}}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading{{$key}}">
                                                    <div class="panel-body acc-view">
                                                        <?php
                                                        if(isset($page_itinerary_body[$key]))echo $page_itinerary_body[$key];
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="profile">
                                {!! $trip_data->page_inclusions !!}
                            </div>
                            <div role="tabpanel" class="tab-pane" id="messages">
                                {!! $trip_data->page_exclusions !!}
                            </div>
                        </div>
                    </div>


                </div>


                <div class="col-md-12">
                    <div class="price-table">
                        @include("front.subviews.category.trip.price_table")
                    </div>
                </div>



                <div class="col-md-12">
                    <h3>{{show_content($trip_page_keywords,"related_tours")}}</h3>
                    <br />
                </div>

                <div class="owl-carousel owl-theme">
                    <?php foreach($related_trips as $key=>$trip_obj): ?>
                        <?php
                            $trip_path = url($lang_url_segment."/".urlencode($trip_obj->parent_cat_slug)."/".urlencode($trip_obj->child_cat_slug)."/".urlencode($trip_obj->page_slug));
                        ?>
                        <div class="col-md-12 col-sm-12">
                            <div class="single-portfolio related-tours">
                                <a href="{{$trip_path}}">
                                    <img src="{{get_image_or_default($trip_obj->small_img_path)}}" alt="" >
                                </a>
                                <div class="portfolio-text effect-bottom">
                                    <h4><a href="{{$trip_path}}">{{$trip_obj->page_title}}</a></h4>
                                    <p style="height: 77px;overflow: hidden;">{{$trip_obj->page_short_desc}}</p>
                                    <div class="portfolio-price">
                                        <label>
                                            <span class="currency_value" data-original_price="{{$trip_obj->page_price}}">{{$trip_obj->page_price}}</span>
                                            <span class="currency_sign">$</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>




            </div>


        </div>
    </div>
    <!--End of Adventures Grid-->




@endsection