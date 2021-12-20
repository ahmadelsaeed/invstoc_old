
<div class=" " data-mh="friend-groups-item" style="width: 100%">

    <!-- Friend Item -->

    <div class="friend-item friend-groups">

        <div class="" style="text-align: center;">
            <div class="friend-avatar">
                <div class=" broker_block_div" style="margin-bottom: 20px">
                    <a href="{{url("books/$book->cat_slug")}}">
                        <img src="{{get_image_or_default($book->small_img_path)}}" title="{{$book->cat_name}}"
                              alt="{{$book->cat_name}}" style="width: 120px; ">
                    </a>
                </div>
                <div class="author-content">
                    <a class="h5 author-name" href="{{url("books/$book->cat_slug")}}" >

                        {!! split_word_into_chars_ar_without_more_link(
                            $book->cat_name,20,
                            " ...") !!}
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- ... end Friend Item -->
</div>

