<div class="footer footer--dark" id="footer" style="width: 100%;">
    <div class="container">
        <div class="row">
            <div class="col col-lg-4 col-md-4 col-sm-12 col-12">

                <!-- Widget About -->

                <div class="widget w-about">

                    <a href="{{url("/home")}}" class="logo">
                        <div class="img-wrap">
                            <?php if(isset($logo_and_icon->logo) && isset($logo_and_icon->logo->path)): ?>
                            <img src="{{get_image_or_default($logo_and_icon->logo->path)}}"
                                 title="{{$logo_and_icon->logo->title}}"
                                 alt="{{$logo_and_icon->logo->alt}}"
                            />
                            <?php endif; ?>
                        </div>
                        <div class="title-block">
                            <h6 class="logo-title"></h6>
                            <div class="sub-title"></div>
                        </div>
                    </a>
                    <p>{{show_content($intro_keywords,"intro_title")}}</p>
                    <ul class="socials">

                        <?php if(isset($email_page->social_imgs) && is_array($email_page->social_imgs) && count($email_page->social_imgs) &&
                        isset($email_page->social_links) && is_array($email_page->social_links) && count($email_page->social_links)): ?>
                            <?php
                                $social_imgs = array_diff($email_page->social_imgs,[""]);
                                $social_links = array_diff($email_page->social_links,[""]);
                            ?>
                            <?php foreach($social_imgs as $key => $social_img): ?>
                                <li>
                                    <a href="{{(isset($social_links[$key])?$social_links[$key]:"")}}" target="_blank">
                                        <i class="{{$social_img}}"></i>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </ul>
                </div>

                <!-- ... end Widget About -->

            </div>

            <?php if(count($static_pages)): ?>

            <?php
            $group_static_pages = collect($static_pages)->chunk(4)->all();
            ?>

            <?php foreach($group_static_pages as $key => $static_pages_arr): ?>

            <div class="col col-lg-2 col-md-4 col-sm-12 col-12">

                <!-- Widget List -->

                <div class="widget w-list">
                    <h6 class="title"></h6>

                    <ul>

                        <?php foreach($static_pages_arr as $key2 => $static_page): ?>
                        <li>
                            <a href="{{url(urlencode($static_page->page_slug))}}">{{$static_page->page_title}}</a>
                        </li>
                        <?php endforeach; ?>

                    </ul>

                </div>

                <!-- ... end Widget List -->

            </div>
            <?php endforeach; ?>

            <?php endif; ?>

            <div class="col col-lg-2 col-md-4 col-sm-12 col-12">

                <!-- Widget List -->

                <div class="widget w-list">
                    <h6 class="title"></h6>

                    <ul>

                        <li>
                            <a href="{{url("/cashback")}}">{{show_content($user_homepage_keywords,"brokers_link")}}</a>
                        </li>

                        <li>
                            <a href="{{url("/economic_calendar")}}">{{show_content($user_homepage_keywords,"date_time_link")}}</a>
                        </li>

                        <li>
                            <a href="{{url("/news")}}">{{show_content($user_homepage_keywords,"news_link")}}</a>
                        </li>

                        <li>
                            <a href="{{url("/articles")}}">{{show_content($user_homepage_keywords,"articles_link")}}</a>
                        </li>

                        <li>
                            <a href="{{url("/support")}}">
                                {{show_content($user_homepage_keywords,"support_link")}}
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- ... end Widget List -->

            </div>

            <div class="col col-lg-12 col-md-12 col-sm-12 col-12">

                <!-- SUB Footer -->

                <div class="sub-footer-copyright" style="color: #fff ">
					<span>
						{{show_content($general_static_keywords,"copyright")}}
					</span>
                </div>

                <!-- ... end SUB Footer -->

            </div>

        </div>
    </div>
</div>
