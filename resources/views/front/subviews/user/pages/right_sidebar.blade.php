<div class="col col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-6 col-12">

   <div class="ui-block">
        <div class="ui-block-title">
            <a href="{{url("report/user/$user_obj->user_id")}}"><h6 class="title">{{show_content($user_profile_keywords,"report_link")}}</h6></a>
        </div>
        <div class="ui-block-content">

            <!-- W-Faved-Page -->

            <ul class="widget w-last-photo ">

                    <li style="width: 100%;">
                        <a href="{{url("report/user/$user_obj->user_id")}}">
                            <img src="https://www.amcharts.com/wp-content/uploads/2013/12/demo_7395_none-2.png" style="width: 100%;height: 100%;">
                        </a>
                    </li>



            </ul>

            <!-- .. end W-Faved-Page -->
        </div>
    </div>



    <div class="ui-block">
        <div class="ui-block-title">

            <a href="{{url("media/$user_obj->user_id")}}"><h6 class="title">{{show_content($user_profile_keywords,"media_link")}}</h6></a>
        </div>
        <div class="ui-block-content">

            <!-- W-Latest-Photo -->

            <ul class="widget w-last-photo js-zoom-gallery">

                <?php foreach($medias as $path): ?>
                    <li>
                        <img src="{{get_image_or_default($path)}}" style="width: 62px !important; height: 62px !important;" alt="photo" class="open_post_image">
                    </li>
                <?php endforeach; ?>

            </ul>

        </div>
    </div>


    <!-- .. end W-Latest-Photo -->
    <?php if(isset($get_ads['profile_right'])): ?>
    <div class="ui-block remove_back_color">
        {!! get_adv($get_ads['profile_right']->all(),"295px","auto") !!}
    </div>
    <?php endif; ?>
</div>
