<li>
    <h4 class="text-center">{{show_content($user_homepage_keywords,"your_groups")}}</h4>
</li>
<?php foreach ($all_groups as $key => $group_obj): ?>
    @include("front.subviews.groups_workshops.group_li")
<?php endforeach; ?>
