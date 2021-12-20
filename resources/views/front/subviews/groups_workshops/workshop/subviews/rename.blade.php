@extends('front.subviews.groups_workshops.workshop.workshop_main_layout')

@section('workshop_subview')

    <div class="ui-block" style="width: 100%">
        <div class="ui-block-title">
            <h6 class="title">
                {{show_content($general_static_keywords,"rename_btn")}} {{$workshop_obj->workshop_name}}
            </h6>
        </div>
        <div class="card-body">

            <form action="{{url(strtolower(session('language_locale', 'en'))."/workshop/rename/$workshop_obj->workshop_name/$workshop_obj->workshop_id")}}" method="post">
                <div class="col-md-12 text-center" style="margin-top: 10px;">

                    <div class="col-md-12">
                        <input required type="text" class="form-control" name="workshop_name" value="{{$workshop_obj->workshop_name}}">
                    </div>

                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary">{{show_content($general_static_keywords,"save_btn")}}</button>
                </div>
            </form>

        </div>
    </div>

@endsection
