<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\ads_m;
use App\models\notification_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class notifications extends admin_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!check_permission($this->user_permissions,"admin/notifications","delete_action"))
        {
            return Redirect::to('admin/notifications')->send();
        }

        $this->data["all_notifications"] = notification_m::orderBy("not_id","desc")->get()->all();
        return view("admin.subviews.notifications.show",$this->data);
    }

    public function delete_notification(Request $request){
        if (!check_permission($this->user_permissions,"admin/notifications","delete_action"))
        {
            echo json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
            return;
        }

        $this->general_remove_item($request,'App\models\notification_m');
    }

}
