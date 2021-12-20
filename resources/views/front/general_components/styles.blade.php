<!-- {{asset('public_html/front/new_design/Bootstrap/dist/css/bootstrap-reboot.css')}} -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{url("/")}}/public_html/front/new_design/Bootstrap/dist/css/bootstrap-reboot.css">
    <link rel="stylesheet" type="text/css" href="{{url("/")}}/public_html/front/new_design/Bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{url("/")}}/public_html/front/new_design/Bootstrap/dist/css/bootstrap-grid.css">

    <?php if($current_lang->lang_direction == "rtl"): ?>
    <link rel="stylesheet" type="text/css" href="{{url("/")}}/public_html/front/new_design/Bootstrap/dist/css/bootstrap-rtl.css">
    <link rel="stylesheet" type="text/css" href="{{url("/")}}/public_html/front/new_design/css/main-rtl.css">
    <?php else: ?>
    <link rel="stylesheet" type="text/css" href="{{url("/")}}/public_html/front/new_design/css/main.css">
    <?php endif; ?>


    <!-- Main Styles CSS -->



    <link rel="stylesheet" type="text/css" href="{{url("/")}}/public_html/front/new_design/css/fonts.min.css">



    <!-- Main Font -->
    <script src="{{url("/")}}/public_html/front/new_design/js/webfontloader.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ['Roboto:300,400,500,700:latin']
            }
        });
    </script>

    <script src="{{url("/")}}/public_html/front/new_design/js/jquery-3.2.1.js"></script>

    <!-- end new design  -->


    <!-- star rate  -->
    <link href="{{url('/public_html/rate')}}/starrr.css" rel="stylesheet">
    <script src="{{url('/public_html/rate')}}/starrr.js"></script>
    <!-- end star rate -->

    <!-- Stylesheets
    ================================================= -->
    <!--
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/lightview.css" />
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/bootstra-ar.css" />
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/style.css" />
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/screen.css" />
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/ionicons.min.css" />
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/fonts/font.css" />
    -->
    <link rel="stylesheet" href="{{url("public_html/front/css")}}/font-awesome.min.css" />

    <!--Google Font-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i" rel="stylesheet">

    <!--Favicon-->
    <!--<link rel="shortcut icon" type="image/png" href="images/fav.png"/>-->



    <!--
    <script src="{{url("public_html/front")}}/js/jquery-3.1.1.min.js"></script>
    <script src="{{url("public_html/front")}}/js/bootstrap.min.js"></script>
    <script src="{{url("public_html/front")}}/js/jquery.appear.min.js"></script>
    <script src="{{url("public_html/front")}}/js/jquery.incremental-counter.js"></script>
    <script src="{{url("public_html/front")}}/js/script.js"></script>

    <script>

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })




    </script>

    <script>
        $(window).bind('scroll', function() {
            if ($(window).scrollTop() > 100) {
                $('.banner-main').addClass('fixed_mm');

            }
            else {
                $('.banner-main').removeClass('fixed_mm');

            }
        });

    </script>

    -->

    <!-- Toastr -->
    <link href="{{url('/public_html/toastr')}}/toastr.css" rel="stylesheet">
    <script src="{{url('/public_html/toastr')}}/toastr.js"></script>

    <script src="https://www.invstoc.com/public_html/front/js/emoji/emojione.min.js"></script>
    <script src="https://www.invstoc.com/public_html/front/js/emotions.js"></script>
    <script src="<?= url('public_html/jscode/config.js') ?>"></script>
    <script src="<?= url('public_html/jscode/admin/utility.js') ?>"></script>


<!--  start external code  -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-138374830-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-138374830-1');
</script>

<meta name="google-site-verification" content="JibWIM--FDEhP0uEpfbHsh25FXSaWRDtK2pLhWOVDng" />
<meta name="p:domain_verify" content="75266abcb5c4e674be3c3c1159235980"/>
<meta name="msvalidate.01" content="FB5A1C8F03FB18310C72BA53C74A2CDA" />
<meta name="yandex-verification" content="d73e8f6882f25eeb" />
<meta name="RSS" content="https://www.invstoc.com/rss">
<!--  End external code  -->
