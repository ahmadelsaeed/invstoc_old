<!DOCTYPE html>
<html lang="en">
<head prefix="og: http://ogp.me/ns#" >


    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="{{$meta_keywords}}" />
    <meta name="description" content="{{$meta_desc}}" />
    <meta name="robots" content="index, follow" />
    <title>{{$meta_title}}</title>

    <?php if(isset($logo_and_icon->icon) && isset($logo_and_icon->icon->path)): ?>
    <link rel="shortcut icon" href="{{get_image_or_default($logo_and_icon->icon->path)}}" type="image/x-icon">
    <?php endif; ?>

    <meta property="og:image" content="{{(isset($og_img)?$og_img:"")}}"/>
    <meta property="og:url" content="{{(isset($og_url)?$og_url:"")}}"/>
    <meta property="og:title" content="{{(isset($og_title)?$og_title:"")}}"/>
    <meta property="og::description" content="{{(isset($og_description)?$og_description:"")}}"/>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{(isset($og_title)?$og_title:"")}}">
    <meta name="twitter:description" content="{{(isset($og_description)?$og_description:"")}}">
    <meta name="twitter:text:description" content="{{(isset($og_description)?$og_description:"")}}">
    <meta name="twitter:image" content="{{(isset($og_img)?$og_img:"")}}">
    <meta name="twitter:url" content="{{(isset($og_url)?$og_url:"")}}">

    @include('front.general_components.styles')

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="landing-page" >

<!-- hidden csrf -->

<input type="hidden" class="csrf_input_class" value="{{csrf_token()}}">
<!-- /hidden csrf -->
<!-- hidden base url -->
<input type="hidden" class="url_class" value="<?= url("/") .'/'. $language_locale ?>">
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

@yield('subview')



@include('front.general_components.scripts')

</body>


</html>
