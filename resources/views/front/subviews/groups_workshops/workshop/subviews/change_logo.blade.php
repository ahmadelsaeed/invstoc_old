@extends('front.subviews.groups_workshops.workshop.workshop_main_layout')

@section('workshop_subview')

    <div class="ui-block" style="width: 100%">
        <div class="ui-block-title">
            <h6 class="title">
                {{show_content($general_static_keywords,"change_image_label")}}
            </h6>
        </div>
        <div class="card-body">

            <form action="{{url(strtolower(session('language_locale', 'en'))."/workshop/change_logo/$workshop_obj->workshop_name/$workshop_obj->workshop_id")}}" method="post" enctype="multipart/form-data">
                <div class="col-md-12 text-center" style="margin-top: 10px;">

                    <div class="col-md-12">
                        <img src="{{get_image_or_default($workshop_obj->path)}}" width="100" height="100" class="img-circle">
                        <br>
                        <input type="file" name="workshop_logo" required>
                    </div>

                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary">{{show_content($general_static_keywords,"save_btn")}}</button>
                </div>
            </form>

        </div>
    </div>

@endsection
