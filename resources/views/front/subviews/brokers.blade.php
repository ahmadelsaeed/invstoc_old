@extends('front.main_layout')

@section('subview')
    <div id="page-contents">
        <div class="container">
            <div class="row">

                <div class="col-md-2 static padd-left padd-right">
                    @include('front.main_components.right_sidebar')
                </div>

                <div class="col-md-8">

                    <div class="page-news">

                        <div class="col-md-12">
                            <div class="img-comapny">
                                <img src="{{url("public_html/front")}}/images/1.jpg" />
                            </div>
                        </div>

                        <?php foreach($brokers as $key => $broker): ?>
                            <div class="col-md-2">
                                @include('blocks.broker_block')
                            </div>
                        <?php endforeach; ?>

                    </div>

                    <div class="break-page">
                        <?php if(!empty($brokers_pagination)): ?>
                        {{$brokers_pagination->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                        <?php endif; ?>
                    </div>

                </div>

                <div class="col-md-2 static nopadding">
                    <?php $load_filter = true; ?>
                    @include('front.main_components.left_sidebar')
                </div>

            </div>
        </div>
    </div>

@endsection