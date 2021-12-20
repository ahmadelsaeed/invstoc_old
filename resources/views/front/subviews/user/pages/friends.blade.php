@extends('front.subviews.user.user_layout')
@section('page')


    <div class="container">
        <div class="row">


            <?php if(isset($_GET['type'])): ?>

            <?php
            $arr = [];
            if (is_array($followers_following_data->all()) && count($followers_following_data->all()))
            {
                $arr = $followers_following_data->all();
            }
            ?>


            <?php foreach($arr as $key => $user_obj_item): ?>

                @include("blocks.user_block")

            <?php endforeach; ?>

            <div class="col-md-12 text-center">
                {{$followers_following_data->appends(\Illuminate\Support\Facades\Input::except('page'))}}
            </div>


            <?php endif; ?>

        </div>
    </div>

@endsection


