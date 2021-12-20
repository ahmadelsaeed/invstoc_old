<div class="ui-block" data-mh="friend-groups-item">

    <!-- Friend Item -->

    <div class="friend-item friend-groups">

        <div class="friend-item-content">
            <div class="friend-avatar">
                <div class="author-thumb">
                    <a href="{{url("workshop/$workshop->workshop_name/$workshop->workshop_id")}}">
                        <img src="{{get_image_or_default($workshop->path)}}" title="{{$workshop->workshop_name}}"
                             alt="{{$workshop->workshop_name}}" style="width: 130px; height: 130px">
                    </a>
                </div>
                <div class="author-content">

                    <a class="h5 author-name" href="{{url("workshop/$workshop->workshop_name/$workshop->workshop_id")}}" target="_blank">
                        {!! split_word_into_chars_ar_without_more_link(
                            $workshop->workshop_name,20,
                            " ...") !!}
                        <br>
                        {{$workshop->workshop_visits}} <i class="fa fa-eye"></i>
                    </a>

                </div>
            </div>

        </div>
    </div>

    <!-- ... end Friend Item -->
</div>

