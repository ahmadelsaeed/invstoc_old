<!DOCTYPE HTML>
<html>
<head>
    <title>@if(isset($meta_title)) {{$meta_title}} @endif</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <script type="application/x-javascript"> addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);
        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- Bootstrap Core CSS -->
    <link href="{{url('/public_html/admin')}}/css/bootstrap.min.css" rel='stylesheet' type='text/css'/>
    <!-- Custom CSS -->
    <link href="{{url('/public_html/admin')}}/css/style.css" rel='stylesheet' type='text/css'/>
    <!-- Graph CSS -->
    <link href="{{url('/public_html/admin')}}/css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <!-- lined-icons -->
    <link rel="stylesheet" href="{{url('/public_html/admin')}}/css/icon-font.min.css" type='text/css'/>
    <!-- //lined-icons -->

    <!--animate-->
    <link href="{{url('/public_html/admin')}}/css/animate.css" rel="stylesheet" type="text/css" media="all">

    <!--//end-animate-->
    <!----webfonts--->
    <link href='//fonts.googleapis.com/css?family=Cabin:400,400italic,500,500italic,600,600italic,700,700italic'
          rel='stylesheet' type='text/css'>
    <!---//webfonts--->
    <!-- Meters graphs -->
    {{--<script src="js/jquery-1.10.2.min.js"></script>--}}


    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <!-- Placed js at the end of the document so the pages load faster -->
    <script src="<?= url('public_html/admin/js/moment.js') ?>"></script>
    <script src="<?= url('public_html/admin/js/bootstrap-datetimepicker.js') ?>"></script>
    <link href="<?= url('public_html/admin/css/bootstrap-datetimepicker.css') ?>" rel="stylesheet" type="text/css">



    <!--datatable css-->
    <link href="<?= url('public_html/admin/js/datatables/css/jquery.dataTables.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="<?= url('public_html/admin/js/datatables/css/dataTables.bootstrap.min.css') ?>" rel="stylesheet" type="text/css">
    <!--datatable js-->
    <script src="<?= url('public_html/admin/js/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= url('public_html/admin/js/datatables/js/dataTables.bootstrap.min.js') ?>"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>



    <script src="<?= url('public_html/jscode/config.js') ?>"></script>
    <script src="<?= url('public_html/jscode/admin/admin.js') ?>"></script>
    <script src="<?= url('public_html/jscode/admin/utility.js') ?>"></script>



    <style>
        .rtl .cke_toolbar {
            float: right !important;
        }
        .ltr .cke_toolbar {
            float: left !important;
        }
    </style>


</head>

<body class="sticky-header left-side-collapsed">


    <!-- hidden csrf -->

    <input type="hidden" class="csrf_input_class" value="{{csrf_token()}}">
    <!-- /hidden csrf -->
    <!-- hidden base url -->
    <input type="hidden" class="url_class" value="<?= url("/") ?>">
    <!-- /hidden base url -->

    <?php if(isset($disable_hide_input)): ?>
    <input type="hidden" id="disable_hide_input_id">
    <?php endif; ?>

    <section>

        <div class="fixed_header">

            <!-- header-starts -->
            @include('admin.components.header')
            <!-- //header-ends -->

            <!-- left side start-->
            @include('admin.components.left_sidebar')
            <!-- left side end-->
        </div>


        <!-- main content start-->
        <div class="main-content">

            <div id="page-wrapper">
                {!! \Session::get("msg") !!}



                @yield("subview")

            </div>

        </div>
        <!--footer section start-->
        <footer>
            <p>&copy 2017 SEOERA. All Rights Reserved | Design by
                <a href="http://www.seoera.net/" target="_blank">SEOERA.</a></p>
        </footer>
        <!--footer section end-->

        <!-- main content end-->
    </section>

    <!--
    <script src="{{url('/public_html/admin')}}/js/jquery.nicescroll.js"></script>
    -->

    <script src="{{url('/public_html/admin')}}/js/scripts.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{url('/public_html/admin')}}/js/bootstrap.min.js"></script>


</body>
</html>