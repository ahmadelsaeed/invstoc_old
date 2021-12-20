<?php if(count($activities)): ?>
<div class="ui-block">

    <div class="ui-block-title">
        <h6 class="title">
            <i class="fa fa-book"></i>
            {{show_content($user_homepage_keywords,"books_trending_header")}}
        </h6>
    </div>

    <div class="accordion" id="accordionExample">

        <?php foreach($activities as $key => $cat): ?>
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse"
                            data-target="#collapse_{{$key}}" aria-expanded="true" aria-controls="collapseOne"
                            style="background-color: #37793c; width: 100%; margin-bottom: 0px">
                        {{$cat->cat_name}} &nbsp;
                        <i class="fa fa-plus"></i>

                    </button>
                </h2>
            </div>

            <div id="collapse_{{$key}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <?php if(isset($cat->childs) && count($cat->childs)): ?>
                    <ul class="widget w-friend-pages-added notification-list friend-requests">
                        <?php foreach($cat->childs as $key2 => $child): ?>
                        <li class="inline-items" style="padding: 10px">
                            <a  class="green_color"  href="{{url("activity/".urlencode($cat->cat_slug)."/".urlencode($child->cat_slug))}}">{{$child->cat_name}}</a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

</div>
<?php endif; ?>
