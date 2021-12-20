<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\dashbaord_controller;
use App\models\support_messages_m;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class support_messages extends admin_controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index($msg_type="support")
    {
        //check_availability,support,build_trip

        if (!check_permission($this->user_permissions,"admin/support_messages","show_action"))
        {
            return  Redirect::to('admin/dashboard')->with(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"])->send();
        }
        $this->data["msg_type"]=$msg_type;

        $this->data["all_messages"] = support_messages_m::get_data("
            AND support.msg_type = '$msg_type'
        "," order by support.id desc ");

//        $this->data["all_messages"] = support_messages_m::
//            select(DB::raw("
//                support_messages.*,attachments.*,
//                users.username
//            "))
//            ->leftJoin("attachments","support_messages.img_id","=","attachments.id")
//            ->leftJoin("users","users.user_id","=","support_messages.user_id")
//            ->where("support_messages.msg_type","$msg_type")->orderBy("support_messages.id","desc")->get()->all();


        return view("admin.subviews.support_messages.show")->with($this->data);
    }

    public function remove_msg(Request $request){
        if (!check_permission($this->user_permissions,"admin/support_messages","show_action"))
        {
            echo json_encode(["msg"=>"<div class='alert alert-danger'>You can not access here</div>"]);
            return;
        }

        $this->general_remove_item($request,'App\models\support_messages_m');
    }


}
