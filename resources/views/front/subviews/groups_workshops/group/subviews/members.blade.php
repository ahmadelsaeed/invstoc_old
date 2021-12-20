@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')


    <div class="ui-block" style="width: 100%;">
        <div class="ui-block-title">
            <h6 class="title">{{show_content($group_keywords,"add_members_header")}}</h6>
        </div>
        <div class="ui-block-content">

            <form class="prevent_form_submit_on_enter" action="{{url(strtolower(session('language_locale', 'en'))."/group/members/$group_obj->group_name/$group_obj->group_id")}}" method="post">

                <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group label-floating">
                        <input type="text" class="form-control border_style search_for_user" placeholder="{{show_content($group_keywords,"add_members_search_for_users")}}">
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: 10px;">
                    <select name="selected_members[]" class="form-control border_style hide_input load_search_users_results" multiple></select>
                </div>

                <div class="col-md-12 text-center" style="margin-top: 10px;">
                    {!! csrf_field() !!}



                    {!! csrf_field() !!}
                    <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
                        <button type="submit" class="btn btn-primary btn-lg full-width">{{show_content($group_keywords,"add_members_btn")}}</button>
                    </div>


                </div>
            </form>

        </div>
    </div>




    <div class="ui-block" style="width: 100%;">
        <div class="ui-block-title">
            <h6 class="title">{{show_content($group_keywords,"members_header")}}</h6>
        </div>
        <div class="ui-block-content">
            <ul class="notification-list friend-requests">
                <?php foreach ($all_members as $key => $member): ?>

                <li>
                    <div class="author-thumb">
                        <img src="{{get_image_or_default($member->logo_path)}}" alt="{{$member->full_name}}" style="width: 42px; height: 42px">
                    </div>
                    <div class="notification-event">
                        <a href="{{url(strtolower(session('language_locale', 'en'))."information/$member->user_id")}}" class="h6 notification-friend">{{$member->full_name}}</a>
                    </div>

                    <a href="{{url(strtolower(session('language_locale', 'en'))."group/change_member_role/$group_obj->group_name/$group_obj->group_id?member_id=$member->g_m_id")}}">Change Role</a>

                    <span class="notification-icon">

                        <?php if($member->user_role=="admin"): ?>
                        <label class="label label-primary">
                                            {{$member->user_role}}
                                        </label>
                        <?php else: ?>
                        <label class="label label-info">
                                            {{$member->user_role}}
                                        </label>
                        <?php endif; ?>

										<a href="{{url(strtolower(session('language_locale', 'en'))."group/remove_member/$group_obj->group_name/$group_obj->group_id?member_id=$member->g_m_id")}}"  class="accept-request request-del " >
											<span class="icon-minus">
												<svg class="olymp-happy-face-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>
											</span>
										</a>

									</span>

                </li>
                <?php endforeach; ?>


            </ul>



        </div>
    </div>




@endsection






