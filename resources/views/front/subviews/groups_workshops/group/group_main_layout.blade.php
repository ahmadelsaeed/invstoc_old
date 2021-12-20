@extends('front.main_layout')
@section('meta')
    <meta property="og:image" content="{{get_image_or_default($group_obj->path)}}" />
@endsection
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
                                <h6 class="title">{{$group_obj->group_name}}</h6>
                                <img src="{{get_image_or_default($group_obj->path)}}"
                                     title="{{$group_obj->title}}"
                                     alt="{{$group_obj->alt}}"
                                     width="50" height="50" class="img-circle">
                            </div>

                            <div class="ui-block-content">
                                <?php if(!isset($request_join) && isset($this_user_group_membership) && $this_user_group_membership->user_role=="admin"): ?>

                                    <div class="more" style="float: right;">
                                        <svg class="olymp-three-dots-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>

                                        <ul class="more-dropdown more-with-triangle" >

                                            <li>
                                                <a href="{{url("/group/$group_obj->group_name/$group_obj->group_id")}}">{{$group_obj->group_name}}</a>
                                            </li>
                                            <li>
                                                <a href="{{url("/group/settings/$group_obj->group_name/$group_obj->group_id")}}">{{show_content($group_keywords,"menu_group_settings")}}</a>
                                            </li>
                                            <li>
                                                <a href="{{url("/group/change_logo/$group_obj->group_name/$group_obj->group_id")}}">{{show_content($general_static_keywords,"change_image_label")}}</a>
                                            </li>
                                            <li>
                                                <a href="{{url("/group/members/$group_obj->group_name/$group_obj->group_id")}}">{{show_content($group_keywords,"menu_group_members")}}</a>
                                            </li>

                                            <li>
                                                <a href="{{url("/group/requests/$group_obj->group_name/$group_obj->group_id")}}">{{show_content($group_keywords,"menu_group_members_requests")}}</a>
                                            </li>

                                            <?php if($group_obj->group_owner_id==$current_user->user_id): ?>
                                            <li>
                                                <a href="{{url("/group/rename/$group_obj->group_name/$group_obj->group_id")}}">{{show_content($general_static_keywords,"rename_btn")}}</a>
                                            </li>
                                            <li>
                                                <a href="{{url("/group/delete/$group_obj->group_name/$group_obj->group_id")}}">{{show_content($general_static_keywords,"delete_btn")}}</a>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                <?php endif; ?>


                            </div>


                            <!-- ... end Single Post -->

                        </div>

                        @yield("group_subview")

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
