@extends('front.subviews.groups_workshops.workshop.workshop_main_layout')

@section('workshop_subview')

    <div class="ui-block" style="width: 100%">
        <div class="ui-block-title">
            <h6 class="title">
                {{show_content($general_static_keywords,"delete_btn")}} {{$workshop_obj->workshop_name}}
            </h6>
        </div>
        <div class="card-body">

            <form action="{{url(strtolower(session('language_locale', 'en'))."/workshop/delete/$workshop_obj->workshop_name/$workshop_obj->workshop_id")}}" method="post">
                <div class="col-md-12 text-center" style="margin-top: 10px;">
                    <p class="alert alert-info">{{show_content($general_static_keywords,"are_you_sure")}}</p>

                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-danger">{{show_content($general_static_keywords,"delete_btn")}}</button>
                </div>
            </form>

        </div>
    </div>

@endsection
