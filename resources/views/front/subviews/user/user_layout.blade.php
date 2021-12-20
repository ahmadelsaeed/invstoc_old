@extends('front.main_layout')

@section('subview')


    @include('front.subviews.user.components.profile_header')

    @yield('page')


    <?php if(false):?>
    <div class="container">

        <div class="col-md-1 nopadding">

            <?php if(isset($get_ads['profile_right'])): ?>
                <div class="banner-hroz">
                    {!! get_adv($get_ads['profile_right']->all(),"110px","auto") !!}
                </div>
            <?php endif; ?>

        </div>

        <div class="col-md-10">

        </div>

        <div class="col-md-1 nopadding">
            <?php if(isset($get_ads['profile_left'])): ?>
                <div class="banner-hroz">
                    {!! get_adv($get_ads['profile_left']->all(),"110px","auto") !!}
                </div>
            <?php endif; ?>
        </div>

    </div>
    <?php endif;?>


@endsection