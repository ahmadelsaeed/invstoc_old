@extends('front.subviews.groups_workshops.group.group_main_layout')

@section('group_subview')



    <div class="ui-block" style="width: 100%;">
        <div class="ui-block-title">
            <h6 class="title">{{show_content($general_static_keywords,"group_requests_header")}}</h6>
        </div>
        <div class="ui-block-content">

            <ul class="notification-list friend-requests">
                <?php foreach ($users_requests as $key => $user_obj): ?>

                <li>
                    <div class="author-thumb">
                        <img src="{{get_image_or_default($user_obj->logo_path)}}" alt="{{$user_obj->full_name}}" style="width: 42px; height: 42px">
                    </div>
                    <div class="notification-event">
                        <a href="{{url(strtolower(session('language_locale', 'en'))."information/$user_obj->user_id")}}" class="h6 notification-friend">{{$user_obj->full_name}}</a>
                    </div>
                    <span class="notification-icon">
										<a href="#" class="accept-request accept_group_request" data-group_id="{{$group_obj->group_id}}" data-user_id="{{$user_obj->user_id}}" >
											<span class="icon-add without-text">
												<svg class="olymp-happy-face-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>
											</span>
										</a>

										<a href="#" class="accept-request request-del remove_group_request" data-group_id="{{$group_obj->group_id}}" data-user_id="{{$user_obj->user_id}}" >
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



