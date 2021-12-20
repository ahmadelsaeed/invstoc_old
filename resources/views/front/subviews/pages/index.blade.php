
<section class="blog-post-wrap">
    <div class="container">
        <div class="row">

    <div class="col col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

        <div class="title-block " style="text-align: center;">
           <p><h3 class="logo-title"> {{$page_data->page_title}} </h3> </p>
            {!! $page_data->page_short_desc !!}
        </div>

    <?php

        $text_styles = "text-align: right;direction:rtl;";
        if ($current_lang->lang_direction == "ltr")
        {
            $text_styles = "text-align: left;direction:ltr;";
        }

    ?>

    <?php if(!empty($page_data->big_img_path)): ?>
        <div class="col-md-12" style="text-align: center;">
            <img src="{{get_image_or_default($page_data->big_img_path)}}" style="height: 280px;margin-bottom: 10px;">
        </div>
    <?php endif; ?>

    <div class="col-md-12" style="{{$text_styles}}">
        {!! $page_data->page_body !!}
    </div>

    </div>


        </div>
    </div>

</section>

