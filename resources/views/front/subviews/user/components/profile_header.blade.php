<!-- Window-popup Update Header Photo -->
<div class="modal fade" id="update-profile-photo" tabindex="-1" role="dialog" aria-labelledby="update-header-photo" aria-hidden="true">
    <div class="modal-dialog window-popup update-header-photo" role="document">
        <div class="modal-content">

            <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close">
                <svg class="olymp-close-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
            </a>

            <div class="modal-header">
                <h6 class="title">Update profile Photo</h6>
            </div>

            <div class="modal-body">

                <?php if($user_obj->user_id == $current_user->user_id): ?>
                <form action="{{url("/$language_locale/information/$user_obj->user_id/edit_profile_img")}}" class="upload-photo-item profile_img_form " id="profile_img_form" method="POST" enctype="multipart/form-data">

                        <input type="file" name="profile_img" id="profile_img_file" class=" profile_img_file" data-toggle="tooltip" data-placement="bottom"
                               title="" data-original-title="{{show_content($user_profile_keywords,"change_image")}}" style="width: 276px !important;height: 175px !important;z-index: 0;position: absolute;">



                    <svg class="olymp-computer-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-computer-icon"></use></svg>

                    <h6>Upload Photo</h6>
                    <span>Browse your computer.</span>

                    {{csrf_field()}}

                </form>

                <?php endif; ?>


            </div>
        </div>
    </div>
</div>


<!-- ... end Window-popup Update Header Photo -->

<!-- Window-popup Update Header Photo -->

<div class="modal fade" id="update-cover-photo" tabindex="-1" role="dialog" aria-labelledby="update-header-photo" aria-hidden="true">
    <div class="modal-dialog window-popup update-header-photo" role="document">
        <div class="modal-content">

            <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close">
                <svg class="olymp-close-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
            </a>

            <div class="modal-header">
                <h6 class="title">Update cover Photo</h6>
            </div>

            <div class="modal-body">


                <?php if($user_obj->user_id == $current_user->user_id): ?>
                <form action="{{url("/$language_locale/information/$user_obj->user_id/edit_cover_img")}}" class="cover_img_form upload-photo-item" id="cover_img_form" method="POST" enctype="multipart/form-data">
                       <input type="file" name="cover_img" id="cover_img_file" class="cover_img_file"
                               data-toggle="tooltip" data-placement="bottom"
                               title="" data-original-title="{{show_content($user_profile_keywords,"change_image")}}" style="width: 276px !important;height: 175px !important;z-index: 0;position: absolute;">

                    <svg class="olymp-computer-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-computer-icon"></use></svg>

                    <h6>Upload Photo</h6>
                    <span>Browse your computer.</span>

                    {{csrf_field()}}
                </form>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="ui-block">
                <div class="top-header">
                    <div class="top-header-thumb">
                        <img  style="width: 100%; height: 300px;" src="{{get_image_or_default($user_obj->cover_path)}}"
                             alt="{{$user_obj->cover_alt}}" title="{{$user_obj->cover_title}}" >

                    </div>
                    <div class="profile-section">
                        <div class="row">
                            <div class="col col-lg-5 col-md-5 col-sm-12 col-12">
                                <ul class="profile-menu">

                                     <li>
                                        <a href="{{url("$language_locale/friends/$user_obj->user_id?type=following")}}">{{show_content($general_static_keywords,"followings_label")}} ({{count($user_obj->following)}})</a>
                                    </li>

                                    <li>
                                        <a href="{{url("$language_locale/friends/$user_obj->user_id?type=followers")}}">{{show_content($general_static_keywords,"followers_label")}} ({{count($user_obj->followers)}})</a>
                                    </li>



                                </ul>
                            </div>
                            <div class="col col-lg-5 ml-auto col-md-5 col-sm-12 col-12">
                                <ul class="profile-menu">

                                    <li>
                                        <a href="{{url("$language_locale/user/posts/post/$user_obj->user_id")}}">{{show_content($user_profile_keywords,"posts_link")}} ({{$posts_count}})</a>
                                    </li>
                                    <li>
                                        <a href="{{url("$language_locale/user/posts/order/$user_obj->user_id")}}">{{show_content($user_profile_keywords,"orders_link")}}  ({{$recommendations_count}})</a>
                                    </li>

                                    <!-- <li>
                                        <div class="more">
                                            <svg class="olymp-three-dots-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
                                            <ul class="more-dropdown more-with-triangle">


                                                <li>
                                                    <a href="{{url("$language_locale/report/user/$user_obj->user_id")}}">{{show_content($user_profile_keywords,"report_link")}}</a>
                                                </li>


                                              <?php if($user_obj->user_id != $current_user->user_id): ?>
                                                <li>
                                                    <?php
                                                    $get_ids = $user_obj->followers;
                                                    ?>
                                                    <?php if(in_array($current_user->user_id,$get_ids)): ?>
                                                    <a href="#" class="unfollow_user" data-follower_id="{{$current_user->user_id}}" data-target_id="{{$user_obj->user_id}}"> <span>{{show_content($general_static_keywords,"unfollow_label")}}</span> <i class="fa fa-user-plus" aria-hidden="true"></i> </a>
                                                    <?php else: ?>
                                                    <a href="#" class="follow_user" data-follower_id="{{$current_user->user_id}}" data-target_id="{{$user_obj->user_id}}"> <span>{{show_content($general_static_keywords,"follow_label")}}</span> <i class="fa fa-user-plus" aria-hidden="true"></i> </a>
                                                    <?php endif; ?>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </li> -->
                                </ul>
                            </div>
                        </div>

                        <?php if($user_obj->user_id == $current_user->user_id): ?>

                            <div class="control-block-button">
                                <div style="font-size: 10px; color: #FFF" class="btn btn-control bg-primary more">
                                  <i  class="fa fa-camera"></i>
                                    <ul class="more-dropdown more-with-triangle triangle-bottom-right">
                                        <li>
                                            <a href="#" data-toggle="modal" data-target="#update-profile-photo">Update Profile Photo</a>
                                        </li>
                                        <li>
                                            <a href="#" data-toggle="modal" data-target="#update-cover-photo">Update Cover Photo</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        <?php endif;?>

                         <?php if(auth()->check() && $user_obj->user_id != $current_user->user_id): ?>
                                  <div class="control-block-button" style="background-color: #278d47; padding: 10px; border-radius: 5px; font-size: 14px ;width: 100px">

                                    <?php
                                    $get_ids = $user_obj->followers;
                                    ?>
                                    <?php if(in_array($current_user->user_id,$get_ids)): ?>
                                    <a style="color: #FFF" href="#" class="unfollow_user" data-follower_id="{{$current_user->user_id}}" data-target_id="{{$user_obj->user_id}}">
                                            <span>{{show_content($general_static_keywords,"unfollow_label")}}</span> <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </a>
                                    <?php else: ?>
                                    <a style="color: #FFF" href="#" class="follow_user" data-follower_id="{{$current_user->user_id}}" data-target_id="{{$user_obj->user_id}}">
                                         <span>{{show_content($general_static_keywords,"follow_label")}}</span> <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </a>
                                    <?php endif; ?>
                                  </div>
                                <?php endif; ?>


                    </div>
                    <div class="top-header-author">
                        <div  class="author-thumb">
                            <a  href="{{url("user/posts/all/$user_obj->user_id")}}">
                                <img src="{{user_default_image($user_obj)}}"
                                     alt="{{$user_obj->logo_alt}}" title="{{$user_obj->logo_title}}" style="width: 130px; height: 130px">
                            </a>
                        </div>



                        <div class="author-content">
                            <h5><a style="color: #515365" href="{{url("user/posts/all/$user_obj->user_id")}}">
                                {{$user_obj->full_name}}
                                <?php if($user_obj->is_privet_account): ?>
                                @include('blocks.verify_padge_block')
                                <?php endif; ?>


                            </a></h5>
                            <div class="country">
                                <?php if(!empty($user_obj->user_bio)): ?>
                               {{$user_obj->user_bio}}
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


