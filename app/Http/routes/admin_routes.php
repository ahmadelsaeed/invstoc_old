<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => 'check_admin'], function () {


    // Start Admin Category Routing
    Route::get('/admin/category/save_cat/{cat_type?}','admin\category@save_cat');
    Route::get('/admin/category/save_cat/{cat_type}/{cat_id?}','admin\category@save_cat');
    Route::post('/admin/category/save_cat/{cat_type}/{cat_id?}','admin\category@save_cat');
    Route::post('/admin/category/check_validation_for_save_cat/{cat_id?}','admin\category@check_validation_for_save_cat');
    Route::post('/admin/category/delete_cat','admin\category@delete_cat');

    Route::get('/admin/category/{cat_type?}/{parent_id?}','admin\category@index')->where('parent_id', '[0-9]+');


    // End Admin Category Routing


    #region books

    Route::get('/admin/books/pages/{book_id}','admin\books@index');
    Route::get('/admin/books/save_book_page/{book_id}/{page_id?}','admin\books@save_book_page');
    Route::post('/admin/books/save_book_page/{book_id}/{page_id?}','admin\books@save_book_page');

    Route::post('/admin/books/remove_book_page','admin\books@remove_book_page');

    Route::post('/admin/books/show_book_pages','admin\books@show_book_pages');


    #endregion

    //pages

    Route::get('/admin/pages/save_page/{page_type?}/{page_id?}','admin\pages@save_page');
    Route::post('/admin/pages/save_page/{page_type?}/{page_id?}','admin\pages@save_page');
    Route::post('/admin/pages/check_validation_for_save_page/{page_id?}','admin\pages@check_validation_for_save_page');
    Route::post('/admin/pages/remove_page','admin\pages@remove_page');

    Route::get('/admin/pages/show_all/{page_type?}/{cat_id?}','admin\pages@index');

    Route::post('/admin/pages/search_for_page_name','admin\pages@search_for_page_name');

    Route::get('/admin/company/users_accounts/{page_id}','admin\pages@users_accounts');

    Route::post('/admin/pages/save_book_page','admin\pages@save_book_page');
    Route::post('/admin/pages/load_book_pages','admin\pages@load_book_pages');
    Route::post('/admin/pages/load_book_pages_li','admin\pages@load_book_pages_li');
    Route::post('/admin/pages/load_page_translates','admin\pages@load_page_translates');
    Route::post('/admin/pages/order_pages_trans','admin\pages@order_pages_trans');

    Route::post('/admin/pages/show_all/stock_exchange','admin\pages@index');

    //END pages


    //edit_content
    Route::get('/admin/show_methods','admin\edit_content@show_methods');
    Route::get('admin/edit_content/{lang_id}/{slug}','admin\edit_content@check_function');
    Route::post('admin/edit_content/{lang_id}/{slug}','admin\edit_content@check_function');
    //END edit_content


    Route::get('admin/dashboard', 'admin\dashboard@index');

    // Start notifications

    Route::get('/admin/notifications/show_all','admin\notifications@index');
    Route::post('/admin/notifications/delete_notification','admin\notifications@delete_notification');

    // End notifications


    // Start Admin Langs Routing

    Route::get('/admin/langs','admin\langs@index');
    Route::get('/admin/langs/save_lang/{lang_id?}','admin\langs@save_lang');
    Route::post('/admin/langs/save_lang/{lang_id?}','admin\langs@save_lang');
    Route::post('/admin/langs/delete_lang','admin\langs@delete_lang');

    // End Admin Langs Routing


    // Start Admin currencies Routing

    Route::get('/admin/currencies','admin\currencies@index');
    Route::get('/admin/currencies/save_currency/{id?}','admin\currencies@save_currency');
    Route::post('/admin/currencies/save_currency/{id?}','admin\currencies@save_currency');
    Route::post('/admin/currencies/delete_currency','admin\currencies@delete_currency');

    // End Admin currencies Routing

    // Start Admin pair_currency Routing

    Route::get('/admin/pair_currency','admin\pair_currency@index');
    Route::get('/admin/pair_currency/save_pair_currency/{id?}','admin\pair_currency@save_pair_currency');
    Route::post('/admin/pair_currency/save_pair_currency/{id?}','admin\pair_currency@save_pair_currency');
    Route::post('/admin/pair_currency/delete_pair_currency','admin\pair_currency@delete_pair_currency');

    // End Admin pair_currency Routing


    // Start Admin users Routing

    Route::get('admin/users/get_all_admins', 'admin\users@get_all_admins');
    Route::get('admin/users/get_all_messages/{user_id}', 'admin\users@get_all_messages')->name('admin.messages.user');
    Route::get('admin/users/get_all_users', 'admin\users@get_all_users');
    Route::get('admin/users/get_all_users/search', 'admin\users@search')->name('admin.user.search');
    Route::post('admin/users/change_user_can_login', 'admin\users@change_user_can_login');
    Route::post('admin/users/is_privet_account', 'admin\users@is_privet_account');
    Route::post('admin/users/has_referrer_link', 'admin\users@has_referrer_link');

    Route::get('admin/users/save/{user_id?}', 'admin\users@save_user');
    Route::post('admin/users/save/{user_id?}', 'admin\users@save_user');

    Route::get('admin/users/assign_permission/{user_id}', 'admin\users@assign_permission');
    Route::post('admin/users/assign_permission/{user_id}', 'admin\users@assign_permission');



    Route::post('/admin/users/remove_admin','admin\users@remove_admin');

    // End Admin users Routing


    // Start Admin support_messages Routing

    Route::get('/admin/support_messages/{msg_type?}','admin\support_messages@index');
    Route::post('/admin/delete_support_messages','admin\support_messages@remove_msg');

    // End Admin support_messages Routing


    // Start Admin subscribe Routing

    Route::get('/admin/subscribe','admin\subscribe@index');
    Route::post('/admin/subscribe/send_custom_email','admin\subscribe@send_custom_email');
    Route::post('/admin/subscribe/send_all_subscribers_email','admin\subscribe@send_all_subscribers_email');
    Route::get('/admin/subscribe/stop','admin\subscribe@stop');
    Route::get('/admin/subscribe/pause','admin\subscribe@pause');
    Route::get('/admin/subscribe/resume','admin\subscribe@resume');

    Route::get('/admin/subscribe/save_email','admin\subscribe@save_email');
    Route::post('/admin/subscribe/save_email','admin\subscribe@save_email');

    Route::get('/admin/subscribe/email_settings','admin\subscribe@email_settings');
    Route::post('/admin/subscribe/email_settings','admin\subscribe@email_settings');
    Route::get('/admin/subscribe/export_subscribe','admin\subscribe@export_subscribe');

    Route::post('/admin/subscribe/remove_email','admin\subscribe@remove_email');


    // End Admin subscribe Routing


    // Start menus Routing
    Route::get('/admin/menus','admin\menus@index');
    Route::get('/admin/menus/save_menu/{lang_id?}/{menu_id?}','admin\menus@get_menu');
    Route::post('/admin/menus/save_sortable_menu','admin\menus@save_sortable_menu');
    Route::post('/admin/menus/delete_menu','admin\menus@delete_menu');
    // End menus Routing

    //manage ads
    Route::get("/admin/ads",'admin\ads@index');
    Route::get("/admin/ads/save_ad/{ad_id?}",'admin\ads@save_ad');
    Route::post("/admin/ads/save_ad/{ad_id?}",'admin\ads@save_ad');
    Route::post("/admin/ads/remove_ads",'admin\ads@remove_ads');
    //END manage ads


    //uploader
    Route::get('/admin/uploader','admin\uploader@index');
    Route::post('/upload_files','admin\uploader@load_files');

    //END uploader

    Route::get('/admin/settings','admin\settings@index');

    Route::get('/admin/add_post','admin\dashboard@add_post');


    #region send message , notification

    Route::post('/admin/send_message','admin\dashboard@send_message');

    #endregion

    #region change_code

    Route::post('/admin/change_code','admin\dashboard@change_code');

    #endregion

    #region workshops ,groups

    Route::get('/admin/workshops','admin\workshops@index');
    Route::get('/admin/groups','admin\groups@index');

    #endregion

});


// Password Reset Routes...
$this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
$this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
$this->post('password/reset', 'Auth\PasswordController@reset');

//Route::auth();
//
//Route::get('/home', 'HomeController@index');
