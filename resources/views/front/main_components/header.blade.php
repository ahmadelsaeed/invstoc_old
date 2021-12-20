<!DOCTYPE html>
<html lang="en">
<head prefix="og: http://ogp.me/ns#" >
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="" content="" />

    <meta name="keywords" content="{{$meta_keywords}}" />
    <meta name="description" content="{{$meta_desc}}" />
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="{{ $meta_title }}">
    <meta property="og:image" content="{{ isset($og_image) ? $og_image : url('/') . '/public_html/front/new_design/img/logo.png' }}">
    <meta property="og:description" content="{{$meta_desc}}">
    <meta property="og:url" content="{{ url()->current() }}">
    <title>{{$meta_title}}</title>
    @yield('meta')


    <?php if(isset($logo_and_icon->icon) && isset($logo_and_icon->icon->path)): ?>
        <link rel="shortcut icon" href="{{get_image_or_default($logo_and_icon->icon->path)}}" type="image/x-icon">
    <?php endif; ?>


    @include('front.general_components.styles')

    <!-- Stylesheets
    ================================================= -->
    <?php if($current_lang->lang_direction == "rtl"): ?>
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/extend_style.css" />

    <!-- emotions -->
    <link href="{{url('/public_html/front/css/emotions.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/profile.css" />

    <?php else: ?>

    <link rel="stylesheet" href="{{url("public_html/front/css")}}/extend_style-ltr.css" />
    <!-- emotions -->
    <link href="{{url('/public_html/front/css/emotions-ltr.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/profile-ltr.css" />

    <?php endif; ?>

    <link rel="stylesheet" href="{{url("public_html/front/css")}}/jssor_slider_style.css" />


    <link href="{{url('/public_html/front/')}}/css/add_post.css" rel='stylesheet' type='text/css'/>
    <link href="{{url('/public_html/front/')}}/css/timeline.css" rel='stylesheet' type='text/css'/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <!--datatable css-->
    <link href="<?= url('public_html/admin/js/datatables/css/jquery.dataTables.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= url('public_html/admin/js/datatables/css/dataTables.bootstrap.min.css') ?>" rel="stylesheet" type="text/css">
    <!--datatable js-->
    <script src="<?= url('public_html/admin/js/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= url('public_html/admin/js/datatables/js/dataTables.bootstrap.min.js') ?>"></script>


    <script src="<?= url('public_html/front/js/jssor.slider.min.js') ?>"></script>

    <script src="<?= url('public_html/jscode/homepage.js') ?>"></script>
    <script src="<?= url('public_html/jscode/chat.js') ?>"></script>
    <script src="{{url("/public_html/jscode/actions/add_post.js")}}"></script>
    <script src="{{url("/public_html/jscode/actions/posts_utility.js")}}"></script>
    <script src="{{url("/public_html/jscode/actions/post_actions.js")}}"></script>
    <script src="{{url("/public_html/jscode/load_posts_in_homepage.js")}}"></script>
    <script src="{{url("/public_html/jscode/notifications_messages.js")}}"></script>
    <script src="{{url("/public_html/jscode/groups_workshops.js")}}"></script>
    <script src="{{url("/public_html/jscode/article.js")}}"></script>

 <!--  Rate  -->
    <link href="<?= url('public_html/rate/starrr.css') ?>" rel="stylesheet" type="text/css">
    <script src="{{url("/public_html/rate/starrr.js")}}"></script>


</head>
<body>

    @include("front.general_components.pre_loader")

    <!-- hidden csrf -->

    <input type="hidden" class="csrf_input_class" value="{{csrf_token()}}">
    <!-- /hidden csrf -->
    <!-- hidden base url -->
    <input type="hidden" class="url_class" value="<?= url("/$language_locale") ?>">
    <input type="hidden" class="url_class_2" value="<?= url("/") ?>">
    <!-- /hidden base url -->

    <input type="hidden" class="current_datetime" value="{{date("Y-m-d H:i:s")}}">

    <input type="hidden" class="this_user_id" value="{{is_object($current_user)?$current_user->user_id:"0"}}">


    <?php

    if (empty($msg))
    {
        $msg=\Session::get("msg");
    }

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

    <!-- Header================================================= -->
    <header id="header" style="height: 65px;">
        @include("front.main_components.page_header")
    </header>
    <!--Header End-->
