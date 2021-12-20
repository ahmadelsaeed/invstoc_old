<?php
Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => ['languageLocale']
], function() {
    Route::group(['middleware' => ['web', 'check_user']], function () {

        Route::get("/get_updates", 'actions\updates@get_updates');

        Route::post('/load_notifications', 'actions\updates@load_notifications');

        Route::post('/load_follow_notifications', 'actions\updates@load_follow_notifications');

        Route::post('/load_hot_orders', 'actions\updates@load_hot_orders');

    });
});
