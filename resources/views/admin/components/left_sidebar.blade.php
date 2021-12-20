<div class="">
    <!--logo and iconic logo end-->
    <div class="left-side-inner">

        <!--sidebar nav start-->
        <ul class="nav nav-pills nav-stacked custom-nav">
            <li class="active"><a href="{{url('/admin/dashboard')}}"><i class="lnr lnr-power-switch"></i><span>Dashboard</span></a>
            </li>

            <?php if(check_permission($user_permissions,"admin/category","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-leaf"></i>
                    <span>Activities (المجالات)</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/category/activity')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/category","add_action")): ?>
                    <li><a href="{{url('/admin/category/save_cat/activity')}}">Add New Activity</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/category","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-book"></i>
                    <span>Books</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/category/book')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/category","add_action")): ?>
                    <li><a href="{{url('/admin/category/save_cat/book')}}">Add New Book</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            {{--companies--}}
            <?php if(check_permission($user_permissions,"admin/pages","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-bookmark"></i>
                    <span>Brokers</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/pages/show_all/company')}}">Show All Brokers</a></li>
                    <?php if(check_permission($user_permissions,"admin/pages","add_action")): ?>
                        <li><a href="{{url('/admin/pages/save_page/company')}}">Add New Broker</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/stock_exchange","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-chart-bars"></i>
                    <span>Stock Exchange</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/pages/show_all/stock_exchange')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/pages","add_action")): ?>
                        <li><a href="{{url('/admin/pages/save_page/stock_exchange')}}">Add New</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>


            <?php if(check_permission($user_permissions,"admin/pages","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-bookmark"></i>
                    <span>News</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/pages/show_all/news')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/pages","add_action")): ?>
                    <li><a href="{{url('/admin/pages/save_page/news')}}">Add New</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/pages","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-bookmark"></i>
                    <span>Articles</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/pages/show_all/article')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/pages","add_action")): ?>
                    <li><a href="{{url('/admin/pages/save_page/article')}}">Add New</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/pages","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-bookmark"></i>
                    <span>Static Pages</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/pages/show_all')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/pages","add_action")): ?>
                    <li><a href="{{url('/admin/pages/save_page')}}">Add New Static Page</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/pair_currency","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-star"></i>
                    <span>Pair of Currencies</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/pair_currency')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/pair_currency","add_action")): ?>
                    <li><a href="{{url('/admin/pair_currency/save_pair_currency')}}">Add New</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/ads","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-flag"></i>
                    <span>Advertisements</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/ads')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/ads","add_action")): ?>
                    <li><a href="{{url('/admin/ads/save_ad')}}">Add New</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/edit_content","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-cog"></i>
                    <span>Edit Content</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/show_methods')}}">Show All Edit Content Links</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/subscribe","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-list"></i>
                    <span>Subscribers</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/subscribe')}}">Show All Subscribers</a></li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if(check_permission($user_permissions,"admin/workshops","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-flag"></i>
                    <span>Workshops</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/workshops')}}">Show All</a></li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if(check_permission($user_permissions,"admin/groups","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-flag"></i>
                    <span>Groups</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/groups')}}">Show All</a></li>
                </ul>
            </li>
            <?php endif; ?>


            <?php if(check_permission($user_permissions,"admin/support_messages","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-list"></i>
                    <span>Requests</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/support_messages/support')}}">Show Support Requests</a></li>
                    <li><a href="{{url('/admin/support_messages/request_privet_account')}}">Show Verification Requests</a></li>
                    <li><a href="{{url('/admin/support_messages/request_referrer_link')}}">Show Referrer Requests</a></li>
                    <li><a href="{{url('/admin/support_messages/withdraw_request')}}">Show Withdraw Accounts Requests</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/uploader","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-upload"></i>
                    <span>Site Files Uploader</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/uploader')}}">Uploader</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/admins","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-users"></i>
                    <span>Site Admins</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/users/get_all_admins')}}">Show All Admins</a></li>
                    <li><a href="{{url('/admin/users/save')}}">Add New Admin</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/langs","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-book"></i>
                    <span>Languages</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/langs')}}">Show All Languages</a></li>
                    <?php if(check_permission($user_permissions,"admin/langs","edit_action")): ?>
                    <li><a href="{{url('admin/langs/save_lang')}}">Add New Language</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/settings","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-flag"></i>
                    <span>Settings</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/settings')}}">Manage</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/users","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-users"></i>
                    <span>Manage Users</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/users/get_all_users')}}">Show Users</a></li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if(check_permission($user_permissions,"admin/currencies","show_action")): ?>
            <li class="menu-list">
                <a>
                    <i class="lnr lnr-star"></i>
                    <span>Currencies</span>
                </a>
                <ul class="sub-menu-list">
                    <li><a href="{{url('/admin/currencies')}}">Show All</a></li>
                    <?php if(check_permission($user_permissions,"admin/currencies","add_action")): ?>
                    <li><a href="{{url('/admin/currencies/save_currency')}}">Add New</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

        </ul>
        <!--sidebar nav end-->
    </div>
</div>