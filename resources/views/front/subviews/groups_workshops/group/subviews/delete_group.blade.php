@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')

    <div class="panel panel-info">
        <div class="panel-heading">Delete {{$group_obj->group_name}}</div>
        <div class="panel-body">

            <form action="{{url(strtolower(session('language_locale', 'en'))."/group/delete/$group_obj->group_name/$group_obj->group_id")}}" method="post">
                <div class="col-md-12 text-center" style="margin-top: 10px;">
                    <p class="alert alert-info">{{show_content($general_static_keywords,"are_you_sure")}}</p>

                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-danger">{{show_content($general_static_keywords,"delete_btn")}}</button>
                </div>
            </form>

        </div>
    </div>

@endsection
