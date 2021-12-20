@extends('front.subviews.user.user_layout')
@section('page')


    <div class="container">
        <div class="row">

            <?php if(count($get_media)): ?>



                <?php foreach($get_media as $key => $img_obj): ?>
                @include('blocks.gallery_block')
                <?php endforeach; ?>


            <div class="col-md-12" style="margin-bottom: 20px;margin-bottom: 20px;">
                    {{$get_media->appends(\Illuminate\Support\Facades\Input::except('page'))}}
                </div>

            <?php endif; ?>


        </div>
    </div>

@endsection




