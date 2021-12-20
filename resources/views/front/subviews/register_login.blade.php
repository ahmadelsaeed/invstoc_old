@extends('front.register_layout')

@section('subview')

    <!-- Preloader -->

    <div id="hellopreloader">
        <div class="preloader">
            <svg width="45" height="45" stroke="#fff">
                <g fill="none" fill-rule="evenodd" stroke-width="2" transform="translate(1 1)">
                    <circle cx="22" cy="22" r="6" stroke="none">
                        <animate attributeName="r" begin="1.5s" calcMode="linear" dur="3s" repeatCount="indefinite" values="6;22"/>
                        <animate attributeName="stroke-opacity" begin="1.5s" calcMode="linear" dur="3s" repeatCount="indefinite" values="1;0"/>
                        <animate attributeName="stroke-width" begin="1.5s" calcMode="linear" dur="3s" repeatCount="indefinite" values="2;0"/>
                    </circle>
                    <circle cx="22" cy="22" r="6" stroke="none">
                        <animate attributeName="r" begin="3s" calcMode="linear" dur="3s" repeatCount="indefinite" values="6;22"/>
                        <animate attributeName="stroke-opacity" begin="3s" calcMode="linear" dur="3s" repeatCount="indefinite" values="1;0"/>
                        <animate attributeName="stroke-width" begin="3s" calcMode="linear" dur="3s" repeatCount="indefinite" values="2;0"/>
                    </circle>
                    <circle cx="22" cy="22" r="8">
                        <animate attributeName="r" begin="0s" calcMode="linear" dur="1.5s" repeatCount="indefinite" values="6;1;2;3;4;5;6"/>
                    </circle>
                </g>
            </svg>

            <div class="text">Loading ...</div>
        </div>
    </div>

    <!-- ... end Preloader -->
    <div class="content-bg-wrap"></div>


    <!-- Header Standard Landing  -->

    <div class="header--standard header--standard-landing" id="header--standard">
        <div class="container">
            <div class="header--standard-wrap">

                <a href="#" class="logo">
                    <div class="img-wrap">
                        <a href="{{url("home")}}">
                            <?php if(isset($logo_and_icon->logo) && isset($logo_and_icon->logo->path)): ?>
                                <img src="{{get_image_or_default($logo_and_icon->logo->path)}}"
                                     title="{{$logo_and_icon->logo->title}}"
                                     alt="{{$logo_and_icon->logo->alt}}"
                                />
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="title-block">
                        <h6 class="logo-title"></h6>
                        <div class="sub-title"></div>
                    </div>
                </a>

                <a href="#" class="open-responsive-menu js-open-responsive-menu">
                    <svg class="olymp-menu-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-menu-icon"></use></svg>
                </a>

                <div class="nav nav-pills nav1 header-menu expanded-menu">
                    <div class="mCustomScrollbar">
                        <ul>




                            <li class="nav-item">
                                <a href="{{url("/$language_locale/cashback")}}" class="nav-link">{{show_content($user_homepage_keywords,"brokers_link")}}</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url("/$language_locale/economic_calendar")}}" class="nav-link">{{show_content($user_homepage_keywords,"date_time_link")}}</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url("/$language_locale/news")}}" class="nav-link">{{show_content($user_homepage_keywords,"news_link")}}</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url("/$language_locale/articles")}}" class="nav-link">{{show_content($user_homepage_keywords,"articles_link")}}</a>
                            </li>

                            <?php if(count($static_pages)): ?>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" tabindex='1'>Invstoc</a>
                                    <div class="dropdown-menu">
                                        <?php foreach($static_pages as $key => $static_page): ?>
                                            <a class="dropdown-item" href="{{url($language_locale . '/' . urlencode($static_page->page_slug))}}">{{$static_page->page_title}}</a>
                                        <?php endforeach; ?>
                                    </div>
                                </li>

                            <?php endif; ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" tabindex='1'>
                                    <img src="{{get_image_or_default($current_lang->lang_img_path)}}" width="24" height="24"> {{$current_lang->lang_text}}
                                </a>
                                <div class="dropdown-menu">
                                    <?php foreach($all_langs as $key => $lang_obj): ?>
                                        <?php if($lang_obj->lang_id != $current_lang->lang_id): ?>
                                            <a href="{{ url('/') . '/' . strtolower($lang_obj->lang_title) }}"  title="{{$lang_obj->lang_title}}" style="color: #06793c;">
                                                <img src="{{get_image_or_default($lang_obj->lang_img_path)}}" width="24" height="24"> {{$lang_obj->lang_text}}
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </li>

                            <li class="close-responsive-menu js-close-responsive-menu">
                                <svg class="olymp-close-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
                            </li>
                           <!--  <li class="nav-item js-expanded-menu">
                               <a href="#" class="nav-link">
                                   <svg class="olymp-menu-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-menu-icon"></use></svg>
                                   <svg class="olymp-close-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
                               </a>
                           </li> -->

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ... end Header Standard Landing  -->
    <div class="header-spacer--standard"></div>

    <div class="container">
        <div class="row display-flex">
            <div class="col col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                <div class="landing-content">
                    <h1>{{show_content($intro_keywords,"intro_title")}}</h1>
                </div>

                <img src="{{url("/")}}/public_html/front/images/line_chart_1.png" width="300" height="300">

            </div>

            <div class="col col-xl-5 col-lg-6 col-md-12 col-sm-12 col-12">

                <!-- Login-Registration Form  -->

                <div class="registration-login-form">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item " >
                            <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">
                               <div class="h6 ">{{show_content($intro_keywords,"login_submit_btn")}}</div>
                              <!-- <svg class="olymp-login-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-login-icon"></use></svg> -->
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#home" role="tab">
                          <div class="h6">{{show_content($intro_keywords,"register_submit_btn")}}</div>
                          <!-- <svg class="olymp-register-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-register-icon"></use></svg> -->
                            </a>
                        </li>

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane " id="home" role="tabpanel" data-mh="log-tab">
                            <div class="title h6">{{show_content($intro_keywords,"register_header")}}</div>

                            <form class="content"  name="registration_form" method="POST" action="{{url("/register")}}">
                                <div class="row">
                                    <div class="col col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label"></label>
                                            <input type="text" required placeholder="{{show_content($intro_keywords,"register_first_name")}}" name="first_name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col col-12 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label"></label>
                                            <input type="text" required placeholder="{{show_content($intro_keywords,"register_last_name")}}" name="last_name" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label"></label>
                                            <input type="text" placeholder="{{show_content($intro_keywords,"register_email")}}" class="form-control" required name="email" >
                                        </div>
                                        <div class="form-group label-floating">
                                            <label class="control-label"></label>
                                            <input type="password" placeholder="{{show_content($intro_keywords,"register_password")}}" class="form-control" required name="password" >
                                        </div>

                                        <div class="form-group label-floating">
                                            <label class="control-label"></label>
                                            <input type="password" placeholder="{{show_content($intro_keywords,"register_confirm_password")}}" class="form-control" required name="password_confirmation" >
                                        </div>


                                        <div class="remember">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="terms" value="1" id="checkboxFourInput" name="" />

                                                    <a href="#" data-toggle="modal" data-target="#privacyModal">
                                                        {{show_content($intro_keywords,"register_privacy")}}
                                                    </a>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="g-recaptcha" data-sitekey="6LfR7eAZAAAAAL2bf-BYZtAkzPfEygQ86TJ-1UQY"></div>

                                        {{csrf_field()}}
                                        <button class="btn btn-grey btn-lg full-width" style="margin-top: 10px" type="submit">{{show_content($intro_keywords,"register_submit_btn")}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane active" id="profile" role="tabpanel" data-mh="log-tab">
                            <div class="title h6">{{show_content($intro_keywords,"login_header")}}</div>

                            <form class="content"  name="Login_form" action="{{url("/login")}}" method="POST">
                                <div class="row">
                                    <div class="col col-12 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group label-floating">
                                            <label class="control-label"></label>
                                            <input class="form-control " required
                                                   type="text" name="email" placeholder="{{show_content($intro_keywords,"login_email_or_id")}}" title="{{show_content($intro_keywords,"login_email_or_id")}}" />

                                        </div>
                                        <div class="form-group label-floating">
                                            <label class="control-label"></label>
                                            <input class="form-control" required placeholder="{{show_content($intro_keywords,"login_password")}}"
                                                   type="password" name="password" title="{{show_content($intro_keywords,"login_password")}}" />

                                        </div>

                                        <div class="remember">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="remember_Me" type="checkbox">
                                                    Remember Me
                                                </label>
                                            </div>
                                            <a href="{{url("password/reset")}}">{{show_content($intro_keywords,"login_forget_password")}}</a>
                                        </div>
                                        <div class="g-recaptcha" data-sitekey="6LfR7eAZAAAAAL2bf-BYZtAkzPfEygQ86TJ-1UQY"></div>

                                        {{csrf_field()}}
                                        <button class="btn btn-lg btn-grey full-width" style="margin-top: 10px" type="submit">{{show_content($intro_keywords,"login_submit_btn")}}</button>

                                        <?php if(false): ?>
                                            <div class="or"></div>

                                            {{--<a href="#" class="btn btn-lg bg-facebook full-width btn-icon-left"><i class="fab fa-facebook-f" aria-hidden="true"></i>Login with Facebook</a>--}}

                                            {{--<a href="#" class="btn btn-lg bg-twitter full-width btn-icon-left"><i class="fab fa-twitter" aria-hidden="true"></i>Login with Twitter</a>--}}


                                            <p>Don’t you have an account? <a data-toggle="tab" href="#home" role="tab" aria-selected="false">Register Now!</a> it’s really simple and you can start enjoing all the benefits!</p>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div style="margin: 0 35px">
                            @if(count($static_pages))
                                @foreach($static_pages as $key => $static_page)
                                    <a style="margin-right: 7px" href="{{url(urlencode($static_page->page_slug))}}">{{$static_page->page_title}}</a>
                                @endforeach
                            @endif
                        </div>
                        <div style="padding: 20px 0;text-align: center;border-top:1px solid #e6ecf5;margin-top: 30px">
                            <span>
                                {{show_content($general_static_keywords,"copyright")}}
                            </span>
                        </div>
                    </div>

                </div>

                <!-- ... end Login-Registration Form  -->
            </div>
        </div>
    </div>

    <!-- Window Popup Main Search -->

    <div class="modal fade" id="main-popup-search" tabindex="-1" role="dialog" aria-labelledby="main-popup-search" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered window-popup main-popup-search" role="document">
            <div class="modal-content">
                <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close">
                    <svg class="olymp-close-icon"><use xlink:href="{{url("/")}}/public_html/front/new_design/svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
                </a>
                <div class="modal-body">
                    <form class="form-inline search-form" method="post">
                        <div class="form-group label-floating">
                            <label class="control-label">What are you looking for?</label>
                            <input class="form-control bg-white" placeholder="" type="text" value="">
                        </div>

                        <button class="btn btn-purple btn-lg">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ... end Window Popup Main Search -->

@endsection
