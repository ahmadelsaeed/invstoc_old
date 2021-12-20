<?php
Route::get('/UUYdfsf_234DSFsfsdf65DFG213123ASasmPiT', 'front\admin_panel@index');
Route::post('/UUYdfsf_234DSFsfsdf65DFG213123ASasmPiT', 'front\admin_panel@try_login');


Route::post('/login', 'front\register@login');
Route::post('/register','front\register@index');
Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => ['languageLocale']
], function() {
    Route::get('/user/posts/{post_type}/{user_id}','front\user_profile@show_user_posts_page');
    Route::post('/user/get_posts/{post_type}/{user_id}','front\user_profile@get_user_posts');
    Route::post("/posts/show_post",'actions\posts\posts@show_post');
    Route::group([
        'middleware' => ['web']
    ], function () {

        Route::get('/test','front\register@test');


    //    Route::get('/move_books','front\books@move_books');

        Route::get('/','front\register@index');
        Route::get('/rss','front\rss@all');


        Route::get('/move_pages_to_translate','front\HomeController@move_pages_to_translate');

        // logout ebn el teeeeeeeeeeeeeeeeeeeeeeeet
        Route::get("/logout","logout@index");

        Route::post('/change_language', 'front\change_language@index');
        Route::post('/change_book_language', 'front\change_book_language@index');


        Route::get('/verify_new_account','front\register@verify_new_account');

        Route::get("/preview/posts/{full_name}/{user_id}/{post_id}",'actions\posts\posts@preview_single_post');


        #region Password Reset Routes...
        $this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
        $this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
        $this->post('password/reset', 'Auth\PasswordController@reset');
        #endregion

        #region change_currency

        Route::post('/change_currency','front\change_currency@index');

        #endregion


        #region subscribe_cron_jop
        Route::get('/subscribe_cron_jop','subscribe_cron_jop@index');
        Route::get('/subscribe_cron_jop/show_email','subscribe_cron_jop@show_email');
        #endregion

        #signals
        Route::get('/signals','front\HomeController@get_signals');

        #end signals


    });

    Route::group(['middleware' => ['web']], function () {

        #region brokers

        Route::get('/cashback','front\brokers@index');
        Route::get('/cashback/{page_id}','front\brokers@get_broker');
        Route::post('/load_broker_currencies','front\brokers@load_broker_currencies');
        Route::post('/calc_broker_trade','front\brokers@calc_broker_trade');

        #endregion

        #region news

        Route::get('/news','front\news@index');
        Route::get('/news/{id}','front\news@view_news');

        #endregion


        #region news

        Route::get('/articles','front\articles@index');
        Route::get('/articles/{id}','front\articles@view_article');
        Route::post('/articles/add_comment','front\articles@add_comment');
        Route::post('/articles/add_article_rate','front\articles@add_rate');


        #endregion


        #region stock_exchange

        Route::get('/economic_calendar','front\stock_exchange@index');
        Route::post('/economic_calendar/events','front\stock_exchange@events');
        Route::post('/economic_calendar/notification','front\stock_exchange@notification');

        #endregion


    });

    Route::group(['middleware' => ['web','check_user']], function () {


        #region stock_exchange

        Route::get('/analytic_page','front\analytic_page@index');

        #endregion


        #region activity and books

        Route::get('/activity/{parent_activity_slug}/{child_activity_slug}','front\books@index');
        Route::post('/load_more_books','front\books@load_more_books');

        Route::get('/books/{book_id}','front\books@view_book');


        #endregion



        #region cash back

        Route::post('/add_new_account_to_cash_back','front\HomeController@add_new_account_to_cash_back');
        Route::post('/get_my_cash_back_accounts','front\HomeController@get_my_cash_back_accounts');
        Route::post('/request_accounts_withdraw','front\HomeController@request_accounts_withdraw');

        #endregion


        #region Get Trending

        Route::post('/get_workshops_trending','front\HomeController@get_workshops_trending');
        Route::post('/get_books_trending','front\HomeController@get_books_trending');
        Route::post('/get_brokers_trending','front\HomeController@get_brokers_trending');
        Route::post('/get_users_trending','front\HomeController@get_users_trending');

        #endregion


        #region add_to_orders_list , load_orders_list_items

        Route::post('/add_to_orders_list','front\HomeController@add_to_orders_list');
        Route::post('/load_orders_list_items','front\HomeController@load_orders_list_items');
        Route::post('/remove_order_list_item','front\HomeController@remove_order_list_item');
        Route::post('/make_orders_list_post','front\HomeController@make_orders_list_post');

        #endregion


        #region support page
        Route::get('/support','front\subscribe_contact@index');
        Route::post('/support','front\subscribe_contact@index');
        Route::post('/subscribe_contact/make_a_contact', 'front\subscribe_contact@make_a_contact');
        #endregion


        #region chat

        Route::get('/messages','front\chat@index');
        Route::get('/messages/{chat_id}','front\chat@chat_messages');
        Route::post('/chat/search_for_users', 'front\chat@search_for_users');
        Route::post('/chat/send_chat_message', 'front\chat@send_chat_message');
        Route::post('/chat/send_chat_message_to_user', 'front\chat@send_chat_message_to_user');
        Route::post('/chat/load_msgs', 'front\chat@load_msgs');

        #endregion





    });

    Route::group(['middleware' => ['web']], function () {

        #region all Pages
        $all_pages=
            \App\models\pages\pages_m::
            select("pages_translate.page_slug")->
            join("pages_translate","pages_translate.page_id","=","pages.page_id")->
            where("pages_translate.page_title","!=","")->
            where("pages_translate.page_slug","!=","")->
            where("page_type","default")->
            where("hide_page","0")->get()->all();

        foreach ($all_pages as $key => $page) {
            Route::get("/".(urlencode($page->page_slug)),'front\pages@show_item');
        }

        #endregion

    });

    Route::group(['middleware' => ['web','check_user']], function () {

        Route::post("/posts/add_post",'actions\posts\posts@add_post');
        Route::post("/posts/delete_post",'actions\posts\posts@delete_post');
        Route::post("/posts/edit_post",'actions\posts\posts@edit_post');
        Route::post("/posts/make_order_not_closed",'actions\posts\posts@make_order_not_closed');

        Route::post("/posts/get_order_closed_price_modal",'actions\posts\posts@get_order_closed_price_modal');
        Route::post("/posts/add_order_closed_price",'actions\posts\posts@add_order_closed_price');

        Route::post("/posts/save_post",'actions\posts\posts@save_post');
        Route::post("/posts/submit_edit_post",'actions\posts\posts@submit_edit_post');
        Route::post("/posts/share_post",'actions\posts\posts@share_post');

        Route::post("/posts/like_post",'actions\posts\likes@like_post');
        Route::post("/posts/like_comment",'actions\posts\likes@like_comment');

        Route::post("/posts/load_post_comments",'actions\posts\comments@load_post_comments');
        Route::post("/posts/add_comment",'actions\posts\comments@add_comment');
        Route::post("/posts/delete_comment",'actions\posts\comments@delete_comment');
        Route::post("/posts/edit_comment",'actions\posts\comments@edit_comment');
        Route::post("/posts/post_edit_comment",'actions\posts\comments@post_edit_comment');
        Route::post("/posts/load_more_comments",'actions\posts\comments@load_more_comments');

        Route::post("/posts/get_post_username_likes",'actions\posts\likes@get_post_username_likes');
        Route::post("/posts/get_post_username_comments",'actions\posts\comments@get_post_username_comments');
        Route::post("/posts/get_post_username_shares",'actions\posts\posts@get_post_username_shares');

        Route::get("/posts/{full_name}/{user_id}/{post_id}",'actions\posts\posts@show_single_post');

        Route::get("/posts/show_hashtag/{hashtag}",'actions\posts\posts@show_hashtag_posts');

        Route::post("/posts/load_hashtag_posts/{hashtag}",'actions\posts\posts@load_hashtag_posts');

    });

    Route::group(['middleware' => ['web','check_user']], function () {

        Route::get('/home','front\HomeController@index');
        Route::post('/load_homepage_posts','front\HomeController@load_homepage_posts');

        Route::get('/load_saved_posts','front\HomeController@saved_posts');
        Route::post('/load_saved_posts','front\HomeController@load_saved_posts');


        #region show and edit information

        Route::get('/information/{user_id}','front\user_profile@personalInfo');

        Route::post('/information/{user_id}/edit_cover_img','front\user_profile@editCoverImage');
        Route::post('/information/{user_id}/edit_profile_img','front\user_profile@editProfileImage');
        Route::post('/information/{user_id}/update_personal_info','front\user_profile@updatePersonalInfo');
        Route::post('/information/{user_id}/update_work_info','front\user_profile@updateWorkInfo');
        Route::post('/information/{user_id}/update_contacts_info','front\user_profile@updateContactsInfo');
        Route::post('/information/{user_id}/update_password','front\user_profile@updatePassword');
        Route::post('/information/{user_id}/request_privet_account','front\user_profile@requestPrivetAccount');
        Route::post('/information/change_timezone','front\user_profile@changeTimezone');

        Route::get('/advanced_search','front\user_profile@advancedSearch');

        Route::get('/request_verification','front\user_profile@requestVerification');

        Route::get('/request_referrer_link','front\user_profile@request_referrer_link');
        Route::post('/request_referrer_link','front\user_profile@request_referrer_link');

        Route::get('/change_password','front\user_profile@changePassword');
        Route::post('/change_password','front\user_profile@changePassword');

        Route::get('/referrer_link','front\user_profile@add_accounts_under_referrer_link');
        Route::post('/referrer_link','front\user_profile@add_accounts_under_referrer_link');

        Route::get('/report/user/{user_id}','front\user_profile@user_report');


        #endregion


        #region follow and un follow

        Route::post('/follow_user','front\user_profile@followUser');
        Route::post('/unfollow_user','front\user_profile@unFollowUser');

        #endregion


        #region show friends

        Route::get('/friends/{user_id}','front\user_profile@showFriends');

        #endregion

        #region show media images

        Route::get('/media/{user_id}','front\user_profile@showMedia');

        #endregion

        #region get user posts
        Route::post('/user/get_matched_posts/{post_type}/{user_id}','front\user_profile@get_matched_user_posts');
        #endregion

        #region notification

        Route::get('/get_all_user_notifications','front\notification@get_all_user_notifications');

        #endregion

    });
});
