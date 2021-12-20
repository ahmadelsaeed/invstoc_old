
    <div class="ui-block" data-mh="friend-groups-item" style="">

        <!-- Friend Item -->

        <div class="friend-item friend-groups">

            <div class="friend-item-content" style="padding: 11px;">
                <div class="friend-avatar">
                    <div class="author-thumb broker_block_div" style="width: 100px; height: 100px">
                        <a href="{{url("$language_locale/cashback/$broker->page_slug")}}">
                            <img src="{{get_image_or_default($broker->small_img_path)}}"
                                 title="{{$broker->small_img_title}}" alt="{{$broker->small_img_alt}}" style="width: 100px; height: 100px">
                        </a>
                    </div>
                    <div class="author-content">

                        <a class="h5 author-name" href="{{url("$language_locale/cashback/$broker->page_slug")}}" target="_blank">
                            <span style="color: #278d47; font-size: 30px;">$ {{floatval($broker->page_price)}}</span><br>
                            {{show_content($brokers_keywords,"per_lot_label")}}
                        </a>

                    </div>
                </div>

            </div>
        </div>

        <!-- ... end Friend Item -->
    </div>

