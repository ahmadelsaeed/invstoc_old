<?php


Route::group(['middleware' => 'check_dev'], function () {

//generate_edit_content
    Route::get("/dev/test",function(){
        return "dev/permissions/permissions_pages/show_all_permissions_pages";
    });

    Route::get("/dev/generate_edit_content/show_all",'dev\generate_site_content@show_all_methods');
    Route::get("/dev/generate_edit_content/save/{method_id?}",'dev\generate_site_content@save_method');
    Route::post("/dev/generate_edit_content/save/{method_id?}",'dev\generate_site_content@save_method');
//END generate_edit_content


    //permissions
    Route::get("/dev/permissions/permissions_pages/show_all_permissions_pages",'dev\permissions@show_all_permissions_pages');

    Route::get("/dev/permissions/permissions_pages/save/{permission_page_id?}",'dev\permissions@save_permission_page');
    Route::post("/dev/permissions/permissions_pages/save/{permission_page_id?}",'dev\permissions@save_permission_page');

    Route::get("/dev/permissions/assign_permission_for_this_user",'dev\permissions@assign_permission_for_this_user');
    Route::post("/dev/permissions/assign_permission_for_this_user",'dev\permissions@assign_permission_for_this_user');


    Route::post("/dev/permissions/permissions_pages/delete",'dev\permissions@delete_permission_page');


    Route::post("/general_remove_item",'dashbaord_controller@general_remove_item');


    //END permissions





});

