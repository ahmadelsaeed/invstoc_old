@extends('front.main_layout')

@section('subview')
    <!--Slider Area Start-->
    @include('front.main_components.slider')
    <!--End of Slider Area-->

    <!--Portfolio Area Start-->
    <?php if(count($homepage_trips)): ?>
    <div class="portfolio-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <div class="title-border">
                            <h1>
                                <?php
                                    $sentence = show_content($edit_index_page,"popular_packages");
                                    echo split_first_word_from_sentence($sentence,"span");
                                ?>
                            </h1>
                        </div>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach($homepage_trips as $key => $trip_obj): ?>

                    <div class="col-md-3 col-sm-4">
                        <?php
                            $additional_class = "";
                            if($key %2 == 0)
                            {
                                $additional_class = "effect-bottom";
                            }
                        ?>
                            @include('blocks.homepage_first_section_trip')
                    </div>

                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--End of Portfolio Area-->


    <!--Best Sell Area Start-->
    <div class="best-sell-area section-padding color-1">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <div class="title-border">
                            <h1>
                                <?php
                                    $sentence = show_content($edit_index_page,"latest_packages");
                                    echo split_first_word_from_sentence($sentence,"span");
                                ?>
                            </h1>
                        </div>
                        <p></p>
                    </div>
                </div>
            </div>

            <?php if(count($parent_cats)): ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="sell-text-container">
                    </div>
                    <div class="row">
                        <div class="best-sell-slider carousel-style-one">
                            <?php for($ind = 0; $ind < (count($parent_cats)); $ind+=2): ?>
                                <div class="col-md-3">
                                    <?php
                                        $cat_obj = $parent_cats[$ind];
                                    ?>
                                    @include('blocks.homepage_parent_cat_block')
                                    <?php
                                        if (isset($parent_cats[$ind+1]))
                                        {
                                            $cat_obj = $parent_cats[$ind+1];
                                    ?>
                                        @include('blocks.homepage_parent_cat_block')
                                    <?php
                                        }
                                    ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {!! show_content($support,"map") !!}
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!--End of Best Sell Area-->
    <!--Service Area Start-->
    <?php if(isset($edit_index_page->fields_icon)&&is_array($edit_index_page->fields_icon) &&
    count($edit_index_page->fields_icon)): ?>
    <div class="service-area section-padding text-center">
        <div class="container">
            <div class="row">
                <?php foreach($edit_index_page->fields_icon as $key => $icon): ?>
                    <div class="col-md-2 col-sm-3">
                        <div class="single-service">
                            <div class="single-icon">
                                {!! $icon !!}
                            </div>
                            <h5>{{$edit_index_page->fields_text[$key]}}</h5>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!--End of Service Area-->


    <div class="newsletter-area newsletter-three" style="background: #ecebeb url({{get_image_or_default($edit_index_page->subscribe_background->path)}}) no-repeat scroll center top / cover;">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-4 col-sm-12">
                    <div class="section-title title-three text-center">
                        <div class="title-border">
                            <h1 class="text-white">
                                <?php
                                    $sentence = show_content($edit_index_page,"subscribe_btn_text");
                                    echo split_first_word_from_sentence($sentence,"span");
                                ?>
                            </h1>
                        </div>
                        <p class="text-white">
                            {{show_content($edit_index_page,"subscribe_short_desc")}}
                        </p>
                    </div>
                    <div id="newsletter">
                        <div class="newsletter-content">
                            <div class="row">
                                <div class="col-lg-9 col-md-8 col-sm-9 col-xs-12">
                                    <input type="email" class="subscribe_submit_email" name="email" placeholder="{{show_content($edit_index_page,'subscribe_placeholder')}}">
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
                                    <button type="submit" class="button button-blue subscribe_submit_btn"><span>{{show_content($edit_index_page,'subscribe_btn_text')}}</span></button>
                                </div>
                                <div class="col-md-12">
                                    <p class="subscribe_msg"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Team Area Start-->
    <?php if(count($special_trips)): ?>
    <div class="team-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title text-center">
                        <div class="title-border">
                            <h1>
                                <?php
                                    $sentence = show_content($edit_index_page,"special_offers");
                                    echo split_first_word_from_sentence($sentence,"span");
                                ?>
                            </h1>
                        </div>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach($special_trips as $key => $trip_obj): ?>
                    <div class="col-md-4 col-sm-6">
                        @include('blocks.homepage_special_offer_trip')
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!--End of Team Area-->

    <!--Partner Area Start-->
    @include('front.main_components.partners')
    <!--End of Partner Area-->



@endsection
