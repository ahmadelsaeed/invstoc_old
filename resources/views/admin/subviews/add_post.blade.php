{{--front style this is for test add post--}}
<!-- Stylesheets ================================================= -->
<link rel="stylesheet" href="{{url("/public_html/front")}}/css/lightview.css" />
<link rel="stylesheet" href="{{url("/public_html/front")}}/css/bootstra-ar.css" />
<link rel="stylesheet" href="{{url("/public_html/front")}}/css/style.css" />
<link rel="stylesheet" href="{{url("/public_html/front")}}/css/screen.css" />
<link rel="stylesheet" href="{{url("/public_html/front")}}/css/ionicons.min.css" />
<link rel="stylesheet" href="{{url("/public_html/front")}}/css/font-awesome.min.css" />
<link rel="stylesheet" href="{{url("/public_html/front")}}/css/fonts/font.css" />

<!--Google Font-->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i" rel="stylesheet">

<div class="col-md-12">


    <div class="panel panel-info">
        <div class="panel-heading">Add Post</div>
        <div class="panel-body">

            @include("actions.posts.add_post")

        </div>
    </div>

</div>
