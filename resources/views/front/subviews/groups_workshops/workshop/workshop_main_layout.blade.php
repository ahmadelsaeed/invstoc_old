@extends('front.main_layout')
@section('subview')

    <div class="container">
        <div class="row">

            <!-- Main Content -->

            <main class="col col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-12">

                <div class="container">
                    <div class="row">
                        <div class="ui-block" style="width: 100%;">


                            <!-- Single Post -->

                            <div class="ui-block-title">
                                <h6 class="title">
                                    {{$workshop_obj->workshop_name}} &nbsp;
                                    <span class="badge badge-success">
                                        <i class="fa fa-user"></i>
                                        <label class="count_followers">{{$count_followers}}</label>
                                    </span>
                                </h6>
                                <img src="{{get_image_or_default($workshop_obj->path)}}"
                                     title="{{$workshop_obj->title}}"
                                     alt="{{$workshop_obj->alt}}"
                                     width="50" height="50" class="img-circle">

                                <?php if($workshop_obj->owner_id != $current_user->user_id): ?>
                                    <?php if($is_follower): ?>
                                        <button class="btn btn-danger follow_workshop" style="float:right;" data-status="unfollow" data-workshop_id="{{$workshop_obj->workshop_id}}">
                                            {{show_content($general_static_keywords,"unfollow_label")}}
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-primary follow_workshop" style="float:right;" data-status="follow" data-workshop_id="{{$workshop_obj->workshop_id}}">
                                            {{show_content($general_static_keywords,"follow_label")}}
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>

                            </div>

                            <div class="ui-block-content">
                                <?php if($workshop_obj->owner_id==$current_user->user_id): ?>

                                <div class="more" style="float: right;">
                                    <svg class="olymp-three-dots-icon">
                                        <use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use>
                                    </svg>

                                    <ul class="more-dropdown more-with-triangle" >
                                        <li>
                                            <a href="{{url("/workshop/delete/$workshop_obj->workshop_name/$workshop_obj->workshop_id")}}">{{show_content($general_static_keywords,"delete_btn")}}</a>
                                        </li>
                                        <li>
                                            <a href="{{url("/workshop/rename/$workshop_obj->workshop_name/$workshop_obj->workshop_id")}}">{{show_content($general_static_keywords,"rename_btn")}}</a>
                                        </li>
                                        <li>
                                            <a href="{{url("/workshop/change_activity/$workshop_obj->workshop_name/$workshop_obj->workshop_id")}}">{{show_content($general_static_keywords,"change_activity_btn")}}</a>
                                        </li>
                                        <li>
                                            <a href="{{url("/workshop/change_logo/$workshop_obj->workshop_name/$workshop_obj->workshop_id")}}">{{show_content($general_static_keywords,"change_image_label")}}</a>
                                        </li>
                                    </ul>
                                </div>

                                <?php endif; ?>


                            </div>

                            <!-- ... end Single Post -->

                        </div>

                        @yield("workshop_subview")

                    </div>
                </div>

            </main>

            <!-- ... end Main Content -->


            <!-- Left Sidebar -->

            <aside class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">
                @include('front.main_components.left_sidebar')
            </aside>

            <!-- ... end Left Sidebar -->


            <!-- Right Sidebar -->

            <aside class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">
                @include('front.main_components.right_sidebar')
            </aside>

            <!-- ... end Right Sidebar -->

        </div>
    </div>

@endsection
