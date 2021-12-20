@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')



    <div class="ui-block" style="width: 100%;">
        <div class="ui-block-title">
            <h6 class="title">{{show_content($general_static_keywords,"rename_btn")}}</h6>
        </div>
        <div class="ui-block-content">


            <form action="{{url(strtolower(session('language_locale', 'en'))."/group/rename/$group_obj->group_name/$group_obj->group_id")}}" method="post">
                <div class="col-md-12 text-center" style="margin-top: 10px;">

                    <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="form-group label-floating">
                            <input required type="text" class="form-control" name="group_name" value="{{$group_obj->group_name}}">
                        </div>
                    </div>



                    {!! csrf_field() !!}
                    <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                        <button class="btn btn-primary btn-lg full-width" type="submit">{{show_content($general_static_keywords,"rename_btn")}}</button>
                    </div>

                </div>
            </form>


        </div>
    </div>




@endsection


