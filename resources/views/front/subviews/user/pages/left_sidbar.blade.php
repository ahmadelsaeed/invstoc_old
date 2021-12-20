<div class="col col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-6 col-12">

    <div class="ui-block">
        <div class="ui-block-title">
            <h6 class="title">{{show_content($user_profile_keywords,"about_me_link")}}</h6>
        </div>
        <div class="ui-block-content">

            <!-- W-Personal-Info -->

            <ul class="widget w-personal-info item-block">

                <li>
                    <span class="title">{{show_content($user_profile_keywords,"about_me_bio_label")}}:</span>
                    <span class="text">{{$user_obj->user_bio}}.</span>
                </li>

                <li>
                    <span class="title">{{show_content($user_profile_keywords,"about_me_interests_label")}}:</span>
                    <span class="text">{{$user_obj->user_interests}}.</span>
                </li>

                <?php if(($user_obj->user_id != $current_user->user_id && $user_obj->birthdate_privacy == 1)
                    || $user_obj->user_id == $current_user->user_id): ?>

                <li>
                    <span class="title">{{show_content($user_profile_keywords,"about_me_birthdate_label")}}:</span>
                    <span class="text">{{$user_obj->birthdate}}.</span>
                </li>
                <?php endif; ?>

                <?php if(($user_obj->user_id != $current_user->user_id && $user_obj->country_privacy == 1)
                    || $user_obj->user_id == $current_user->user_id): ?>

                <li>
                    <span class="title">{{show_content($user_profile_keywords,"about_me_country_label")}}:</span>
                    <span class="text">{{$user_obj->country}}.</span>
                </li>
                <?php endif; ?>

                <?php if(($user_obj->user_id != $current_user->user_id && $user_obj->city_privacy == 1)
                    || $user_obj->user_id == $current_user->user_id): ?>

                <li>
                    <span class="title">{{show_content($user_profile_keywords,"about_me_city_label")}}:</span>
                    <span class="text">{{$user_obj->city}}.</span>
                </li>
                <?php endif; ?>

                <li>
                    <span class="title">{{show_content($user_profile_keywords,"about_me_gender_label")}}:</span>
                    <span class="text">{{($user_obj->gender == "male"?show_content($user_profile_keywords,"about_me_male_label"):show_content($user_profile_keywords,"about_me_female_label"))}}.</span>
                </li>
                <?php if($user_obj->request_referrer_link && !empty($user_obj->referrer_link)): ?>
                    <li>
                        <span class="title">{{show_content($user_profile_keywords,"about_me_referrer_link")}}:</span>
                        <span class="text"><a href="{{url("/$language_locale/referrer_link?ref=$user_obj->referrer_link")}}">
                                            {{url("/$language_locale/referrer_link?ref=$user_obj->referrer_link")}}
                                        </a>.
                        </span>
                    </li>
                <?php endif; ?>


                <?php if(!empty($user_obj->username)): ?>
                    <li>
                        <span class="title">Code : {{$user_obj->username}}:</span>
                        <span class="text"><img src="{{url('get_barcode_img/'.$user_obj->username)}}"/></span>
                    </li>
                <?php endif; ?>


            </ul>

            <!-- .. end W-Personal-Info -->



        </div>
    </div>

    <!-- .. end W-Latest-Photo -->
    <?php if(isset($get_ads['profile_left'])): ?>
    <div class="ui-block remove_back_color">
        {!! get_adv($get_ads['profile_left']->all(),"295px","auto") !!}
    </div>
    <?php endif; ?>
</div>
