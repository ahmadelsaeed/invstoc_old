<div class="header-section">

    <!--toggle button start-->
    <a class="toggle-btn  menu-collapsed"><i class="fa fa-bars"></i></a>
    <!--toggle button end-->

    <!--notification menu start -->
    <div class="menu-right">
        <div class="user-panel-top">
            <div class="profile_details_left">
                <ul class="nofitications-dropdown">
                    <?php if(1!=1): ?>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-envelope"></i><span class="badge">{{count($user_messages)}}</span></a>

                        <ul class="dropdown-menu">
                            <li>
                                <div class="notification_header">
                                    <h3>Today System Messages</h3>
                                </div>
                            </li>
                            <?php if(1!=1 && is_array($user_messages) && count($user_messages)): ?>
                                <?php foreach($user_messages as $key => $msg): ?>
                                    <?php
                                        if($key == 5)
                                        {
                                            break;
                                        }
                                    ?>
                                    <li>
                                        <a href="#">
                                            <div class="user_img"><img src="{{get_profile_logo_or_default($msg->logo_path)}}" alt=""></div>
                                            <div class="notification_desc">
                                                <p title="{{$msg->message}}">{{$msg->full_name}} : {{split_word_into_chars_ar($msg->message , 40)}}</p>
                                                <p><span>{{\Carbon\Carbon::createFromTimestamp(strtotime($msg->msg_date))->diffForHumans()}}</span></p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <li>
                                <div class="notification_bottom">
                                    <a href="#">See all support messages</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if(check_permission($user_permissions,"admin/notifications","show_action")): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                                    class="fa fa-bell"></i><span class="badge blue">{{count($notifications)}}</span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <div class="notification_header">
                                    <h3>Today System Notifications</h3>
                                </div>
                            </li>
                            <?php if(is_array($notifications) && count($notifications)): ?>
                                <?php foreach($notifications as $key => $not): ?>
                                    <?php
                                        if($key == 5)
                                        {
                                            break;
                                        }
                                    ?>
                                    <li>
                                        <a href="#">
                                            <div class="notification_desc alert alert-{{$not->not_type}}">
                                                <p>{{$not->not_title}}</p>
                                                <p><span>{{\Carbon\Carbon::createFromTimestamp(strtotime($not->created_at))->diffForHumans()}}</span></p>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <li>
                                <div class="notification_bottom">
                                    <a href="{{url("/admin/notifications/show_all")}}">See all notification</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                </ul>
            </div>
            <div class="profile_details">
                <ul>
                    <li class="dropdown profile_details_drop">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <div class="profile_img">
                                <span style="background:url({{get_profile_logo_or_default($current_user->logo_path)}}) no-repeat center"> </span>
                                <div class="user-name">
                                    <p>{{$current_user->full_name}}<span>{{$current_user->role}}</span></p>
                                </div>
                                <i class="lnr lnr-chevron-down"></i>
                                <i class="lnr lnr-chevron-up"></i>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <ul class="dropdown-menu drp-mnu">
                            <li><a href="{{url('admin/users/save/'.$current_user->user_id)}}"><i class="fa fa-cog"></i> Settings</a></li>
                            <li><a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!--notification menu end -->
</div>