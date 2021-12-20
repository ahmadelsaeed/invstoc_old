<!DOCTYPE html>
<html lang="en">

<head prefix="og: http://ogp.me/ns#" >

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="@if(isset($broker_data->page_meta_keywords)) {{ $broker_data->page_meta_keywords }} @else {{$meta_keywords}} @endif" />
    <meta name="description" content="@if(isset($broker_data->page_meta_desc)) {{ $broker_data->page_meta_desc }} @else {{$meta_desc}} @endif" />
    <meta name="robots" content="index, follow" />
    @yield('meta')
    <title>@if(isset($broker_data->page_meta_title)) {{ $broker_data->page_meta_title }} @else {{$meta_title}} @endif</title>

    <?php if(isset($logo_and_icon->icon) && isset($logo_and_icon->icon->path)): ?>
        <link rel="shortcut icon" href="{{get_image_or_default($logo_and_icon->icon->path)}}" type="image/x-icon">
    <?php endif; ?>

    <meta property="og:image" content="{{(isset($og_img)?$og_img:"")}}"/>
    <meta property="og:url" content="{{(isset($og_url)?$og_url:"")}}"/>
    <meta property="og:title" content="{{ isset($meta_title) ? $meta_title : "" }}"/>
    <meta property="og::description" content="{{(isset($meta_desc)?$meta_desc:"")}}"/>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{(isset($og_title)?$og_title:"")}}">
    <meta name="twitter:description" content="{{(isset($og_description)?$og_description:"")}}">
    <meta name="twitter:text:description" content="{{(isset($og_description)?$og_description:"")}}">
    <meta name="twitter:image" content="{{(isset($og_img)?$og_img:"")}}">
    <meta name="twitter:url" content="{{(isset($og_url)?$og_url:"")}}">

    @include('front.general_components.styles')

<!-- Start of invstochelp Zendesk Widget script -->
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=0f7c548c-b669-4190-a9d6-6906c6dd0a41"> </script>
<!-- End of invstochelp Zendesk Widget script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<body >

<!-- hidden csrf -->

<input type="hidden" class="csrf_input_class" value="{{csrf_token()}}">
<!-- /hidden csrf -->
<!-- hidden base url -->
<input type="hidden" class="url_class" value="<?= url("/") ?>">
<!-- /hidden base url -->

<?php

$msg=\Session::get("msg");

if($msg==""){
    if (count($errors->all()) > 0)
    {
        $dump = "<div class='alert alert-danger'>";
        foreach ($errors->all() as $key => $error)
        {
            $dump .= $error." <br>";
        }
        $dump .= "</div>";

        $msg=$dump;
    }
}

?>

<input type="hidden" class="get_flash_message" value="{!! $msg !!}">


<!--
<div class="header header--logout header--standard-dark" id="site-header">
    <a href="{{url("/")}}" class="logo">
        <div class="img-wrap">
            <?php if(isset($logo_and_icon->logo) && isset($logo_and_icon->logo->path)): ?>
            <img src="{{get_image_or_default($logo_and_icon->logo->path)}}"
                 title="{{$logo_and_icon->logo->title}}"
                 alt="{{$logo_and_icon->logo->alt}}"
            />
            <?php endif; ?>
        </div>
    </a>


    <div class="nav nav-pills nav1 header-menu expanded-menu">
        <div class="mCustomScrollbar">
            <ul>
                <li class="open-responsive-menu js-open-responsive-menu">
                    <svg class="olymp-menu-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-menu-icon"></use></svg>
                </li>
                <li class="nav-item">
                    <a href="{{url("/")}}" class="nav-link">{{show_content($general_static_keywords,"homepage")}}</a>
                </li>


                <?php if(count($static_pages)): ?>
                    <?php foreach($static_pages as $key => $static_page): ?>
                        <li class="nav-item">
                            <a class="nav-link " href="{{url(urlencode($static_page->page_slug))}}" >{{$static_page->page_title}}</a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                  <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" tabindex='1'>
                                    <img src="{{get_image_or_default($current_lang->lang_img_path)}}" width="24" height="24"> {{$current_lang->lang_title}}
                                </a>
                                <div class="dropdown-menu">
                                    <?php foreach($all_langs as $key => $lang_obj): ?>
                                        <?php if($lang_obj->lang_id != $current_lang->lang_id): ?>
                                            <a href="#" class="change_language" data-lang_id="{{$lang_obj->lang_id}}" title="{{$lang_obj->lang_title}}" style="color: #ffffff;">
                                                <img src="{{get_image_or_default($lang_obj->lang_img_path)}}" width="24" height="24"> {{$lang_obj->lang_title}}
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                    </li>

                 <li class="close-responsive-menu js-close-responsive-menu">
                    <svg class="olymp-close-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
                </li>

            </ul>
        </div>
    </div>


</div>
 -->



<!--
header header--logout header--standard-dark
 -->

  <div class=" header--standard header--standard-landing header--standard-landing-intro header--standard--intro" id="header--standard">
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
                                <a href="{{url("/$language_locale")}}" class="nav-link">{{show_content($general_static_keywords,"homepage")}}</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url("/$language_locale/cashback")}}" class="nav-link">{{show_content($user_homepage_keywords,"brokers_link")}}</a>
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
                                            <a class="dropdown-item" href="{{url($language_locale .'/'. urlencode($static_page->page_slug))}}">{{$static_page->page_title}}</a>
                                        <?php endforeach; ?>
                                    </div>
                                </li>

                            <?php endif; ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" tabindex='1'>
                                    <img src="{{get_image_or_default($current_lang->lang_img_path)}}" width="24" height="24"> {{$current_lang->lang_title}}
                                </a>
                                <div class="dropdown-menu">
                                    <?php
                                    foreach($all_langs as $key => $lang_obj): ?>
                                        <?php if($lang_obj->lang_id != $current_lang->lang_id): ?>
                                            <a href="{{ URL::to('/').'/' . strtolower($lang_obj->lang_title) . '/'.  request()->segment(2) . '/' . request()->segment(3) }}"  title="{{$lang_obj->lang_title}}" style="color: #06793c;">
                                                <img src="{{get_image_or_default($lang_obj->lang_img_path)}}" width="24" height="24"> {{$lang_obj->lang_title}}
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </li>

                            <li class="nav-item">
                          <a data-toggle="modal" data-target="#registration-login-form-popup" href="#" class="full-block"> {{show_content($intro_keywords,"login_submit_btn")}}</a>


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
