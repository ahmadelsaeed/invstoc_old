<?php
Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => ['languageLocale']
], function() {
    Route::get('/group/{group_name}/{group_id}', 'front\group_workshop@show_group');
    Route::get('/workshop/{workshop_name}/{workshop_id}', 'front\group_workshop@show_workshop');
    Route::post('/load_group_posts', 'front\group_workshop@load_group_posts');
    Route::post('/load_workshop_posts', 'front\group_workshop@load_workshop_posts');
    Route::group(['middleware' => ['web', 'check_user']], function () {

        Route::post('/groups_workshops/create_group_workshop', 'front\group_workshop@create_group_or_workshop');
        Route::post('/groups_workshops/get_user_groups_and_workshops', 'front\group_workshop@get_user_groups_and_workshops');

        Route::post('/groups_workshops/create_group', 'front\group_workshop@create_group');
        Route::post('/groups_workshops/create_workshop', 'front\group_workshop@create_workshop');

        #region group


        Route::get('/group/settings/{group_name}/{group_id}', 'front\group_workshop@group_settings');
        Route::post('/group/settings/{group_name}/{group_id}', 'front\group_workshop@group_settings');

        Route::get('/group/members/{group_name}/{group_id}', 'front\group_workshop@group_members');
        Route::post('/group/members/{group_name}/{group_id}', 'front\group_workshop@group_members');

        Route::get('/group/change_member_role/{group_name}/{group_id}', 'front\group_workshop@change_member_role');
        Route::post('/group/change_member_role/{group_name}/{group_id}', 'front\group_workshop@change_member_role');

        Route::get('/group/remove_member/{group_name}/{group_id}', 'front\group_workshop@remove_member');
        Route::post('/group/remove_member/{group_name}/{group_id}', 'front\group_workshop@remove_member');


        Route::get('/group/delete/{group_name}/{group_id}', 'front\group_workshop@delete_group');
        Route::post('/group/delete/{group_name}/{group_id}', 'front\group_workshop@delete_group');

        Route::get('/group/rename/{group_name}/{group_id}', 'front\group_workshop@rename_group');
        Route::post('/group/rename/{group_name}/{group_id}', 'front\group_workshop@rename_group');

        Route::get('/group/change_logo/{group_name}/{group_id}', 'front\group_workshop@change_logo_group');
        Route::post('/group/change_logo/{group_name}/{group_id}', 'front\group_workshop@change_logo_group');

        Route::get('/request_to_join_group/{group_id}', 'front\group_workshop@request_to_join_group');
        Route::post('/request_to_join_group/{group_id}', 'front\group_workshop@request_to_join_group');

        Route::get('/group/requests/{group_name}/{group_id}', 'front\group_workshop@group_requests');

        Route::post('/group/accept_request_join', 'front\group_workshop@accept_request_join');
        Route::post('/group/remove_request_join', 'front\group_workshop@remove_request_join');


        #endregion

        #region workshop

        Route::get('/workshop/rename/{workshop_name}/{workshop_id}', 'front\group_workshop@rename_workshop');
        Route::post('/workshop/rename/{workshop_name}/{workshop_id}', 'front\group_workshop@rename_workshop');

        Route::get('/workshop/change_activity/{workshop_name}/{workshop_id}', 'front\group_workshop@change_activity_workshop');
        Route::post('/workshop/change_activity/{workshop_name}/{workshop_id}', 'front\group_workshop@change_activity_workshop');


        Route::get('/workshop/change_logo/{workshop_name}/{workshop_id}', 'front\group_workshop@change_logo_workshop');
        Route::post('/workshop/change_logo/{workshop_name}/{workshop_id}', 'front\group_workshop@change_logo_workshop');


        Route::get('/workshop/delete/{workshop_name}/{workshop_id}', 'front\group_workshop@delete_workshop');
        Route::post('/workshop/delete/{workshop_name}/{workshop_id}', 'front\group_workshop@delete_workshop');
        #endregion


        #region workshops follow

        Route::post('/follow_workshop', 'front\group_workshop@follow_workshop');

        #endregion

    });
});



