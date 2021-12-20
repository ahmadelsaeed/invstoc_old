@extends('front.main_layout')

@section('subview')


    <div class="clearfix"></div>

    <div class="container" style="margin-top: 150px;">
        <div class="row">

            <div class="col-md-12">
                <div class="brek">
                    <ol class="breadcrumb">
                        <li><a href="{{url($lang_url_segment."/")}}">{{show_content($general_static_keywords,"homepage")}}</a></li>
                        <li class="active">{{show_content($search_page,"search_header")}}</li>
                    </ol>
                </div>
            </div>


            <div class="col-md-12">
                <?php foreach($trips as $key=>$trip_obj): ?>
                    <div class="col-md-4">
                        @include("blocks.homepage_trip_block")
                    </div>
                <?php endforeach;?>
            </div>

            <div class="col-md-12 center" style="text-align: center;">
                {{$trips_pagination->links()}}
            </div>


        </div>
    </div>


@endsection