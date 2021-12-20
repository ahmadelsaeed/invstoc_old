@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')



    <div class="ui-block" style="width: 100%;">
        <div class="ui-block-title">
            <h6 class="title">{{show_content($general_static_keywords,"change_image_label")}}</h6>
        </div>
        <div class="ui-block-content">


            <form action="{{url(strtolower(session('language_locale', 'en'))."/group/change_logo/$group_obj->group_name/$group_obj->group_id")}}" method="post" enctype="multipart/form-data">
                <div class="col-md-12 text-center" style="margin-top: 10px;">



                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group label-floating">
                            <img src="{{get_image_or_default($group_obj->path)}}"
                                 title="{{$group_obj->title}}"
                                 alt="{{$group_obj->alt}}"
                                 width="100" height="100" class="img-circle">
                        </div>
                    </div>
                    <div class="col col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="form-group label-floating">
                            <input type="file" name="group_logo" required>
                        </div>
                    </div>


                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-info">{{show_content($general_static_keywords,"save_btn")}}</button>
                </div>
            </form>


        </div>
    </div>




@endsection

