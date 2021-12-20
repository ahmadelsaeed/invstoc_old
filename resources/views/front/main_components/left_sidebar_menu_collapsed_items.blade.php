<li class="hot_orders_div">
    <a href="#" class="hot_orders_btn" data-toggle="modal" data-target="#hot_orders_modal">
        <i class="fa fa-fire" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"hot_orders_header")}}
    </a>
</li>

<li class="orders_list_div">
    <a href="#" class="orders_list_btn" data-toggle="modal" data-target="#orders_list_modal">
        <i class="fa fa-shopping-bag" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"your_orders_list")}}
    </a>
</li>

<li class="show_comming_events_div">
    <a href="#" class="show_comming_events" data-toggle="modal" data-target="#show_comming_events_modal">
        <i class="fa fa-clock-o" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"events_notification_header")}}
    </a>
</li>

<li class="load_groups_workshops_div">
    <a href="#" class="load_groups_workshops_btn" data-toggle="modal" data-target="#load_groups_workshops_modal">
        <i class="fa fa-users" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"create_new_workshop_group_header")}}
    </a>
</li>

<li class="get_my_cash_back_accounts_div">
    <a href="#" class="get_my_cash_back_accounts" data-toggle="modal" data-target="#get_my_cash_back_accounts_modal">
        <i class="fa fa-briefcase" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"cashback_header")}}
    </a>
</li>

<?php if(isset($banks->slider1) && isset($banks->slider1->imgs) && count($banks->slider1->imgs)): ?>
<li class="get_banks_div">
    <a href="#" class="get_banks_btn" data-toggle="modal" data-target="#get_banks_modal">
        <i class="fa fa-dollar" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"bank_accounts_header")}}
    </a>
</li>
<?php endif; ?>


<li class="workshops_trending_div">
    <a href="#" class="workshops_trending_hover" data-toggle="modal" data-target="#workshops_trending_modal">
        <i class="fa fa-building" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"workshops_trending_header")}}
    </a>
</li>

<li class="books_trending_div">
    <a href="#" class="books_trending_hover" data-toggle="modal" data-target="#books_trending_modal">
        <i class="fa fa-book" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"books_trending_header")}}
    </a>
</li>

<?php if($settings->show_brokers_trending): ?>
<li class="brokers_trending_div">
    <a href="#" class="brokers_trending_hover" data-toggle="modal" data-target="#brokers_trending_modal">
        <i class="fa fa-user-secret" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"brokers_trending")}}
    </a>
</li>
<?php endif; ?>

<li class="users_trending_div">
    <a href="#" class="users_trending_hover" data-toggle="modal" data-target="#users_trending_modal">
        <i class="fa fa-user" aria-hidden="true"></i> &nbsp;&nbsp;
        {{show_content($user_homepage_keywords,"profile_trending_header")}}
    </a>
</li>
