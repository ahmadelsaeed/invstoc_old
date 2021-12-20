
<div id="jssor_slider_{{$post_data->post_id}}" style="position: relative; width: 500px;
        height: 480px; overflow: hidden;">

    <!-- Loading Screen -->
    <div u="loading" style="position: absolute; top: 0px; left: 0px;">
        <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
                background-color: #000; top: 0px; left: 0px;width: 100%;height:100%;">
        </div>
        <div style="position: absolute; display: block; background: url({{url("public_html/front/img/loading.gif")}}) no-repeat center center;
                top: 0px; left: 0px;width: 100%;height:100%;">
        </div>
    </div>

    <!-- Slides Container -->
    <div u="slides" style="position: absolute; left: 0px; top: 0px; width: 500px; height: 480px;
            overflow: hidden;">

        <?php foreach ($post_data->imgs_data as $key => $img): ?>
            <div>
                <img u="image" src="{{get_image_or_default($img->path)}}" />
                <img u="thumb" src="{{get_image_or_default($img->path)}}" />
            </div>
        <?php endforeach; ?>

    </div>
    <!--#region Thumbnail Navigator Skin Begin -->
    <!-- thumbnail navigator container -->
    <div u="thumbnavigator" class="jssort07" style="width: 500px; height: 100px; left: 0px; bottom: 0px;">
        <!-- Thumbnail Item Skin Begin -->
        <div u="slides" style="cursor: default;">
            <div u="prototype" class="p">
                <div u="thumbnailtemplate" class="i"></div>
                <div class="o"></div>
            </div>
        </div>
        <!-- Thumbnail Item Skin End -->
        <!--#region Arrow Navigator Skin Begin -->
        <!-- Arrow Left -->
        <span u="arrowleft" class="jssora11l" style="top: 123px; left: 8px;">
            </span>
        <!-- Arrow Right -->
        <span u="arrowright" class="jssora11r" style="top: 123px; right: 8px;">
            </span>
        <!--#endregion Arrow Navigator Skin End -->
    </div>
    <!--#endregion Thumbnail Navigator Skin End -->

</div>

