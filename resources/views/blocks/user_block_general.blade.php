<div class="ui-block" data-mh="friend-groups-item">

    <!-- Friend Item -->

    <div class="friend-item friend-groups">

        <div class="friend-item-content">
            <div class="friend-avatar">
                <div class="author-thumb">
                    <a href="{{url("user/posts/all/$user_obj_item->user_id")}}">
                        <img src="{{user_default_image($user_obj_item)}}" alt="Olympus" style="width: 120px; height: 120px">
                    </a>
                </div>
                <div class="author-content">

                    <a class="h5 author-name" href="{{url("user/posts/all/$user_obj_item->user_id")}}" target="_blank">
                        <?php if($user_obj_item->is_privet_account): ?>
                        @include('blocks.verify_padge_block')
                        <?php endif; ?>

                        {{$user_obj_item->full_name}}
                    </a>


                    <?php if(!empty($user_obj_item->user_bio)): ?>
                    <div class="country">
                        {!! split_word_into_chars_ar_without_more_link(
                        $user_obj_item->user_bio,40,
                        " ...") !!}
                    </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="control-block-button">
                <div class="country">{{show_content($general_static_keywords,"code_label")}} : {{$user_obj_item->username}}</div>
                <img src="{{url('get_barcode_img/'.$user_obj_item->username)}}"/>
            </div>


            <div class="control-block-button">


                <a href="{{url("user/posts/all/$user_obj_item->user_id")}}"
                   class="btn btn-control bg-blue">
                    <svg class="olymp-happy-faces-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-faces-icon"></use></svg>
                </a>

                <?php if(false): ?>


                <?php if($user_obj_item->user_id != $current_user->user_id): ?>

                <?php

                $get_my_followers_ids = $current_user->followers;
                $get_my_followings_ids = $current_user->following;

                ?>

                <?php if(isset($_GET['type']) && ( $_GET['type'] == "followers" || $_GET['type'] == "following" )): ?>

                <?php if(in_array($user_obj_item->user_id,$get_my_followings_ids)): ?>

                <a href="#" class="unfollow_user  btn btn-control bg-blue" data-follower_id="{{$current_user->user_id}}" data-target_id="{{$user_obj_item->user_id}}">
                    <svg class="olymp-happy-faces-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-faces-icon"></use></svg>
                </a>
                <?php else: ?>
                <a href="#" class="follow_user btn btn-control bg-blue" data-follower_id="{{$current_user->user_id}}" data-target_id="{{$user_obj_item->user_id}}">
                    <svg class="olymp-happy-face-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>
                </a>


                <?php endif; ?>

                <?php endif; ?>


                <?php if(!isset($_GET['type'])): ?>

                <?php if(in_array($user_obj_item->user_id,$get_my_followings_ids)): ?>
                <a href="#" class="unfollow_user  btn btn-control bg-blue" data-follower_id="{{$current_user->user_id}}" data-target_id="{{$user_obj_item->user_id}}">
                    <svg class="olymp-happy-faces-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-faces-icon"></use></svg>
                </a>

                <?php else: ?>

                <a href="#" class="follow_user btn btn-control bg-blue" data-follower_id="{{$current_user->user_id}}" data-target_id="{{$user_obj_item->user_id}}">
                    <svg class="olymp-happy-face-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>
                </a>
                <?php endif; ?>

                <?php endif; ?>


                <?php endif; ?>



                <?php if(false): ?>
                    <a href="#" class="btn btn-control bg-purple send_custom_message_to_user" data-user_code="{{$user_obj_item->username}}">
                        <svg class="olymp-chat---messages-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
                    </a>
                <?php endif; ?>


                <?php endif; ?>


            </div>


        </div>
    </div>

    <!-- ... end Friend Item -->
</div>
